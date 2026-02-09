<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    // TAMPIL DATA
    public function index()
    {
        $bahans = Bahan::all();
        return view('admin.bahan.index', compact('bahans'));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('admin.bahan.create');
    }

    // SIMPAN DATA
public function store(Request $request)
{
    $request->validate([
        'nama_bahan' => 'required',
        'satuan'     => 'required',
        'stok_awal'  => 'required|integer|min:0',
    ]);

    Bahan::create([
        'nama_bahan' => $request->nama_bahan,
        'satuan'     => $request->satuan,
        'stok_awal'  => $request->stok_awal,

        // stok awal = stok sekarang
        'stok'       => $request->stok_awal,
    ]);

    return redirect()
        ->route('admin.bahan.index')
        ->with('success', 'Bahan berhasil ditambahkan');
}


    // FORM EDIT
    public function edit($id)
    {
        $bahan = Bahan::findOrFail($id);

        return view('admin.bahan.edit', compact('bahan'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'satuan'     => 'required',
            'stok_awal'  => 'required|integer',
        ]);

        $bahan = Bahan::findOrFail($id);

        $bahan->update($request->all());

        return redirect()
            ->route('admin.bahan.index')
            ->with('success', 'Data bahan berhasil diupdate');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        Bahan::findOrFail($id)->delete();

        return redirect()
            ->route('admin.bahan.index')
            ->with('success', 'Bahan berhasil dihapus');
    }
}
