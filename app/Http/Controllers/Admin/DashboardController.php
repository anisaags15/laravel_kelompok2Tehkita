<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Outlet;
use App\Models\Bahan;
use App\Models\StokMasuk;
use App\Models\Distribusi;
use App\Models\Pemakaian;
use App\Models\StokOutlet;
use App\Models\User;
use App\Models\Message;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // ✅ SEARCH QUERY
        $search = $request->get('search');

        /*
        |--------------------------------------------------------------------------
        | 1. STATISTIK UTAMA
        |--------------------------------------------------------------------------
        */
        $outlet     = Outlet::count();
        $bahan      = Bahan::count();
        $stokMasuk  = StokMasuk::count();
        $distribusi = Distribusi::count();

        /*
        |--------------------------------------------------------------------------
        | 2. RADAR STOK KRITIS
        |--------------------------------------------------------------------------
        */
        $stokKritis = StokOutlet::with(['outlet', 'bahan'])
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc')
            ->get();

        $stokKritisCount = $stokKritis->count();
        $stokPusatKritis = Bahan::where('stok_awal', '<=', 50)->count();

        /*
        |--------------------------------------------------------------------------
        | 3. DATA TERBARU — filter by search jika ada
        |--------------------------------------------------------------------------
        */
        $outletsQuery = Outlet::latest();
        if ($search) {
            $outletsQuery->where('nama_outlet', 'like', "%{$search}%");
        }
        $outlets = $outletsQuery->take(5)->get();

        $latestDistribusiQuery = Distribusi::with(['outlet', 'bahan'])->latest();
        if ($search) {
            $latestDistribusiQuery->where(function($q) use ($search) {
                $q->whereHas('outlet', fn($o) => $o->where('nama_outlet', 'like', "%{$search}%"))
                  ->orWhereHas('bahan',  fn($b) => $b->where('nama_bahan',  'like', "%{$search}%"));
            });
        }
        $latestDistribusi = $latestDistribusiQuery->take(5)->get();

        $latestStokMasukQuery = StokMasuk::with('bahan')->latest('tanggal');
        if ($search) {
            $latestStokMasukQuery->whereHas('bahan', fn($b) => $b->where('nama_bahan', 'like', "%{$search}%"));
        }
        $latestStokMasuk = $latestStokMasukQuery->take(5)->get();

        // ✅ Search result: bahan
        $searchBahan = $search
            ? Bahan::where('nama_bahan', 'like', "%{$search}%")->take(5)->get()
            : collect();

        /*
        |--------------------------------------------------------------------------
        | 4. DATA CHAT
        |--------------------------------------------------------------------------
        */
        $unreadCount = 0;
        $latestChats = collect();

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
        |--------------------------------------------------------------------------
        | 5. DATA GRAFIK PEMAKAIAN (7 HARI TERAKHIR)
        |--------------------------------------------------------------------------
        */
        $labelsRaw = [];
        $labelsFormatted = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labelsRaw[]       = $date->format('Y-m-d');
            $labelsFormatted[] = $date->format('d M');
        }

        $outletList = Outlet::take(5)->get();
        $datasets   = [];
        $colors     = ['#198754', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'];

        foreach ($outletList as $index => $o) {
            $dataPemakaian = [];
            foreach ($labelsRaw as $tgl) {
                $total = Pemakaian::where('outlet_id', $o->id)
                    ->whereDate('tanggal', $tgl)
                    ->sum('jumlah');
                $dataPemakaian[] = (int) $total;
            }
            $color      = $colors[$index] ?? '#' . substr(md5($o->id), 0, 6);
            $datasets[] = [
                'label'            => $o->nama_outlet,
                'data'             => $dataPemakaian,
                'borderColor'      => $color,
                'backgroundColor'  => $color . '33',
                'tension'          => 0.4,
                'fill'             => true,
                'pointRadius'      => 4,
                'pointHoverRadius' => 6,
            ];
        }

        $pemakaianChart = [
            'labels'   => $labelsFormatted,
            'datasets' => $datasets,
        ];

        /*
        |--------------------------------------------------------------------------
        | 6. DATA KALENDER DISTRIBUSI
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
        | 7. MONITORING REAL-TIME OUTLET
        |--------------------------------------------------------------------------
        */
        $monitoringOutlets = Outlet::withSum(['pemakaian' => function ($query) {
            $query->whereDate('tanggal', today());
        }], 'jumlah')->get()->map(function ($outlet) {
            $target     = $outlet->target_pemakaian_harian ?? 100;
            $realisasi  = $outlet->pemakaian_sum_jumlah ?? 0;
            $persentase = ($target > 0) ? ($realisasi / $target) * 100 : 0;

            if ($persentase >= 100) {
                $status = 'Over Limit'; $warna = 'danger';
            } elseif ($persentase >= 80) {
                $status = 'Waspada'; $warna = 'warning';
            } else {
                $status = 'Aman'; $warna = 'success';
            }

            return (object) [
                'nama'       => $outlet->nama_outlet,
                'target'     => $target,
                'realisasi'  => $realisasi,
                'persentase' => $persentase,
                'status'     => $status,
                'warna'      => $warna,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | 8. OUTLET TERAKTIF
        |--------------------------------------------------------------------------
        */
        $outletTeraktif = Pemakaian::select('outlet_id', DB::raw('SUM(jumlah) as total'))
            ->with('outlet')
            ->whereBetween('tanggal', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('outlet_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get()
            ->map(fn($item) => (object) [
                'nama_outlet' => $item->outlet->nama_outlet ?? 'Unknown Outlet',
                'total'       => (int) $item->total,
            ]);

        /*
        |--------------------------------------------------------------------------
        | 9. PREDIKSI STOK (SMART REORDER)
        |--------------------------------------------------------------------------
        */
        $bahanPusat          = Bahan::all();
        $rekomendasiRestock  = [];

        foreach ($bahanPusat as $b) {
            $totalKeluar   = Distribusi::where('bahan_id', $b->id)
                ->where('tanggal', '>=', now()->subDays(7))
                ->sum('jumlah');
            $rataRataHarian = $totalKeluar / 7;

            if ($rataRataHarian > 0) {
                $sisaHari = $b->stok_awal / $rataRataHarian;
                if ($sisaHari <= 3) {
                    $rekomendasiRestock[] = (object) [
                        'nama'       => $b->nama_bahan,
                        'sisa_stok'  => $b->stok_awal,
                        'estimasi'   => round($sisaHari),
                        'saran_beli' => ceil(($rataRataHarian * 7) - $b->stok_awal) . ' ' . $b->satuan,
                    ];
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */
        return view('admin.dashboard', compact(
            'rekomendasiRestock',
            'outlet', 'bahan', 'stokMasuk', 'distribusi',
            'stokKritis', 'stokKritisCount',
            'stokPusatKritis',
            'outlets', 'latestDistribusi', 'latestStokMasuk',
            'unreadCount', 'latestChats',
            'pemakaianChart', 'calendarEvents',
            'monitoringOutlets',
            'outletTeraktif',
            'search', 'searchBahan'  // ✅ search vars
        ));
    }
}