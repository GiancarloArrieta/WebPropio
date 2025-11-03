<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/admin/panel', function () {
    return view('paneladmin');
})->middleware(['auth', 'role:admin'])->name('admin.panel');

Route::get('/departamento/panel', function () {
    return view('paneldepartamento');
})->middleware(['auth', 'role:departamento'])->name('departamento.panel');

Route::get('/usuario/panel', function () {
    return view('panelusuario');
})->middleware(['auth', 'role:usuario'])->name('usuario.panel');

Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        if ($user->id_rol === 1) {
            return redirect()->route('admin.panel'); 
        } elseif ($user->id_rol === 2) {
            return redirect()->route('departamento.panel');
        }
        return redirect()->route('usuario.panel'); 
    }
    return redirect()->route('login'); 
})->name('home');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    
    // 2. PATCH para actualizar nombre/correo
    Route::patch('/profile/update/info', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
    
    // 3. POST para actualizar la foto
    Route::post('/profile/update/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    // 4. PATCH para actualizar la contraseña 🔑 ESTA ES LA RUTA FALTANTE
    Route::patch('/profile/update/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
});