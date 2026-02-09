<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    // tampilkan semua outlet (admin pusat)
    public function index()
    {
        // Ambil semua outlet, bisa dengan relasi users jika nanti ingin dihubungkan
        $outlets = Outlet::with('users')->get();
        return view('admin.outlet.index', compact('outlets'));
    }

    // form tambah outlet
    public function create()
    {
        // Form hanya menampilkan nama_outlet dan alamat
        return view('admin.outlet.create');
    }

    // simpan outlet baru
    public function store(Request $request)
    {
$request->validate([
    'nama_outlet' => 'required',
    'alamat'      => 'required',
    'no_hp'       => 'nullable',
]);

Outlet::create([
    'nama_outlet' => $request->nama_outlet,
    'alamat'      => $request->alamat,
    'no_hp'       => $request->no_hp, // <-- ambil dari form
]);

        return redirect()->route('admin.outlet.index')
                         ->with('success', 'Outlet berhasil ditambahkan');
    }

    // form edit outlet (opsional)
 public function edit(Outlet $outlet)
{
    // Ambil semua user (admin)
    $admins = User::all();

    // Kirim ke view
    return view('admin.outlet.edit', compact('outlet', 'admins'));
}


    // update data outlet
public function update(Request $request, Outlet $outlet)
{
    $request->validate([
        'nama_outlet' => 'required',
        'alamat'      => 'required',
        'no_hp'       => 'nullable',
    ]);

    $outlet->update([
        'nama_outlet' => $request->nama_outlet,
        'alamat'      => $request->alamat,
        'no_hp'       => $request->no_hp,
    ]);

    return redirect()->route('admin.outlet.index')
        ->with('success', 'Outlet berhasil diupdate');
}



    // hapus outlet
    public function destroy(Outlet $outlet)
    {
        // Jika nanti ada relasi user, bisa lepas dulu
        User::where('outlet_id', $outlet->id)
            ->update(['outlet_id' => null]);

        $outlet->delete();

        return redirect()->route('admin.outlet.index')
                         ->with('success', 'Outlet berhasil dihapus');
    }
}
