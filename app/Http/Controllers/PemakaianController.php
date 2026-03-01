<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use App\Models\StokOutlet;
use App\Models\Message;
use App\Models\User;
use App\Models\Waste;
use App\Notifications\WasteBaruNotification;
use App\Notifications\StokKritisNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class PemakaianController extends Controller
{
    /**
     * 1. RIWAYAT PEMAKAIAN RUTIN (OUTLET)
     */
    public function index()
    {
        $user = Auth::user();
        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('tipe', 'rutin') 
            ->latest()
            ->paginate(10); 

        return view('user.pemakaian.index', compact('pemakaians'));
    }

    /**
     * 2. RIWAYAT WASTE (OUTLET)
     */
    public function indexWaste()
    {
        $user = Auth::user();
        $wastes = Waste::with('stokOutlet.bahan')
            ->where('outlet_id', $user->outlet_id)
            ->latest()
            ->paginate(10);

        return view('user.pemakaian.index_waste', compact('wastes'));
    }

    /**
     * 3. FORM INPUT RUTIN
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
     * 4. FORM INPUT WASTE
     */
    public function createWaste()
    {
        $user = Auth::user();
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        $wasteBulanIni = Waste::where('outlet_id', $user->outlet_id)
            ->whereMonth('tanggal', now()->month)
            ->count(); // Menghitung berapa kali laporan bulan ini

        return view('user.pemakaian.create_waste', compact('stokOutlets', 'wasteBulanIni'));
    }

    /**
     * 5. SIMPAN PEMAKAIAN RUTIN
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|numeric|min:0.01',
            'tanggal'  => 'required|date',
        ]);

        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            $stokOutlet = StokOutlet::with('bahan')->where('outlet_id', $user->outlet_id)
                ->where('bahan_id', $request->bahan_id)
                ->lockForUpdate() 
                ->first();

            if (!$stokOutlet || $stokOutlet->stok < $request->jumlah) {
                return redirect()->back()->with('error', 'Maaf, stok bahan tidak mencukupi.');
            }

            Pemakaian::create([
                'bahan_id'   => $request->bahan_id,
                'outlet_id'  => $user->outlet_id,
                'jumlah'     => $request->jumlah,
                'tanggal'    => $request->tanggal,
                'tipe'       => 'rutin',
            ]);

            $stokOutlet->decrement('stok', $request->jumlah);
            
            $this->handleBotNotifications($user, $stokOutlet, $request->jumlah, $request->tanggal);

            DB::commit();
            return redirect()->route('user.riwayat_pemakaian')->with('success', 'Data pemakaian berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * 6. SIMPAN WASTE + NOTIFIKASI LONCENG
     */
    public function storeWaste(Request $request)
    {
        $request->validate([
            'stok_outlet_id' => 'required|exists:stok_outlets,id',
            'jumlah'         => 'required|numeric|min:0.01',
            'keterangan'     => 'required|string', 
            'foto'           => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $stokOutlet = StokOutlet::with('bahan')->findOrFail($request->stok_outlet_id);

        if ($stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Gagal! Stok di outlet tidak mencukupi.');
        }

        $fotoPath = null;
        try {
            DB::beginTransaction();

            // 1. Handle Upload Foto
            if ($request->hasFile('foto')) {
                $namaFile = time() . '_' . $user->id . '.' . $request->file('foto')->extension();
                $fotoPath = $request->file('foto')->storeAs('waste_photos', $namaFile, 'public');
            }

            // 2. Simpan ke Database
            $waste = Waste::create([
                'outlet_id'      => $user->outlet_id,
                'stok_outlet_id' => $request->stok_outlet_id,
                'jumlah'         => $request->jumlah,
                'tanggal'        => now(),
                'keterangan'     => $request->keterangan,
                'foto'           => $fotoPath,
                'status'         => 'pending',
            ]);

            // 3. Potong Stok Sementara
            $stokOutlet->decrement('stok', $request->jumlah);

            // 4. Kirim Notifikasi ke Admin
            $adminPusat = User::where('role', 'admin')->first();
            if ($adminPusat) {
                // Notifikasi Pesan (Chat System)
                Message::create([
                    'sender_id'   => $user->id,
                    'receiver_id' => $adminPusat->id,
                    'subject'     => 'NOTIFIKASI WASTE',
                    'message'     => "[SISTEM] Laporan Waste Baru: {$stokOutlet->bahan->nama_bahan} dari {$user->outlet->nama_outlet}",
                    'is_read'     => 0
                ]);

                // Notifikasi Lonceng (Laravel Notification)
                $waste->load(['outlet', 'stokOutlet.bahan']); // Penting: Load relasi agar notifikasi tidak error
                $adminPusat->notify(new WasteBaruNotification($waste)); 
            }

            DB::commit();
            return redirect()->route('user.waste.index')->with('success', 'Laporan waste berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Hapus foto jika DB gagal agar tidak menyampah di storage
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * 7. ADMIN PUSAT - MONITORING WASTE
     */
    public function indexPusat()
    {
        $allWastes = Waste::with(['stokOutlet.bahan', 'outlet'])
            ->latest()
            ->paginate(15);

        $totalPending = Waste::where('status', 'pending')->count();
        $totalWasteBulanIni = Waste::whereMonth('tanggal', now()->month)->sum('jumlah');

        return view('admin.waste.index', compact('allWastes', 'totalPending', 'totalWasteBulanIni'));
    }

    /**
     * 8. VERIFIKASI WASTE OLEH ADMIN
     */
    public function verifyWaste($id)
    {
        try {
            $waste = Waste::findOrFail($id);
            $waste->update(['status' => 'verified']);

            return redirect()->back()->with('success', 'Laporan waste berhasil diverifikasi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * BOT NOTIFICATION HANDLER (RUTIN)
     */
    private function handleBotNotifications($user, $stokOutlet, $jumlah, $tanggal) 
    {
        $stokSekarang = $stokOutlet->stok;
        $adminPusat = User::where('role', 'admin')->first();
        
        if(!$adminPusat) return;

        // Jika stok kritis atau habis
        if ($stokSekarang <= 5) {
            $status = ($stokSekarang <= 0) ? "🚨 *STOK HABIS*" : "⚠️ *STOK KRITIS*";
            
            Message::create([
                'sender_id' => $user->id, 
                'receiver_id' => $adminPusat->id,
                'subject' => 'NOTIFIKASI SISTEM',
                'message' => "[SISTEM NOTIFIKASI]\n\n{$status}\nOutlet: {$user->outlet->nama_outlet}\nBahan: {$stokOutlet->bahan->nama_bahan}\nSisa Stok: {$stokSekarang}",
                'is_read' => 0
            ]);

            // Bisa juga pemicu StokKritisNotification di sini jika diinginkan
        }
    }
}