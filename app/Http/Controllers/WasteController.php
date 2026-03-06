<?php

namespace App\Http\Controllers;

use App\Models\Waste;
use Illuminate\Http\Request;

class WasteController extends Controller
{
    public function store(Request $request)
    {
        Waste::create([
            'outlet_id' => auth()->user()->outlet_id,
            'stok_outlet_id' => $request->stok_outlet_id,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
        ]);

        return back()->with('success', 'Waste berhasil ditambahkan');
    }
}