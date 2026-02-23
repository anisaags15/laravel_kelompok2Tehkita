@extends('layouts.main')

@section('page', 'Chat Detail')

@section('content')
<style>
    .chat-card { border-radius: 20px !important; overflow: hidden; border: none !important; box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important; max-width: 900px; }
    .chat-header { background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); padding: 12px 20px !important; border: none; }
    .chat-body { height: 500px; overflow-y: auto; background: #f0f2f5 url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png') repeat; scroll-behavior: smooth; }
    .bubble { max-width: 70%; padding: 8px 14px; border-radius: 15px; position: relative; font-size: 0.95rem; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
    .bubble-me { background-color: #dcf8c6; color: #303030; border-top-right-radius: 2px; }
    .bubble-other { background-color: #ffffff; color: #303030; border-top-left-radius: 2px; }
    .time-stamp { font-size: 0.65rem; margin-top: 4px; display: block; opacity: 0.6; }
    .chat-body::-webkit-scrollbar { width: 5px; }
    .chat-body::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
    .object-fit-cover { object-fit: cover; }
</style>

<div class="container-fluid pb-4">
    <div class="card chat-card mx-auto">
        
{{-- Header Chat --}}
<div class="card-header chat-header d-flex align-items-center text-white">
    <div class="d-flex justify-content-between align-items-center w-100">
        {{-- SISI KIRI: Profil --}}
        <div class="d-flex align-items-center">
            @if($user->photo)
                <img src="{{ asset('uploads/profile/' . $user->photo) }}" 
                     class="rounded-circle me-3 border border-2 border-white object-fit-cover shadow-sm" 
                     style="width: 45px; height: 45px;"
                     onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=FFFFFF&background=0d6efd';">
            @else
                <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center shadow-sm me-3 fw-bold" 
                     style="width: 45px; height: 45px; font-size: 1.1rem;">
                    {{ strtoupper(substr($user->outlet ? $user->outlet->nama_outlet : $user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h6 class="mb-0 fw-bold text-white">{{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}</h6>
                <small class="opacity-75 d-flex align-items-center">
                    <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> Aktif Sekarang
                </small>
            </div>
        </div>

        {{-- SISI KANAN: Tombol Kembali --}}
        <div class="ms-auto">
            <a href="{{ route('chat.index') }}" class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary shadow-sm transition-all hover-scale">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>
        {{-- Body Chat --}}
        <div class="card-body chat-body p-4" id="chat-container">
            <div class="d-flex flex-column">
                @foreach($messages as $msg)
                    @php $isMe = $msg->sender_id === auth()->id(); @endphp
                    <div class="d-flex {{ $isMe ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                        <div class="bubble {{ $isMe ? 'bubble-me' : 'bubble-other' }}">
                            <div class="message-text">{{ $msg->message }}</div>
                            <span class="time-stamp {{ $isMe ? 'text-end' : 'text-start' }}">
                                {{ $msg->created_at->format('H:i') }}
                                @if($isMe) <i class="fas fa-check-double ms-1 {{ $msg->is_read ? 'text-primary' : '' }}"></i> @endif
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Footer Input --}}
        <div class="card-footer bg-white p-3 border-0">
            <form action="{{ route('chat.store', $user->id) }}" method="POST" id="chat-form">
                @csrf
                <div class="input-group">
                    <input type="text" name="message" class="form-control border-0 bg-light rounded-pill px-4" 
                           placeholder="Ketik pesan..." required autocomplete="off" style="height: 48px;">
                    <button class="btn btn-primary rounded-circle ms-2 shadow-sm d-flex align-items-center justify-content-center" 
                            type="submit" style="width: 48px; height: 48px;">
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