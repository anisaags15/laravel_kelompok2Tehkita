<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\StokOutlet;
use App\Models\User;
use App\Notifications\PengirimanDiterimaNotification;
use App\Notifications\InfoPengirimanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistribusiController extends Controller
{
    public function index()
    {
        // Distribusi terbaru tampil di bawah (sesuai request asc)
        $distribusis = Distribusi::with(['bahan', 'outlet'])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.distribusi.index', compact('distribusis'));
    }

    public function create()
    {
        $bahans  = Bahan::where('stok_awal', '>', 0)->get();
        $outlets = Outlet::all();
        return view('admin.distribusi.create', compact('bahans', 'outlets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_id'  => 'required|exists:bahans,id',
            'outlet_id' => 'required|exists:outlets,id',
            'jumlah'    => 'required|integer|min:1',
            'tanggal'   => 'required|date',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $bahan = Bahan::findOrFail($request->bahan_id);

                if ($bahan->stok_awal < $request->jumlah) {
                    throw new \Exception('Stok gudang tidak mencukupi!');
                }

                // Kurangi stok di gudang pusat
                $bahan->stok_awal -= $request->jumlah;
                $bahan->save();

                $distribusi = Distribusi::create([
                    'bahan_id'  => $request->bahan_id,
                    'outlet_id' => $request->outlet_id,
                    'jumlah'    => $request->jumlah,
                    'tanggal'   => $request->tanggal,
                    'status'    => 'dikirim',
                ]);

                // Notifikasi ke semua user di outlet tujuan
                $usersOutlet = User::where('outlet_id', $request->outlet_id)->get();
                foreach ($usersOutlet as $user) {
                    $user->notify(new InfoPengirimanNotification($distribusi));
                }
            });

            return redirect()->route('admin.distribusi.index')->with('success', 'Barang berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $distribusi = Distribusi::findOrFail($id);
        
        if ($distribusi->status === 'diterima') {
            return redirect()->route('admin.distribusi.index')->with('error', 'Data sudah diterima, tidak bisa diedit.');
        }

        $bahans = Bahan::all();
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
                
                // Balikkan stok lama ke gudang pusat dulu
                $bahanLama = Bahan::find($distribusi->bahan_id);
                $bahanLama->stok_awal += $distribusi->jumlah;
                $bahanLama->save();

                // Cek stok bahan baru
                $bahanBaru = Bahan::find($request->bahan_id);
                if ($bahanBaru->stok_awal < $request->jumlah) {
                    throw new \Exception('Stok gudang tidak cukup untuk perubahan ini!');
                }

                // Kurangi stok bahan baru
                $bahanBaru->stok_awal -= $request->jumlah;
                $bahanBaru->save();

                $distribusi->update([
                    'bahan_id'  => $request->bahan_id,
                    'outlet_id' => $request->outlet_id,
                    'jumlah'    => $request->jumlah,
                    'tanggal'   => $request->tanggal,
                ]);
            });

            return redirect()->route('admin.distribusi.index')->with('success', 'Data distribusi diperbarui.');
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

                // Balikkan stok ke gudang pusat sebelum hapus
                $bahan = Bahan::find($distribusi->bahan_id);
                $bahan->stok_awal += $distribusi->jumlah;
                $bahan->save();

                $distribusi->delete();
            });

            return redirect()->route('admin.distribusi.index')->with('success', 'Distribusi dibatalkan, stok dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tampilan Riwayat Distribusi untuk User Outlet
     */
    public function indexUser()
    {
        $distribusis = Distribusi::with('bahan')
            ->where('outlet_id', auth()->user()->outlet_id)
            ->orderBy('id', 'asc')
            ->get();
            
        return view('user.distribusi.index', compact('distribusis'));
    }

    /**
     * Fungsi Konfirmasi Terima Barang oleh User Outlet
     */
    public function terima($id)
    {
        try {
            $distribusi = Distribusi::findOrFail($id);

            // Cek jika barang sudah pernah dikonfirmasi
            if ($distribusi->status === 'diterima') {
                return back()->with('error', 'Barang ini sudah dikonfirmasi sebelumnya.');
            }

            DB::transaction(function () use ($distribusi) {
                // 1. Update status distribusi menjadi diterima
                $distribusi->update(['status' => 'diterima']);

                // 2. Tambah stok di StokOutlet
                $stokOutlet = StokOutlet::firstOrCreate(
                    [
                        'outlet_id' => $distribusi->outlet_id, 
                        'bahan_id'  => $distribusi->bahan_id
                    ],
                    ['stok' => 0]
                );
                $stokOutlet->stok += $distribusi->jumlah;
                $stokOutlet->save();

                // 3. Kirim notifikasi ke Admin bahwa barang sudah sampai
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new PengirimanDiterimaNotification($distribusi));
                }
            });

            return back()->with('success', 'Barang berhasil diterima! Stok outlet otomatis bertambah.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}