@extends('layouts.main')

@push('css')
{{-- Memanggil file yang benar: profile-user.css --}}
<link rel="stylesheet" href="{{ asset('templates/dist/css/profile-user.css') }}">
@endpush

@section('page', 'Profile Admin Outlet')

@section('content')
<div class="row">
    {{-- SISI KIRI: PREVIEW PROFILE --}}
    <div class="col-md-4">
        <div class="card profile-card shadow-sm">
            <div class="card-body text-center p-4">
                {{-- Container Pengunci Ukuran --}}
                <div class="profile-avatar-container shadow-sm">
                    <img src="{{ auth()->user()->photo ? asset('uploads/profile/'.auth()->user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=28a745&color=fff' }}"
                         class="profile-avatar" alt="User Image">
                </div>
                
                <h4 class="mt-3 font-weight-bold">{{ auth()->user()->name }}</h4>
                <p class="badge badge-success px-3">{{ ucfirst(auth()->user()->role) }}</p>
                
                <hr class="my-4">
                
                <div class="text-left">
                    <label class="small text-muted text-uppercase d-block mb-0">Email</label>
                    <p class="font-weight-bold">{{ auth()->user()->email }}</p>
                    
                    <label class="small text-muted text-uppercase d-block mb-0">No HP</label>
                    <p class="font-weight-bold">{{ auth()->user()->no_hp ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- SISI KANAN: FORM EDIT --}}
    <div class="col-md-8">
        <div class="card profile-right-card shadow-sm">
            <div class="card-header bg-white p-0 pt-1">
                <ul class="nav nav-tabs px-3" id="profileTab">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#account">Account Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#password">Change Password</a>
                    </li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content">
                    {{-- TAB PENGATURAN AKUN --}}
                    <div class="tab-pane fade show active" id="account">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                            </div>
                        @endif

                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="profile-section-title">Informasi Akun</div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold">Username</label>
                                    <input type="text" name="username" class="form-control" value="{{ old('username', auth()->user()->username) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold">Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold">No HP</label>
                                    <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', auth()->user()->no_hp) }}">
                                </div>
                                <div class="col-md-12 mb-4">
                                    <label class="small font-weight-bold">Upload Foto Baru</label>
                                    <input type="file" name="photo" class="form-control">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>

                    {{-- TAB GANTI PASSWORD --}}
                    <div class="tab-pane fade" id="password">
                        <div class="profile-section-title">Keamanan Password</div>
                        <form action="{{ route('user.profile.update-password') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label class="small font-weight-bold">Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="Masukkan password baru">
                            </div>
                            <div class="form-group mb-4">
                                <label class="small font-weight-bold">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                            </div>
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-key mr-2"></i> Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection