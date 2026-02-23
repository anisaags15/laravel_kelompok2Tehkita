<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Distribusi;
use App\Models\StokOutlet;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1️⃣ INDEX LAPORAN
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return view('user.laporan.index');
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ LAPORAN STOK OUTLET
    |--------------------------------------------------------------------------
    */
    public function stok()
    {
        $outlet = auth()->user()->outlet;

        $stok = StokOutlet::with('bahan')
            ->where('outlet_id', $outlet->id)
            ->orderBy('id', 'asc')
            ->get();

        return view('user.laporan.stok', compact('stok', 'outlet'));
    }

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ CETAK PDF STOK
    |--------------------------------------------------------------------------
    */
    public function cetakStok()
    {
        $outlet = auth()->user()->outlet;

        $stok = StokOutlet::with('bahan')
            ->where('outlet_id', $outlet->id)
            ->orderBy('id', 'asc')
            ->get();

        $pdf = Pdf::loadView('user.laporan.pdf.stok', compact('outlet', 'stok'));

        $filename = 'Laporan_Stok_'.$outlet->nama_outlet.'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | 4️⃣ LAPORAN DISTRIBUSI
    |--------------------------------------------------------------------------
    */
    public function distribusi()
    {
        $outlet = auth()->user()->outlet;

        $distribusi = Distribusi::with('bahan')
            ->where('outlet_id', $outlet->id)
            ->latest()
            ->get();

        return view('user.laporan.distribusi', compact('distribusi', 'outlet'));
    }

    /*
    |--------------------------------------------------------------------------
    | 5️⃣ CETAK PDF DISTRIBUSI
    |--------------------------------------------------------------------------
    */
    public function cetakDistribusi()
    {
        $outlet = auth()->user()->outlet;

        $distribusi = Distribusi::with('bahan')
            ->where('outlet_id', $outlet->id)
            ->latest()
            ->get();

        $pdf = Pdf::loadView('user.laporan.pdf.distribusi', compact('outlet', 'distribusi'));

        $filename = 'Laporan_Distribusi_'.$outlet->nama_outlet.'_'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }

    /*
    |--------------------------------------------------------------------------
    | 6️⃣ RINGKASAN BULANAN
    |--------------------------------------------------------------------------
    */
    public function ringkasan(Request $request)
    {
        $outlet = auth()->user()->outlet;

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $distribusi = Distribusi::where('outlet_id', $outlet->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalDistribusi = $distribusi->sum('jumlah');

        $totalStok = StokOutlet::where('outlet_id', $outlet->id)->sum('stok');

        $stokMenipis = StokOutlet::where('outlet_id', $outlet->id)
            ->where('stok', '<', 10)
            ->count();

        return view('user.laporan.ringkasan', compact(
            'outlet',
            'distribusi',
            'bulan',
            'tahun',
            'totalDistribusi',
            'totalStok',
            'stokMenipis'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | 7️⃣ CETAK PDF RINGKASAN
    |--------------------------------------------------------------------------
    */
    public function cetakRingkasan(Request $request)
    {
        $outlet = auth()->user()->outlet;

        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $distribusi = Distribusi::where('outlet_id', $outlet->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $totalDistribusi = $distribusi->sum('jumlah');

        $totalStok = StokOutlet::where('outlet_id', $outlet->id)->sum('stok');

        $stokMenipis = StokOutlet::where('outlet_id', $outlet->id)
            ->where('stok', '<', 10)
            ->count();

        $pdf = Pdf::loadView('user.laporan.pdf.ringkasan', compact(
            'outlet',
            'distribusi',
            'bulan',
            'tahun',
            'totalDistribusi',
            'totalStok',
            'stokMenipis'
        ));

        $filename = 'Ringkasan_'.$outlet->nama_outlet.'_'.$bulan.'_'.$tahun.'.pdf';

        return $pdf->download($filename);
    }
}