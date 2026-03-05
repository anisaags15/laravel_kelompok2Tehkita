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
        // Revisi: Menggunakan with('user') singular sesuai model terbaru
        $outlets = Outlet::with('user')->get();
        return view('admin.outlet.index', compact('outlets'));
    }

    public function create()
    {
        // Menampilkan admin yang belum punya outlet saja
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
            'user_id'     => 'nullable|exists:users,id', // Tambahkan validasi user_id
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan data outlet
            $outlet = Outlet::create($request->all());

            // 2. Jika ada admin dipilih, hubungkan ke outlet ini
            if ($request->user_id) {
                User::where('id', $request->user_id)->update(['outlet_id' => $outlet->id]);
            }
        });

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil ditambahkan');
    }

    public function edit(Outlet $outlet)
    {
        // Ambil admin yang role-nya admin_outlet (bisa ditambah logic whereNull atau admin miliknya sendiri)
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
            // 1. Update data outlet
            $outlet->update($request->all());

            // 2. Reset admin lama yang sebelumnya pegang outlet ini
            User::where('outlet_id', $outlet->id)->update(['outlet_id' => null]);

            // 3. Set admin baru (jika ada yang dipilih)
            if ($request->user_id) {
                User::where('id', $request->user_id)->update(['outlet_id' => $outlet->id]);
            }
        });

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil diupdate');
    }

    public function destroy(Outlet $outlet)
    {
        // Lepas relasi admin sebelum outlet dihapus
        User::where('outlet_id', $outlet->id)->update(['outlet_id' => null]);
        $outlet->delete();

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil dihapus');
    }
}