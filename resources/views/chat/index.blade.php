@extends('layouts.main')

@section('title', 'Chat Outlet')
@section('page', 'Komunikasi Outlet')

@section('content')
<style>
    /* --- LIGHT MODE STYLES --- */
    .chat-index-card { 
        border-radius: 24px !important; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important; 
    }
    
    .chat-header-index {
        background-color: #ffffff;
        border-bottom: 1px solid #f0f0f0;
    }

    .bg-soft-primary { background-color: #eef4ff; color: #0d6efd; }
    
    .hover-chat-item {
        transition: all 0.2s ease-in-out;
        border-left: 4px solid transparent;
    }

    .hover-chat-item:hover { 
        background-color: #f8f9fa; 
        transform: translateX(5px);
        border-left: 4px solid #0d6efd;
    }

    .bg-unread-active { 
        background-color: rgba(13, 110, 253, 0.04) !important; 
    }

    /* --- DARK MODE OVERRIDES --- */
    .dark-mode .chat-index-card { 
        background-color: #1a1a1a !important; 
        border: 1px solid #333 !important;
    }

    .dark-mode .chat-header-index {
        background-color: #1a1a1a !important;
        border-bottom: 1px solid #333 !important;
    }

    .dark-mode .chat-header-index h5 { color: #ffffff !important; }

    .dark-mode .list-group-item {
        background-color: transparent !important;
        border-bottom: 1px solid #333 !important;
        color: #e9edef !important;
    }

    .dark-mode .hover-chat-item:hover {
        background-color: #252525 !important;
    }

    .dark-mode .bg-unread-active {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .dark-mode .text-dark { color: #ffffff !important; }
    .dark-mode .text-muted { color: #8696a0 !important; }

    .dark-mode .bg-soft-primary {
        background-color: #2d2d2d !important;
        color: #3b9bff !important;
    }
</style>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card chat-index-card border-0 overflow-hidden">
                {{-- Header Section --}}
                <div class="card-header chat-header-index py-4 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-1 text-dark">Pesan Terbaru</h5>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-sync-alt me-1"></i> Kelola koordinasi stok dan waste secara real-time.
                            </p>
                        </div>
                        <span class="badge bg-soft-primary rounded-pill px-3 py-2 fw-bold">
                            {{ $users->count() }} Kontak
                        </span>
                    </div>
                </div>

                {{-- Body Section --}}
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($users as $user)
                            @php $hasUnread = $user->unread_count > 0; @endphp
                            
                            <a href="{{ route('chat.show', $user->id) }}" 
                               class="list-group-item list-group-item-action px-4 py-4 d-flex align-items-center hover-chat-item {{ $hasUnread ? 'bg-unread-active' : '' }}">
                                
                                {{-- Avatar Section --}}
                                <div class="position-relative">
                                    @if($user->photo)
                                        <img src="{{ asset('uploads/profile/' . $user->photo) }}" 
                                             class="rounded-circle shadow-sm object-fit-cover" 
                                             style="width: 58px; height: 58px; border: 2px solid #fff;"
                                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=719ece&background=eef4ff';">
                                    @else
                                        <div class="bg-soft-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" 
                                             style="width: 58px; height: 58px; font-size: 1.3rem; border: 2px solid #fff;">
                                            {{ strtoupper(substr($user->outlet ? $user->outlet->nama_outlet : $user->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    {{-- Green Online Dot --}}
                                    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" 
                                          style="width: 14px; height: 14px;" title="Online"></span>
                                </div>

                                {{-- Text Content --}}
                                <div class="ms-4 flex-grow-1 overflow-hidden">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold {{ $hasUnread ? 'text-primary' : 'text-dark' }}">
                                            {{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}
                                        </h6>
                                        @if($user->last_message)
                                            <small class="text-muted" style="font-size: 0.75rem;">
                                                {{ $user->last_message->created_at->diffForHumans(null, true) }}
                                            </small>
                                        @endif
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 text-muted text-truncate small pe-3" style="max-width: 85%;">
                                            @if($user->last_message)
                                                @if(str_contains($user->last_message->message, '[SISTEM NOTIFIKASI STOK]'))
                                                    <span class="text-danger fw-bold"><i class="fas fa-robot me-1"></i> Notifikasi Stok</span>
                                                @else
                                                    {{ $user->last_message->sender_id == auth()->id() ? 'Anda: ' : '' }}{{ $user->last_message->message }}
                                                @endif
                                            @else
                                                <span class="fst-italic opacity-50">Belum ada percakapan...</span>
                                            @endif
                                        </p>
                                        
                                        @if($hasUnread)
                                            <span class="badge bg-primary rounded-pill small animate-pulse">
                                                {{ $user->unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            {{-- Empty State --}}
                            <div class="text-center py-5 my-4">
                                <div class="mb-3">
                                    <i class="fas fa-comment-slash fa-4x text-muted opacity-25"></i>
                                </div>
                                <h6 class="fw-bold text-muted">Belum ada percakapan</h6>
                                <p class="text-muted small">Hubungi outlet atau pusat untuk memulai koordinasi.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection