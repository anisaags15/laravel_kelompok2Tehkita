<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * NOTIFIKASI UNTUK ADMIN PUSAT
     * Menampilkan semua notifikasi masuk (Waste, Stok Kritis, Konfirmasi Terima)
     */
    public function indexAdmin(Request $request)
    {
        $query = Auth::user()->notifications()->latest();

        // Filter by type kalau ada query ?type=waste dll
        if ($request->filled('type') && $request->type !== 'semua') {
            $query->where('data->type', $request->type);
        }

        $notifications = $query->paginate(10)->withQueryString();

        return view('admin.notifikasi.index', compact('notifications'));
    }

    /**
     * NOTIFIKASI UNTUK USER OUTLET
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);

        return view('user.notifikasi.index', compact('notifications'));
    }

    /**
     * FITUR HAPUS NOTIFIKASI (DELETE)
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        
        if ($notification) {
            $notification->delete();
            return back()->with('success', 'Notifikasi berhasil dihapus!');
        }

        return back()->with('error', 'Notifikasi tidak ditemukan.');
    }

    /**
     * FITUR TANDAI SEMUA SUDAH DIBACA
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    /**
     * FITUR TANDAI SATU NOTIFIKASI DIBACA
     * Dipanggil saat klik "Tindak Lanjut" atau tombol centang
     */
    public function markOneRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification && is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // Redirect ke URL tujuan notifikasi
        $url = $notification->data['url'] ?? route('admin.notifikasi.index');
        return redirect($url);
    }
}