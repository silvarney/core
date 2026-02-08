<?php

use App\Http\Controllers\Admin\AccommodationController;
use App\Http\Controllers\Admin\AccommodationTypeController; // <--- ADICIONE ESTA LINHA
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

// Rate limiting configuration
RateLimiter::for('search', function (Request $request) {
    return Limit::perMinute(30)->by($request->ip());
});

RateLimiter::for('booking', function (Request $request) {
    return Limit::perMinute(10)->by($request->ip());
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
});

// 1. Rotas Públicas
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/p/{property}', [PublicController::class, 'property'])->name('public.property');
Route::get('/search', [PublicController::class, 'search'])
    ->middleware('throttle:search')
    ->name('public.search');
Route::get('/checkout', [PublicController::class, 'checkout'])->name('public.checkout');
Route::post('/checkout', [PublicController::class, 'storeBooking'])
    ->middleware('throttle:booking')
    ->name('public.checkout.store');

// 2. Carrega as rotas de Auth
require __DIR__.'/auth.php';

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

    // CRM Routes
    Route::resource('properties', PropertyController::class);
    Route::resource('accommodation-types', AccommodationTypeController::class);
    Route::resource('accommodations', AccommodationController::class);
    Route::resource('seasons', SeasonController::class);
    Route::resource('rates', RateController::class);
    Route::resource('bookings', BookingController::class);
    Route::post('bookings/calculate-price', [BookingController::class, 'calculatePrice'])->name('bookings.calculate-price');
    Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::patch('inventory/{accommodation}/status', [InventoryController::class, 'updateStatus'])->name('inventory.update-status');

    // Query logging for monitoring (only in local environment)
    if (app()->environment('local')) {
        Route::get('/debug/queries', function () {
            $queries = DB::getQueryLog();

            return response()->json([
                'queries' => $queries,
                'total_queries' => count($queries),
                'total_time' => array_sum(array_column($queries, 'time')),
            ]);
        });

        Route::post('/debug/queries/clear', function () {
            DB::flushQueryLog();

            return response()->json(['message' => 'Query log cleared']);
        });
    }
});
