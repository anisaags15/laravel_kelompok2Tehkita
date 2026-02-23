<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use App\Models\StokOutlet;
use App\Models\Message; // Tambahin ini buat fitur chat otomatis
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemakaianController extends Controller
{
    /**
     * Tampilkan riwayat (Outlet)
     */
    public function index()
    {
        $user = Auth::user();
        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->latest()
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
     * SIMPAN 1: Logika Pemakaian Rutin + Bot Notifikasi Target
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

        $pemakaian = Pemakaian::create([
            'bahan_id'  => $request->bahan_id,
            'outlet_id' => $user->outlet_id,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
            'tipe'      => 'rutin',
        ]);

        $stokOutlet->decrement('stok', $request->jumlah);

        // --- FITUR GILA 1: CEK TARGET & KIRIM CHAT OTOMATIS ---
        $outlet = $user->outlet;
        $totalHariIni = Pemakaian::where('outlet_id', $user->outlet_id)
            ->where('tanggal', $request->tanggal)
            ->where('tipe', 'rutin')
            ->sum('jumlah');

        if ($outlet && $totalHariIni >= $outlet->target_pemakaian_harian) {
            Message::create([
                'sender_id'   => $user->id,
                'receiver_id' => 1, // Asumsi ID 1 adalah Admin Pusat
                'subject'     => '⚠️ OVER TARGET',
                'message'     => "Halo Pusat! Outlet {$outlet->nama_outlet} baru saja mencapai target harian. Total pemakaian: {$totalHariIni} unit.",
                'is_read'     => 0
            ]);
        }

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

        $wasteBulanIni = Pemakaian::where('outlet_id', $user->outlet_id)
            ->where('tipe', 'waste')
            ->whereMonth('tanggal', now()->month)
            ->sum('jumlah');

        return view('user.pemakaian.create_waste', compact('stokOutlets', 'wasteBulanIni'));
    }

    /**
     * SIMPAN 2: Logika Waste Management + Bot Laporan ke Pusat
     */
    public function storeWaste(Request $request)
    {
        $request->validate([
            'stok_outlet_id' => 'required|exists:stok_outlets,id',
            'jumlah'         => 'required|integer|min:1',
            'keterangan'     => 'required|string', 
        ]);

        $user = Auth::user();
        $stokOutlet = StokOutlet::findOrFail($request->stok_outlet_id);

        if ($stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak cukup! Sisa stok: ' . $stokOutlet->stok);
        }

        $waste = Pemakaian::create([
            'bahan_id'   => $stokOutlet->bahan_id,
            'outlet_id'  => $user->outlet_id,
            'jumlah'     => $request->jumlah,
            'tanggal'    => now(),
            'tipe'       => 'waste', 
            'keterangan' => $request->keterangan, 
            'status'     => 'pending', 
        ]);

        $stokOutlet->decrement('stok', $request->jumlah);

        // --- FITUR GILA 2: CHAT OTOMATIS LAPOR WASTE ---
        Message::create([
            'sender_id'   => $user->id,
            'receiver_id' => 1,
            'subject'     => '🚨 LAPORAN WASTE',
            'message'     => "Ada laporan barang rusak (Waste) dari {$user->outlet->nama_outlet}. Bahan: {$stokOutlet->bahan->nama_bahan}, Jumlah: {$request->jumlah}. Mohon segera diverifikasi!",
            'is_read'     => 0
        ]);

        return redirect()->back()
            ->with('success', 'Laporan waste berhasil dikirim dan Admin Pusat sudah dinotifikasi via Chat.');
    }

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
        return redirect()->back()->with('success', 'Data dihapus dan stok dikembalikan.');
    }

    /* --- ADMIN PUSAT AREA --- */

    public function indexPusat()
    {
        $totalWaste = Pemakaian::where('tipe', 'waste')->where('status', 'verified')->sum('jumlah');
        $totalPending = Pemakaian::where('tipe', 'waste')->where('status', 'pending')->count();
        $allWastes = Pemakaian::with(['outlet', 'bahan'])
            ->where('tipe', 'waste')
            ->latest()
            ->get();

        return view('admin.waste.index', compact('totalWaste', 'totalPending', 'allWastes'));
    }

    public function verifyWaste(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:verified,rejected']);
        $waste = Pemakaian::findOrFail($id);
        
        $waste->update(['status' => $request->status]);

        if ($request->status == 'rejected') {
            $stokOutlet = StokOutlet::where('outlet_id', $waste->outlet_id)
                ->where('bahan_id', $waste->bahan_id)
                ->first();

            if ($stokOutlet) {
                $stokOutlet->increment('stok', $waste->jumlah);
            }
            return redirect()->back()->with('error', 'Laporan ditolak. Stok dikembalikan ke outlet.');
        }

        return redirect()->back()->with('success', 'Laporan waste berhasil diverifikasi.');
    }
}