<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\Distribusi;
use App\Models\Pemakaian;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | 1️⃣ STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */

        $outlet     = Outlet::count();
        $bahan      = Bahan::count();
        $stokMasuk  = StokMasuk::count();
        $distribusi = Distribusi::count();


        /*
        |--------------------------------------------------------------------------
        | 2️⃣ DATA TERBARU
        |--------------------------------------------------------------------------
        */

        $outlets = Outlet::latest()->take(5)->get();

        $latestDistribusi = Distribusi::with(['outlet', 'bahan'])
            ->latest()
            ->take(5)
            ->get();

        $latestStokMasuk = StokMasuk::with('bahan')
            ->latest('tanggal')
            ->take(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | 3️⃣ DATA CHAT
        |--------------------------------------------------------------------------
        */

        $unreadCount = 0;
        $latestChats = collect();

        if (class_exists(Chat::class)) {

            $unreadCount = Chat::where('receiver_id', Auth::id())
                ->where('is_read', 0)
                ->count();

            $latestChats = Chat::with('sender')
                ->latest()
                ->take(5)
                ->get();
        }


        /*
        |--------------------------------------------------------------------------
        | 4️⃣ DATA GRAFIK PEMAKAIAN
        |--------------------------------------------------------------------------
        */

        $outletList = Outlet::take(5)->get();

        $labels = Pemakaian::select('tanggal')
            ->distinct()
            ->orderBy('tanggal')
            ->pluck('tanggal')
            ->toArray();

        $datasets = [];

        foreach ($outletList as $o) {

            $dataPemakaian = Pemakaian::where('outlet_id', $o->id)
                ->orderBy('tanggal')
                ->pluck('jumlah')
                ->toArray();

            $datasets[] = [
                'label' => $o->nama_outlet,
                'data' => $dataPemakaian,
                'borderColor' => '#' . substr(md5($o->id), 0, 6),
                'backgroundColor' => 'transparent',
                'tension' => 0.4,
                'fill' => false,
            ];
        }

        $pemakaianChart = [
            'labels' => $labels,
            'datasets' => $datasets
        ];

/*
|--------------------------------------------------------------------------
| 5️⃣ DATA KALENDER DISTRIBUSI (PREMIUM)
|--------------------------------------------------------------------------
*/

$calendarEvents = Distribusi::with('outlet')
    ->get()
    ->map(function ($item) {

        // generate warna unik tiap outlet
        $color = '#' . substr(md5($item->outlet_id), 0, 6);

        return [
            'title' => $item->outlet->nama_outlet,
            'start' => \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d'),
            'backgroundColor' => $color,
            'borderColor' => $color,
            'extendedProps' => [
                'jumlah' => $item->jumlah,
                'url' => route('admin.distribusi.index'),
            ],
        ];
    })
    ->values()
    ->toArray();

        /*
        |--------------------------------------------------------------------------
        | 6️⃣ RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'outlet',
            'bahan',
            'stokMasuk',
            'distribusi',
            'outlets',
            'latestDistribusi',
            'latestStokMasuk',
            'unreadCount',
            'latestChats',
            'pemakaianChart',
            'calendarEvents'
        ));
    }
}
