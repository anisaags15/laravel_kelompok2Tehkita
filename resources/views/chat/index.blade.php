@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Daftar User</h3>

    <ul class="list-group mt-3">
        @foreach($users as $user)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>{{ $user->name }}</span>
                <a href="{{ route('chat.show', $user->id) }}" class="btn btn-sm btn-primary">
                    Chat
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection


