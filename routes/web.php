<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// CONTROLLER ADMIN
// ==========================================
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\StokKritisController; // Pastikan ini ada!

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

    // Dashboard Utama
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    
    // 1. HALAMAN KERJA OPERASIONAL STOK KRITIS
    Route::get('/stok-kritis', [StokKritisController::class, 'index'])->name('stok-kritis.index');

    // Notifikasi Admin
    Route::get('/notifikasi', [NotifikasiController::class, 'indexAdmin'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

    // Resources Dasar
    Route::resource('outlet', OutletController::class);
    Route::resource('bahan', BahanController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('stok-outlet', StokOutletController::class);

    // Waste Monitoring (Sisi Admin)
    Route::get('/waste', [PemakaianController::class, 'indexPusat'])->name('waste.index');
    Route::post('/waste/{id}/verify', [PemakaianController::class, 'verifyWaste'])->name('waste.verify');

    // 2. AREA LAPORAN (Khusus Rekapitulasi & Cetak PDF)
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/cetak-ringkasan', [LaporanController::class, 'cetakIndex'])->name('cetak');
        
        // Fitur Laporan Stok Kritis
        Route::get('/stok-kritis', [LaporanController::class, 'stokKritis'])->name('stok-kritis');
        Route::get('/stok-kritis/cetak', [LaporanController::class, 'cetakStokKritis'])->name('stok-kritis.cetak'); 
        
        // Fitur Laporan Stok Outlet
        Route::get('/stok-outlet', [LaporanController::class, 'stokOutlet'])->name('stok-outlet');
        Route::get('/stok-outlet/cetak-semua', [LaporanController::class, 'cetakStokSemua'])->name('stok-outlet.cetak-semua');
        Route::get('/stok-outlet/{outlet}', [LaporanController::class, 'detailStokOutlet'])->name('stok-outlet.detail');
        Route::get('/stok-outlet/{outlet}/cetak', [LaporanController::class, 'cetakStokOutlet'])->name('stok-outlet.cetak');
        
        // Fitur Laporan Distribusi
        Route::get('/distribusi', [LaporanController::class, 'distribusi'])->name('distribusi');
        Route::get('/distribusi/{id}', [LaporanController::class, 'detailDistribusi'])->name('distribusi.detail');
        Route::get('/distribusi/{id}/cetak', [LaporanController::class, 'cetakDistribusi'])->name('distribusi.cetak');
    });

    // Profile Admin
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('update-password');
    });
});
/*
|--------------------------------------------------------------------------
| ================= USER AREA (OUTLET) =================
|--------------------------------------------------------------------------
*/
Route::prefix('user')->middleware(['auth', 'role:user'])->name('user.')->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Notifikasi User
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');

    // Riwayat
    Route::get('/riwayat/pemakaian', [UserDashboardController::class, 'riwayatPemakaian'])->name('riwayat_pemakaian');
    Route::get('/riwayat/distribusi', [UserDashboardController::class, 'riwayatDistribusi'])->name('riwayat_distribusi');

    // Modul Pemakaian & Waste
    Route::resource('pemakaian', PemakaianController::class);
    Route::get('/waste', [PemakaianController::class, 'indexWaste'])->name('waste.index');
    Route::get('/waste/lapor', [PemakaianController::class, 'createWaste'])->name('waste.create');
    Route::post('/waste/simpan', [PemakaianController::class, 'storeWaste'])->name('waste.store');

    // Distribusi & Stok
    Route::get('/distribusi', [DistribusiController::class, 'indexUser'])->name('distribusi.index');
    Route::match(['post', 'patch'], '/distribusi/{id}/terima', [DistribusiController::class, 'terima'])->name('distribusi.terima');
    Route::get('/stok-outlet', [StokOutletController::class, 'indexUser'])->name('stok-outlet.index');

    // Laporan User
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [UserLaporanController::class, 'index'])->name('index');
        Route::get('/stok', [UserLaporanController::class, 'stok'])->name('stok');
        Route::get('/stok/pdf', [UserLaporanController::class, 'cetakStok'])->name('stok.pdf');
        Route::get('/distribusi', [UserLaporanController::class, 'distribusi'])->name('distribusi');
        Route::get('/distribusi/pdf', [UserLaporanController::class, 'cetakDistribusi'])->name('distribusi.pdf');
        Route::get('/waste', [UserLaporanController::class, 'waste'])->name('waste');
        Route::get('/waste/pdf', [UserLaporanController::class, 'wastePdf'])->name('waste.pdf');
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
| GLOBAL AREA (AUTH ONLY)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store'])->name('chat.store');
});

require __DIR__ . '/auth.php';