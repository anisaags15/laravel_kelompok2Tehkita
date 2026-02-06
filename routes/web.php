<?php

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\PemakaianController;
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

    Route::resource('pemakaian', PemakaianController::class);
    Route::resource('stok-outlet', StokOutletController::class);
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

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
