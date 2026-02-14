@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h4>Chat dengan {{ $user->name }}</h4>

    <div class="chat-box border p-3 mb-3" style="height:300px; overflow-y:scroll;">
        @foreach($messages as $msg)
            <div class="mb-2 {{ $msg->sender_id == auth()->id() ? 'text-right' : '' }}">
                <b>{{ $msg->sender->name }}:</b> {{ $msg->message }}
            </div>
        @endforeach
    </div>

    <form action="{{ route('chat.store', $user->id) }}" method="POST">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Tulis pesan..." required>
            <button class="btn btn-primary">Kirim</button>
        </div>
    </form>
</div>
@endsection
