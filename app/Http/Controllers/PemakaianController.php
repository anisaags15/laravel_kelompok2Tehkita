<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use App\Models\StokOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemakaianController extends Controller
{
    /**
     * Tampilkan daftar pemakaian (user/outlet)
     */
    public function index()
    {
        $user = Auth::user();

        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('user.pemakaian.index', compact('pemakaians'));
    }

    /**
     * Form input pemakaian bahan
     */
    public function create()
    {
        $user = Auth::user();

        // Ambil stok bahan yang ada di outlet user
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->get();

        return view('user.pemakaian.create', compact('stokOutlets'));
    }

    /**
     * Simpan data pemakaian dan kurangi stok
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        $user = Auth::user();

        // Ambil stok outlet terkait
        $stokOutlet = StokOutlet::where('outlet_id', $user->outlet_id)
            ->where('bahan_id', $request->bahan_id)
            ->first();

        if (!$stokOutlet || $stokOutlet->stok < $request->jumlah) {
            return redirect()->back()
                ->with('error', 'Stok bahan tidak cukup atau belum tersedia.');
        }

        // Simpan pemakaian
        Pemakaian::create([
            'bahan_id'  => $request->bahan_id,
            'outlet_id' => $user->outlet_id,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
        ]);

        // Kurangi stok di outlet
        $stokOutlet->stok -= $request->jumlah;
        $stokOutlet->save();

        return redirect()->route('user.pemakaian.index')
            ->with('success', 'Pemakaian bahan berhasil dicatat');
    }

    /**
     * Hapus data pemakaian (opsional, bisa ditambah stok kembali)
     */
    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);

        // Tambah stok kembali
        $stokOutlet = StokOutlet::where('outlet_id', $pemakaian->outlet_id)
            ->where('bahan_id', $pemakaian->bahan_id)
            ->first();

        if ($stokOutlet) {
            $stokOutlet->stok += $pemakaian->jumlah;
            $stokOutlet->save();
        }

        $pemakaian->delete();

        return redirect()->route('user.pemakaian.index')
            ->with('success', 'Data pemakaian berhasil dihapus');
    }
}
