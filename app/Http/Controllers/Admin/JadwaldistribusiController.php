<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalDistribusi;
use Illuminate\Http\Request;

class JadwalDistribusiController extends Controller
{
    public function index()
    {
$jadwals = JadwalDistribusi::orderBy('tanggal_rencana', 'asc')->get();
        return view('admin.jadwal-distribusi.index', compact('jadwals'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'keterangan'     => 'required|string|max:255',
            'tanggal_rencana'=> 'required|date',
            'catatan'        => 'nullable|string',
        ]);

        JadwalDistribusi::create([
            'keterangan'      => $request->keterangan,
            'tanggal_rencana' => $request->tanggal_rencana,
            'catatan'         => $request->catatan,
            'status'          => 'upcoming',
            'created_by'      => auth()->id(),
        ]);

        return back()->with('success', 'Jadwal distribusi berhasil ditambahkan!');
    }

    public function selesai($id)
    {
        $jadwal = JadwalDistribusi::findOrFail($id);
        $jadwal->update(['status' => 'selesai']);
        return back()->with('success', 'Jadwal ditandai selesai!');
    }

    public function destroy($id)
    {
        JadwalDistribusi::findOrFail($id)->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }
}