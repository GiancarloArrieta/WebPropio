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
use App\Models\Tickets;
use Illuminate\Support\Facades\Validator;

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
        $user = Auth::user();

        // 1. Validación de Datos (Devolver errores en JSON)
        $validator = Validator::make($request->all(), [
            'photo' => ['required', 'image', 'max:2048'], // 2048 KB = 2MB
        ]);

        if ($validator->fails()) {
            // Devolver un JSON con los errores de validación
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first('photo'),
            ], 422);
        }
        
        // 2. Procesamiento de la Imagen
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            
            // 2.1 Eliminar la foto antigua si existe (mantenemos la lógica de almacenamiento)
            if ($user->profile_photo_path && $user->profile_photo_path !== 'profiles/default-profile.png') {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // 2.2 Guardar la nueva foto
            $path = Storage::disk('public')->putFile('profiles', $file);

            // 2.3 Actualizar el campo en la BD
            $user->profile_photo_path = $path;
            $user->save();

            // 3. Respuesta JSON de Éxito
            return response()->json([
                'success' => true,
                'message' => 'Foto actualizada exitosamente.',
                // Devolvemos la URL pública completa para actualizar el DOM
                'path' => Storage::url($path),
            ]);
        }

        // Respuesta si no se encontró el archivo por alguna razón
        return response()->json([
            'success' => false,
            'message' => 'No se detectó ningún archivo.',
        ], 400);
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

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], 
        ]);

        // 2. Verificación de Contraseña Actual
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual proporcionada es incorrecta.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'Contraseña actualizada exitosamente.');
    }

    public function showPanel()
    {
        $user = Auth::user();
        
        $tickets = Tickets::where('id_usuario', $user->id)
                         ->with(['encargado', 'estatus'])
                         ->orderBy('fecha_hora_reporte', 'desc') // Ordenar por la fecha más reciente
                         ->get();

        return view('panelusuario', [ // Reemplaza 'user-panel' con el nombre real de tu vista
            'user' => $user,
            'tickets' => $tickets, // Pasamos los tickets a la vista
        ]);
    }
}
