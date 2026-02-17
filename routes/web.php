<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;

/*
|--------------------------------------------------------------------------
| PUBLIC AREA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT (ROLE BASED)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {
<<<<<<< Updated upstream

    return match (auth()->user()->role) {
=======
    return match(auth()->user()->role) {
>>>>>>> Stashed changes
        'admin' => redirect()->route('admin.dashboard'),
        'user' => redirect()->route('user.dashboard'),
        default => abort(403),
    };

})->name('dashboard');


/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

<<<<<<< Updated upstream
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Resource Controllers
        Route::resource('outlet', OutletController::class);
        Route::resource('bahan', BahanController::class);
        Route::resource('stok-masuk', StokMasukController::class);
        Route::resource('distribusi', DistribusiController::class);
        Route::resource('stok-outlet', StokOutletController::class);
=======
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])
            ->name('dashboard');

        Route::resources([
            'outlet' => OutletController::class,
            'bahan' => BahanController::class,
            'stok-masuk' => StokMasukController::class,
            'distribusi' => DistribusiController::class,
            'stok-outlet' => StokOutletController::class,
        ]);
>>>>>>> Stashed changes
    });


/*
|--------------------------------------------------------------------------
| USER AREA
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

<<<<<<< Updated upstream
        // Dashboard
        Route::get('/dashboard', function () {
=======
        // Dashboard User
        Route::get('/dashboard', [UserDashboardController::class, 'index'])
            ->name('dashboard');
>>>>>>> Stashed changes

        // Resource Stok Outlet (user hanya bisa lihat index/show)
        Route::resource('stok-outlet', StokOutletController::class)
            ->only(['index', 'show']);

        // Pemakaian
<<<<<<< Updated upstream
        Route::resource('pemakaian', PemakaianController::class)
            ->only(['index', 'create', 'store']);

        // Distribusi (user view)
=======
        Route::get('/pemakaian', [PemakaianController::class, 'index'])
            ->name('pemakaian.index');
        Route::get('/pemakaian/create', [PemakaianController::class, 'create'])
            ->name('pemakaian.create');
        Route::post('/pemakaian', [PemakaianController::class, 'store'])
            ->name('pemakaian.store');

        // Distribusi
>>>>>>> Stashed changes
        Route::get('/distribusi', [DistribusiController::class, 'indexUser'])
            ->name('distribusi.index');

        // Notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');
    });


/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Admin & User (role-aware bisa dipisah kalau mau)
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');
});


/*
|--------------------------------------------------------------------------
| CHAT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
<<<<<<< Updated upstream

require __DIR__.'/auth.php';
=======
require __DIR__ . '/auth.php';
>>>>>>> Stashed changes
