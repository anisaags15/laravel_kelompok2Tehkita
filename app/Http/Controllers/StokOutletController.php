<?php

namespace App\Http\Controllers;

use App\Models\StokOutlet;
use Illuminate\Http\Request;

class StokOutletController extends Controller
{
    public function index()
    {
        $stok = StokOutlet::all();
        return view('stok_outlet.index', compact('stok'));
    }

    public function create()
    {
        return view('stok_outlet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_outlet' => 'required',
            'nama_barang' => 'required',
            'stok' => 'required|integer',
        ]);

        StokOutlet::create($request->all());
        return redirect()->route('stok-outlet.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function edit(StokOutlet $stok_outlet)
    {
        return view('stok_outlet.edit', compact('stok_outlet'));
    }

    public function update(Request $request, StokOutlet $stok_outlet)
    {
        $request->validate([
            'nama_outlet' => 'required',
            'nama_barang' => 'required',
            'stok' => 'required|integer',
        ]);

        $stok_outlet->update($request->all());
        return redirect()->route('stok-outlet.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(StokOutlet $stok_outlet)
    {
        $stok_outlet->delete();
        return redirect()->route('stok-outlet.index')->with('success', 'Data berhasil dihapus');
    }
}
