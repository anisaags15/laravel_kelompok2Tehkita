<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\DistribusiController;
use Illuminate\Support\Facades\Route;

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
| DASHBOARD DEFAULT (WAJIB BUAT BREEZE)
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
Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('stok-outlet', StokOutletController::class);

    Route::resource('distribusi', DistribusiController::class);
});

/*
|--------------------------------------------------------------------------
| USER AREA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {

    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    })->name('user.dashboard');

    Route::patch('/distribusi/{id}/terima', [DistribusiController::class, 'terima'])
        ->name('distribusi.terima');

    Route::middleware(['auth', 'role:user'])->group(function () {
    Route::resource('pemakaian', PemakaianController::class)
    ->except(['show', 'edit', 'update']);
});
    Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('stok-masuk', StokMasukController::class)
        ->except(['show', 'edit', 'update']);
});

});
/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



require __DIR__.'/auth.php';
