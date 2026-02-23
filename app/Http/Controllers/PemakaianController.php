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
            'tipe'      => 'rutin',
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
        
        // Ambil stok yang tersedia di outlet tersebut
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        // HITUNG OTOMATIS: Statistik untuk box "Waste Bulan Ini"
        $wasteBulanIni = Pemakaian::where('outlet_id', $user->outlet_id)
            ->where('tipe', 'waste')
            ->whereMonth('tanggal', now()->month)
            ->sum('jumlah');

// Sesuaikan dengan letak folder: user -> pemakaian -> create_waste
return view('user.pemakaian.create_waste', compact('stokOutlets', 'wasteBulanIni'));    }

    /**
     * SIMPAN 2: Logika Waste Management
     */
    public function storeWaste(Request $request)
    {
        // REVISI: Sesuaikan dengan 'name' di View kamu (stok_outlet_id)
        $request->validate([
            'stok_outlet_id' => 'required|exists:stok_outlets,id',
            'jumlah'         => 'required|integer|min:1',
            'keterangan'     => 'required|string', 
        ]);

        $user = Auth::user();
        
        // Cari data stok outletnya
        $stokOutlet = StokOutlet::findOrFail($request->stok_outlet_id);

        // Validasi stok cukup atau tidak
        if ($stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak cukup! Sisa stok: ' . $stokOutlet->stok);
        }

        // Simpan data Waste
        Pemakaian::create([
            'bahan_id'   => $stokOutlet->bahan_id, // Ambil ID Bahan dari stok_outlet
            'outlet_id'  => $user->outlet_id,
            'jumlah'     => $request->jumlah,
            'tanggal'    => now(),
            'tipe'       => 'waste', 
            'keterangan' => $request->keterangan, 
            'status'     => 'pending', 
        ]);

        // Potong stok outlet secara otomatis
        $stokOutlet->decrement('stok', $request->jumlah);

        return redirect()->back()
            ->with('success', 'Laporan waste ' . $request->jumlah . ' item berhasil dikirim ke pusat.');
    }

    /**
     * Hapus data pemakaian
     */
    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        
        // Jika dihapus, stok dikembalikan (Opsional, tergantung kebijakan outlet)
        $stokOutlet = StokOutlet::where('outlet_id', $pemakaian->outlet_id)
            ->where('bahan_id', $pemakaian->bahan_id)
            ->first();

        if ($stokOutlet) {
            $stokOutlet->increment('stok', $pemakaian->jumlah);
        }

        $pemakaian->delete();

        return redirect()->back()->with('success', 'Data dihapus dan stok dikembalikan.');
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN PUSAT AREA
    |--------------------------------------------------------------------------
    */

    public function indexPusat()
    {
        // Total seluruh item yang dibuang (Verified)
        $totalWaste = Pemakaian::where('tipe', 'waste')->where('status', 'verified')->sum('jumlah');
        
        // Total laporan yang butuh perhatian (Pending)
        $totalPending = Pemakaian::where('tipe', 'waste')->where('status', 'pending')->count();
        
        $allWastes = Pemakaian::with(['outlet', 'bahan'])
            ->where('tipe', 'waste')
            ->latest()
            ->get();

        return view('admin.waste.index', compact('totalWaste', 'totalPending', 'allWastes'));
    }

    public function verifyWaste(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected'
        ]);

        $waste = Pemakaian::findOrFail($id);
        
        $waste->update([
            'status' => $request->status
        ]);

        // Jika DITOLAK pusat, stok outlet yang tadi dipotong harus DIKEMBALIKAN
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