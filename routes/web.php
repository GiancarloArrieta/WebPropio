<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    if (Auth::check()) {
        // Redirigir a un dashboard por defecto si ya está logueado
        // O usar la lógica de redirección por rol que implementaste en el controlador:
        $user = Auth::user();
        if ($user->id_rol === 1) {
            return redirect('/admin/panel'); 
        } elseif ($user->id_rol === 2) {
            return redirect('/departamento/panel');
        }
        return redirect('/usuario/panel'); 
    }
    return view('/login');
});

Route::get('/login', function () {
    return view('iniciosesion');
});
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::get('/admin/panel', function () {
    return view('paneladmin');
})->middleware('auth');

// Ruta de Departamento
Route::get('/departamento/panel', function () {
    return view('paneldepartamento');
})->middleware('auth');

// Ruta de Usuario Básico
Route::get('/usuario/panel', function () {
    return view('panelusuario');
})->middleware('auth');