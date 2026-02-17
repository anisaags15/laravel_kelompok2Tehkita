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
    | 1️⃣ LIST USER / OUTLET
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        // Ambil semua user selain diri sendiri + pastikan outlet ikut ke-load
   $users = User::with('outlet')
            ->where('role', 'user')
            ->get();
            
        // Hitung unread message per pengirim
        $unreadCounts = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as unread')
            ->groupBy('sender_id')
            ->pluck('unread', 'sender_id');

        return view('chat.index', [
            'users' => $users,
            'unreadCounts' => $unreadCounts
        ]);
    }


    /*
/*
|--------------------------------------------------------------------------
| 2️⃣ HALAMAN CHAT DETAIL
|--------------------------------------------------------------------------
*/
public function show($id)
{
    // Ambil user + outlet
    $user = User::with('outlet')->findOrFail($id);

    // Ambil semua pesan antara user login dan user tujuan (GROUPING FIXED)
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

    // Tandai pesan dari lawan chat sebagai sudah dibaca
    Message::where('sender_id', $id)
        ->where('receiver_id', Auth::id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    return view('chat.show', compact('user', 'messages'));
}

    /*
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