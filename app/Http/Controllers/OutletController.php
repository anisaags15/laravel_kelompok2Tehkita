<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::with('users')->get();
        return view('admin.outlet.index', compact('outlets'));
    }

    public function create()
    {
        $admins = User::where('role', 'admin_outlet')->whereNull('outlet_id')->get();
        return view('admin.outlet.create', compact('admins'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_outlet' => 'required|string|max:255',
            'alamat'      => 'required|string',
            'no_hp'       => 'required|string|max:15',
            'target_pemakaian_harian' => 'required|integer|min:1',
        ]);

        Outlet::create($request->all());

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil ditambahkan');
    }

    public function edit(Outlet $outlet)
    {
        $admins = User::where('role', 'admin_outlet')->get();
        return view('admin.outlet.edit', compact('outlet', 'admins'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'nama_outlet' => 'required|string|max:255',
            'alamat'      => 'required|string',
            'no_hp'       => 'required|string|max:15',
            'target_pemakaian_harian' => 'required|integer|min:1',
        ]);

        $outlet->update($request->all());

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil diupdate');
    }

    public function destroy(Outlet $outlet)
    {
        User::where('outlet_id', $outlet->id)->update(['outlet_id' => null]);
        $outlet->delete();

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil dihapus');
    }
}