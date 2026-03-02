@extends('layouts.main')

@section('title', 'Tambah Outlet')
@section('page', 'Tambah Outlet Baru')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.dashboard') }}" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="{{ route('admin.outlet.index') }}" class="text-success">Daftar Outlet</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Registrasi</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.outlet.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm mr-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #e3e6f0;">
                    <i class="fas fa-chevron-left text-success"></i>
                </a>
                <h3 class="font-weight-bold text-dark mb-0" style="letter-spacing: -1px;">Registrasi Outlet Baru</h3>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card border-0 shadow-xl" style="border-radius: 24px; background: #ffffff;">
                <div class="card-body p-4 p-md-5">
                    {{-- Visual Indicator --}}
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border-radius: 22px;">
                            <i class="fas fa-store text-white fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark">Informasi Cabang</h4>
                        <p class="text-muted small px-lg-5">Data ini digunakan untuk manajemen distribusi stok dan pemantauan performa harian setiap cabang.</p>
                    </div>

                    <form action="{{ route('admin.outlet.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            {{-- Nama Outlet --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Nama Cabang / Outlet</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-building text-success"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="nama_outlet" 
                                           class="form-control form-control-lg border-left-0 @error('nama_outlet') is-invalid @enderror" 
                                           value="{{ old('nama_outlet') }}" 
                                           placeholder="Contoh: Outlet Tebet"
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;" required>
                                    @error('nama_outlet')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- No HP --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Nomor Telepon/WA</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fab fa-whatsapp text-success"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="no_hp" 
                                           class="form-control form-control-lg border-left-0 @error('no_hp') is-invalid @enderror" 
                                           value="{{ old('no_hp') }}" 
                                           placeholder="0812xxxxxxxx"
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;" required>
                                    @error('no_hp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Alamat Lokasi</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0 py-3" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea; align-items: flex-start;">
                                            <i class="fas fa-map-marker-alt text-success mt-1"></i>
                                        </span>
                                    </div>
                                    <textarea name="alamat" rows="2" 
                                              class="form-control form-control-lg border-left-0 @error('alamat') is-invalid @enderror" 
                                              placeholder="Masukkan alamat lengkap cabang..."
                                              style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 500;" required>{{ old('alamat') }}</textarea>
                                    @error('alamat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Target Harian --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Target Pemakaian Harian (Unit)</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-bullseye text-success"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="target_pemakaian_harian" 
                                           class="form-control form-control-lg border-left-0 @error('target_pemakaian_harian') is-invalid @enderror" 
                                           value="{{ old('target_pemakaian_harian', 100) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 700;" required>
                                    @error('target_pemakaian_harian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted italic mt-2 d-block">Target ini digunakan sebagai acuan Progress Bar pada dashboard monitor.</small>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-5">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('admin.outlet.index') }}" class="btn btn-light btn-lg w-100 py-3" style="border-radius: 15px; font-weight: 700; color: #858796; background: #f8f9fc; border: 1px solid #e3e6f0;">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg" style="border-radius: 15px; font-weight: 700; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none;">
                                    <i class="fas fa-store mr-2"></i> Daftarkan Outlet
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection