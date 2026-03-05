<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Distribusi;

class DashboardController extends Controller
{
    public function index()
    {
        $aktivitas = Distribusi::with(['bahan','outlet'])
            ->where('outlet_id', auth()->user()->outlet_id)
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('aktivitas'));
    }
}