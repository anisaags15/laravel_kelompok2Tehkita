<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Bahan;
use Illuminate\Http\Request;

class StokMasukController extends Controller
{
    /**
     * Menampilkan daftar stok masuk
     */
    public function index()
    {
        $stokMasuks = StokMasuk::with('bahan')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.stok-masuk.index', compact('stokMasuks'));
    }

    /**
     * Form tambah stok masuk
     */
    public function create()
    {
        $bahans = Bahan::all();
        return view('admin.stok-masuk.create', compact('bahans'));
    }

    /**
     * Simpan stok masuk
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        StokMasuk::create([
            'bahan_id' => $request->bahan_id,
            'jumlah'   => $request->jumlah,
            'tanggal'  => $request->tanggal,
        ]);

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Stok masuk berhasil ditambahkan');
    }

    /**
     * Hapus data stok masuk
     */
    public function destroy($id)
    {
        StokMasuk::findOrFail($id)->delete();

        return redirect()->route('stok-masuk.index')
            ->with('success', 'Data stok masuk berhasil dihapus');
    }
}