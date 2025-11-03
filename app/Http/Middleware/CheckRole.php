<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; 

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('/login')->with('error', 'Debes iniciar sesión para acceder.');
        }

        $user = Auth::user();

        $requiredRoleId = match ($role) {
            'admin' => 1,
            'departamento' => 2,
            'usuario' => 3,
            default => null, 
        };

        if ($user->id_rol !== $requiredRoleId) {
            return redirect()->route('home')->with('error', 'Acceso denegado. No tienes permiso para acceder a este recurso.');
        }

        return $next($request);
    }
}