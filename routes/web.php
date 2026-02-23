<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// CONTROLLER ADMIN
// ==========================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController; // Pakai ini agar konsisten dengan class LaporanController

// ==========================================
// CONTROLLER USER/OUTLET
// ==========================================
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\LaporanController as UserLaporanController;

// ==========================================
// CONTROLLER UMUM/RESOURCES
// ==========================================
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| HOME & DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');

Route::middleware('auth')->get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('user.dashboard'),
        default => abort(403),
    };
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| ================= ADMIN AREA =================
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    
    // Notifikasi Pusat
    Route::get('/notifikasi', [NotifikasiController::class, 'indexAdmin'])->name('notifikasi.index');

    // Resources
    Route::resource('outlet', OutletController::class);
    Route::resource('bahan', BahanController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('stok-outlet', StokOutletController::class);

    // Waste Monitoring (Admin Pusat)
    Route::get('/waste', [PemakaianController::class, 'indexPusat'])->name('waste.index');
    Route::post('/waste/{id}/verify', [PemakaianController::class, 'verifyWaste'])->name('waste.verify');

    // Laporan Admin (Diarahkan ke Admin\LaporanController)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/cetak', [LaporanController::class, 'cetakIndex'])->name('index.cetak');
        
        // Route Stok Kritis
        Route::get('/stok-kritis', [LaporanController::class, 'stokKritis'])->name('stok-kritis');
        
        Route::get('/stok-outlet', [LaporanController::class, 'stokOutlet'])->name('stok-outlet');
        Route::get('/stok-outlet/{outlet}', [LaporanController::class, 'detailStokOutlet'])->name('stok-outlet.detail');
        Route::get('/stok-outlet/{outlet}/cetak', [LaporanController::class, 'cetakStokOutlet'])->name('stok-outlet.cetak');
        
        Route::get('/distribusi', [LaporanController::class, 'distribusi'])->name('distribusi');
        Route::get('/distribusi/{id}', [LaporanController::class, 'detailDistribusi'])->name('distribusi.detail');
        Route::get('/distribusi/{id}/cetak', [LaporanController::class, 'cetakDistribusi'])->name('distribusi.cetak');
        
        // Jika ada method cetakPDF untuk laporan lengkap
        Route::get('/lengkap', [LaporanController::class, 'cetakIndex'])->name('lengkap'); 
    });

    // Profile Admin
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });

}); // End Admin Area


/*
|--------------------------------------------------------------------------
| ================= USER AREA (OUTLET) =================
|--------------------------------------------------------------------------
| Prefix: /user | Name: user. | Role: user
|--------------------------------------------------------------------------
*/
Route::prefix('user')
    ->middleware(['auth', 'role:user'])
    ->name('user.')
    ->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');

    // Riwayat
    Route::get('/riwayat/pemakaian', [UserDashboardController::class, 'riwayatPemakaian'])->name('riwayat_pemakaian');
    Route::get('/riwayat/distribusi', [UserDashboardController::class, 'riwayatDistribusi'])->name('riwayat_distribusi');

    // Pemakaian & Waste
    Route::resource('pemakaian', PemakaianController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('/waste/lapor', [PemakaianController::class, 'createWaste'])->name('waste.create');
    Route::post('/waste/simpan', [PemakaianController::class, 'storeWaste'])->name('waste.store');

    // Distribusi & Stok
    Route::get('/distribusi', [DistribusiController::class, 'indexUser'])->name('distribusi.index');
    Route::get('/stok-outlet', [StokOutletController::class, 'indexUser'])->name('stok-outlet.index');

    // Laporan User
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [UserLaporanController::class, 'index'])->name('index');
        Route::get('/stok', [UserLaporanController::class, 'stok'])->name('stok');
        Route::get('/stok/pdf', [UserLaporanController::class, 'cetakStok'])->name('stok.pdf');
        Route::get('/distribusi', [UserLaporanController::class, 'distribusi'])->name('distribusi');
        Route::get('/distribusi/pdf', [UserLaporanController::class, 'cetakDistribusi'])->name('distribusi.pdf');
        Route::get('/ringkasan', [UserLaporanController::class, 'ringkasan'])->name('ringkasan');
        Route::get('/ringkasan/pdf', [UserLaporanController::class, 'cetakRingkasan'])->name('ringkasan.pdf');
    });

    // Profile User
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});

/*
|--------------------------------------------------------------------------
| GLOBAL CHAT & AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
});

require __DIR__ . '/auth.php';