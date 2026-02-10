<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\PemakaianController;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\Distribusi;

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
| DASHBOARD REDIRECT (ROLE BASED)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'user') {
        return redirect()->route('user.dashboard');
    }

    abort(403);
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| ADMIN AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            $totalUsers   = User::count();
            $totalOutlets = Outlet::count();
            $totalBahan   = Bahan::count();
            $stokGudang   = StokMasuk::sum('jumlah');
            $distribusiHariIni = Distribusi::whereDate('created_at', now())
                ->sum('jumlah');

            return view('admin.dashboard', compact(
                'totalUsers',
                'totalOutlets',
                'totalBahan',
                'stokGudang',
                'distribusiHariIni'
            ));
        })->name('dashboard');

        Route::resource('outlet', OutletController::class);
        Route::resource('bahan', BahanController::class);
        Route::resource('stok-masuk', StokMasukController::class);
        Route::resource('distribusi', DistribusiController::class);
        Route::resource('stok-outlet', StokOutletController::class);

        /*
        |--------------------------------------------------------------------------
        | ADMIN PROFILE (ALIAS KE PROFILE GLOBAL)
        |--------------------------------------------------------------------------
        */
        Route::get('/profile/edit', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::put('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');
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

        Route::get('/dashboard', function () {
            return view('user.dashboard');
        })->name('dashboard');

        Route::resource('pemakaian', PemakaianController::class);
        Route::resource('stok-outlet', StokOutletController::class);
    });

/*
|--------------------------------------------------------------------------
| PROFILE GLOBAL (UNTUK USER / AKSES LANGSUNG)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

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
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
