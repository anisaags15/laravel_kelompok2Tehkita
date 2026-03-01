@extends('layouts.main')

@section('title', 'Chat Outlet')
@section('page', 'Komunikasi Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 overflow-hidden" style="border-radius: 24px;">
                <div class="card-header bg-white py-4 px-4 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold mb-0 text-dark">Pesan Terbaru</h5>
                            <p class="text-muted small mb-0">Kelola koordinasi stok dan waste</p>
                        </div>
                        <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2">
                            {{ $users->count() }} Kontak
                        </span>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($users as $user)
                        <a href="{{ route('chat.show', $user->id) }}" 
                           class="list-group-item list-group-item-action px-4 py-4 d-flex align-items-center transition-all hover-chat-item {{ $user->unread_count > 0 ? 'bg-light-blue' : '' }}">
                            
                            <div class="position-relative">
                                @if($user->photo)
                                    <img src="{{ asset('uploads/profile/' . $user->photo) }}" class="rounded-circle shadow-sm object-fit-cover" style="width: 55px; height: 55px; border: 2px solid #fff;">
                                @else
                                    <div class="bg-soft-primary text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 55px; height: 55px; font-size: 1.2rem;">
                                        {{ strtoupper(substr($user->outlet ? $user->outlet->nama_outlet : $user->name, 0, 1)) }}
                                    </div>
                                @endif
                                @if($user->unread_count > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
                                @endif
                            </div>

                            <div class="ms-4 flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold {{ $user->unread_count > 0 ? 'text-primary' : 'text-dark' }}">
                                        {{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}
                                    </h6>
                                    @if($user->last_message)
                                        <small class="text-muted" style="font-size: 0.7rem;">
                                            {{ $user->last_message->created_at->diffForHumans(null, true) }}
                                        </small>
                                    @endif
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-0 text-muted text-truncate small pe-3" style="max-width: 90%;">
                                        @if($user->last_message)
                                            @if(str_contains($user->last_message->message, '[SISTEM NOTIFIKASI STOK]'))
                                                <span class="text-danger fw-bold"><i class="fas fa-robot"></i> Notifikasi Stok</span>
                                            @else
                                                {{ $user->last_message->sender_id == auth()->id() ? 'Anda: ' : '' }}{{ $user->last_message->message }}
                                            @endif
                                        @else
                                            <span class="fst-italic opacity-50">Belum ada percakapan...</span>
                                        @endif
                                    </p>
                                    @if($user->unread_count > 0)
                                        <span class="badge bg-primary rounded-pill small">{{ $user->unread_count }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="text-center py-5">
                            <i class="fas fa-comment-slash fa-3x text-light mb-3"></i>
                            <p class="text-muted">Tidak ada pesan ditemukan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-blue { background-color: rgba(13, 110, 253, 0.02); }
    .hover-chat-item:hover { background-color: #fbfbfb; transform: scale(1.01); transition: 0.2s; z-index: 1; }
    .bg-soft-primary { background-color: #eef4ff; }
</style>
@endsection