<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController; // <--- ADICIONE ESTA LINHA
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;

// 1. Redireciona a raiz para Login ou Dashboard
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// 2. Carrega as rotas de Auth
require __DIR__ . '/auth.php';

// 3. Rotas de Perfil (Autenticadas)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 4. Rota do Dashboard Protegido
Route::middleware(['auth', 'permission:access_dashboard'])->group(function () {
    Route::get('/dashboard', function () {
        return inertia('Dashboard');
    })->name('dashboard');
});

// 5. Rota para Listar Usuários com Permissão
Route::middleware(['auth'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])
        ->name('users.index')
        ->middleware('permission:view_users');

    Route::post('/users', [UserController::class, 'store'])
        ->name('users.store')
        ->middleware('permission:create_users');

    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('users.update')
        ->middleware('permission:edit_users');

    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])
        ->name('users.update-role')
        ->middleware('permission:manage_roles');

    // Roles
    Route::resource('roles', RoleController::class)
        ->middleware('permission:manage_roles');

    // Permissions
    Route::resource('permissions', PermissionController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('permission:manage_permissions');
});