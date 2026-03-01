<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use App\Models\StokOutlet;
use App\Models\Message;
use App\Models\User;
use App\Models\Waste; // <-- PASTIKAN INI DI-IMPORT
use App\Notifications\WasteBaruNotification;
use App\Notifications\StokKritisNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class PemakaianController extends Controller
{
    /**
     * 1. RIWAYAT PEMAKAIAN RUTIN
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
     * 2. RIWAYAT WASTE (AMBIL DARI TABEL WASTE)
     */
    public function indexWaste()
    {
        $user = Auth::user();
        // Ambil dari model Waste, bukan Pemakaian
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

        // Hitung total waste bulan ini dari tabel Waste
        $wasteBulanIni = Waste::where('outlet_id', $user->outlet_id)
            ->whereMonth('tanggal', now()->month)
            ->sum('jumlah');

        return view('user.pemakaian.create_waste', compact('stokOutlets', 'wasteBulanIni'));
    }

    /**
     * 5. SIMPAN PEMAKAIAN RUTIN
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
            
            // Logika Bot Over Target & Stok Habis (Sama seperti sebelumnya)
            $this->handleBotNotifications($user, $stokOutlet, $request->jumlah, $request->tanggal);

            DB::commit();
            return redirect()->route('user.riwayat_pemakaian')->with('success', 'Data pemakaian berhasil dicatat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    /**
     * 6. SIMPAN WASTE (KE TABEL WASTE)
     */
    public function storeWaste(Request $request)
    {
        $request->validate([
            'stok_outlet_id' => 'required|exists:stok_outlets,id',
            'jumlah'         => 'required|integer|min:1',
            'keterangan'     => 'required|string', 
            'tanggal'        => 'required|date',
        ]);

        $user = Auth::user();
        $stokOutlet = StokOutlet::with('bahan')->findOrFail($request->stok_outlet_id);

        if ($stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok di outlet tidak mencukupi untuk dilaporkan waste!');
        }

        try {
            DB::beginTransaction();

            // SIMPAN KE TABEL WASTE
            $waste = Waste::create([
                'outlet_id'      => $user->outlet_id,
                'stok_outlet_id' => $request->stok_outlet_id,
                'jumlah'         => $request->jumlah,
                'tanggal'        => $request->tanggal,
                'keterangan'     => $request->keterangan,
                'status'         => 'pending',
            ]);

            // POTONG STOK
            $stokOutlet->decrement('stok', $request->jumlah);
            $stokSekarang = $stokOutlet->stok;

            // BOT NOTIFICATION
            $adminPusat = User::where('role', 'admin')->first();
            if ($adminPusat) {
                $msgBody = "[SISTEM NOTIFIKASI]\n\n⚠️ *LAPORAN WASTE (KERUSAKAN)*\n------------------------------------------\nOutlet: {$user->outlet->nama_outlet}\nBahan: *{$stokOutlet->bahan->nama_bahan}*\nJumlah: {$request->jumlah}\nKeterangan: \"{$request->keterangan}\"\n\n";
                $msgBody .= ($stokSekarang <= 0) ? "❗ *STATUS:* Stok bahan ini HABIS." : "Sisa stok: {$stokSekarang} unit.";

                Message::create([
                    'sender_id'   => $user->id,
                    'receiver_id' => $adminPusat->id,
                    'subject'     => 'NOTIFIKASI SISTEM',
                    'message'     => $msgBody,
                    'is_read'     => 0
                ]);
            }

            DB::commit();
            return redirect()->route('user.waste.index')->with('success', 'Laporan waste berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses waste: ' . $e->getMessage());
        }
    }

    // Helper untuk notifikasi rutin (Opsional agar kode bersih)
    private function handleBotNotifications($user, $stokOutlet, $jumlah, $tanggal) {
        $stokSekarang = $stokOutlet->stok;
        $adminPusat = User::where('role', 'admin')->first();
        if(!$adminPusat) return;

        // Cek Stok Habis
        if ($stokSekarang <= 0) {
            Message::create([
                'sender_id' => $user->id, 'receiver_id' => $adminPusat->id,
                'subject' => 'NOTIFIKASI SISTEM',
                'message' => "[SISTEM NOTIFIKASI]\n\n🚨 *STOK HABIS*\nOutlet: {$user->outlet->nama_outlet}\nBahan: {$stokOutlet->bahan->nama_bahan}\nSegera restock!",
                'is_read' => 0
            ]);
        }
    }

    /**
     * 7. ADMIN PUSAT - INDEX WASTE
     */
    public function indexPusat()
    {
        $allWastes = Waste::with(['stokOutlet.bahan', 'outlet'])
            ->latest()
            ->paginate(15);

        return view('admin.waste.index', compact('allWastes'));
    }

    /**
     * 8. VERIFIKASI WASTE OLEH ADMIN
     */
    public function verifyWaste($id)
    {
        $waste = Waste::findOrFail($id);
        $waste->update(['status' => 'verified']);

        return redirect()->back()->with('success', 'Laporan waste diverifikasi.');
    }
}