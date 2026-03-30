<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokOutlet; 
use App\Models\Outlet;
use Illuminate\Http\Request;
use DB;

class StokKritisController extends Controller
{
    public function index(Request $request)
    {
        // 1. Ambil Summary per Outlet (Berapa banyak bahan kritis di tiap outlet)
        $laporanOutlet = StokOutlet::select('outlet_id', DB::raw('count(*) as total_item_kritis'))
            ->with('outlet')
            ->where('stok', '<=', 10) // Sesuai kodinganmu sebelumnya
            ->when($request->outlet_id, function($query) use ($request) {
                return $query->where('outlet_id', $request->outlet_id);
            })
            ->groupBy('outlet_id')
            ->get();

        // 2. Data pendukung untuk filter dan dashboard
        $outlets = Outlet::all();
        $totalKritisGlobal = StokOutlet::where('stok', '<=', 10)->count();

        return view('admin.laporan.stok-kritis.index', compact('laporanOutlet', 'outlets', 'totalKritisGlobal'));
    }

    public function detail($id)
    {
        $outlet = Outlet::findOrFail($id);
        
        // Ambil detail bahan apa saja yang kritis di outlet ini
        $detailStok = StokOutlet::with('bahan')
            ->where('outlet_id', $id)
            ->where('stok', '<=', 10)
            ->get();

        return view('admin.laporan.stok-kritis.detail', compact('outlet', 'detailStok'));
    }
}