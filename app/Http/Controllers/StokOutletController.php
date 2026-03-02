<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\StokOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StokOutletController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADMIN - Lihat Semua Stok Outlet
    |--------------------------------------------------------------------------
    */
public function index(Request $request)
    {
        $outlets = Outlet::all();

        $stokOutlets = StokOutlet::with(['bahan', 'outlet'])
            ->when($request->outlet_id, function ($query) use ($request) {
                $query->where('outlet_id', $request->outlet_id);
            })
            ->get();

        // Arahkan ke view KHUSUS ADMIN
        return view('admin.stok-outlet.index', compact('outlets', 'stokOutlets'));
    }

    public function indexUser()
    {
        $outletId = Auth::user()->outlet_id;

        $stokOutlets = StokOutlet::with(['bahan', 'outlet'])
            ->where('outlet_id', $outletId)
            ->get();

        // Tetap di view USER
        return view('user.stok-outlet.index', compact('stokOutlets'));
    }
}
