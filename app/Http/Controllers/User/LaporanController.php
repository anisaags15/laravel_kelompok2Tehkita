<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Distribusi;
use App\Models\StokOutlet;
use App\Models\Waste; 
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * TAMPILAN INDEX LAPORAN
     */
    public function index() 
    { 
        return view('user.laporan.index'); 
    }

    /**
     * LAPORAN STOK (VIEW)
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

    /**
     * LAPORAN WASTE (VIEW)
     */
// Ganti query di LaporanController kamu jadi begini:
public function waste(Request $request)
{
    $outlet = auth()->user()->outlet;
    $bulan = $request->bulan ?? now()->month;
    $tahun = $request->tahun ?? now()->year;

    // AMBIL DARI PEMAKAIAN, BUKAN WASTE!
    $wasteData = \App\Models\Pemakaian::with('bahan')
        ->where('outlet_id', $outlet->id)
        ->where('tipe', 'waste') // Kuncinya di sini
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->latest()
        ->get();
        
    return view('user.laporan.waste', compact('wasteData', 'outlet', 'bulan', 'tahun'));
}
    /**
     * CETAK PDF WASTE
     */
public function wastePdf(Request $request)
{
    $outlet = auth()->user()->outlet;
    // Ambil bulan dan tahun dari request agar sinkron dengan yang tampil di web
    $bulan = $request->bulan ?? now()->month;
    $tahun = $request->tahun ?? now()->year;

    // Pastikan query ini SAMA PERSIS dengan yang ada di fungsi index laporan
    $wasteData = \App\Models\Pemakaian::with('bahan')
        ->where('outlet_id', $outlet->id)
        ->where('tipe', 'waste') // Pastikan tipe ini ada di database
        ->whereMonth('tanggal', $bulan)
        ->whereYear('tanggal', $tahun)
        ->get();

    // Cek apakah data ada sebelum dikirim ke PDF
    if ($wasteData->isEmpty()) {
        // Ini buat jaga-jaga kalau filter bulan/tahun bikin datanya nol
        $wasteData = \App\Models\Pemakaian::with('bahan')
            ->where('outlet_id', $outlet->id)
            ->where('tipe', 'waste')
            ->latest()
            ->limit(10)
            ->get();
    }

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('user.laporan.pdf.laporan_waste_pdf', [
        'wasteData' => $wasteData,
        'outlet' => $outlet,
        'bulan' => $bulan,
        'tahun' => $tahun
    ]);

    return $pdf->stream('Laporan_Waste_' . $outlet->nama_outlet . '.pdf');
}
    // ... (Sisa fungsi distribusi dan ringkasan tetap sama) ...
    
    public function distribusi(Request $request)
    {
        $outlet = auth()->user()->outlet;
        $query = Distribusi::with('bahan')->where('outlet_id', $outlet->id);
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        $distribusi = $query->latest()->get();
        return view('user.laporan.distribusi', compact('distribusi', 'outlet'));
    }

    public function cetakDistribusi(Request $request)
    {
        $outlet = auth()->user()->outlet;
        $query = Distribusi::with('bahan')->where('outlet_id', $outlet->id);
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }
        $distribusi = $query->latest()->get();
        $logoBase64 = $this->getLogoBase64();
        $pdf = Pdf::loadView('user.laporan.pdf.distribusi', compact('outlet', 'distribusi', 'logoBase64'))
            ->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true]);
        return $pdf->download('Laporan_Distribusi_'.$outlet->nama_outlet.'.pdf');
    }

    public function ringkasan(Request $request)
    {
        $outlet = auth()->user()->outlet;
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);
        $distribusi = Distribusi::where('outlet_id', $outlet->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();
        $totalDistribusi = $distribusi->sum('jumlah');
        $totalStok = StokOutlet::where('outlet_id', $outlet->id)->sum('stok');
        $stokMenipis = StokOutlet::where('outlet_id', $outlet->id)->where('stok', '<', 10)->count();
        return view('user.laporan.ringkasan', compact('outlet', 'distribusi', 'bulan', 'tahun', 'totalDistribusi', 'totalStok', 'stokMenipis'));
    }

    public function cetakRingkasan(Request $request)
    {
        $outlet = auth()->user()->outlet;
        $bulan = (int) ($request->bulan ?? now()->month);
        $tahun = (int) ($request->tahun ?? now()->year);
        $namaBulan = Carbon::create()->month($bulan)->translatedFormat('F');
        $distribusi = Distribusi::where('outlet_id', $outlet->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();
        $totalDistribusi = $distribusi->sum('jumlah');
        $totalStok = StokOutlet::where('outlet_id', $outlet->id)->sum('stok');
        $stokMenipis = StokOutlet::where('outlet_id', $outlet->id)->where('stok', '<', 10)->count();
        $logoBase64 = $this->getLogoBase64();
        $pdf = Pdf::loadView('user.laporan.pdf.ringkasan', compact('outlet', 'distribusi', 'bulan', 'tahun', 'totalDistribusi', 'totalStok', 'stokMenipis', 'logoBase64'))->setOption(['isRemoteEnabled' => true]);
        return $pdf->download("Ringkasan_{$outlet->nama_outlet}_{$namaBulan}.pdf");
    }

    private function getLogoBase64()
    {
        try {
            $path = public_path('templates/dist/img/logoDistribusi.png'); 
            if (!file_exists($path)) return null;
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } catch (\Exception $e) { return null; }
    }
}