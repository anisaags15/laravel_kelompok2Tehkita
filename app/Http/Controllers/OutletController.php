<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Tampilkan semua outlet (Admin Pusat)
     */
    public function index()
    {
        $outlets = Outlet::with('users')->get();

        return view('admin.outlet.index', compact('outlets'));
    }

    /**
     * Form tambah outlet
     */
    public function create()
    {
        // Ambil semua outlet untuk dropdown
        $outlets = Outlet::all();

        // Ambil admin outlet yang belum punya outlet (opsional, kalau nanti butuh assign admin)
        $admins = User::where('role', 'admin_outlet')
            ->whereNull('outlet_id')
            ->get();

        // Kirim kedua variabel ke view
        return view('admin.outlet.create', compact('outlets', 'admins'));
    }

    /**
     * Simpan outlet baru
     */
public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'nama_outlet' => 'required|string|max:255',
        'alamat'      => 'required|string',
    ]);

    // Simpan outlet baru
    Outlet::create([
        'nama_outlet' => $request->nama_outlet,
        'alamat'      => $request->alamat,
        'no_hp'       => '-', // atau bisa kosong jika optional
    ]);

    // Redirect ke list outlet
    return redirect()->route('admin.outlet.index')
        ->with('success', 'Outlet berhasil ditambahkan');
}


    /**
     * Form edit outlet
     */
    public function edit(Outlet $outlet)
    {
        $admins = User::where('role', 'admin_outlet')->get();

        return view('admin.outlet.edit', compact('outlet', 'admins'));
    }

    /**
     * Update outlet
     */
    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'nama_outlet' => 'required|string|max:255',
            'alamat'      => 'required|string',
            'no_hp'       => 'required|string|max:15',
        ]);

        $outlet->update([
            'nama_outlet' => $request->nama_outlet,
            'alamat'      => $request->alamat,
            'no_hp'       => $request->no_hp,
        ]);

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil diupdate');
    }

    /**
     * Hapus outlet
     */
    public function destroy(Outlet $outlet)
    {
        // Lepas user dari outlet dulu
        User::where('outlet_id', $outlet->id)
            ->update([
                'outlet_id' => null
            ]);

        $outlet->delete();

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil dihapus');
    }
}
