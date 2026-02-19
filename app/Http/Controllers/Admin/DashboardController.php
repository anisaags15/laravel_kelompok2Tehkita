<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\Distribusi;
use App\Models\Pemakaian;
use App\Models\Message;
use App\Models\StokOutlet; // <--- WAJIB ADA INI
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        | 2️⃣ RADAR STOK KRITIS (EARLY WARNING SYSTEM) - NEW! 🚨
        |--------------------------------------------------------------------------
        | Mencari bahan di outlet yang sisa stoknya 5 atau kurang.
        */
        $stokKritis = StokOutlet::with(['outlet', 'bahan'])
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc') // Urutkan dari yang paling sedikit
            ->get();

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ DATA TERBARU
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
        | 4️⃣ DATA CHAT
        |--------------------------------------------------------------------------
        */
        $unreadCount = 0;
        $latestChats = collect();

        // Cek standar apakah model Message ada (untuk safety)
        if (class_exists(Message::class)) {
            $unreadCount = Message::where('receiver_id', Auth::id())
                ->where('is_read', 0)
                ->count();

            $latestChats = Message::with(['sender.outlet', 'receiver.outlet'])
                ->where(function ($query) {
                    $query->where('sender_id', Auth::id())
                          ->orWhere('receiver_id', Auth::id());
                })
                ->latest()
                ->take(10)
                ->get();
        }

/*
/*
|--------------------------------------------------------------------------
| 5️⃣ DATA GRAFIK PEMAKAIAN (REVISI DINAMIS)
|--------------------------------------------------------------------------
*/
// Ambil 7 tanggal terakhir secara berurutan
$labels = Pemakaian::select('tanggal')
    ->distinct()
    ->orderBy('tanggal', 'asc')
    ->take(7) 
    ->pluck('tanggal')
    ->toArray();

$outletList = Outlet::take(5)->get();
$datasets = [];

foreach ($outletList as $index => $o) {
    $dataPemakaian = [];
    
    foreach ($labels as $tgl) {
        $total = Pemakaian::where('outlet_id', $o->id)
            ->where('tanggal', $tgl)
            ->sum('jumlah'); 
        
        $dataPemakaian[] = $total;
    }

    $colors = ['#198754', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];
    $color = $colors[$index] ?? '#' . substr(md5($o->id), 0, 6);

    $datasets[] = [
        'label'           => $o->nama_outlet,
        'data'            => $dataPemakaian,
        'borderColor'     => $color,
        'backgroundColor' => $color . '15', // Efek bayangan halus (lebih tipis)
        'tension'         => 0.4,
        'fill'            => true,
        'pointRadius'     => 4,
        'pointHoverRadius'=> 6,
    ];
}

$pemakaianChart = [
    'labels'   => array_map(function($tgl) { 
        return \Carbon\Carbon::parse($tgl)->format('d M'); 
    }, $labels),
    'datasets' => $datasets
];
        /*
        |--------------------------------------------------------------------------
        | 6️⃣ DATA KALENDER DISTRIBUSI
        |--------------------------------------------------------------------------
        */
        $calendarEvents = Distribusi::with('outlet')
            ->get()
            ->map(function ($item) {
                $color = '#' . substr(md5($item->outlet_id), 0, 6);
                return [
                    'title'           => $item->outlet->nama_outlet ?? 'Outlet Hapus',
                    'start'           => Carbon::parse($item->tanggal)->format('Y-m-d'),
                    'backgroundColor' => $color,
                    'borderColor'     => $color,
                    'extendedProps'   => [
                        'jumlah' => $item->jumlah,
                        'url'    => route('admin.distribusi.index'),
                    ],
                ];
            })
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | 7️⃣ RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('admin.dashboard', compact(
            'outlet', 'bahan', 'stokMasuk', 'distribusi', // Stats
            'stokKritis', // <--- Variabel Baru
            'outlets', 'latestDistribusi', 'latestStokMasuk', // Tables
            'unreadCount', 'latestChats', // Chat
            'pemakaianChart', 'calendarEvents' // Charts
        ));
    }
}