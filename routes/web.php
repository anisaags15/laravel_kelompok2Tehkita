<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotifikasiController;

Route::get('/', fn() => view('welcome'))->name('home');

Route::middleware('auth')->get('/dashboard', function () {
    return match(auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('user.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

/* --- ADMIN AREA --- */
Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function() {
    Route::get('/dashboard', [AdminDashboardController::class,'dashboard'])->name('dashboard');
    
    // Notifikasi Khusus Admin (Penting!)
    Route::get('/notifikasi', [NotifikasiController::class, 'indexAdmin'])->name('notifikasi.index');

    Route::resource('outlet', OutletController::class);
    Route::resource('bahan', BahanController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('stok-outlet', StokOutletController::class);

    Route::get('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class,'updatePassword'])->name('profile.update-password');
});

/* --- USER AREA (OUTLET) --- */
Route::prefix('user')->middleware(['auth','role:user'])->name('user.')->group(function() {
    Route::get('/dashboard', [UserDashboardController::class,'index'])->name('dashboard');
    Route::resource('pemakaian', PemakaianController::class)->only(['index','create','store']);
    Route::get('/distribusi', [DistribusiController::class,'indexUser'])->name('distribusi.index');
    Route::get('/stok-outlet', [StokOutletController::class,'indexUser'])->name('stok-outlet.index');

    Route::get('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class,'updatePassword'])->name('profile.update-password');

    Route::get('/notifikasi', [NotifikasiController::class,'index'])->name('notifikasi.index');
});

/* --- GLOBAL CHAT --- */
Route::middleware('auth')->group(function() {
    Route::get('/chat', [ChatController::class,'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class,'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class,'store'])->name('chat.store');
});

require __DIR__.'/auth.php';