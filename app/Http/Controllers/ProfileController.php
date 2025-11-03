<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:5120',
        ]);

        $path = $request->file('photo')->store('profiles', 'public');

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = Storage::disk('public')->putFile('profiles', $file);

            // 2.3 Actualizar el campo en la BD
            $user->profile_photo_path = $path;
            $user->save();
        }

        return back()->with('status', 'Foto de perfil actualizada.');
    }

    /**
     * Actualiza el nombre y el correo electrónico del usuario autenticado.
     */
    public function updateInfo(Request $request)
    {
        $user = Auth::user();

        // 1. Validación de Datos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Regla crucial: el correo debe ser único en la tabla 'usuarios',
            // pero se permite que sea el correo actual del usuario ($user->id).
            'email' => ['required', 'string', 'email', 'max:255', 
                        Rule::unique('usuarios', 'email')->ignore($user->id)],
        ]);

        // 2. Actualización de Datos
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // 3. Redirección con mensaje de éxito
        return redirect()->route('profile.edit')->with('status', 'Información de perfil actualizada exitosamente.');
    }

    /**
     * Actualiza la contraseña del usuario autenticado (profile.update.password).
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // 1. Validación de Seguridad (Contraseña actual y nueva)
        $request->validate([
            'current_password' => ['required', 'string'],
            // 'confirmed' requiere un campo 'password_confirmation' coincidente
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        // 2. Verificación de Contraseña Actual
        if (!Hash::check($request->current_password, $user->password)) {
            // Si la contraseña actual es incorrecta, lanza una excepción de validación
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual proporcionada es incorrecta.',
            ]);
        }

        // 3. Actualización de Contraseña
        $user->password = Hash::make($request->password);
        $user->save();

        // 4. Redirección con mensaje de éxito
        return redirect()->route('profile.edit')->with('status', 'Contraseña actualizada exitosamente.');
    }
}
