<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user(); // Obtenemos el usuario autenticado

        if ($user->id_rol === 1) {
            // Asumiendo que 1 es el Administrador
            return redirect('/admin/panel'); 
            
        } elseif ($user->id_rol === 2) {
            // Asumiendo que 2 es el Departamento
            return redirect('/departamento/panel');
            
        } else {
            // Cualquier otro rol (Usuario Básico)
            return redirect('/usuario/panel'); 
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
