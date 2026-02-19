<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Bahan;
use App\Models\StokOutlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemakaianController extends Controller
{
    /**
     * Tampilkan daftar pemakaian lengkap dengan Pagination
     */
    public function index()
    {
        $user = Auth::user();

        // Menggunakan paginate agar halaman riwayat tidak terlalu panjang
        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->latest('tanggal')
            ->latest('created_at')
            ->paginate(10); 

        return view('user.pemakaian.index', compact('pemakaians'));
    }

    /**
     * Form input pemakaian bahan
     */
    public function create()
    {
        $user = Auth::user();

        // Hanya ambil bahan yang stoknya lebih dari 0 untuk ditampilkan di form
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->where('stok', '>', 0)
            ->get();

        return view('user.pemakaian.create', compact('stokOutlets'));
    }

    /**
     * Simpan data pemakaian dan kurangi stok
     */
    public function store(Request $request)
    {
        $request->validate([
            'bahan_id' => 'required|exists:bahans,id',
            'jumlah'   => 'required|integer|min:1',
            'tanggal'  => 'required|date',
        ]);

        $user = Auth::user();

        // Ambil data stok outlet
        $stokOutlet = StokOutlet::where('outlet_id', $user->outlet_id)
            ->where('bahan_id', $request->bahan_id)
            ->first();

        // Validasi kecukupan stok sebelum menyimpan
        if (!$stokOutlet || $stokOutlet->stok < $request->jumlah) {
            return redirect()->back()
                ->with('error', 'Maaf, stok bahan tidak mencukupi untuk jumlah tersebut.');
        }

        // Simpan transaksi pemakaian
        Pemakaian::create([
            'bahan_id'  => $request->bahan_id,
            'outlet_id' => $user->outlet_id,
            'jumlah'    => $request->jumlah,
            'tanggal'   => $request->tanggal,
        ]);

        // Kurangi jumlah stok di tabel stok_outlets
        $stokOutlet->decrement('stok', $request->jumlah);

        return redirect()->route('user.pemakaian.index')
            ->with('success', 'Pemakaian bahan berhasil dicatat dan stok telah diperbarui.');
    }

    /**
     * Hapus data pemakaian dan kembalikan stoknya
     */
    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);

        // Cari stok terkait untuk mengembalikan jumlah yang dihapus
        $stokOutlet = StokOutlet::where('outlet_id', $pemakaian->outlet_id)
            ->where('bahan_id', $pemakaian->bahan_id)
            ->first();

        if ($stokOutlet) {
            $stokOutlet->increment('stok', $pemakaian->jumlah);
        }

        $pemakaian->delete();

        return redirect()->route('user.pemakaian.index')
            ->with('success', 'Data pemakaian berhasil dihapus dan stok telah dikembalikan.');
    }
}