<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokOutlet; 
use App\Models\Outlet;
use App\Models\Distribusi;
use Illuminate\Http\Request;

class StokKritisController extends Controller
{
public function index(Request $request)
{
    $stokKritisRaw = StokOutlet::with(['outlet', 'bahan'])
        ->where('stok', '<=', 10)
        ->get();

    $stokKritis = $stokKritisRaw->filter(function ($item) {
        // SESUAIKAN: Pakai 'dikirim' sesuai DistribusiController kamu
        $sedangDikirim = \App\Models\Distribusi::where('outlet_id', $item->outlet_id)
            ->where('bahan_id', $item->bahan_id)
            ->where('status', 'dikirim') 
            ->exists();

        return !$sedangDikirim;
    });

    $outlets = Outlet::all();
    return view('admin.stok-kritis.index', compact('stokKritis', 'outlets'));
}
}