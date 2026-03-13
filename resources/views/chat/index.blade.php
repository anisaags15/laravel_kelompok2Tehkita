@extends('layouts.main')

@section('title', 'Chat Outlet')
@section('page', 'Komunikasi Outlet')

@section('content')
<style>
    .chat-index-card {
        border-radius: 24px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06) !important;
        border: none !important;
        overflow: hidden;
    }
    .chat-header-index {
        background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%);
        padding: 22px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .chat-header-index h5 {
        color: white; font-weight: 800; margin: 0 0 3px; font-size: 1.1rem;
    }
    .chat-header-index p {
        color: rgba(255,255,255,0.72); margin: 0; font-size: 0.8rem;
    }
    .chat-kontak-pill {
        background: rgba(255,255,255,0.15);
        border: 1.5px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* List item */
    .chat-item {
        border-bottom: 1px solid #f3f3f3 !important;
        transition: background 0.15s, transform 0.15s;
        border-left: 4px solid transparent;
        padding: 14px 22px;
        display: flex;
        align-items: center;
        text-decoration: none !important;
        color: inherit !important;
    }
    .chat-item:hover {
        background: #eef4ff !important;
        border-left-color: #0d6efd;
        transform: translateX(3px);
    }
    .chat-item.unread { background: rgba(13,110,253,0.04) !important; }

    /* Avatar */
    .chat-avatar-wrap { position: relative; flex-shrink: 0; }
    .chat-avatar-img {
        width: 50px; height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e0e8ff;
        display: block;
    }
    .chat-avatar-letter {
        width: 50px; height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #0d6efd, #0043a8);
        color: white;
        display: flex; align-items: center; justify-content: center;
        font-weight: 800; font-size: 1.15rem;
        border: 2px solid #e0e8ff;
        flex-shrink: 0;
    }
    .online-dot {
        position: absolute; bottom: 1px; right: 1px;
        width: 12px; height: 12px;
        background: #2ecc71;
        border: 2px solid white;
        border-radius: 50%;
    }

    /* Text */
    .chat-content { flex: 1; min-width: 0; margin-left: 14px; }
    .chat-row-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 4px;
    }
    .chat-name {
        font-weight: 700;
        font-size: 0.93rem;
        color: #1a1a1a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }
    .chat-name.unread { color: #0d6efd; }
    .chat-time { font-size: 0.7rem; color: #bbb; white-space: nowrap; flex-shrink: 0; margin-left: 8px; }
    .chat-row-bottom { display: flex; align-items: center; justify-content: space-between; }
    .chat-preview {
        font-size: 0.8rem;
        color: #999;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        flex: 1;
        min-width: 0;
    }
    .preview-system { color: #e65100; font-weight: 600; }
    .badge-unread {
        background: #0d6efd;
        color: white;
        border-radius: 50px;
        padding: 2px 9px;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-left: 8px;
    }

    /* Empty */
    .empty-chat { text-align: center; padding: 60px 20px; color: #ccc; }
    .empty-chat i { font-size: 3rem; display: block; margin-bottom: 14px; }

    /* Dark mode */
    .dark-mode .chat-index-card { background: #1a1a1a !important; border: 1px solid #2a2a2a !important; }
    .dark-mode .chat-header-index { background: linear-gradient(135deg, #0a4ebd, #0033a0); }
    .dark-mode .chat-item { border-bottom-color: #252525 !important; color: #e0e0e0 !important; }
    .dark-mode .chat-item:hover { background: #1a2035 !important; }
    .dark-mode .chat-item.unread { background: rgba(13,110,253,0.08) !important; }
    .dark-mode .chat-name { color: #e0e0e0; }
    .dark-mode .chat-name.unread { color: #5b9bff; }
    .dark-mode .chat-preview { color: #666; }
    .dark-mode .chat-time { color: #444; }
    .dark-mode .chat-avatar-img,
    .dark-mode .chat-avatar-letter { border-color: #1a2a50; }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card chat-index-card">

                {{-- Header --}}
                <div class="chat-header-index">
                    <div>
                        <h5><i class="fas fa-comments me-2"></i>Pesan Terbaru</h5>
                        <p>Kelola koordinasi stok dan waste secara real-time</p>
                    </div>
                    <div class="chat-kontak-pill">
                        <i class="fas fa-store me-1"></i> {{ $users->count() }} Outlet
                    </div>
                </div>

                {{-- List --}}
                <div class="card-body p-0">
                    @forelse($users as $user)
                        @php $hasUnread = $user->unread_count > 0; @endphp
                        <a href="{{ route('chat.show', $user->id) }}"
                           class="chat-item {{ $hasUnread ? 'unread' : '' }}">

                            {{-- Avatar --}}
                            <div class="chat-avatar-wrap">
                                @if($user->photo)
                                    <img src="{{ asset('uploads/profile/' . $user->photo) }}"
                                         class="chat-avatar-img"
                                         onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0d6efd&color=fff'">
                                @else
                                    <div class="chat-avatar-letter">
                                        {{ strtoupper(substr($user->outlet ? $user->outlet->nama_outlet : $user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span class="online-dot"></span>
                            </div>

                            {{-- Content --}}
                            <div class="chat-content">
                                <div class="chat-row-top">
                                    <span class="chat-name {{ $hasUnread ? 'unread' : '' }}">
                                        {{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}
                                    </span>
                                    <span class="chat-time">
                                        {{ $user->last_message ? $user->last_message->created_at->diffForHumans(null, true) : '' }}
                                    </span>
                                </div>
                                <div class="chat-row-bottom">
                                    <span class="chat-preview">
                                        @if($user->last_message)
                                            @if(str_contains($user->last_message->message, '[SISTEM'))
                                                <span class="preview-system">
                                                    <i class="fas fa-robot me-1"></i>Notifikasi Sistem
                                                </span>
                                            @else
                                                @if($user->last_message->sender_id == auth()->id())
                                                    <span style="color:#aaa;">Anda: </span>
                                                @endif
                                                {{ Str::limit($user->last_message->message, 55) }}
                                            @endif
                                        @else
                                            <span class="fst-italic" style="opacity:0.4;">Belum ada percakapan...</span>
                                        @endif
                                    </span>
                                    @if($hasUnread)
                                        <span class="badge-unread">{{ $user->unread_count }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="empty-chat">
                            <i class="fas fa-comment-slash"></i>
                            <p class="fw-bold mb-1">Belum ada percakapan</p>
                            <small>Hubungi outlet atau pusat untuk memulai koordinasi</small>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</div>
@endsection