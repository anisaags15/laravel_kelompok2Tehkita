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
     * Mengarah ke halaman tabel log pemakaian
     */
    public function index()
    {
        $user = Auth::user();
        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('tipe', 'rutin') // Memastikan hanya pemakaian rutin yang muncul di log utama
            ->latest()
            ->paginate(10); 

        return view('user.pemakaian.index', compact('pemakaians'));
    }

    /**
     * FORM: Input Pemakaian Rutin
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
     * SIMPAN: Setelah klik simpan, lari ke Log Pemakaian
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
                'bahan_id'   => $request->bahan_id,
                'outlet_id'  => $user->outlet_id,
                'jumlah'     => $request->jumlah,
                'tanggal'    => $request->tanggal,
                'tipe'       => 'rutin',
            ]);

            $stokOutlet->decrement('stok', $request->jumlah);

            // Logika Notifikasi Target Harian & Stok Kritis tetap dipertahankan
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

            if ($stokOutlet->stok <= 10) {
                $admins = User::where('role', 'admin')->get();
                Notification::send($admins, new StokKritisNotification($stokOutlet));
            }

            DB::commit();

            // REDIRECT KE LOG PEMAKAIAN (Kuning di sidebar-mu)
            return redirect()->route('user.riwayat_pemakaian')->with('success', 'Data pemakaian berhasil masuk ke log.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * Sinkronisasi Redirect
     */
    public function show($id)
    {
        return redirect()->route('user.riwayat_pemakaian');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $pemakaian = Pemakaian::where('outlet_id', $user->outlet_id)->findOrFail($id);
        
        return view('user.pemakaian.edit', compact('pemakaian'));
    }

    /**
     * UPDATE: Setelah edit, lari kembali ke Log Pemakaian
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $user = Auth::user();
        $pemakaian = Pemakaian::where('outlet_id', $user->outlet_id)->findOrFail($id);
        
        try {
            DB::beginTransaction();

            $stokOutlet = StokOutlet::where('outlet_id', $user->outlet_id)
                ->where('bahan_id', $pemakaian->bahan_id)
                ->lockForUpdate()
                ->first();

            $stokTersediaSaatIni = $stokOutlet->stok + $pemakaian->jumlah;

            if ($stokTersediaSaatIni < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk perubahan ini.');
            }

            $stokOutlet->update(['stok' => $stokTersediaSaatIni - $request->jumlah]);

            $pemakaian->update([
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal
            ]);

            DB::commit();
            return redirect()->route('user.riwayat_pemakaian')->with('success', 'Perubahan data berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    /**
     * DESTROY: Hapus dan tetap di Log Pemakaian
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $pemakaian = Pemakaian::where('outlet_id', $user->outlet_id)->findOrFail($id);
        
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
            
            return redirect()->route('user.riwayat_pemakaian')->with('success', 'Data dihapus dan stok dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus.');
        }
    }

    // --- BAGIAN WASTE ---
    
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

            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new WasteBaruNotification($waste));

            DB::commit();
            return redirect()->back()->with('success', 'Laporan waste terkirim!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses waste.');
        }
    }

    // --- BAGIAN ADMIN PUSAT ---

    public function indexPusat()
    {
        $allWastes = Pemakaian::with(['bahan', 'outlet'])
            ->where('tipe', 'waste')
            ->latest()
            ->paginate(15);

        $totalPending = Pemakaian::where('tipe', 'waste')->where('status', 'pending')->count();
        $totalWaste = Pemakaian::where('tipe', 'waste')->whereMonth('tanggal', now()->month)->sum('jumlah');

        return view('admin.waste.index', compact('allWastes', 'totalPending', 'totalWaste'));
    }

    public function verifyWaste($id)
    {
        $waste = Pemakaian::where('tipe', 'waste')->findOrFail($id);
        $waste->update(['status' => 'verified']);

        return redirect()->back()->with('success', 'Laporan waste telah diverifikasi.');
    }
}