<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JadwalDistribusi;
use Carbon\Carbon;

class JadwalDistribusiController extends Controller
{
    public function index()
    {
        // Upcoming — yang tanggalnya belum lewat, diurutkan paling dekat dulu
        $jadwalUpcoming = JadwalDistribusi::where('status', 'upcoming')
            ->where('tanggal_rencana', '>=', Carbon::today())
            ->orderBy('tanggal_rencana', 'asc')
            ->get();

        // Sudah lewat tapi belum ditandai selesai (telat)
        $jadwalTelat = JadwalDistribusi::where('status', 'upcoming')
            ->where('tanggal_rencana', '<', Carbon::today())
            ->orderBy('tanggal_rencana', 'desc')
            ->get();

        // Riwayat selesai
        $jadwalSelesai = JadwalDistribusi::where('status', 'selesai')
            ->orderBy('tanggal_rencana', 'desc')
            ->get();

        return view('user.jadwal-distribusi.index', compact(
            'jadwalUpcoming', 'jadwalTelat', 'jadwalSelesai'
        ));
    }
}