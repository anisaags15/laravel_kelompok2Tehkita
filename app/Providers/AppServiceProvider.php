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
        // View composer untuk navbar notifikasi
        View::composer('layouts.components.navbar', function ($view) {

            $user = Auth::user();

            if ($user) {

                // 1️⃣ Stok kurang (hanya untuk user biasa yang punya outlet)
                $stokAlert = collect();
                if ($user->role === 'user') {
                    $stokAlert = StokOutlet::with('bahan')
                        ->where('outlet_id', $user->outlet_id)
                        ->where('stok', '<=', 5)
                        ->get();
                }

                // 2️⃣ Pemakaian hari ini (hanya user biasa)
                $pemakaianHariIni = collect();
                if ($user->role === 'user') {
                    $pemakaianHariIni = Pemakaian::with('bahan')
                        ->where('outlet_id', $user->outlet_id)
                        ->whereDate('tanggal', now())
                        ->get();
                }

                // 3️⃣ Pesan belum dibaca (semua role bisa lihat)
                $unreadMessages = Message::where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->get();

                // Share data ke view
                $view->with(compact('stokAlert', 'pemakaianHariIni', 'unreadMessages'));
            }
        });
    }
}
