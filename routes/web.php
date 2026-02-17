<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
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
| PUBLIC AREA
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


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

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Resource Controllers
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

        // Pemakaian
        Route::resource('pemakaian', PemakaianController::class)
            ->only(['index', 'create', 'store']);

        // Distribusi (user view)
        Route::get('/distribusi', [DistribusiController::class, 'indexUser'])
            ->name('distribusi.index');

        // Notifikasi
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])
            ->name('notifikasi');
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
| CHAT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/chat', [ChatController::class, 'index'])
        ->name('chat.index');

    Route::get('/chat/{user}', [ChatController::class, 'show'])
        ->name('chat.show');

    Route::post('/chat/{user}', [ChatController::class, 'store'])
        ->name('chat.store');
});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';