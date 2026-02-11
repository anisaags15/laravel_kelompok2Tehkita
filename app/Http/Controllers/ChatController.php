<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Auth;

class ChatController extends Controller
{
    // Halaman list user untuk chat
    public function index()
    {
        // Ambil semua user kecuali diri sendiri
        $users = User::where('id', '!=', Auth::id())->get();

        // Hitung unread messages untuk navbar / badge
        $unreadCounts = Message::where('receiver_id', Auth::id())
                            ->where('is_read', false)
                            ->select('sender_id', \DB::raw('count(*) as unread'))
                            ->groupBy('sender_id')
                            ->pluck('unread', 'sender_id');

        return view('chat.index', compact('users', 'unreadCounts'));
    }

    // Halaman chat dengan user tertentu
    public function show(User $user)
    {
        // Ambil semua pesan antara user login dan user tujuan
        $messages = Message::where(function($q) use ($user) {
            $q->where('sender_id', Auth::id())->where('receiver_id', $user->id);
        })->orWhere(function($q) use ($user) {
            $q->where('sender_id', $user->id)->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        // Tandai semua pesan dari user lain sebagai sudah dibaca
        Message::where('sender_id', $user->id)
               ->where('receiver_id', Auth::id())
               ->where('is_read', false)
               ->update(['is_read' => true]);

        return view('chat.show', compact('user', 'messages'));
    }

    // Kirim pesan ke user tertentu
    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $user->id,
            'message'     => $request->message,
        ]);

        return redirect()->route('chat.show', $user->id);
    }
}
