<?php

namespace App\Http\Controllers;

use App\Models\Distribusi;
use App\Models\Bahan;
use App\Models\Outlet;
use App\Models\StokOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistribusiController extends Controller
{
    /**
     * =========================
     * INDEX (ADMIN PUSAT)
     * =========================
     */
    public function index()
    {
        $distribusis = Distribusi::with(['bahan', 'outlet'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.distribusi.index', compact('distribusis'));
    }

    /**
     * =========================
     * CREATE (ADMIN PUSAT)
     * =========================
     */
    public function create()
    {
        $bahans  = Bahan::all();
        $outlets = Outlet::all();

        return view('admin.distribusi.create', compact('bahans', 'outlets'));
    }

    /**
     * =========================
     * STORE (ADMIN PUSAT)
     * =========================
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id'  => 'required|exists:bahans,id',
            'outlet_id' => 'required|exists:outlets,id',
            'jumlah'    => 'required|integer|min:1',
            'tanggal'   => 'required|date',
        ]);

        DB::transaction(function () use ($request) {

            // Ambil bahan (stok gudang)
            $bahan = Bahan::findOrFail($request->bahan_id);

            // Cek stok gudang
            if ($bahan->stok_awal < $request->jumlah) {
                abort(400, 'Stok gudang tidak mencukupi');
            }

            // Kurangi stok gudang
            $bahan->stok_awal -= $request->jumlah;
            $bahan->save();

            // Tambah / update stok outlet
            $stokOutlet = StokOutlet::firstOrCreate(
                [
                    'outlet_id' => $request->outlet_id,
                    'bahan_id'  => $request->bahan_id,
                ],
                [
                    'stok' => 0,
                ]
            );

            $stokOutlet->stok += $request->jumlah;
            $stokOutlet->save();

            // Simpan distribusi
            Distribusi::create([
                'bahan_id'  => $request->bahan_id,
                'outlet_id' => $request->outlet_id,
                'jumlah'    => $request->jumlah,
                'tanggal'   => $request->tanggal,
                'status'    => 'dikirim',
            ]);
        });

        return redirect()
            ->route('admin.distribusi.index')
            ->with('success', 'Distribusi berhasil & stok otomatis diperbarui');
    }

    /**
     * =========================
     * INDEX (ADMIN OUTLET / USER)
     * =========================
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
     * =========================
     * TERIMA BARANG (ADMIN OUTLET)
     * =========================
     */
    public function terima($id)
    {
        $distribusi = Distribusi::findOrFail($id);

        // Pastikan outlet milik user login
        if ($distribusi->outlet_id !== auth()->user()->outlet_id) {
            abort(403);
        }

        // Ubah status
        $distribusi->status = 'diterima';
        $distribusi->save();

        return back()->with('success', 'Barang berhasil diterima');
    }
}
