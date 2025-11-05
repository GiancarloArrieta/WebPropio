<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/admin/panel', function () {
    $user = Auth::user();
    return view('paneladmin', compact('user'));
})->middleware(['auth', 'role:admin'])->name('admin.panel');

Route::get('/departamento/panel', function () {
    $user = Auth::user();
    return view('paneldepartamento', compact('user'));
})->middleware(['auth', 'role:departamento'])->name('departamento.panel');

Route::get('/usuario/panel', function () {
    $user = Auth::user();
    return view('panelusuario', compact('user'));
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
    Route::patch('/profile/update/info', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
    Route::post('/profile/update/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::patch('/profile/update/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
});

Route::get('/usuario/ticket', function () {
    return view('crearticket');
})->middleware(['auth', 'role:usuario'])->name('crear.ticket');
Route::post('/usuario/ticket', [TicketController::class, 'store'])->name('crear.ticket');