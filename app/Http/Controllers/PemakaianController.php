<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
     * Simpan data pemakaian bahan
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        $user = Auth::user();

        Pemakaian::create([
            'bahan_id'  => $request->bahan_id,
            'outlet_id' => $user->outlet_id,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
        ]);

        return redirect()->route('pemakaian.index')
            ->with('success', 'Pemakaian bahan berhasil dicatat');
    }

    /**
     * Hapus data pemakaian
     */
    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);

        $pemakaian->delete();

        return redirect()->route('pemakaian.index')
            ->with('success', 'Data pemakaian berhasil dihapus');
    }
}
