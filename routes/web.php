<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\StokOutletController;
<<<<<<< HEAD
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\DistribusiController;
=======
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\PemakaianController;
>>>>>>> e779653a4929e1c70e342e4ff5ac2ee88fd51c5d
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome'); // Landing page
});

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        $totalUsers = \App\Models\User::count();
        $totalOutlets = \App\Models\Outlet::count();
        $totalBahan = \App\Models\Bahan::count();
        $stokGudang = \App\Models\StokMasuk::sum('jumlah');
        $distribusiHariIni = \App\Models\Distribusi::whereDate('created_at', now())->sum('jumlah');

        return view('admin.dashboard', compact('totalUsers', 'totalOutlets', 'totalBahan', 'stokGudang', 'distribusiHariIni'));
    })->name('dashboard');

    // Resource routes
    Route::resource('outlet', OutletController::class);
    Route::resource('bahan', BahanController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('stok-outlet', StokOutletController::class);

    Route::resource('distribusi', DistribusiController::class);
});

/*
|--------------------------------------------------------------------------
| USER AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');

<<<<<<< HEAD
    Route::patch('/distribusi/{id}/terima', [DistribusiController::class, 'terima'])
        ->name('distribusi.terima');

    Route::middleware(['auth', 'role:user'])->group(function () {
    Route::resource('pemakaian', PemakaianController::class)
    ->except(['show', 'edit', 'update']);
});
    Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('stok-masuk', StokMasukController::class)
        ->except(['show', 'edit', 'update']);
=======
    Route::resource('pemakaian', PemakaianController::class);
    Route::resource('stok-outlet', StokOutletController::class);
>>>>>>> e779653a4929e1c70e342e4ff5ac2ee88fd51c5d
});

});
/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

<<<<<<< HEAD


=======
/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
>>>>>>> e779653a4929e1c70e342e4ff5ac2ee88fd51c5d
require __DIR__.'/auth.php';
