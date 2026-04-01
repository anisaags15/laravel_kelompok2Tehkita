<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\StokOutlet;
use App\Models\Pemakaian;
use App\Models\Message;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // 1. View composer untuk NAVBAR (Notifikasi)
        View::composer('layouts.components.navbar', function ($view) {
            $user = Auth::user();
            if ($user) {
                // Stok kurang (user biasa)
                $stokAlert = collect();
                if ($user->role === 'user') {
                    $stokAlert = StokOutlet::with('bahan')
                        ->where('outlet_id', $user->outlet_id)
                        ->where('stok', '<=', 5)
                        ->get();
                }

                // Pemakaian hari ini
                $pemakaianHariIni = collect();
                if ($user->role === 'user') {
                    $pemakaianHariIni = Pemakaian::with('bahan')
                        ->where('outlet_id', $user->outlet_id)
                        ->whereDate('tanggal', now())
                        ->get();
                }

                // Pesan belum dibaca
                $unreadMessages = Message::where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->get();

                $view->with(compact('stokAlert', 'pemakaianHariIni', 'unreadMessages'));
            }
        });

      // Di dalam function boot()
View::composer('layouts.components.sidebar', function ($view) {
    $stokKritisCount = StokOutlet::where('stok', '<=', 10)
        ->whereNotExists(function ($query) {
            $query->select(\DB::raw(1))
                ->from('distribusis')
                ->whereRaw('distribusis.outlet_id = stok_outlets.outlet_id')
                ->whereRaw('distribusis.bahan_id = stok_outlets.bahan_id')
                ->where('status', 'dikirim'); // HARUS SAMA: 'dikirim'
        })->count();
    
    $view->with('stokKritisCount', $stokKritisCount);
});
    }
}