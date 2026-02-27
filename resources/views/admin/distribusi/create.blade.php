@extends('layouts.main')

@section('title', 'Kirim Barang')
@section('page', 'Pengiriman Barang ke Outlet')

@section('content')
<div class="container-fluid py-4">
    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.dashboard') }}" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="{{ route('admin.distribusi.index') }}" class="text-success">Log Distribusi</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Kirim Barang Baru</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.distribusi.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm mr-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #e3e6f0;">
                    <i class="fas fa-chevron-left text-success"></i>
                </a>
                <h3 class="font-weight-bold text-dark mb-0" style="letter-spacing: -1px;">Form Pengiriman Barang</h3>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-xl" style="border-radius: 24px; background: #ffffff;">
                <div class="card-body p-4 p-md-5">
                    {{-- Visual Indicator --}}
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border-radius: 22px;">
                            <i class="fas fa-paper-plane text-white fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark">Kirim Distribusi</h4>
                        <p class="text-muted small px-lg-5">Sistem akan secara otomatis memotong stok gudang utama dan menambah stok di outlet tujuan setelah barang dikirim.</p>
                    </div>

                    <form action="{{ route('admin.distribusi.store') }}" method="POST" id="sendForm">
                        @csrf

                        <div class="row">
                            {{-- Pilih Bahan --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Pilih Bahan Baku</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-box text-success"></i>
                                        </span>
                                    </div>
                                    <select name="bahan_id" class="form-control form-control-lg border-left-0 @error('bahan_id') is-invalid @enderror" style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;" required>
                                        <option value="" selected disabled>-- Pilih Bahan yang Akan Dikirim --</option>
                                        @foreach($bahans as $bahan)
                                            <option value="{{ $bahan->id }}">
                                                {{ $bahan->nama_bahan }} (Tersedia: {{ $bahan->stok_awal }} {{ $bahan->satuan }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bahan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Outlet Tujuan --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Outlet Tujuan</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-store text-success"></i>
                                        </span>
                                    </div>
                                    <select name="outlet_id" class="form-control form-control-lg border-left-0 @error('outlet_id') is-invalid @enderror" style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;" required>
                                        <option value="" selected disabled>-- Pilih Outlet Tujuan --</option>
                                        @foreach($outlets as $outlet)
                                            <option value="{{ $outlet->id }}">{{ $outlet->nama_outlet }}</option>
                                        @endforeach
                                    </select>
                                    @error('outlet_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jumlah Dikirim --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Jumlah Dikirim</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-truck-loading text-success"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="jumlah" class="form-control form-control-lg border-left-0 @error('jumlah') is-invalid @enderror" value="{{ old('jumlah') }}" placeholder="0" min="1" style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 700;" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tanggal --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Tanggal Pengiriman</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-calendar-alt text-success"></i>
                                        </span>
                                    </div>
                                    <input type="date" name="tanggal" class="form-control form-control-lg border-left-0 @error('tanggal') is-invalid @enderror" value="{{ date('Y-m-d') }}" style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="row mt-5">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('admin.distribusi.index') }}" class="btn btn-light btn-lg w-100 py-3" style="border-radius: 15px; font-weight: 700; color: #858796; background: #f8f9fc; border: 1px solid #e3e6f0;">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg" style="border-radius: 15px; font-weight: 700; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none;">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Barang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script Animasi Saat Klik Kirim --}}
<script>
    document.getElementById('sendForm').onsubmit = function() {
        let btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';
        btn.classList.add('disabled');
        btn.style.opacity = '0.8';
    };
</script>
@endsection