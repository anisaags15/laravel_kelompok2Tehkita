@extends('layouts.main')

@section('content')
<div class="container mt-4">
<h3>
    Chat dengan 
    {{ $user->outlet ? $user->outlet->nama_outlet : $user->name }}
</h3>
    <div class="chat-box border rounded p-3 mb-3" style="height:400px; overflow-y:auto;">
        @foreach($messages as $msg)
            @if($msg->sender_id === auth()->id())
                <!-- Pesan sendiri -->
                <div class="d-flex justify-content-end mb-2">
                    <div class="bg-primary text-white p-2 rounded" style="max-width:70%;">
                        {{ $msg->message }}
                        <div class="text-right text-muted" style="font-size:0.7rem;">
                            {{ $msg->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Pesan lawan chat -->
                <div class="d-flex justify-content-start mb-2">
                    <div class="bg-light p-2 rounded" style="max-width:70%;">
                        {{ $msg->message }}
                      <div class="text-right text-muted" style="font-size:0.7rem;">
    {{ $msg->created_at->format($msg->created_at->isToday() ? 'H:i' : 'd M H:i') }}
</div>

                    </div>
                </div>
            @endif
        @endforeach
    </div>

    <!-- Form kirim pesan -->
    <form action="{{ route('chat.store', $user->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." required>
            <button class="btn btn-primary" type="submit">Kirim</button>
        </div>
    </form>
</div>
@endsection
