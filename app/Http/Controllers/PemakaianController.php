<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use App\Models\StokOutlet;
use App\Models\Message;
use App\Models\User;
use App\Notifications\WasteBaruNotification;
use App\Notifications\StokKritisNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PemakaianController extends Controller
{
    /**
     * Tampilkan riwayat (Outlet/User)
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
     * FORM 1: Input Pemakaian Rutin (PENTING: Ini yang tadi hilang!)
     */
    public function create()
    {
        $user = Auth::user();
        
        // Mengambil stok yang tersedia di outlet user tersebut
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        return view('user.pemakaian.create', compact('stokOutlets'));
    }

    /**
     * Tampilkan MONITORING WASTE (Admin Pusat)
     */
    public function indexPusat()
    {
        $allWastes = Pemakaian::with(['bahan', 'outlet'])
            ->where('tipe', 'waste')
            ->latest()
            ->paginate(15);

        $totalPending = Pemakaian::where('tipe', 'waste')
            ->where('status', 'pending')
            ->count();

        $totalWaste = Pemakaian::where('tipe', 'waste')
            ->whereMonth('tanggal', now()->month)
            ->sum('jumlah');

        return view('admin.waste.index', compact('allWastes', 'totalPending', 'totalWaste'));
    }

    /**
     * SIMPAN: Logika Pemakaian Rutin
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        $user = Auth::user();
        
        try {
            DB::beginTransaction();

            $stokOutlet = StokOutlet::where('outlet_id', $user->outlet_id)
                ->where('bahan_id', $request->bahan_id)
                ->lockForUpdate() 
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

            // Cek Target Harian
            $outlet = $user->outlet;
            $totalHariIni = Pemakaian::where('outlet_id', $user->outlet_id)
                ->where('tanggal', $request->tanggal)
                ->where('tipe', 'rutin')
                ->sum('jumlah');

            if ($outlet && $outlet->target_pemakaian_harian > 0 && $totalHariIni >= $outlet->target_pemakaian_harian) {
                Message::create([
                    'sender_id'   => $user->id,
                    'receiver_id' => 1, 
                    'subject'     => '⚠️ OVER TARGET',
                    'message'     => "Outlet {$outlet->nama_outlet} mencapai target harian. Total: {$totalHariIni} unit.",
                    'is_read'     => 0
                ]);
            }

            // Cek Stok Kritis
            if ($stokOutlet->stok <= 10) {
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new StokKritisNotification($stokOutlet));
            }

            DB::commit();
            return redirect()->route('user.pemakaian.index')->with('success', 'Pemakaian berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * FORM & SIMPAN WASTE
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

    public function storeWaste(Request $request)
    {
        $request->validate([
            'stok_outlet_id' => 'required|exists:stok_outlets,id',
            'jumlah'         => 'required|integer|min:1',
            'keterangan'     => 'required|string', 
        ]);

        $user = Auth::user();
        $stokOutlet = StokOutlet::with('bahan')->findOrFail($request->stok_outlet_id);

        if ($stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok tidak cukup!');
        }

        try {
            DB::beginTransaction();

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

            // Chat & Notif Lonceng
            Message::create([
                'sender_id'   => $user->id,
                'receiver_id' => 1,
                'subject'     => '🚨 LAPORAN WASTE',
                'message'     => "Waste baru dari {$user->outlet->nama_outlet}: {$stokOutlet->bahan->nama_bahan}.",
                'is_read'     => 0
            ]);

            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new WasteBaruNotification($waste));

            DB::commit();
            return redirect()->back()->with('success', 'Laporan waste terkirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses waste.');
        }
    }

    /**
     * VERIFIKASI WASTE (Admin Pusat)
     */
    public function verifyWaste($id)
    {
        $waste = Pemakaian::where('tipe', 'waste')->findOrFail($id);
        $waste->update(['status' => 'verified']);

        return redirect()->back()->with('success', 'Laporan waste telah diverifikasi.');
    }

    /**
     * HAPUS DATA
     */
    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        
        try {
            DB::beginTransaction();
            $stokOutlet = StokOutlet::where('outlet_id', $pemakaian->outlet_id)
                ->where('bahan_id', $pemakaian->bahan_id)
                ->first();

            if ($stokOutlet) {
                $stokOutlet->increment('stok', $pemakaian->jumlah);
            }

            $pemakaian->delete();
            
            DB::commit();
            return redirect()->back()->with('success', 'Data dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus.');
        }
    }
}