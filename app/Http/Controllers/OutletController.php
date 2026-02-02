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
        $outlets = Outlet::with('users')->get();
        return view('outlet.index', compact('outlets'));
    }

    // form tambah outlet
    public function create()
    {
        // ambil admin outlet yang belum punya outlet
        $admins = User::where('role', 'admin_outlet')
            ->whereNull('outlet_id')
            ->get();

        return view('outlet.create', compact('admins'));
    }

    // simpan outlet baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_outlet' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'admin_id' => 'nullable'
        ]);

        // simpan data outlet
        $outlet = Outlet::create([
            'nama_outlet' => $request->nama_outlet,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        // hubungkan admin outlet ke outlet
        if ($request->admin_id) {
            User::where('id', $request->admin_id)
                ->update([
                    'outlet_id' => $outlet->id
                ]);
        }

        return redirect()->route('outlet.index')
            ->with('success', 'Outlet berhasil ditambahkan');
    }

    // form edit outlet
    public function edit(Outlet $outlet)
    {
        $admins = User::where('role', 'admin_outlet')->get();
        return view('outlet.edit', compact('outlet', 'admins'));
    }

    // update data outlet
    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'nama_outlet' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
        ]);

        $outlet->update([
            'nama_outlet' => $request->nama_outlet,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('outlet.index')
            ->with('success', 'Outlet berhasil diupdate');
    }

    // hapus outlet
    public function destroy(Outlet $outlet)
    {
        // lepas user dari outlet dulu
        User::where('outlet_id', $outlet->id)
            ->update(['outlet_id' => null]);

        $outlet->delete();

        return redirect()->route('outlet.index')
            ->with('success', 'Outlet berhasil dihapus');
    }
}
