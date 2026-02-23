@extends('layouts.main')

@section('title', 'Chat Outlet')
@section('page', 'Komunikasi Outlet')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 border-radius-xl">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">
                            <i class="fas fa-comments text-primary me-2"></i> Kotak Masuk Pesan
                        </h5>
                        <small class="text-muted">Pilih outlet atau admin untuk mulai mengobrol</small>
                    </div>
                    <span class="badge bg-soft-primary text-primary px-3 py-2">{{ $users->count() }} Kontak</span>
                </div>
                
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($users as $user)
                        <a href="{{ route('chat.show', $user->id) }}" 
                           class="list-group-item list-group-item-action border-0 px-4 py-3 d-flex align-items-center transition-all hover-bg-light">
                            
                       {{-- AVATAR LOGIC --}}
<div class="position-relative">
    @if($user->photo)
        {{-- Tambahkan /profile/ setelah uploads --}}
        <img src="{{ asset('uploads/profile/' . $user->photo) }}" 
             class="rounded-circle shadow-sm object-fit-cover" 
             style="width: 55px; height: 55px; border: 2px solid #fff;"
             onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF';">
    @else
        <div class="bg-soft-primary text-primary rounded-circle d-flex justify-content-center align-items-center fw-bold shadow-sm" 
             style="width: 55px; height: 55px; font-size: 1.2rem; border: 2px solid #fff;">
            {{ strtoupper(substr($user->outlet ? $user->outlet->nama_outlet : $user->name, 0, 1)) }}
        </div>
    @endif
    <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-2 border-white rounded-circle"></span>
</div>
                            <div class="ms-4 flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="mb-0 fw-bold text-dark">
                                        {{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}
                                        <span class="badge {{ $user->role == 'admin' ? 'bg-soft-danger text-danger' : 'bg-soft-info text-info' }} ms-2 fw-normal" style="font-size: 0.65rem;">
                                            {{ strtoupper($user->role) }}
                                        </span>
                                    </h6>
                                    @if($user->last_message)
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            {{ $user->last_message->created_at->diffForHumans(null, true) }}
                                        </small>
                                    @endif
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="mb-0 text-muted text-truncate small" style="max-width: 80%;">
                                        @if($user->last_message)
                                            {{ $user->last_message->sender_id == auth()->id() ? 'Anda: ' : '' }} {{ $user->last_message->message }}
                                        @else
                                            <span class="fst-italic text-light">Belum ada percakapan...</span>
                                        @endif
                                    </p>
                                    
                                    @if($user->unread_count > 0)
                                        <span class="badge bg-danger rounded-pill pulse-red shadow-sm">
                                            {{ $user->unread_count }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <i class="fas fa-chevron-right ms-3 text-light small opacity-50"></i>
                        </a>
                        @empty
                        <div class="text-center py-5">
                            <img src="https://illustrations.popsy.co/amber/no-messages.svg" alt="No Messages" style="width: 150px;" class="mb-3">
                            <p class="text-muted">Belum ada user lain untuk di-chat.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-light:hover { background-color: #f8f9fa; transform: translateX(5px); }
    .transition-all { transition: all 0.3s ease; }
    .pulse-red { animation: pulse-red-small 2s infinite; }
    @keyframes pulse-red-small {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    .bg-soft-primary { background-color: rgba(13, 110, 253, 0.1); }
    .bg-soft-info { background-color: rgba(13, 202, 240, 0.1); }
    .bg-soft-danger { background-color: rgba(220, 53, 69, 0.1); }
    .object-fit-cover { object-fit: cover; }
</style>
@endsection