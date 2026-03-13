@extends('layouts.main')

@section('page', 'Chat Detail')

@section('content')
<style>
    .chat-card {
        border-radius: 20px !important;
        overflow: hidden;
        border: none !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.07) !important;
        max-width: 900px;
    }

    /* HEADER BIRU */
    .chat-header {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 12px 20px !important;
        border: none;
    }
    .chat-header-avatar {
        width: 38px; height: 38px;
        border-radius: 50%; object-fit: cover;
        border: 2px solid rgba(255,255,255,0.45);
        flex-shrink: 0;
        display: block;
    }
    .chat-header-letter {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: rgba(255,255,255,0.18);
        border: 2px solid rgba(255,255,255,0.35);
        color: white; font-weight: 800; font-size: 0.95rem;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .btn-tutup {
        background: rgba(255,255,255,0.12) !important;
        border: 1px solid rgba(255,255,255,0.28) !important;
        color: rgba(255,255,255,0.9) !important;
        border-radius: 50px !important;
        padding: 4px 14px !important;
        font-size: 0.78rem !important;
        font-weight: 600 !important;
        letter-spacing: 0.2px;
    }
    .btn-tutup:hover { background: rgba(255,255,255,0.22) !important; }

    /* CHAT BODY */
    .chat-body {
        height: 520px;
        overflow-y: auto;
        background-color: #e5ddd5;
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        padding: 16px 20px;
        gap: 2px;
    }
    .chat-body::before {
        content: "";
        position: absolute; inset: 0;
        background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png');
        background-repeat: repeat; background-size: 350px;
        opacity: 0.06; z-index: -1;
    }
    .chat-body::-webkit-scrollbar { width: 4px; }
    .chat-body::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }

    /* BUBBLE WRAP */
    .bubble-wrap { display: flex; flex-direction: column; margin-bottom: 8px; }
    .bubble-wrap.me    { align-items: flex-end; }
    .bubble-wrap.other { align-items: flex-start; }

    /* BUBBLE NORMAL */
    .bubble {
        max-width: 70%;
        padding: 9px 13px;
        font-size: 0.88rem;
        line-height: 1.55;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        word-break: break-word;
        border-radius: 16px;
    }
    .bubble-me {
        background: #dcf8c6; color: #202c33;
        border-top-right-radius: 3px;
    }
    .bubble-other {
        background: #fff; color: #202c33;
        border-top-left-radius: 3px;
    }

    /* BUBBLE BOT — selalu kanan, amber */
    .bubble-wrap.bot { align-items: flex-end; } /* legacy */
    .bubble-bot {
        max-width: 72%;
        background: linear-gradient(135deg, #fff8e1, #fff3cd);
        border: 1.5px solid #ffe082;
        border-radius: 16px;
        border-top-right-radius: 3px;
        padding: 11px 15px;
        font-size: 0.86rem;
        line-height: 1.6;
        color: #4a3500;
        box-shadow: 0 2px 8px rgba(255,193,7,0.15);
        word-break: break-word;
    }
    .bot-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: #ffc107; color: #4a2e00;
        border-radius: 50px;
        padding: 2px 11px;
        font-size: 0.68rem; font-weight: 800;
        margin-bottom: 7px;
        text-transform: uppercase; letter-spacing: 0.4px;
    }

    /* TIMESTAMP */
    .time-stamp {
        font-size: 0.66rem; color: #aaa;
        margin-top: 3px;
        display: flex; align-items: center; gap: 3px;
    }
    .time-stamp.right { justify-content: flex-end; }

    /* FOOTER */
    .chat-footer {
        background: #f0f0f0;
        padding: 10px 14px;
        border-top: 1px solid #e0e0e0;
    }
    .chat-input-wrap {
        display: flex; align-items: center;
        background: #fff;
        border-radius: 50px;
        padding: 5px 6px 5px 18px;
        gap: 8px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.06);
    }
    .chat-input-wrap input {
        border: none; background: transparent; outline: none;
        flex-grow: 1; font-size: 0.88rem; color: #333;
    }
    .chat-input-wrap input::placeholder { color: #bbb; }
    .btn-send {
        width: 38px; height: 38px; border-radius: 50%;
        background: linear-gradient(135deg, #0d6efd, #0043a8);
        border: none; color: white;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; flex-shrink: 0;
        cursor: pointer;
        transition: transform 0.15s, box-shadow 0.15s;
    }
    .btn-send:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 14px rgba(13,110,253,0.4);
    }

    /* DARK MODE */
    .dark-mode .chat-card { background: #1a1a1a !important; }
    .dark-mode .chat-header { background: linear-gradient(135deg, #0a4ebd, #0033a0) !important; }
    .dark-mode .chat-body { background-color: #0b141a !important; }
    .dark-mode .chat-body::before { filter: invert(1); opacity: 0.03 !important; }
    .dark-mode .bubble-me    { background: #005c4b !important; color: #e9edef !important; }
    .dark-mode .bubble-other { background: #202c33 !important; color: #e9edef !important; }
    .dark-mode .bubble-bot   { background: linear-gradient(135deg, #2d2510, #332a10) !important; border-color: #5c4a00 !important; color: #ffe082 !important; }
    .dark-mode .bot-badge    { background: #5c4000; color: #ffe082; }
    .dark-mode .chat-footer  { background: #1a1a1a !important; border-top-color: #252525 !important; }
    .dark-mode .chat-input-wrap { background: #2a3942 !important; }
    .dark-mode .chat-input-wrap input { color: #e0e0e0 !important; }
    .dark-mode .time-stamp { color: #555 !important; }
</style>

<div class="container-fluid pb-4">
    <div class="card chat-card mx-auto">

        {{-- Header --}}
        <div class="card-header chat-header">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center" style="gap:12px;">
                    {{-- Back (mobile) --}}
                    <a href="{{ route('chat.index') }}" class="text-white d-md-none" style="opacity:0.8;">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    {{-- Avatar --}}
                    <div class="position-relative" style="flex-shrink:0;">
                        @if($user->photo)
                            <img src="{{ asset('uploads/profile/' . $user->photo) }}"
                                 class="chat-header-avatar"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=0d6efd'">
                        @else
                            <div class="chat-header-letter">
                                {{ strtoupper(substr($user->outlet ? $user->outlet->nama_outlet : $user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    {{-- Info --}}
                    <div style="line-height:1;">
                        <div class="fw-bold text-white" style="font-size:0.9rem;margin-bottom:3px;">
                            {{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}
                        </div>
                        <div style="color:rgba(255,255,255,0.62);font-size:0.7rem;display:flex;align-items:center;gap:4px;">
                            <span style="width:6px;height:6px;background:#2ecc71;border-radius:50%;display:inline-block;"></span>
                            Online
                        </div>
                    </div>
                </div>
                {{-- Tombol Tutup --}}
                <a href="{{ route('chat.index') }}" class="btn btn-tutup d-none d-md-inline-flex align-items-center" style="gap:6px;">
                    <i class="fas fa-times" style="font-size:0.75rem;"></i> Tutup
                </a>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body chat-body" id="chat-container">
            @foreach($messages as $msg)
                @php
                    $isMe  = $msg->sender_id === auth()->id();


                    // Deteksi BOT: cek semua variasi [SISTEM...]
                    $isBot = str_contains($msg->message, '[SISTEM');

                    // Bersihkan semua tag [SISTEM...] dari tampilan
                    $cleanMsg = trim(preg_replace('/\[SISTEM[^\]]*\]\s*/i', '', $msg->message));
                @endphp

                @if($isBot)
                    {{-- BOT BUBBLE — posisi ikut $isMe, styling amber --}}
                    <div class="bubble-wrap {{ $isMe ? 'me' : 'other' }}">
                        <div class="bubble-bot {{ $isMe ? 'bot-me' : 'bot-other' }}">
                            <div class="bot-badge">
                                <i class="fas fa-robot"></i> Notifikasi Sistem
                            </div>
                            <div style="white-space:pre-wrap;">{{ $cleanMsg }}</div>
                        </div>
                        <div class="time-stamp {{ $isMe ? 'right' : '' }}">
                            {{ $msg->created_at->format('H:i') }}
                            @if($isMe)
                                <i class="fas fa-check-double {{ $msg->is_read ? 'text-primary' : '' }}" style="font-size:0.6rem;"></i>
                            @endif
                        </div>
                    </div>

                @else
                    {{-- BUBBLE NORMAL --}}
                    <div class="bubble-wrap {{ $isMe ? 'me' : 'other' }}">
                        <div class="bubble {{ $isMe ? 'bubble-me' : 'bubble-other' }}">
                            <div style="white-space:pre-wrap;">{{ $msg->message }}</div>
                        </div>
                        <div class="time-stamp {{ $isMe ? 'right' : '' }}">
                            {{ $msg->created_at->format('H:i') }}
                            @if($isMe)
                                <i class="fas fa-check-double {{ $msg->is_read ? 'text-primary' : '' }}" style="font-size:0.6rem;"></i>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Footer --}}
        <div class="card-footer chat-footer">
            <form action="{{ route('chat.store', $user->id) }}" method="POST">
                @csrf
                <div class="chat-input-wrap">
                    <input type="text" name="message"
                           placeholder="Ketik pesan di sini..."
                           required autocomplete="off">
                    <button type="submit" class="btn-send">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

@push('js')
<script>
    const chatBox = document.getElementById("chat-container");
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
@endsection