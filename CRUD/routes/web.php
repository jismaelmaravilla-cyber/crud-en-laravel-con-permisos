<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// rutas de autenticación 
Route::get('/', [AuthController::class, 'mostrarLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.procesar');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// rutas permitidas para Administrador y Empleado
Route::middleware(['auth', 'rol:Administrador,Empleado'])->group(function () {
    Route::get('/usuarios', [UsuarioController::class, 'index'])
        ->name('usuarios.index');

    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])
        ->name('usuarios.create');

    Route::post('/usuarios', [UsuarioController::class, 'store'])
        ->name('usuarios.store');

    Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])
        ->whereNumber('id')
        ->name('usuarios.show');

    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])
        ->whereNumber('id')
        ->name('usuarios.edit');

    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])
        ->whereNumber('id')
        ->name('usuarios.update');
});

// rutas admin
Route::middleware(['auth', 'rol:Administrador'])->group(function () {
    // Eliminación lógica (Soft Delete)
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])
        ->whereNumber('id')
        ->name('usuarios.destroy');

    // eliminacion force
    Route::delete('/usuarios/{id}/permanente', [UsuarioController::class, 'forceDestroy'])
        ->whereNumber('id')
        ->name('usuarios.forceDestroy');

    // Ver usuarios eliminados con logica
    Route::get('/usuarios-eliminados', [UsuarioController::class, 'trashed'])
        ->name('usuarios.trashed');

    // Restaurar usuario eliminado 
    Route::patch('/usuarios-eliminados/{id}/restaurar', [UsuarioController::class, 'restore'])
        ->whereNumber('id')
        ->name('usuarios.restore');
});
