@extends('layouts.main')

@section('title', 'Edit Bahan')
@section('page', 'Edit Bahan')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.dashboard') }}" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="{{ route('admin.bahan.index') }}" class="text-success">Daftar Bahan</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Edit Bahan</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.bahan.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm mr-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #e3e6f0;">
                    <i class="fas fa-chevron-left text-success"></i>
                </a>
                <h3 class="font-weight-bold text-dark mb-0" style="letter-spacing: -1px;">Perbarui Data Bahan</h3>
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
                            <i class="fas fa- edit text-white fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark">Informasi Bahan</h4>
                        <p class="text-muted small px-lg-5">Silakan perbarui informasi detail bahan baku di bawah ini untuk menjaga akurasi data inventaris.</p>
                    </div>

                    <form action="{{ route('admin.bahan.update', $bahan->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Nama Bahan --}}
                            <div class="col-md-12 mb-4">
                                <label for="nama_bahan" class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Nama Bahan Baku</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-tag text-success"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="nama_bahan" id="nama_bahan" 
                                           class="form-control form-control-lg border-left-0 @error('nama_bahan') is-invalid @enderror" 
                                           value="{{ old('nama_bahan', $bahan->nama_bahan) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 600; height: 55px;" 
                                           placeholder="Masukkan nama bahan..." required>
                                    @error('nama_bahan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Satuan --}}
                            <div class="col-md-6 mb-4">
                                <label for="satuan" class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Satuan</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-weight-hanging text-success"></i>
                                        </span>
                                    </div>
                                    <input type="text" name="satuan" id="satuan" 
                                           class="form-control form-control-lg border-left-0 @error('satuan') is-invalid @enderror" 
                                           value="{{ old('satuan', $bahan->satuan) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 600; height: 55px;" 
                                           placeholder="kg / gr / ml" required>
                                    @error('satuan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Stok Awal --}}
                            <div class="col-md-6 mb-4">
                                <label for="stok_awal" class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Stok Awal</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-cubes text-success"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="stok_awal" id="stok_awal" 
                                           class="form-control form-control-lg border-left-0 @error('stok_awal') is-invalid @enderror" 
                                           value="{{ old('stok_awal', $bahan->stok_awal) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 700; height: 55px;" required>
                                    @error('stok_awal')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-5" style="border-top: 2px dashed #e1f5ea;">

                        <div class="row">
                            <div class="col-6">
                                <a href="{{ route('admin.bahan.index') }}" class="btn btn-light btn-lg w-100 py-3" style="border-radius: 15px; font-weight: 700; color: #858796; background: #f8f9fc; border: 1px solid #e3e6f0;">
                                    Batal
                                </a>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg" style="border-radius: 15px; font-weight: 700; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none;">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 border-0" style="border-radius: 18px; background: rgba(28, 200, 138, 0.05);">
                <div class="card-body d-flex align-items-center py-3">
                    <div class="icon-circle bg-success text-white mr-3 shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">
                        <i class="fas fa-info-circle fa-sm"></i>
                    </div>
                    <p class="mb-0 small text-dark"><b>Penting:</b> Perubahan pada nama bahan atau satuan akan langsung tercermin pada laporan stok dan distribusi outlet.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editForm').onsubmit = function() {
        let btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        btn.disabled = true;
    };
</script>
@endsection