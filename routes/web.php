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

    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'user') {
        return redirect()->route('user.dashboard');
    }

    abort(403);

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

        // Admin Dashboard
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

        // Master Data
        Route::resource('outlet', OutletController::class);
        Route::resource('bahan', BahanController::class);
        Route::resource('stok-masuk', StokMasukController::class);
        Route::resource('distribusi', DistribusiController::class);
        Route::resource('stok-outlet', StokOutletController::class);

        // Admin Profile
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

        Route::resource('stok-outlet', StokOutletController::class)
            ->only(['index']);

        Route::resource('pemakaian', PemakaianController::class);

        // 🔥 INI YANG KURANG
        Route::resource('distribusi', DistribusiController::class)
            ->only(['index']);

    });



/*
|--------------------------------------------------------------------------
| PROFILE GLOBAL
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
