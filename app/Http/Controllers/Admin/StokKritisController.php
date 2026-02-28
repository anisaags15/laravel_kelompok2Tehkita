<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokOutlet; 
use App\Models\Outlet;
use Illuminate\Http\Request;

class StokKritisController extends Controller
{
    public function index()
    {
        // 1. Ambil data stok yang kritis (DIREVISI: Hapus .kategori)
        $stokKritis = StokOutlet::with(['outlet', 'bahan'])
                        ->where('stok', '<=', 10) 
                        ->orderBy('stok', 'asc')
                        ->get();

        // 2. Ambil data outlet (untuk keperluan filter jika ada)
        $outlets = Outlet::all();

        // 3. Ambil stok kritis count untuk cadangan (opsional jika view composer bermasalah)
        $stokKritisCount = $stokKritis->count();

        // 4. Lempar ke view
return view('admin.stok-kritis.index', compact('stokKritis', 'outlets', 'stokKritisCount'));
    }
}