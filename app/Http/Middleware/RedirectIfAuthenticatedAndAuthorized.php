<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticatedAndAuthorized
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                // ⬇️ Lógica de Redirección por Rol ⬇️

                if (Auth::user()->isAdmin()) {
                    return redirect('/admin/dashboard'); // Redirección para Administradores
                }
                
                if (Auth::user()->isDepartamento()) {
                    return redirect('/departamento/panel'); // Redirección para Departamentos
                }
                
                // Si no es ninguno de los anteriores, usa la redirección por defecto (Usuario Básico)
                return redirect('/usuario/home'); 
            }
        }

        return $next($request);
    }
}
