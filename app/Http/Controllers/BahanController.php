<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        $bahans = Bahan::all();
        return view('admin.bahan.index', compact('bahans'));
    }

    public function create()
    {
        return view('admin.bahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan'     => 'required|string|max:50',
            'stok_awal'  => 'required|integer|min:0',
        ]);

        Bahan::create([
            'nama_bahan' => $request->nama_bahan,
            'satuan'     => $request->satuan,
            'stok_awal'  => $request->stok_awal,
        ]);

        return redirect()
            ->route('admin.bahan.index')
            ->with('success', 'Bahan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Bahan::findOrFail($id)->delete();

        return redirect()
            ->route('admin.bahan.index')
            ->with('success', 'Bahan berhasil dihapus');
    }
}
