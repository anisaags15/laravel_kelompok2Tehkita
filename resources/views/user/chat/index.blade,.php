@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Chat dengan Admin/Outlet Lain</h3>
    <ul class="list-group mt-3">
        @foreach($users as $user)
            <li class="list-group-item">
                <a href="{{ route('chat.show', $user->id) }}">
                    {{ $user->name }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endsection
