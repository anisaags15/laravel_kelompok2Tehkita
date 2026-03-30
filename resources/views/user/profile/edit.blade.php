@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('templates/dist/css/profile-user.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 px-md-5">
    <div class="user-card-luxury overflow-hidden">
        <div class="row no-gutters">
            
            {{-- SIDEBAR KIRI (IDENTITAS OUTLET) --}}
            <div class="col-lg-4 profile-sidebar text-center">
                <div class="avatar-wrapper">
                    <div class="user-avatar-circle">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('uploads/profile/'.auth()->user()->photo) }}" class="w-100 h-100 object-fit-cover" id="previewImg">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-white">
                                <i class="fas fa-store fa-4x text-success"></i>
                            </div>
                        @endif
                    </div>
                    <label for="uploadFoto" class="camera-trigger">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                
                <h3 class="font-weight-bold text-dark mb-1">{{ auth()->user()->name }}</h3>
                <div class="badge badge-pill px-4 py-2 bg-light text-success font-weight-bold mb-4 border">
                    <i class="fas fa-check-circle mr-1"></i> PARTNER OUTLET
                </div>

                <div class="nav flex-column mb-5 text-left">
                    <a href="#info" class="nav-link-custom active" data-toggle="tab">
                        <i class="fas fa-id-card"></i> Informasi Akun
                    </a>
                    <a href="#keamanan" class="nav-link-custom" data-toggle="tab">
                        <i class="fas fa-shield-alt"></i> Ganti Password
                    </a>
                    {{-- Tombol Keluar --}}
                    <a href="#" class="nav-link-custom text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off"></i> Keluar Aplikasi
                    </a>
                </div>

                <div class="info-grid text-left">
                    <div class="info-card-small shadow-sm">
                        <small class="text-muted d-block font-weight-bold small">ID OUTLET</small>
                        <span class="text-dark font-weight-bold">{{ auth()->user()->username }}</span>
                    </div>
                    <div class="info-card-small shadow-sm">
                        <small class="text-muted d-block font-weight-bold small">NO. HANDPHONE</small>
                        <span class="text-dark font-weight-bold">{{ auth()->user()->no_hp ?? 'Belum Diatur' }}</span>
                    </div>
                </div>
            </div>

            {{-- KONTEN KANAN (FORM SATU KOLOM) --}}
            <div class="col-lg-8 p-4 p-md-5">
                <div class="tab-content">
                    
                    {{-- Tab: Data Profil --}}
                    <div class="tab-pane fade show active" id="info">
                        <div class="mb-5">
                            <h2 class="font-weight-bold text-dark mb-1">Pengaturan Akun</h2>
                            <p class="text-muted">Kelola informasi profil dan kontak outlet Anda agar tetap aktif.</p>
                        </div>

                        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="file" name="photo" id="uploadFoto" class="d-none" onchange="previewImage(this)">
                            
                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">NAMA ADMIN OUTLET</label>
                                <input type="text" name="name" class="form-control premium-input" value="{{ auth()->user()->name }}" placeholder="Masukkan Nama Outlet">
                            </div>

                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">USERNAME LOGIN</label>
                                <input type="text" name="username" class="form-control premium-input" value="{{ auth()->user()->username }}" placeholder="Username">
                            </div>

                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">ALAMAT EMAIL</label>
                                <input type="email" name="email" class="form-control premium-input" value="{{ auth()->user()->email }}" placeholder="email@contoh.com">
                            </div>

                            <div class="form-group-custom mb-5">
                                <label class="small font-weight-bold ml-2">NOMOR WHATSAPP</label>
                                <input type="text" name="no_hp" class="form-control premium-input" value="{{ auth()->user()->no_hp }}" placeholder="08xxxx">
                            </div>
                            
                            <div class="text-right">
                                <button type="submit" class="btn-premium-save shadow">Simpan Profil Sekarang</button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab: Keamanan --}}
                    <div class="tab-pane fade" id="keamanan">
                        <div class="mb-5">
                            <h2 class="font-weight-bold text-dark mb-1">Keamanan Akun</h2>
                            <p class="text-muted">Pastikan kata sandi Anda kuat untuk melindungi data outlet.</p>
                        </div>
                        <form action="{{ route('user.profile.update-password') }}" method="POST">
                            @csrf
                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">KATA SANDI BARU</label>
                                <input type="password" name="password" class="form-control premium-input" placeholder="Masukan Kata Sandi Baru">
                            </div>
                            <div class="form-group-custom mb-5">
                                <label class="small font-weight-bold ml-2">KONFIRMASI KATA SANDI</label>
                                <input type="password" name="password_confirmation" class="form-control premium-input" placeholder="Ulangi kata sandi">
                            </div>
                            <button type="submit" class="btn-premium-save w-100 shadow">Update Kata Sandi</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- FORM LOGOUT TERSEMBUNYI (PENTING AGAR TOMBOL KELUAR BERFUNGSI) --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection