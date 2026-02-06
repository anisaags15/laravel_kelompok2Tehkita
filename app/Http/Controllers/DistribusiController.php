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
    public function index()
    {
        $distribusis = Distribusi::with(['bahan', 'outlet'])->get();
        return view('distribusi.index', compact('distribusis'));
    }

    public function create()
    {
        $bahans = Bahan::all();
        $outlets = Outlet::all();
        return view('distribusi.create', compact('bahans', 'outlets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'outlet_id' => 'required|exists:outlets,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        DB::transaction(function () use ($request) {

            $bahan = Bahan::findOrFail($request->bahan_id);
            if ($bahan->stok_awal < $request->jumlah) {
                abort(400, 'Stok gudang tidak mencukupi');
            }
            $bahan->stok_awal -= $request->jumlah;
            $bahan->save();
            $stokOutlet = StokOutlet::firstOrCreate(
                [
                    'outlet_id' => $request->outlet_id,
                    'bahan_id' => $request->bahan_id,
                ],
                [
                    'stok' => 0,
                ]
            );

            $stokOutlet->stok += $request->jumlah;
            $stokOutlet->save();
            Distribusi::create([
                'bahan_id' => $request->bahan_id,
                'outlet_id' => $request->outlet_id,
                'jumlah' => $request->jumlah,
                'tanggal' => $request->tanggal,
                'status' => 'dikirim',
            ]);
        });

        return redirect()->route('distribusi.index')
            ->with('success', 'Distribusi berhasil & stok otomatis diperbarui');
    }
}
