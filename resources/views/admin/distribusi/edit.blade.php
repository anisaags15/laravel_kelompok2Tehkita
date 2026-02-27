@extends('layouts.main')

@section('title', 'Edit Distribusi')
@section('page', 'Edit Pengiriman Barang')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.dashboard') }}" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="{{ route('admin.distribusi.index') }}" class="text-success">Log Distribusi</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Edit Pengiriman</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.distribusi.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm mr-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #e3e6f0;">
                    <i class="fas fa-chevron-left text-success"></i>
                </a>
                <h3 class="font-weight-bold text-dark mb-0" style="letter-spacing: -1px;">Edit Data Distribusi</h3>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-xl" style="border-radius: 24px; background: #ffffff;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border-radius: 22px;">
                            <i class="fas fa-truck-loading text-white fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark">Detail Log Distribusi</h4>
                        <p class="text-muted small px-lg-5">Perubahan pada data pengiriman akan secara otomatis menyesuaikan kembali stok di gudang utama dan outlet terkait.</p>
                    </div>

                    <form action="{{ route('admin.distribusi.update', $distribusi->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

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
                                    <select name="bahan_id" id="bahan_id" class="form-control form-control-lg border-left-0 @error('bahan_id') is-invalid @enderror" style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;">
                                        @foreach($bahans as $bahan)
                                            <option value="{{ $bahan->id }}" {{ $distribusi->bahan_id == $bahan->id ? 'selected' : '' }}>
                                                {{ $bahan->nama_bahan }} — (Tersedia: {{ $bahan->stok_awal }} {{ $bahan->satuan }})
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
                                    <select name="outlet_id" id="outlet_id" class="form-control form-control-lg border-left-0 @error('outlet_id') is-invalid @enderror" style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;">
                                        @foreach($outlets as $outlet)
                                            <option value="{{ $outlet->id }}" {{ $distribusi->outlet_id == $outlet->id ? 'selected' : '' }}>
                                                {{ $outlet->nama_outlet }}
                                            </option>
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
                                            <i class="fas fa-paper-plane text-success"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="jumlah" id="jumlah" 
                                           class="form-control form-control-lg border-left-0 @error('jumlah') is-invalid @enderror" 
                                           value="{{ old('jumlah', $distribusi->jumlah) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 700; height: 55px;" required>
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
                                            <i class="fas fa-calendar-check text-success"></i>
                                        </span>
                                    </div>
                                    <input type="date" name="tanggal" id="tanggal" 
                                           class="form-control form-control-lg border-left-0 @error('tanggal') is-invalid @enderror" 
                                           value="{{ old('tanggal', $distribusi->tanggal) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 600; height: 55px;" required>
                                    @error('tanggal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-5" style="border-top: 2px dashed #e1f5ea;">

                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('admin.distribusi.index') }}" class="btn btn-light btn-lg w-100 py-3" style="border-radius: 15px; font-weight: 700; color: #858796; background: #f8f9fc; border: 1px solid #e3e6f0;">
                                    Batal
                                </a>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg" style="border-radius: 15px; font-weight: 700; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none;">
                                    Update Log
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 border-0" style="border-radius: 18px; background: rgba(28, 200, 138, 0.05);">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="icon-circle bg-success text-white mr-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="fas fa-sync fa-sm"></i>
                    </div>
                    <p class="mb-0 small text-dark"><b>Auto-Sync:</b> Sistem akan mengembalikan stok lama ke gudang terlebih dahulu sebelum memproses jumlah pengiriman yang baru.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editForm').onsubmit = function() {
        let btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengsinkronisasi...';
        btn.disabled = true;
    };
</script>
@endsection