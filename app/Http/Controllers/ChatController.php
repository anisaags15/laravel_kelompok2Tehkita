<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1️⃣ LIST USER / OUTLET (URUTAN TERBARU & NOTIFIKASI)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $loggedInUser = Auth::user();
        $myId = Auth::id();

        // 1. Filter target user berdasarkan role
        if ($loggedInUser->role === 'admin') {
            // Admin melihat semua Outlet (User)
            $usersQuery = User::with('outlet')->where('role', 'user');
        } else {
            // Outlet (User) hanya melihat Admin
            // NOTE: Jika kamu ingin outlet bisa chat antar outlet, hapus filter role ini.
            $usersQuery = User::where('role', 'admin');
            
            // Redirect otomatis ke admin pertama jika outlet ingin chat (UX Shortcut)
            // Hapus bagian ini jika admin kamu ada banyak dan ingin dipilih manual
            $adminPusat = User::where('role', 'admin')->first();
            if ($adminPusat) {
                return redirect()->route('chat.show', $adminPusat->id);
            }
        }

        // 2. Ambil data user dan tempelkan info pesan terakhir & jumlah unread
        $users = $usersQuery->get()->map(function($user) use ($myId) {
            
            // Ambil pesan terakhir (baik yang dikirim atau diterima)
            $user->last_message = Message::where(function($q) use ($user, $myId) {
                    $q->where('sender_id', $myId)->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($user, $myId) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $myId);
                })
                ->latest()
                ->first();

            // Hitung pesan masuk yang belum dibaca
            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', $myId)
                ->where('is_read', false)
                ->count();

            return $user;
        });

        // 3. SORTING: Chat terbaru berada paling atas
        $users = $users->sortByDesc(function($user) {
            // Urutkan berdasarkan timestamp pesan terakhir, jika belum pernah chat taruh paling bawah (0)
            return $user->last_message ? $user->last_message->created_at->timestamp : 0;
        });

        return view('chat.index', [
            'users' => $users->values() // values() untuk me-reset index array setelah sorting
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ HALAMAN CHAT DETAIL (BACA PESAN)
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $myId = Auth::id();
        $user = User::with('outlet')->findOrFail($id);

        // Ambil semua percakapan antara saya dan target user
        $messages = Message::where(function ($query) use ($id, $myId) {
                $query->where('sender_id', $myId)->where('receiver_id', $id);
            })
            ->orWhere(function ($query) use ($id, $myId) {
                $query->where('sender_id', $id)->where('receiver_id', $myId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Tandai pesan dari lawan bicara sebagai "dibaca"
        Message::where('sender_id', $id)
            ->where('receiver_id', $myId)
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
            'message' => 'required|string|max:2000', // Limit sedikit lebih besar
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $id,
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        // Gunakan redirect back agar posisi scroll user tetap aman (tergantung setup frontend)
        return redirect()->route('chat.show', $id);
    }
}