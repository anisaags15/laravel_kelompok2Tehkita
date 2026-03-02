@extends('layouts.main')

@section('title', 'Edit Stok Masuk')
@section('page', 'Edit Stok Masuk')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-2">
                    <li class="breadcrumb-item small"><a href="{{ route('admin.dashboard') }}" class="text-success">Dashboard</a></li>
                    <li class="breadcrumb-item small"><a href="{{ route('admin.stok-masuk.index') }}" class="text-success">Stok Masuk</a></li>
                    <li class="breadcrumb-item small active" aria-current="page">Edit Data</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-white btn-sm rounded-circle shadow-sm mr-3" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; border: 1px solid #e3e6f0;">
                    <i class="fas fa-chevron-left text-success"></i>
                </a>
                <h3 class="font-weight-bold text-dark mb-0" style="letter-spacing: -1px;">Edit Log Stok Masuk</h3>
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
                            <i class="fas fa-file-import text-white fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold text-dark">Detail Penerimaan</h4>
                        <p class="text-muted small px-lg-5">Perbarui data penerimaan bahan baku. Perubahan ini akan langsung menyesuaikan saldo stok di gudang utama.</p>
                    </div>

                    <form action="{{ route('admin.stok-masuk.update', $stok_masuk->id) }}" method="POST" id="editForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Pilih Bahan --}}
                            <div class="col-md-12 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Nama Bahan Baku</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-box-open text-success"></i>
                                        </span>
                                    </div>
                                    <select name="bahan_id" id="bahan_id" class="form-control form-control-lg border-left-0 @error('bahan_id') is-invalid @enderror" style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; height: 55px; font-weight: 600;">
                                        @foreach($bahans as $bahan)
                                            <option value="{{ $bahan->id }}" {{ $stok_masuk->bahan_id == $bahan->id ? 'selected' : '' }}>
                                                {{ $bahan->nama_bahan }} ({{ $bahan->satuan }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bahan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jumlah Masuk --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Jumlah Masuk</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-plus-circle text-success"></i>
                                        </span>
                                    </div>
                                    <input type="number" name="jumlah" id="jumlah" 
                                           class="form-control form-control-lg border-left-0 @error('jumlah') is-invalid @enderror" 
                                           value="{{ old('jumlah', $stok_masuk->jumlah) }}" 
                                           style="border-radius: 0 14px 14px 0; border: 2px solid #e1f5ea; font-weight: 700; height: 55px;" 
                                           placeholder="0" min="1" required>
                                    @error('jumlah')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Tanggal Masuk --}}
                            <div class="col-md-6 mb-4">
                                <label class="text-xs font-weight-bold text-uppercase tracking-wider text-muted mb-2 d-block">Tanggal Masuk</label>
                                <div class="input-group input-group-merge">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white border-right-0" style="border-radius: 14px 0 0 14px; border: 2px solid #e1f5ea;">
                                            <i class="fas fa-calendar-alt text-success"></i>
                                        </span>
                                    </div>
                                    <input type="date" name="tanggal" id="tanggal" 
                                           class="form-control form-control-lg border-left-0 @error('tanggal') is-invalid @enderror" 
                                           value="{{ old('tanggal', $stok_masuk->tanggal) }}" 
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
                                <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-light btn-lg w-100 py-3" style="border-radius: 15px; font-weight: 700; color: #858796; background: #f8f9fc; border: 1px solid #e3e6f0;">
                                    Batal
                                </a>
                            </div>
                            <div class="col-6">
                                <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-lg" style="border-radius: 15px; font-weight: 700; background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); border: none;">
                                    Update Stok
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
                    <p class="mb-0 small text-dark"><b>Catatan:</b> Jika Anda mengubah jumlah, sistem akan mengkalkulasi ulang stok akhir bahan baku secara otomatis.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('editForm').onsubmit = function() {
        let btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memperbarui Stok...';
        btn.disabled = true;
    };
</script>
@endsection