<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\StokOutlet;
use App\Models\User;
use App\Notifications\PengirimanDiterimaNotification;
use App\Notifications\InfoPengirimanNotification;
use App\Notifications\StokKritisNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DistribusiController extends Controller
{
    // =================== ADMIN ===================

    public function index()
    {
        $distribusis = Distribusi::with(['bahan', 'outlet'])
            ->orderBy('id', 'desc') // Biasanya yang terbaru di atas lebih enak dilihat
            ->get();

        return view('admin.distribusi.index', compact('distribusis'));
    }

    public function create()
    {
        // Ambil bahan yang stoknya lebih dari 0
        $bahans  = Bahan::where('stok_awal', '>', 0)->get();
        $outlets = Outlet::all();

        return view('admin.distribusi.create', compact('bahans', 'outlets'));
    }

    /**
     * REVISI: Store mendukung banyak bahan sekaligus (Massal)
     */
    public function store(Request $request)
    {
        // 1. Validasi awal
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'tanggal'   => 'required|date',
            'items'     => 'required|array|min:1', 
            'items.*.bahan_id' => 'required|exists:bahans,id',
            'items.*.jumlah'   => 'nullable|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $processedCount = 0;

                foreach ($request->items as $item) {
                    // Hanya proses item yang dicentang (checked == 1)
                    if (isset($item['checked']) && $item['checked'] == '1') {
                        
                        if (empty($item['jumlah']) || $item['jumlah'] < 1) {
                            $bahanNama = Bahan::find($item['bahan_id'])->nama_bahan;
                            throw new \Exception("Jumlah untuk bahan {$bahanNama} harus diisi.");
                        }

                        $bahan = Bahan::findOrFail($item['bahan_id']);

                        // 2. Cek Stok Gudang
                        if ($bahan->stok_awal < $item['jumlah']) {
                            throw new \Exception("Stok {$bahan->nama_bahan} tidak mencukupi! (Tersedia: {$bahan->stok_awal})");
                        }

                        // 3. Potong Stok Gudang Utama
                        $bahan->stok_awal -= $item['jumlah'];
                        $bahan->save();

                        // 4. Buat Record Distribusi
                        $distribusi = Distribusi::create([
                            'bahan_id'  => $item['bahan_id'],
                            'outlet_id' => $request->outlet_id,
                            'jumlah'    => $item['jumlah'],
                            'tanggal'   => $request->tanggal . ' ' . now()->format('H:i:s'),
                            'status'    => 'dikirim',
                            'tanggal_diterima' => null,
                        ]);

                        // 5. Kirim Notifikasi ke User Outlet
                        $usersOutlet = User::where('outlet_id', $request->outlet_id)->get();
                        foreach ($usersOutlet as $user) {
                            $user->notify(new InfoPengirimanNotification($distribusi));
                        }

                        $processedCount++;
                    }
                }

                if ($processedCount === 0) {
                    throw new \Exception('Pilih minimal satu bahan untuk dikirim!');
                }
            });

            return redirect()->route('admin.distribusi.index')
                ->with('success', 'Barang-barang berhasil dikirim ke outlet!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        $distribusi = Distribusi::findOrFail($id);

        if ($distribusi->status === 'diterima') {
            return redirect()->route('admin.distribusi.index')
                ->with('error', 'Data sudah diterima, tidak bisa diedit.');
        }

        $bahans  = Bahan::all();
        $outlets = Outlet::all();

        return view('admin.distribusi.edit', compact('distribusi', 'bahans', 'outlets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'bahan_id'  => 'required|exists:bahans,id',
            'outlet_id' => 'required|exists:outlets,id',
            'jumlah'    => 'required|integer|min:1',
            'tanggal'   => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $distribusi = Distribusi::findOrFail($id);

                // Kembalikan stok lama
                $bahanLama = Bahan::find($distribusi->bahan_id);
                $bahanLama->stok_awal += $distribusi->jumlah;
                $bahanLama->save();

                // Cek stok baru
                $bahanBaru = Bahan::find($request->bahan_id);
                if ($bahanBaru->stok_awal < $request->jumlah) {
                    throw new \Exception('Stok gudang tidak cukup!');
                }

                $bahanBaru->stok_awal -= $request->jumlah;
                $bahanBaru->save();

                $distribusi->update([
                    'bahan_id'  => $request->bahan_id,
                    'outlet_id' => $request->outlet_id,
                    'jumlah'    => $request->jumlah,
                    'tanggal'   => $request->tanggal . ' ' . now()->format('H:i:s'),
                ]);
            });

            return redirect()->route('admin.distribusi.index')
                ->with('success', 'Data distribusi diperbarui.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $distribusi = Distribusi::findOrFail($id);

                if ($distribusi->status === 'diterima') {
                    throw new \Exception('Data sudah diterima, tidak bisa dihapus.');
                }

                $bahan = Bahan::find($distribusi->bahan_id);
                $bahan->stok_awal += $distribusi->jumlah;
                $bahan->save();

                $distribusi->delete();
            });

            return redirect()->route('admin.distribusi.index')
                ->with('success', 'Distribusi dibatalkan.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // =================== USER ===================

    public function indexUser()
    {
        $distribusis = Distribusi::with('bahan')
            ->where('outlet_id', auth()->user()->outlet_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.distribusi.index', compact('distribusis'));
    }

    public function terima($id)
    {
        try {
            $distribusi = Distribusi::findOrFail($id);

            if ($distribusi->status === 'diterima') {
                return back()->with('error', 'Barang sudah dikonfirmasi.');
            }

            DB::transaction(function () use ($distribusi) {
                $distribusi->update([
                    'status' => 'diterima',
                    'tanggal_diterima' => now(),
                ]);

                $stokOutlet = StokOutlet::firstOrCreate(
                    ['outlet_id' => $distribusi->outlet_id, 'bahan_id' => $distribusi->bahan_id],
                    ['stok' => 0]
                );

                $stokOutlet->stok += $distribusi->jumlah;
                $stokOutlet->save();

                // Cek stok kritis
                $STOK_KRITIS = 5;
                if ($stokOutlet->stok <= $STOK_KRITIS) {
                    $stokOutlet->load(['bahan', 'outlet']);
                    $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new StokKritisNotification($stokOutlet));
                    }
                }

                // Notify admin
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new PengirimanDiterimaNotification($distribusi));
                }
            });

            return back()->with('success', 'Barang berhasil diterima!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // =================== LAPORAN ===================

    public function detail($periode)
    {
        $outletId = auth()->user()->outlet_id;

        $distribusi = Distribusi::with(['bahan', 'outlet'])
            ->where('outlet_id', $outletId)
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('user.laporan.detail', compact('distribusi', 'periode'));
    }

    public function cetakDetail($periode)
    {
        $distribusi = Distribusi::with(['bahan', 'outlet'])
            ->where('outlet_id', auth()->user()->outlet_id)
            ->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$periode])
            ->orderBy('tanggal', 'asc')
            ->get()
            ->map(function ($item) {
                $item->bahan_nama  = $item->bahan->nama_bahan ?? 'Tidak ada bahan';
                $item->outlet_nama = $item->outlet->nama_outlet ?? 'Tidak ada outlet';
                
                $item->tanggal_format = $item->tanggal
                    ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y H:i') . ' WIB'
                    : '-';

                $item->tanggal_diterima_format = $item->tanggal_diterima
                    ? \Carbon\Carbon::parse($item->tanggal_diterima)->format('d M Y H:i') . ' WIB'
                    : '-';

                return $item;
            });

        $pdf = Pdf::loadView('user.laporan.detail_pdf', [
            'distribusi' => $distribusi,
            'periode' => $periode
        ]);

        return $pdf->download('laporan_distribusi_' . $periode . '.pdf');
    }
}