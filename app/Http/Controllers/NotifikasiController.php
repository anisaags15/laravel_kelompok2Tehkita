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
    public function indexAdmin()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);

        return view('admin.notifikasi.index', compact('notifications'));
    }

    /**
     * NOTIFIKASI UNTUK USER OUTLET
     * Menampilkan info pengiriman dan peringatan stok outlet sendiri
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);

        return view('user.notifikasi.index', compact('notifications'));
    }

    /**
     * FITUR HAPUS NOTIFIKASI (DELETE)
     * Bisa digunakan oleh Admin maupun User
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
     * Menghilangkan tanda "unread" di database
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}