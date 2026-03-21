<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::with('user')->get();
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
            'user_id'     => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () use ($request) {
            $outlet = Outlet::create($request->all());
            if ($request->user_id) {
                User::where('id', $request->user_id)->update(['outlet_id' => $outlet->id]);
            }
        });

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil ditambahkan');
    }

    public function edit(Outlet $outlet)
    {
        $admins = User::where('role', 'admin_outlet')
                      ->where(function($query) use ($outlet) {
                          $query->whereNull('outlet_id')
                                ->orWhere('outlet_id', $outlet->id);
                      })->get();

        return view('admin.outlet.edit', compact('outlet', 'admins'));
    }

    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'nama_outlet' => 'required|string|max:255',
            'alamat'      => 'required|string',
            'no_hp'       => 'required|string|max:15',
            'target_pemakaian_harian' => 'required|integer|min:1',
            'user_id'     => 'nullable|exists:users,id',
        ]);

        DB::transaction(function () use ($request, $outlet) {
            $outlet->update($request->all());
            User::where('outlet_id', $outlet->id)->update(['outlet_id' => null]);
            if ($request->user_id) {
                User::where('id', $request->user_id)->update(['outlet_id' => $outlet->id]);
            }
        });

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil diupdate');
    }

    public function destroy(Outlet $outlet)
    {
        // ✅ Kalau outlet dihapus, user-nya ikut dihapus sekalian
        if ($outlet->user) {
            $outlet->user->delete();
        }
        $outlet->delete();

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet & akun admin berhasil dihapus');
    }

    /**
     * ✅ HAPUS USER SAJA — outlet tetap ada, status jadi "Belum ada admin"
     */
    public function destroyUser(Outlet $outlet)
    {
        if ($outlet->user) {
            $outlet->user->delete();
        }

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Akun admin outlet berhasil dihapus. Outlet masih aktif.');
    }
}