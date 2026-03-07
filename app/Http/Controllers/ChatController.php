<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\NewChatNotification; // ← TAMBAH INI

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

        if ($loggedInUser->role === 'admin') {
            $usersQuery = User::with('outlet')->where('role', 'user');
        } else {
            $usersQuery = User::where('role', 'admin');
            
            $adminPusat = User::where('role', 'admin')->first();
            if ($adminPusat) {
                return redirect()->route('chat.show', $adminPusat->id);
            }
        }

        $users = $usersQuery->get()->map(function($user) use ($myId) {
            
            $user->last_message = Message::where(function($q) use ($user, $myId) {
                    $q->where('sender_id', $myId)->where('receiver_id', $user->id);
                })->orWhere(function($q) use ($user, $myId) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $myId);
                })
                ->latest()
                ->first();

            $user->unread_count = Message::where('sender_id', $user->id)
                ->where('receiver_id', $myId)
                ->where('is_read', false)
                ->count();

            return $user;
        });

        $users = $users->sortByDesc(function($user) {
            return $user->last_message ? $user->last_message->created_at->timestamp : 0;
        });

        return view('chat.index', [
            'users' => $users->values()
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

        $messages = Message::where(function ($query) use ($id, $myId) {
                $query->where('sender_id', $myId)->where('receiver_id', $id);
            })
            ->orWhere(function ($query) use ($id, $myId) {
                $query->where('sender_id', $id)->where('receiver_id', $myId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

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
            'message' => 'required|string|max:2000',
        ]);

        // Simpan pesan
        $message = Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $id,
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        // ✅ Kirim notifikasi ke penerima
        // Load relasi sender agar tidak error di dalam Notification
        $message->load('sender');
        $receiver = User::find($id);

        if ($receiver) {
            $receiver->notify(new NewChatNotification($message));
        }

        return redirect()->route('chat.show', $id);
    }
}