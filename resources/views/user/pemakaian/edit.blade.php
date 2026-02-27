@extends('layouts.main')

@section('title', 'Edit Pemakaian')
@section('page', 'Edit Pemakaian')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item small"><a href="{{ route('user.dashboard') }}" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="{{ route('user.pemakaian.index') }}" class="text-success">Pemakaian</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Edit Data</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <a href="{{ route('user.pemakaian.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm mr-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #e3e6f0;">
                    <i class="fas fa-chevron-left text-success"></i>
                </a>
                <h3 class="font-weight-bold text-dark mb-0" style="letter-spacing: -1px;">Perbarui Log Pemakaian</h3>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-xl" style="border-radius: 24px; background: #ffffff;">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-5">
                        <div class="d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" 
                             style="width: 80px; height: 80px; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border-radius: 22px;">
                            <i class="fas fa-edit text-white fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark">Detail Perubahan</h4>
                        <p class="text-muted small px-lg-5">Pastikan jumlah yang Anda masukkan akurat karena akan langsung mengkoreksi stok di gudang outlet secara otomatis.</p>
                    </div>

                    <form action="{{ route('user.pemakaian.update', $pemakaian->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Informasi Bahan</label>
                                <div class="d-flex align-items-center p-4" style="background: #f8fffb; border: 1px dashed #1cc88a; border-radius: 18px;">
                                    <div class="avatar-box mr-4" 
                                         style="width: 60px; height: 60px; background: #fff; border-radius: 15px; display: flex; align-items: center; justify-content: center; shadow: 0 4px 12px rgba(0,0,0,0.05); border: 1px solid #e1f5ea;">
                                        <span class="h4 font-weight-bold text-success mb-0">{{ strtoupper(substr($pemakaian->bahan->nama_bahan, 0, 1)) }}</span>
                                    </div>
                                    <div>
                                        <h5 class="font-weight-bold text-dark mb-1">{{ $pemakaian->bahan->nama_bahan }}</h5>
                                        <div class="badge badge-soft-success border-0 px-3 py-2" style="border-radius: 8px;">
                                            <i class="fas fa-fingerprint mr-1"></i> ID: #PMK-{{ $pemakaian->id }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="jumlah" class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Jumlah Digunakan</label>
                                <div class="input-group input-group-merge shadow-none">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-boxes text-success"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="jumlah" id="jumlah" 
                                           class="form-control form-control-lg border-left-0 @error('jumlah') is-invalid @enderror" 
                                           value="{{ old('jumlah', $pemakaian->jumlah) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 700; height: 55px;" 
                                           placeholder="0" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mt-2 text-right">
                                    <span class="badge badge-light text-muted" style="border-radius: 6px;">Satuan: {{ $pemakaian->bahan->satuan }}</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="tanggal" class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Tanggal Aktivitas</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-calendar-alt text-success"></i>
                                        </span>
                                    </div>
                                    <input type="date" name="tanggal" id="tanggal" 
                                           class="form-control form-control-lg border-left-0 @error('tanggal') is-invalid @enderror" 
                                           value="{{ old('tanggal', $pemakaian->tanggal) }}" 
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
                                <button type="button" onclick="window.history.back()" class="btn btn-light btn-lg w-100 py-3" style="border-radius: 15px; font-weight: 700; color: #858796; background: #f8f9fc; border: 1px solid #e3e6f0;">
                                    Batal
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg" style="border-radius: 15px; font-weight: 700; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none;">
                                    Update Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 border-0" style="border-radius: 18px; background: rgba(28, 200, 138, 0.05);">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="icon-circle bg-success text-white mr-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="fas fa-lightbulb fa-sm"></i>
                    </div>
                    <p class="mb-0 small text-dark"><b>Info Stok:</b> Jika jumlah dikurangi, stok akan bertambah secara otomatis. Pastikan data sudah sesuai.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.getElementById('editForm').onsubmit = function() {
        let btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memperbarui...';
        btn.disabled = true;
    };
</script>
@endsection