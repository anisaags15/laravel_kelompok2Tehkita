<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\StokOutlet;
use App\Models\User;
use App\Notifications\PengirimanDiterimaNotification;
use App\Notifications\InfoPengirimanNotification; // Sesuaikan dengan nama file notification pengiriman baru
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DistribusiController extends Controller
{
    /**
     * INDEX (ADMIN PUSAT)
     */
    public function index()
    {
        $distribusis = Distribusi::with(['bahan', 'outlet'])
            ->orderBy('tanggal', 'desc')
            ->paginate(15);

        return view('admin.distribusi.index', compact('distribusis'));
    }

    /**
     * CREATE (ADMIN PUSAT)
     */
    public function create()
    {
        $bahans  = Bahan::where('stok_awal', '>', 0)->get();
        $outlets = Outlet::all();

        return view('admin.distribusi.create', compact('bahans', 'outlets'));
    }

    /**
     * STORE (ADMIN PUSAT) - DISINI TRIGGER NOTIF KE USER OUTLET
     */
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

                // 1. Kurangi stok gudang pusat
                $bahan->stok_awal -= $request->jumlah;
                $bahan->save();

                // 2. Simpan record distribusi
                $distribusi = Distribusi::create([
                    'bahan_id'  => $request->bahan_id,
                    'outlet_id' => $request->outlet_id,
                    'jumlah'    => $request->jumlah,
                    'tanggal'   => $request->tanggal,
                    'status'    => 'dikirim',
                ]);

                // 3. TRIGGER NOTIFIKASI KE USER OUTLET TUJUAN
                $usersOutlet = User::where('outlet_id', $request->outlet_id)->get();
                foreach ($usersOutlet as $user) {
                    // Pastikan nama class notification ini sesuai dengan file di folder Notifications kamu
                    $user->notify(new InfoPengirimanNotification($distribusi));
                }
            });

            return redirect()
                ->route('admin.distribusi.index')
                ->with('success', 'Barang sedang dikirim dan notifikasi telah dikirim ke outlet.');
                
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * INDEX (ADMIN OUTLET / USER)
     */
    public function indexUser()
    {
        $outletId = auth()->user()->outlet_id;

        $distribusis = Distribusi::with('bahan')
            ->where('outlet_id', $outletId)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('user.distribusi.index', compact('distribusis'));
    }

    /**
     * TERIMA BARANG (ADMIN OUTLET) - DISINI TRIGGER NOTIF BALIK KE ADMIN
     */
    public function terima($id)
    {
        $distribusi = Distribusi::with(['outlet', 'bahan'])->findOrFail($id);

        if ($distribusi->outlet_id !== auth()->user()->outlet_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($distribusi->status === 'diterima') {
            return back()->with('info', 'Barang sudah pernah diterima sebelumnya.');
        }

        DB::transaction(function () use ($distribusi) {
            // 1. Ubah status distribusi
            $distribusi->status = 'diterima';
            $distribusi->save();

            // 2. Tambah stok di outlet
            $stokOutlet = StokOutlet::firstOrCreate(
                ['outlet_id' => $distribusi->outlet_id, 'bahan_id' => $distribusi->bahan_id],
                ['stok' => 0]
            );
            $stokOutlet->stok += $distribusi->jumlah;
            $stokOutlet->save();

            // 3. TRIGGER NOTIFIKASI BALIK KE ADMIN PUSAT (Bahwa barang sudah diterima)
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new PengirimanDiterimaNotification($distribusi));
            }
        });

        return back()->with('success', 'Konfirmasi berhasil! Stok Anda telah bertambah.');
    }
}