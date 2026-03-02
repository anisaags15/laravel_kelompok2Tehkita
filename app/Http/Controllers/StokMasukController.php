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

            StokMasuk::create([
                'bahan_id' => $request->bahan_id,
                'jumlah'   => $request->jumlah,
                'tanggal'  => $request->tanggal,
            ]);

            $bahan = Bahan::findOrFail($request->bahan_id);
            $bahan->stok_awal += $request->jumlah;
            $bahan->save();
        });

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Stok masuk berhasil ditambahkan');
    }

    /**
     * Form edit
     */
    public function edit(StokMasuk $stok_masuk)
    {
        $bahans = Bahan::all();
        return view('admin.stok-masuk.edit', compact('stok_masuk', 'bahans'));
    }

    /**
     * Update stok masuk + penyesuaian stok bahan
     */
    public function update(Request $request, StokMasuk $stok_masuk)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        DB::transaction(function () use ($request, $stok_masuk) {

            // Kurangi stok lama dulu
            $bahanLama = $stok_masuk->bahan;
            $bahanLama->stok_awal -= $stok_masuk->jumlah;
            $bahanLama->save();

            // Update data stok masuk
            $stok_masuk->update([
                'bahan_id' => $request->bahan_id,
                'jumlah'   => $request->jumlah,
                'tanggal'  => $request->tanggal,
            ]);

            // Tambahkan stok baru
            $bahanBaru = Bahan::findOrFail($request->bahan_id);
            $bahanBaru->stok_awal += $request->jumlah;
            $bahanBaru->save();
        });

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Data stok masuk berhasil diperbarui');
    }

    /**
     * Hapus stok masuk
     */
    public function destroy(StokMasuk $stok_masuk)
    {
        DB::transaction(function () use ($stok_masuk) {

            $bahan = $stok_masuk->bahan;
            $bahan->stok_awal -= $stok_masuk->jumlah;
            $bahan->save();

            $stok_masuk->delete();
        });

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Data stok masuk berhasil dihapus');
    }
}