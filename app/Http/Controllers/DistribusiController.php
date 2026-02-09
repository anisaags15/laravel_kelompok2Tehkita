<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Distribusi;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\StokOutlet;

class DistribusiController extends Controller
{
    // Tampil data distribusi
    public function index()
    {
        $distribusi = Distribusi::with(['bahan','outlet'])
            ->latest()
            ->get();

        return view('admin.distribusi.index', compact('distribusi'));
    }

    // Form kirim barang
    public function create()
    {
        $bahans  = Bahan::all();
        $outlets = Outlet::all();

        return view('admin.distribusi.create', compact('bahans','outlets'));
    }

    // Simpan distribusi (Admin pusat kirim barang)
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id'  => 'required|exists:bahans,id',
            'outlet_id' => 'required|exists:outlets,id',
            'jumlah'    => 'required|integer|min:1',
            'tanggal'   => 'required|date',
        ]);

        // Ambil bahan
        $bahan = Bahan::findOrFail($request->bahan_id);

        // Cek stok gudang
        if ($bahan->stok_awal < $request->jumlah) {
            return back()->with('error', 'Stok gudang tidak cukup!');
        }

        // Simpan distribusi
        Distribusi::create([
            'bahan_id'  => $request->bahan_id,
            'outlet_id' => $request->outlet_id,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
            'status'    => 'dikirim',
        ]);

        // Kurangi stok gudang
        $bahan->stok_awal -= $request->jumlah;
        $bahan->save();

        return redirect()
            ->route('admin.distribusi.index')
            ->with('success', 'Distribusi berhasil dibuat');
    }

    // Outlet menerima barang
    public function terima($id)
    {
        $distribusi = Distribusi::findOrFail($id);

        // Kalau sudah diterima
        if ($distribusi->status == 'diterima') {
            return back()->with('error', 'Barang sudah diterima!');
        }

        // Update status
        $distribusi->update([
            'status' => 'diterima'
        ]);

        // Cari stok outlet
        $stok = StokOutlet::where('outlet_id', $distribusi->outlet_id)
            ->where('bahan_id', $distribusi->bahan_id)
            ->first();

        // Kalau sudah ada → tambah
        if ($stok) {

            $stok->stok = $stok->stok + $distribusi->jumlah;
            $stok->save();

        } 
        // Kalau belum ada → buat baru
        else {

            StokOutlet::create([
                'outlet_id' => $distribusi->outlet_id,
                'bahan_id'  => $distribusi->bahan_id,
                'stok'      => $distribusi->jumlah,
            ]);

        }

        return back()->with('success', 'Barang berhasil diterima outlet');
    }
}
