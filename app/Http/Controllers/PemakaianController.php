<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemakaianController extends Controller
{
    /**
     * Menampilkan daftar pemakaian bahan (per outlet)
     */
    public function index()
    {
        $user = Auth::user();

        $pemakaians = Pemakaian::with(['bahan', 'outlet'])
            ->where('outlet_id', $user->outlet_id)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('pemakaian.index', compact('pemakaians'));
    }

    /**
     * Form input pemakaian bahan
     */
    public function create()
    {
        $bahans = Bahan::all();
        return view('pemakaian.create', compact('bahans'));
    }

    /**
     * Simpan data pemakaian + kurangi stok bahan
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {

            $bahan = Bahan::findOrFail($request->bahan_id);

            // 🚨 CEGAH STOK MINUS
            if ($bahan->stok_awal < $request->jumlah) {
                abort(400, 'Stok bahan tidak mencukupi');
            }

            // 1️⃣ Simpan pemakaian
            Pemakaian::create([
                'bahan_id'  => $request->bahan_id,
                'outlet_id' => $user->outlet_id,
                'jumlah'    => $request->jumlah,
                'tanggal'   => $request->tanggal,
            ]);

            // 2️⃣ Kurangi stok bahan
            $bahan->stok_awal -= $request->jumlah;
            $bahan->save();
        });

        return redirect()
            ->route('pemakaian.index')
