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

        // TOTAL STOK OUTLET
        $totalStok = StokOutlet::where('outlet_id', $user->outlet_id)
            ->sum('stok');

        // PEMAKAIAN BAHAN HARI INI
        $pemakaian = Pemakaian::where('outlet_id', $user->outlet_id)
            ->whereDate('tanggal', now())
            ->sum('jumlah');

        // TOTAL DISTRIBUSI
        $distribusi = Distribusi::where('outlet_id', $user->outlet_id)
            ->sum('jumlah');

        // OPTIONAL: data detail terakhir 5 stok dan pemakaian
        $stokOutlets = StokOutlet::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->get();

        $pemakaians = Pemakaian::with('bahan')
            ->where('outlet_id', $user->outlet_id)
            ->latest()
            ->limit(5)
            ->get();

        return view('user.dashboard', compact(
            'totalStok',
            'pemakaian',
            'distribusi',
            'stokOutlets',
            'pemakaians'
        ));
    }
}