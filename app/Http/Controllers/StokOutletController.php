<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\StokOutlet;
use Illuminate\Http\Request;

class StokOutletController extends Controller
{
    public function index(Request $request)
    {
        $outlets = Outlet::all();

        $stokOutlets = StokOutlet::with('bahan', 'outlet')
            ->when($request->outlet_id, function ($query) use ($request) {
                $query->where('outlet_id', $request->outlet_id);
            })
            ->get();

        return view('stok_outlet.index', compact('outlets', 'stokOutlets'));
    }
}
