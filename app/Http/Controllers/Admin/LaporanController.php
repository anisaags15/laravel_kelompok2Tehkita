<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Distribusi;
use App\Models\StokMasuk;
use App\Models\Pemakaian;

class LaporanController extends Controller
{
    public function cetakPDF()
    {
        $distribusi = Distribusi::with(['outlet','bahan'])->get();
        $stokMasuk  = StokMasuk::with('bahan')->get();
        $pemakaian  = Pemakaian::with(['outlet','bahan'])->get();

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('distribusi','stokMasuk','pemakaian'));
        return $pdf->download('laporan-lengkap.pdf');
    }
}