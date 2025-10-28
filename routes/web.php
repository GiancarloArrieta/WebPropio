<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    if (Auth::check()) {
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

Route::post('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])
    ->middleware(['auth']) // Asegura que el usuario esté logueado
    ->name('profile.photo.update');