@extends('layouts.main')

@push('css')
<link rel="stylesheet" href="{{ asset('templates/dist/css/profile.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4 px-md-5">
    <div class="user-card-luxury overflow-hidden">
        <div class="row no-gutters">
            
            {{-- SIDEBAR KIRI --}}
            <div class="col-lg-4 profile-sidebar text-center">
                <div class="avatar-wrapper">
                    <div class="user-avatar-circle">
                        @if($user->photo)
                            <img src="{{ asset('uploads/profile/'.$user->photo) }}" class="w-100 h-100 object-fit-cover" id="previewImg">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-white">
                                <i class="fas fa-user-shield fa-4x text-success"></i>
                            </div>
                        @endif
                    </div>
                    <label for="uploadFoto" class="camera-trigger">
                        <i class="fas fa-camera"></i>
                    </label>
                </div>
                
                <h3 class="font-weight-bold text-dark mb-1">{{ $user->name }}</h3>
                <div class="badge badge-pill px-4 py-2 bg-dark text-white font-weight-bold mb-4">
                    <i class="fas fa-shield-alt mr-1 text-warning"></i> ADMINISTRATOR PUSAT
                </div>

                <div class="nav flex-column mb-5 text-left">
                    <a href="#info" class="nav-link-custom active" data-toggle="tab">
                        <i class="fas fa-user-cog"></i> Pengaturan Profil
                    </a>
                    <a href="#keamanan" class="nav-link-custom" data-toggle="tab">
                        <i class="fas fa-key"></i> Keamanan Sandi
                    </a>
                    <a href="#" class="nav-link-custom text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Keluar Sistem
                    </a>
                </div>

                <div class="info-grid text-left">
                    <div class="info-card-small shadow-sm">
                        <small class="text-muted d-block font-weight-bold small">ID LOGIN</small>
                        <span class="text-dark font-weight-bold">{{ $user->username }}</span>
                    </div>
                    <div class="info-card-small shadow-sm">
                        <small class="text-muted d-block font-weight-bold small">KONTAK ADMIN</small>
                        <span class="text-dark font-weight-bold">{{ $user->no_hp ?? 'Internal Only' }}</span>
                    </div>
                </div>
            </div>

            {{-- KONTEN KANAN (FORM SATU KOLOM) --}}
            <div class="col-lg-8 p-4 p-md-5">
                <div class="tab-content">
                    
                    {{-- Tab: Data Profil --}}
                    <div class="tab-pane fade show active" id="info">
                        <div class="mb-5">
                            <h2 class="font-weight-bold text-dark mb-1">Data Master Admin</h2>
                            <p class="text-muted">Kelola identitas utama administrator pusat di sini.</p>
                        </div>

                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="file" name="photo" id="uploadFoto" class="d-none" onchange="previewImage(this)">
                            
                            {{-- Form Ke Bawah --}}
                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">NAMA LENGKAP</label>
                                <input type="text" name="name" class="form-control premium-input" value="{{ $user->name }}" placeholder="Masukkan Nama Lengkap">
                            </div>

                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">USERNAME SISTEM</label>
                                <input type="text" name="username" class="form-control premium-input" value="{{ $user->username }}" placeholder="Username">
                            </div>

                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">EMAIL OFFICIAL</label>
                                <input type="email" name="email" class="form-control premium-input" value="{{ $user->email }}" placeholder="email@tehkita.com">
                            </div>

                            <div class="form-group-custom mb-5">
                                <label class="small font-weight-bold ml-2">NOMOR WHATSAPP</label>
                                <input type="text" name="no_hp" class="form-control premium-input" value="{{ $user->no_hp }}" placeholder="0812xxxx">
                            </div>
                            
                            <div class="text-right">
                                <button type="submit" class="btn-premium-save shadow">Update Profile Admin</button>
                            </div>
                        </form>
                    </div>

                    {{-- Tab: Keamanan --}}
                    <div class="tab-pane fade" id="keamanan">
                        <div class="mb-5">
                            <h2 class="font-weight-bold text-dark mb-1">Sistem Keamanan</h2>
                            <p class="text-muted">Ganti kata sandi akses administrator Anda secara berkala.</p>
                        </div>
                        <form action="{{ route('admin.profile.update-password') }}" method="POST">
                            @csrf
                            <div class="form-group-custom">
                                <label class="small font-weight-bold ml-2">KATA SANDI BARU</label>
                                <input type="password" name="password" class="form-control premium-input" placeholder="Masukan Kata Sandi Baru">
                            </div>
                            <div class="form-group-custom mb-5">
                                <label class="small font-weight-bold ml-2">ULANGI KATA SANDI</label>
                                <input type="password" name="password_confirmation" class="form-control premium-input" placeholder="Pastikan kata sandi sama">
                            </div>
                            <button type="submit" class="btn-premium-save w-100 shadow">Ganti Kata Sandi</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

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