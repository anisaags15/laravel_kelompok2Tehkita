@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('templates/dist/css/profile.css') }}">
@endpush

@section('page', 'Profile')

@section('content')

<div class="row">

    {{-- LEFT --}}
    <div class="col-md-4">

        <div class="card profile-card shadow-sm">
            <div class="card-body text-center">

                @if($user->photo)
                    <img src="{{ asset('uploads/profile/'.$user->photo) }}"
                         class="profile-avatar">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ $user->name }}"
                         class="profile-avatar">
                @endif

                <h4 class="mt-3">{{ $user->name }}</h4>
                <p class="text-muted">{{ $user->role }}</p>

                <hr>

                <p><strong>Email:</strong><br>{{ $user->email }}</p>
                <p><strong>No HP:</strong><br>{{ $user->no_hp }}</p>

            </div>
        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-md-8">

        <div class="card profile-right-card shadow-sm">

            <div class="card-header bg-white">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#account">
                            Account Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#password">
                            Change Password
                        </a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content">

                    {{-- ACCOUNT --}}
                    <div class="tab-pane fade show active" id="account">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}"
                              method="POST"
                              enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="profile-section-title">
                                Informasi Akun
                            </div>

                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <label>Nama</label>
                                    <input type="text" name="name"
                                           class="form-control"
                                           value="{{ old('name', $user->name) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Username</label>
                                    <input type="text" name="username"
                                           class="form-control"
                                           value="{{ old('username', $user->username) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email"
                                           class="form-control"
                                           value="{{ old('email', $user->email) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>No HP</label>
                                    <input type="text" name="no_hp"
                                           class="form-control"
                                           value="{{ old('no_hp', $user->no_hp) }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label>Foto Profile</label>
                                    <input type="file" name="photo"
                                           class="form-control">
                                </div>

                            </div>

                            <button class="btn btn-success">
                                Update Profile
                            </button>

                        </form>

                    </div>


                    {{-- PASSWORD --}}
                    <div class="tab-pane fade" id="password">

                        <form action="{{ route('profile.update-password') }}"
                              method="POST">
                            @csrf

                            <div class="mb-3">
                                <label>Password Baru</label>
                                <input type="password"
                                       name="password"
                                       class="form-control">
                            </div>

                            <div class="mb-3">
                                <label>Konfirmasi Password</label>
                                <input type="password"
                                       name="password_confirmation"
                                       class="form-control">
                            </div>

                            <button class="btn btn-warning">
                                Update Password
                            </button>

                        </form>

                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

@endsection