<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemakaianController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Pemakaian (Per Outlet Login)
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | Form Tambah Pemakaian
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $bahans = Bahan::all();
        return view('pemakaian.create', compact('bahans'));
    }


    /*
    |--------------------------------------------------------------------------
    | Simpan Pemakaian + Kurangi Stok
    |--------------------------------------------------------------------------
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

            $bahan = Bahan::lockForUpdate()->findOrFail($request->bahan_id);

            // 🚨 Cegah stok minus
            if ($bahan->stok_awal < $request->jumlah) {
                throw new \Exception('Stok bahan tidak mencukupi');
            }

            // Simpan pemakaian
            Pemakaian::create([
                'bahan_id'  => $request->bahan_id,
                'outlet_id' => $user->outlet_id,
                'jumlah'    => $request->jumlah,
                'tanggal'   => $request->tanggal,
            ]);

            // Kurangi stok
            $bahan->decrement('stok_awal', $request->jumlah);
        });

        return redirect()
            ->route('pemakaian.index')
            ->with('success', 'Pemakaian berhasil disimpan & stok otomatis berkurang');
    }
}
