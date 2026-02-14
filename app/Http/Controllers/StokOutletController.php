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

        // VIEW ADMIN
        return view('user.stok-outlet.index', compact('outlets', 'stokOutlets'));
    }

    /*
    |--------------------------------------------------------------------------
    | USER (ADMIN OUTLET) - Lihat Stok Outlet Sendiri
    |--------------------------------------------------------------------------
    */
    public function indexUser()
    {
        $outletId = Auth::user()->outlet_id;

        $stokOutlets = StokOutlet::with(['bahan', 'outlet'])
            ->where('outlet_id', $outletId)
            ->get();

        // VIEW USER
        return view('user.stok-outlet.index', compact('stokOutlets'));
    }
}
