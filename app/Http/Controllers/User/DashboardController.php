<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StokOutlet;
use App\Models\Pemakaian;
use App\Models\Distribusi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user || !$user->outlet_id) {
            abort(403, 'Outlet tidak ditemukan untuk user ini.');
        }

        $outletId = $user->outlet_id;

        // TOTAL STOK OUTLET
        $totalStok = StokOutlet::where('outlet_id', $outletId)
            ->sum('stok');

        // PEMAKAIAN HARI INI
        $pemakaianHariIni = Pemakaian::where('outlet_id', $outletId)
            ->whereDate('tanggal', now())
            ->sum('jumlah');

        // TOTAL DISTRIBUSI
        $distribusi = Distribusi::where('outlet_id', $outletId)
            ->sum('jumlah');

        // DETAIL TERBARU
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $outletId)
            ->latest()
            ->take(5)
            ->get();

        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $outletId)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact(
            'totalStok',
            'pemakaianHariIni',
            'distribusi',
            'stokOutlets',
            'pemakaians'
        ));
    }
}