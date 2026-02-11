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
Route::middleware('auth')->get('/dashboard', function () {
    return match (auth()->user()->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'user'  => redirect()->route('user.dashboard'),
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

        Route::get('/dashboard', function () {
            return view('admin.dashboard', [
                'totalUsers'        => User::count(),
                'totalOutlets'      => Outlet::count(),
                'totalBahan'        => Bahan::count(),
                'stokGudang'        => StokMasuk::sum('jumlah'),
                'distribusiHariIni' => Distribusi::whereDate('created_at', today())
                                            ->sum('jumlah'),
            ]);
        })->name('dashboard');

        // FULL CRUD untuk admin
        Route::resources([
            'outlet'      => OutletController::class,
            'bahan'       => BahanController::class,
            'stok-masuk'  => StokMasukController::class,
            'distribusi'  => DistribusiController::class,
            'stok-outlet' => StokOutletController::class,
        ]);
    });


/*
|--------------------------------------------------------------------------
| USER AREA (ADMIN OUTLET)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', function () {

            $user = auth()->user();

            $totalStok = \App\Models\StokOutlet::where('outlet_id', $user->outlet_id)
                ->sum('stok');

            $pemakaianHariIni = \App\Models\Pemakaian::where('outlet_id', $user->outlet_id)
                ->whereDate('tanggal', now())
                ->sum('jumlah');

            $distribusiMasuk = \App\Models\Distribusi::where('outlet_id', $user->outlet_id)
                ->sum('jumlah');

            $stokOutlets = \App\Models\StokOutlet::with('bahan')
                ->where('outlet_id', $user->outlet_id)
                ->get();

            $pemakaians = \App\Models\Pemakaian::with('bahan')
                ->where('outlet_id', $user->outlet_id)
                ->latest()
                ->limit(5)
                ->get();

            return view('user.dashboard', compact(
                'totalStok',
                'pemakaianHariIni',
                'distribusiMasuk',
                'stokOutlets',
                'pemakaians'
            ));

        })->name('dashboard');

        // Stok Outlet (hanya index)
        Route::get('/stok-outlet', [StokOutletController::class, 'indexUser'])
            ->name('stok-outlet.index');

        // Pemakaian Bahan
        Route::get('/pemakaian', [PemakaianController::class, 'index'])
            ->name('pemakaian.index');

        Route::get('/pemakaian/create', [PemakaianController::class, 'create'])
            ->name('pemakaian.create');

        Route::post('/pemakaian', [PemakaianController::class, 'store'])
            ->name('pemakaian.store');

        // Riwayat Distribusi (khusus outlet login)
        Route::get('/distribusi', [DistribusiController::class, 'indexUser'])
            ->name('distribusi.index');
    


        // Lihat Stok Outlet
        Route::resource('stok-outlet', StokOutletController::class)
            ->only(['index']);

        Route::resource('pemakaian', PemakaianController::class);

        // 🔥 INI YANG KURANG
        Route::resource('distribusi', DistribusiController::class)
            ->only(['index']);

    });



/*
|--------------------------------------------------------------------------
| PROFILE
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
| AUTH
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
