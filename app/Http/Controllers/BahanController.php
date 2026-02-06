<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        $bahans = Bahan::all();
        return view('bahan.index', compact('bahans'));
    }

    public function create()
    {
        return view('bahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required',
            'satuan' => 'required',
            'stok_awal' => 'required|integer',
        ]);

        Bahan::create($request->all());

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil ditambahkan');
    }

    public function destroy($id)
    {
        Bahan::findOrFail($id)->delete();

        return redirect()->route('bahan.index')
            ->with('success', 'Bahan berhasil dihapus');
    }
}
