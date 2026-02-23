<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\User\DashboardController as UserDashboardController;
use App\Http\Controllers\User\LaporanController as UserLaporanController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\BahanController;
use App\Http\Controllers\StokMasukController;
use App\Http\Controllers\DistribusiController;
use App\Http\Controllers\StokOutletController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotifikasiController;

/*
|--------------------------------------------------------------------------
| HOME
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT SESUAI ROLE
|--------------------------------------------------------------------------
*/
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

    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])
        ->name('dashboard');

    Route::get('/notifikasi', [NotifikasiController::class, 'indexAdmin'])
        ->name('notifikasi.index');

    Route::resource('outlet', OutletController::class);
    Route::resource('bahan', BahanController::class);
    Route::resource('stok-masuk', StokMasukController::class);
    Route::resource('distribusi', DistribusiController::class);
    Route::resource('stok-outlet', StokOutletController::class);

    /*
    |--------------------------------------------------------------------------
    | LAPORAN ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan')->name('laporan.')->group(function () {

        Route::get('/', [AdminLaporanController::class, 'index'])
            ->name('index');

        Route::get('/cetak', [AdminLaporanController::class, 'cetakIndex'])
            ->name('index.cetak');

        Route::get('/stok-outlet', [AdminLaporanController::class, 'stokOutlet'])
            ->name('stok-outlet');

        Route::get('/stok-outlet/{outlet}', [AdminLaporanController::class, 'detailStokOutlet'])
            ->name('stok-outlet.detail');

        Route::get('/stok-outlet/{outlet}/cetak', [AdminLaporanController::class, 'cetakStokOutlet'])
            ->name('stok-outlet.cetak');

        Route::get('/distribusi', [AdminLaporanController::class, 'distribusi'])
            ->name('distribusi');

        Route::get('/distribusi/{id}', [AdminLaporanController::class, 'detailDistribusi'])
            ->name('distribusi.detail');

        Route::get('/distribusi/{id}/cetak', [AdminLaporanController::class, 'cetakDistribusi'])
            ->name('distribusi.cetak');

        Route::get('/lengkap', [AdminLaporanController::class, 'cetakPDF'])
            ->name('lengkap');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE ADMIN
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])
            ->name('update-password');
    });
});


/*
|--------------------------------------------------------------------------
| ================= USER AREA (OUTLET) =================
|--------------------------------------------------------------------------
*/
Route::prefix('user')
    ->middleware(['auth', 'role:user'])
    ->name('user.')
    ->group(function () {

    Route::get('/dashboard', [UserDashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | RIWAYAT USER
    |--------------------------------------------------------------------------
    */
    Route::get('/riwayat/pemakaian', [UserDashboardController::class, 'riwayatPemakaian'])
        ->name('riwayat_pemakaian');

    Route::get('/riwayat/distribusi', [UserDashboardController::class, 'riwayatDistribusi'])
        ->name('riwayat_distribusi');

    /*
    |--------------------------------------------------------------------------
    | PEMAKAIAN
    |--------------------------------------------------------------------------
    */
    Route::resource('pemakaian', PemakaianController::class)
        ->only(['index', 'create', 'store', 'destroy']);

    /*
    |--------------------------------------------------------------------------
    | WASTE
    |--------------------------------------------------------------------------
    */
    Route::get('/waste/lapor', [PemakaianController::class, 'createWaste'])
        ->name('waste.create');

    Route::post('/waste/simpan', [PemakaianController::class, 'storeWaste'])
        ->name('waste.store');

    /*
    |--------------------------------------------------------------------------
    | DISTRIBUSI & STOK OUTLET
    |--------------------------------------------------------------------------
    */
    Route::get('/distribusi', [DistribusiController::class, 'indexUser'])
        ->name('distribusi.index');

    Route::get('/stok-outlet', [StokOutletController::class, 'indexUser'])
        ->name('stok-outlet.index');

    /*
    |--------------------------------------------------------------------------
    | ================= LAPORAN USER =================
    |--------------------------------------------------------------------------
    */
    Route::prefix('laporan')
        ->name('laporan.')
        ->group(function () {

        // INDEX
        Route::get('/', [UserLaporanController::class, 'index'])
            ->name('index');

        // STOK
        Route::get('/stok', [UserLaporanController::class, 'stok'])
            ->name('stok');

        Route::get('/stok/pdf', [UserLaporanController::class, 'cetakStok'])
            ->name('stok.pdf');

        // DISTRIBUSI
        Route::get('/distribusi', [UserLaporanController::class, 'distribusi'])
            ->name('distribusi');

        Route::get('/distribusi/pdf', [UserLaporanController::class, 'cetakDistribusi'])
            ->name('distribusi.pdf');

        // RINGKASAN
        Route::get('/ringkasan', [UserLaporanController::class, 'ringkasan'])
            ->name('ringkasan');

        Route::get('/ringkasan/pdf', [UserLaporanController::class, 'cetakRingkasan'])
            ->name('ringkasan.pdf');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE USER
    |--------------------------------------------------------------------------
    */
    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update-password');

    /*
    |--------------------------------------------------------------------------
    | NOTIFIKASI USER
    |--------------------------------------------------------------------------
    */
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi.index');
});


/*
|--------------------------------------------------------------------------
| GLOBAL CHAT
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
require __DIR__ . '/auth.php';