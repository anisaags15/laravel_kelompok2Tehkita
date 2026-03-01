@extends('layouts.main')

@section('page', 'Chat Detail')

@section('content')
<style>
    .chat-card { border-radius: 20px !important; overflow: hidden; border: none !important; box-shadow: 0 10px 30px rgba(0,0,0,0.05) !important; max-width: 900px; }
    .chat-header { background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); padding: 15px 25px !important; border: none; }
    .chat-body { height: 550px; overflow-y: auto; background: #f8f9fa; background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); background-repeat: repeat; scroll-behavior: smooth; }
    
    /* Bubble Base */
    .bubble { max-width: 75%; padding: 10px 16px; border-radius: 18px; position: relative; font-size: 0.95rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); margin-bottom: 4px; }
    
    /* Bubble Me */
    .bubble-me { background-color: #dcf8c6; color: #202c33; border-top-right-radius: 2px; align-self: flex-end; }
    
    /* Bubble Other */
    .bubble-other { background-color: #ffffff; color: #202c33; border-top-left-radius: 2px; align-self: flex-start; }
    
    /* Bubble System (Bot Notification) */
    .bubble-system { background-color: #fff3f3; color: #721c24; border: 1px solid #f5c6cb; border-radius: 12px; align-self: center; text-align: center; max-width: 90%; font-style: italic; }

    .time-stamp { font-size: 0.65rem; margin-top: 4px; display: block; opacity: 0.6; }
    .chat-body::-webkit-scrollbar { width: 6px; }
    .chat-body::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
</style>

<div class="container-fluid pb-4">
    <div class="card chat-card mx-auto">
        {{-- Header --}}
        <div class="card-header chat-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('chat.index') }}" class="text-white me-3 d-md-none"><i class="fas fa-arrow-left"></i></a>
                    <div class="position-relative">
                        @if($user->photo)
                            <img src="{{ asset('uploads/profile/' . $user->photo) }}" class="rounded-circle border border-2 border-white object-fit-cover shadow-sm" style="width: 45px; height: 45px;" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=FFFFFF&background=0d6efd';">
                        @else
                            <div class="bg-white text-primary rounded-circle d-flex justify-content-center align-items-center shadow-sm fw-bold" style="width: 45px; height: 45px;">
                                {{ strtoupper(substr($user->outlet ? $user->outlet->nama_outlet : $user->name, 0, 1)) }}
                            </div>
                        @endif
                        <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-0 fw-bold text-white">{{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}</h6>
                        <small class="text-white-50">Online</small>
                    </div>
                </div>
                <a href="{{ route('chat.index') }}" class="btn btn-light btn-sm rounded-pill px-3 d-none d-md-block shadow-sm">
                    <i class="fas fa-times me-1"></i> Tutup
                </a>
            </div>
        </div>

        {{-- Body --}}
        <div class="card-body chat-body d-flex flex-column p-4" id="chat-container">
            @foreach($messages as $msg)
                @php 
                    $isMe = $msg->sender_id === auth()->id(); 
                    $isSystem = str_contains($msg->message, '[SISTEM NOTIFIKASI STOK]');
                @endphp
                
                <div class="d-flex flex-column mb-3">
                    <div class="bubble {{ $isSystem ? 'bubble-system' : ($isMe ? 'bubble-me' : 'bubble-other') }}">
                        @if($isSystem) <i class="fas fa-robot me-1"></i> @endif
                        <div class="message-text" style="white-space: pre-wrap;">{{ $msg->message }}</div>
                        <span class="time-stamp {{ $isMe ? 'text-end' : 'text-start' }}">
                            {{ $msg->created_at->format('H:i') }}
                            @if($isMe) <i class="fas fa-check-double ms-1 {{ $msg->is_read ? 'text-primary' : '' }}"></i> @endif
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Footer --}}
        <div class="card-footer bg-white p-3 border-0">
            <form action="{{ route('chat.store', $user->id) }}" method="POST">
                @csrf
                <div class="input-group shadow-sm rounded-pill bg-light overflow-hidden p-1">
                    <input type="text" name="message" class="form-control border-0 bg-light px-4" placeholder="Ketik pesan di sini..." required autocomplete="off">
                    <button class="btn btn-primary rounded-pill px-4" type="submit">
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