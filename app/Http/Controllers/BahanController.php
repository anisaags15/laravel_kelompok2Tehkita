<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    /**
     * INDEX - Menampilkan semua data bahan
     */
    public function index()
    {
        // Diubah menjadi asc agar data baru (ID lebih besar) berada di bawah
        $bahans = Bahan::orderBy('id', 'asc')->get();

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
            ->with('success', 'Bahan berhasil ditambahkan.');
    }

    public function show($id)
    {
        $bahan = Bahan::findOrFail($id);
        return view('admin.bahan.show', compact('bahan'));
    }

    public function edit($id)
    {
        $bahan = Bahan::findOrFail($id);
        return view('admin.bahan.edit', compact('bahan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255',
            'satuan'     => 'required|string|max:50',
            'stok_awal'  => 'required|integer|min:0',
        ]);

        $bahan = Bahan::findOrFail($id);
        $bahan->update([
            'nama_bahan' => $request->nama_bahan,
            'satuan'     => $request->satuan,
            'stok_awal'  => $request->stok_awal,
        ]);

        return redirect()
            ->route('admin.bahan.index')
            ->with('success', 'Bahan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $bahan = Bahan::findOrFail($id);
        $bahan->delete();

        return redirect()
            ->route('admin.bahan.index')
            ->with('success', 'Bahan berhasil dihapus.');
    }
}