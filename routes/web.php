<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\PemakaianController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (ROLE)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {

    $user = auth()->user();

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('user.dashboard'),
        default => abort(403),
    };

})->middleware('auth')->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN PUSAT (GUDANG)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'admin'])
            ->name('dashboard');

        // Master
        Route::resource('outlet', OutletController::class);
        Route::resource('bahan', BahanController::class);

        // Gudang
        Route::resource('stok-masuk', StokMasukController::class);

        // Distribusi
        Route::resource('distribusi', DistribusiController::class);

        // Monitoring stok outlet
        Route::resource('stok-outlet', StokOutletController::class)
            ->only(['index', 'show']);
    });


/*
|--------------------------------------------------------------------------
| ADMIN OUTLET
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'user'])
            ->name('dashboard');

        // Pemakaian
        Route::resource('pemakaian', PemakaianController::class);

        // Stok outlet
        Route::resource('stok-outlet', StokOutletController::class)
            ->only(['index']);

        // Terima distribusi (OUTLET)
        Route::put(
            '/distribusi/{id}/terima',
            [DistribusiController::class, 'terima']
        )->name('distribusi.terima');

    });


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
