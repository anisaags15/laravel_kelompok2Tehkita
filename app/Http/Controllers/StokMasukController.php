<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
     * Simpan stok masuk + update stok bahan
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        DB::transaction(function () use ($request) {

            // 1️⃣ Simpan stok masuk
            StokMasuk::create([
                'bahan_id' => $request->bahan_id,
                'jumlah'   => $request->jumlah,
                'tanggal'  => $request->tanggal,
            ]);

            // 2️⃣ Tambahkan stok bahan
            $bahan = Bahan::findOrFail($request->bahan_id);
            $bahan->stok_awal += $request->jumlah;
            $bahan->save();
        });

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Stok masuk berhasil ditambahkan & stok bahan diperbarui');
    }

    /**
     * Hapus data stok masuk
     */
    public function destroy(StokMasuk $stok_masuk)
    {
        DB::transaction(function () use ($stok_masuk) {

            // kurangi stok bahan
            $bahan = $stok_masuk->bahan;
            $bahan->stok_awal -= $stok_masuk->jumlah;
            $bahan->save();

            // hapus stok masuk
            $stok_masuk->delete();
        });

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Data stok masuk berhasil dihapus');
    }
}
