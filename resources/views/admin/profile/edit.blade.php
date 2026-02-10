@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Edit Profile</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control"
                   value="{{ old('username', $user->username) }}">
        </div>

        <div class="mb-3">
            <label>No HP</label>
            <input type="text" name="no_hp" class="form-control"
                   value="{{ old('no_hp', $user->no_hp) }}">
        </div>

        <button class="btn btn-primary">Simpan</button>
    </form>

    <hr>

    <h4>Ganti Password</h4>
    <form action="{{ route('profile.update-password') }}" method="POST">
        @csrf

        <div class="mb-3">
            <input type="password" name="password" class="form-control"
                   placeholder="Password baru">
        </div>

        <div class="mb-3">
            <input type="password" name="password_confirmation"
                   class="form-control" placeholder="Konfirmasi password">
        </div>

        <button class="btn btn-warning">Update Password</button>
    </form>
</div>
@endsection
