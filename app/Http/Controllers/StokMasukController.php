<?php

namespace App\Http\Controllers;

use App\Models\StokMasuk;
use App\Models\Bahan;
use Illuminate\Http\Request;

class StokMasukController extends Controller
{
    /**
     * Tampilkan data stok masuk
     */
    public function index()
    {
        $stokMasuks = StokMasuk::with('bahan')
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.stok-masuk.index', compact('stokMasuks'));
    }

    /**
     * Form tambah stok
     */
    public function create()
    {
        $bahans = Bahan::all();

        return view('admin.stok-masuk.create', compact('bahans'));
    }

    /**
     * Simpan stok masuk + tambah stok bahan
     */
public function store(Request $request)
{
    $request->validate([
        'bahan_id' => 'required|exists:bahans,id',
        'jumlah'   => 'required|integer|min:1',
        'tanggal'  => 'required|date',
    ]);

    // Simpan stok masuk
    StokMasuk::create([
        'bahan_id' => $request->bahan_id,
        'jumlah'   => $request->jumlah,
        'tanggal'  => $request->tanggal,
    ]);

    // Ambil data bahan
    $bahan = Bahan::findOrFail($request->bahan_id);

    // TAMBAH stok (PAKAI stok_awal)
    $bahan->stok_awal = $bahan->stok_awal + $request->jumlah;

    // Simpan perubahan
    $bahan->save();

    return redirect()
        ->route('admin.stok-masuk.index')
        ->with('success', 'Stok berhasil ditambahkan');
}

    /**
     * Hapus data + kurangi stok bahan
     */
    public function destroy($id)
    {
        $stokMasuk = StokMasuk::findOrFail($id);

        // Ambil bahan terkait
        $bahan = Bahan::find($stokMasuk->bahan_id);

        // Kurangi stok bahan
        if ($bahan) {
            $bahan->stok -= $stokMasuk->jumlah;

            // Biar stok gak minus
            if ($bahan->stok < 0) {
                $bahan->stok = 0;
            }

            $bahan->save();
        }

        // Hapus data stok masuk
        $stokMasuk->delete();

        return redirect()
            ->route('admin.stok-masuk.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
