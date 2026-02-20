<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotifikasiController;

/* --- HOME --- */
Route::get('/', fn() => view('welcome'))->name('home');

/* --- DASHBOARD REDIRECT BERDASARKAN ROLE --- */
Route::middleware('auth')->get('/dashboard', function () {
    return match(auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('user.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

Route::prefix('admin')->middleware(['auth','role:admin'])->name('admin.')->group(function() {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class,'dashboard'])->name('dashboard');

    // LAPORAN ADMIN
    Route::prefix('laporan')->group(function() {
        Route::get('/stok-masuk', [LaporanController::class,'stokMasuk'])->name('laporan.stok-masuk');
        Route::get('/distribusi', [LaporanController::class,'distribusi'])->name('laporan.distribusi');
        Route::get('/pemakaian', [LaporanController::class,'pemakaian'])->name('laporan.pemakaian');
        
        // Laporan Lengkap (gabungan semua)
        Route::get('/lengkap', [LaporanController::class,'cetakPDF'])->name('laporan.lengkap');
    });

    // Notifikasi Admin
    Route::get('/notifikasi', [NotifikasiController::class, 'indexAdmin'])->name('notifikasi.index');

    // CRUD
    Route::resource('outlet', OutletController::class);
    Route::resource('bahan', BahanController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('stok-outlet', StokOutletController::class);

    // Profil Admin
    Route::get('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class,'updatePassword'])->name('profile.update-password');
});

/* --- USER AREA (OUTLET) --- */
Route::prefix('user')->middleware(['auth','role:user'])->name('user.')->group(function() {

    // Dashboard User
    Route::get('/dashboard', [UserDashboardController::class,'index'])->name('dashboard');

    // Pemakaian
    Route::resource('pemakaian', PemakaianController::class)->only(['index','create','store']);

    // Stok & Distribusi
    Route::get('/distribusi', [DistribusiController::class,'indexUser'])->name('distribusi.index');
    Route::get('/stok-outlet', [StokOutletController::class,'indexUser'])->name('stok-outlet.index');

    // Profil User
    Route::get('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::post('/profile/update-password', [ProfileController::class,'updatePassword'])->name('profile.update-password');

    // Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class,'index'])->name('notifikasi.index');

    // LAPORAN USER
    Route::prefix('laporan')->group(function() {
        Route::get('/pemakaian', [PemakaianController::class,'laporanUser'])->name('laporan.pemakaian');
    });
});

/* --- GLOBAL CHAT --- */
Route::middleware('auth')->group(function() {
    Route::get('/chat', [ChatController::class,'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class,'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class,'store'])->name('chat.store');
});

/* --- AUTH --- */
require __DIR__.'/auth.php';