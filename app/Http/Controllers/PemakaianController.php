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

    public function indexWaste()
    {
        $user = Auth::user();
        $wastes = Waste::with('stokOutlet.bahan')
            ->where('outlet_id', $user->outlet_id)
            ->latest()
            ->paginate(10);

        return view('user.pemakaian.index_waste', compact('wastes'));
    }

    public function create()
    {
        $user = Auth::user();
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        return view('user.pemakaian.create', compact('stokOutlets'));
    }

    public function createWaste()
    {
        $user = Auth::user();
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        $wasteBulanIni = Waste::where('outlet_id', $user->outlet_id)
            ->whereMonth('created_at', now()->month) 
            ->count();

        return view('user.pemakaian.create_waste', compact('stokOutlets', 'wasteBulanIni'));
    }

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
            $stokOutlet->refresh();

            $this->handleBotNotifications($user, $stokOutlet, $request->jumlah, $request->tanggal);

            DB::commit();
            return redirect()->route('user.riwayat_pemakaian')->with('success', 'Data pemakaian berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    public function storeWaste(Request $request)
    {
        $request->validate([
            'stok_outlet_id' => 'required|exists:stok_outlets,id',
            'jumlah'         => 'required|numeric|min:0.01',
            'keterangan'     => 'required|string',
            'foto'           => 'required|image|mimes:jpg,jpeg,png|max:2048',
            
        ]);

        $user       = Auth::user();
        $stokOutlet = StokOutlet::with(['bahan', 'outlet'])->findOrFail($request->stok_outlet_id);

        if ($stokOutlet->stok < $request->jumlah) {
            return redirect()->back()->with('error', 'Gagal! Stok di outlet tidak mencukupi.');
        }

        $fotoPath = null;
        try {
            DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $namaFile = time() . '_' . $user->id . '.' . $request->file('foto')->extension();
                $fotoPath = $request->file('foto')->storeAs('waste_photos', $namaFile, 'public');
            }

        $waste = Waste::create([
            'outlet_id'      => $user->outlet_id,
            'stok_outlet_id' => $request->stok_outlet_id,
            'jumlah'         => $request->jumlah,
            'keterangan'     => $request->keterangan,
            'foto'           => $fotoPath,
            'tanggal'        => now(),      
            'status'         => 'pending',  
        ]);

            $stokOutlet->decrement('stok', $request->jumlah);
            $stokOutlet->refresh();

            $adminPusat = User::where('role', 'admin')->first();
            if ($adminPusat) {

                Message::create([
                    'sender_id'   => $user->id,
                    'receiver_id' => $adminPusat->id,
                    'subject'     => '⚠️ PERLU VERIFIKASI: LAPORAN WASTE',
                    'message'     => "[SISTEM WASTE]\n\nHalo Admin, ada laporan waste baru yang butuh verifikasi segera:\n\n" .
                                     "📍 Outlet: {$user->outlet->nama_outlet}\n" .
                                     "📦 Bahan: {$stokOutlet->bahan->nama_bahan}\n" .
                                     "🔢 Jumlah: {$request->jumlah} {$stokOutlet->bahan->satuan}\n" .
                                     "📝 Alasan: {$request->keterangan}\n\n" .
                                     "Mohon segera dicek.",
                    'is_read'     => 0
                ]);

                $waste->load(['outlet', 'stokOutlet.bahan']);
                $adminPusat->notify(new WasteBaruNotification($waste));
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

    public function indexPusat()
    {
        $allWastes = Waste::with(['stokOutlet.bahan', 'outlet'])
            ->latest()
            ->paginate(15);

        $totalPending = Waste::count(); // ✅ FIX
        $totalWaste   = Waste::whereMonth('created_at', now()->month)->sum('jumlah'); // ✅ FIX

        return view('admin.waste.index', compact('allWastes', 'totalPending', 'totalWaste'));
    }

public function verifyWaste(Request $request, $id)
{
    $waste = Waste::findOrFail($id);
    
    $status = $request->input('status');

    try {
        DB::beginTransaction();

        if ($status === 'rejected') {
            $stokOutlet = StokOutlet::find($waste->stok_outlet_id);
            if ($stokOutlet) {
                $stokOutlet->increment('stok', $waste->jumlah);
            }
            $pesan = 'Laporan waste ditolak dan stok telah dikembalikan ke outlet.';
        } else {
            $pesan = 'Laporan waste berhasil disetujui (Verified).';
        }

        $waste->update([
            'status' => $status
        ]);

        DB::commit();
        return redirect()->back()->with('success', $pesan);

    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
// TARUH INI DI PALING BAWAH SEBELUM TUTUP KURUNG CLASS
    private function handleBotNotifications($user, $stokOutlet, $jumlah, $tanggal)
    {
        $stokSekarang = $stokOutlet->stok;
        
        // Cek stok kritis (misal di bawah 5)
        if ($stokSekarang <= 5) {
            $status = ($stokSekarang <= 0) ? "🚨 STOK HABIS" : "⚠️ STOK KRITIS";
            $adminPusat = User::where('role', 'admin')->first();

            if ($adminPusat) {
                // 1. Kirim pesan ke tabel Message (Chat Internal)
                Message::create([
                    'sender_id'   => $user->id,
                    'receiver_id' => $adminPusat->id,
                    'subject'     => 'NOTIFIKASI SISTEM',
                    'message'     => "[SISTEM]\n{$status}\nOutlet: " . ($user->outlet->nama_outlet ?? 'Outlet') . "\nBahan: {$stokOutlet->bahan->nama_bahan}\nSisa: {$stokSekarang} {$stokOutlet->bahan->satuan}",
                    'is_read'     => 0
                ]);

                // 2. Kirim Notifikasi Laravel (Toast/Bell)
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new StokKritisNotification($stokOutlet));
                }
            }
        }
    }
}