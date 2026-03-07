<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use App\Models\StokOutlet;
use App\Models\Message;
use App\Models\User;
use App\Models\Waste;
use App\Notifications\WasteBaruNotification;
use App\Notifications\StokKritisNotification; // ✅ Sudah ada, pastikan tidak dihapus
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
            ->count();

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

            $stokOutlet = StokOutlet::with(['bahan', 'outlet'])
                ->where('outlet_id', $user->outlet_id)
                ->where('bahan_id', $request->bahan_id)
                ->lockForUpdate()
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

            // Refresh nilai stok setelah decrement
            $stokOutlet->refresh();

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
        $stokOutlet = StokOutlet::with(['bahan', 'outlet'])->findOrFail($request->stok_outlet_id);

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

            // 3. Potong Stok
            $stokOutlet->decrement('stok', $request->jumlah);

            // Refresh nilai stok setelah decrement
            $stokOutlet->refresh();

            // 4. Kirim Notifikasi ke Admin
            $adminPusat = User::where('role', 'admin')->first();
            if ($adminPusat) {
                // Notifikasi Chat
                Message::create([
                    'sender_id'   => $user->id,
                    'receiver_id' => $adminPusat->id,
                    'subject'     => '⚠️ PERLU VERIFIKASI: LAPORAN WASTE',
                    'message'     => "Halo Admin, ada laporan waste baru yang butuh verifikasi segera:\n\n" .
                                     "📍 Outlet: {$user->outlet->nama_outlet}\n" .
                                     "📦 Bahan: {$stokOutlet->bahan->nama_bahan}\n" .
                                     "🔢 Jumlah: {$request->jumlah} {$stokOutlet->bahan->satuan}\n" .
                                     "📝 Alasan: {$request->keterangan}\n\n" .
                                     "Mohon segera dicek dan lakukan verifikasi pada menu Manajemen Waste. Terima kasih!",
                    'is_read'     => 0
                ]);

                // Notifikasi Lonceng Waste
                $waste->load(['outlet', 'stokOutlet.bahan']);
                $adminPusat->notify(new WasteBaruNotification($waste));

                // ✅ Cek & kirim notifikasi stok kritis setelah waste
                if ($stokOutlet->stok <= 5) {
                    $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new StokKritisNotification($stokOutlet));
                    }
                }
            }

            DB::commit();
            return redirect()->route('user.waste.index')->with('success', 'Laporan waste berhasil dikirim.');

        } catch (\Exception $e) {
            DB::rollBack();
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
        $totalWaste   = Waste::whereMonth('tanggal', now()->month)->sum('jumlah');

        return view('admin.waste.index', compact('allWastes', 'totalPending', 'totalWaste'));
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
     * BOT NOTIFICATION HANDLER (PEMAKAIAN RUTIN)
     * Dipanggil setelah stok di-decrement & di-refresh
     */
    private function handleBotNotifications($user, $stokOutlet, $jumlah, $tanggal)
    {
        $stokSekarang = $stokOutlet->stok;
        $adminPusat   = User::where('role', 'admin')->first();

        if (!$adminPusat) return;

        // Kirim pesan chat sistem jika stok kritis/habis
        if ($stokSekarang <= 5) {
            $status = ($stokSekarang <= 0) ? "🚨 *STOK HABIS*" : "⚠️ *STOK KRITIS*";

            Message::create([
                'sender_id'   => $user->id,
                'receiver_id' => $adminPusat->id,
                'subject'     => 'NOTIFIKASI SISTEM',
                'message'     => "[SISTEM NOTIFIKASI]\n\n{$status}\nOutlet: {$user->outlet->nama_outlet}\nBahan: {$stokOutlet->bahan->nama_bahan}\nSisa Stok: {$stokSekarang}",
                'is_read'     => 0
            ]);

            // ✅ Kirim notifikasi lonceng stok kritis ke SEMUA admin
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new StokKritisNotification($stokOutlet));
            }
        }
    }
}