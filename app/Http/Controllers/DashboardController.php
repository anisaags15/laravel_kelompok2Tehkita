<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;
use App\Models\Outlet;

class DashboardController extends Controller
{
    // ADMIN
    public function admin()
    {
        return view('admin.dashboard', [
            'totalBahan' => Bahan::count(),
            'totalOutlet' => Outlet::count(),
            'stokGudang' => 0,
            'distribusiHariIni' => 0,
            'stokKritis' => [],
            'aktivitas' => [],
        ]);
    }

    // USER
    public function user()
    {
        return view('user.dashboard');
    }
}
