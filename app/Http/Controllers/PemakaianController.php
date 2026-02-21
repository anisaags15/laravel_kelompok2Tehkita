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
     * Tampilkan daftar riwayat (Campuran Rutin & Waste)
     */
    public function index()
    {
        $user = Auth::user();

        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->latest('tanggal')
            ->latest('created_at')
            ->paginate(10); 

        return view('user.pemakaian.index', compact('pemakaians'));
    }

    /**
     * FORM 1: Input Pemakaian Rutin (Jualan)
     */
    public function create()
    {
        $user = Auth::user();
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        return view('user.pemakaian.create', compact('stokOutlets'));
    }

    /**
     * SIMPAN 1: Logika Pemakaian Rutin
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        $user = Auth::user();
        $stokOutlet = StokOutlet::where('outlet_id', $user->outlet_id)
            ->where('bahan_id', $request->bahan_id)
            ->first();

        if (!$stokOutlet || $stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Maaf, stok bahan tidak mencukupi.');
        }

        Pemakaian::create([
            'bahan_id'  => $request->bahan_id,
            'outlet_id' => $user->outlet_id,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
            'tipe'      => 'rutin', // Penanda jualan normal
        ]);

        $stokOutlet->decrement('stok', $request->jumlah);

        return redirect()->route('user.pemakaian.index')
            ->with('success', 'Pemakaian rutin berhasil dicatat.');
    }

    /**
     * FORM 2: Input Waste (Bahan Rusak/Basi)
     */
    public function createWaste()
    {
        $user = Auth::user();
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        return view('user.pemakaian.create_waste', compact('stokOutlets'));
    }

    /**
     * SIMPAN 2: Logika Waste Management
     */
    public function storeWaste(Request $request)
    {
        $request->validate([
            'bahan_id'   => 'required|exists:bahans,id',
            'jumlah'     => 'required|integer|min:1',
            'keterangan' => 'required|string', // Alasan rusak
        ]);

        $user = Auth::user();
        $stokOutlet = StokOutlet::where('outlet_id', $user->outlet_id)
            ->where('bahan_id', $request->bahan_id)
            ->first();

        if (!$stokOutlet || $stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak cukup untuk dilaporkan rusak.');
        }

        Pemakaian::create([
            'bahan_id'   => $request->bahan_id,
            'outlet_id'  => $user->outlet_id,
            'jumlah'     => $request->jumlah,
            'tanggal'    => now(),
            'tipe'       => 'waste',      // Penanda kerusakan
            'keterangan' => $request->keterangan, 
        ]);

        $stokOutlet->decrement('stok', $request->jumlah);

        return redirect()->route('user.pemakaian.index')
            ->with('success', 'Laporan bahan rusak berhasil disimpan.');
    }

    /**
     * Hapus data pemakaian (Kembalikan stok apapun tipenya)
     */
    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        $stokOutlet = StokOutlet::where('outlet_id', $pemakaian->outlet_id)
            ->where('bahan_id', $pemakaian->bahan_id)
            ->first();

        if ($stokOutlet) {
            $stokOutlet->increment('stok', $pemakaian->jumlah);
        }

        $pemakaian->delete();

        return redirect()->route('user.pemakaian.index')
            ->with('success', 'Data berhasil dihapus dan stok dikembalikan.');
    }
}