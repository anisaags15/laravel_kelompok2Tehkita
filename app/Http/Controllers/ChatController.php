<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1️⃣ LIST USER / OUTLET (DENGAN NOTIF & PREVIEW)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $loggedInUser = Auth::user();

        // 1. Tentukan target user berdasarkan role
        if ($loggedInUser->role === 'admin') {
            // Admin Pusat melihat semua Admin Outlet
            $usersQuery = User::with('outlet')->where('role', 'user');
        } else {
            // Admin Outlet melihat Admin Pusat
            $usersQuery = User::where('role', 'admin');
            
            // Auto-redirect jika hanya ada 1 Admin Pusat agar tidak ribet
            $adminPusat = User::where('role', 'admin')->first();
            if ($adminPusat) {
                return redirect()->route('chat.show', $adminPusat->id);
            }
        }

        $users = $usersQuery->get()->map(function($user) {
            // 2. Ambil Pesan Terakhir antara saya dan user ini
            $user->last_message = Message::where(function($q) use ($user) {
                    $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
                })
                ->latest()
                ->first();

            // 3. Hitung jumlah pesan yang belum saya baca dari user ini
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->count();

            return $user;
        });

      // Urutkan user berdasarkan chat terbaru
$users = $users->sortByDesc(function($user) {
    // Jika ada pesan, ambil timestamp-nya (angka), jika tidak ada beri angka 0
    return $user->last_message ? $user->last_message->created_at->timestamp : 0;
});

        return view('chat.index', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ HALAMAN CHAT DETAIL (SUDAH OKE + UPDATE IS_READ)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $user = User::with('outlet')->findOrFail($id);

        // Ambil percakapan kedua belah pihak
        $messages = Message::with('sender.outlet')
            ->where(function ($query) use ($id) {
                $query->where(function ($q) use ($id) {
                    $q->where('sender_id', Auth::id())
                      ->where('receiver_id', $id);
                })
                ->orWhere(function ($q) use ($id) {
                    $q->where('sender_id', $id)
                      ->where('receiver_id', Auth::id());
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Tandai semua pesan dari user ini sebagai "sudah dibaca"
        Message::where('sender_id', $id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('chat.show', compact('user', 'messages'));
    }

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ KIRIM PESAN
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $id,
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        return redirect()->route('chat.show', $id);
    }
}