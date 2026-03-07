@extends('layouts.main')

@section('title', 'Edit Bahan')
@section('page', 'Edit Bahan')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 20px; padding: 40px;">
                
                <div class="text-center mb-4">
                    <div class="bg-success d-inline-block p-3 shadow-sm mb-3" style="border-radius: 15px !important;">
                        <i class="fas fa-box-open fa-2x text-white"></i>
                    </div>
                    <h3 class="font-weight-bold" style="color: #2c3e50;">Edit Data Bahan</h3>
                    <p class="text-muted">Pastikan nama bahan spesifik dan satuan sesuai dengan standar operasional gudang.</p>
                </div>

                <form action="{{ route('admin.bahan.update', $bahan->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-0">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase">Nama Bahan Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                        <i class="fas fa-tag text-success"></i>
                                    </span>
                                </div>
                                <input type="text" name="nama_bahan" class="form-control form-control-lg border-left-0 @error('nama_bahan') is-invalid @enderror" 
                                    style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" 
                                    value="{{ old('nama_bahan', $bahan->nama_bahan) }}" placeholder="Contoh: Teh Gold">
                            </div>
                            @error('nama_bahan')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted small text-uppercase">Satuan Ukur</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                                <i class="fas fa-balance-scale text-success"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="satuan" class="form-control form-control-lg border-left-0 @error('satuan') is-invalid @enderror" 
                                            style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" 
                                            value="{{ old('satuan', $bahan->satuan) }}" placeholder="kg, liter, pcs, box">
                                    </div>
                                    @error('satuan')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted small text-uppercase">Stok Awal (Inventory)</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                                <i class="fas fa-warehouse text-success"></i>
                                            </span>
                                        </div>
                                        <input type="number" name="stok_awal" class="form-control form-control-lg border-left-0 @error('stok_awal') is-invalid @enderror" 
                                            style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" 
                                            value="{{ old('stok_awal', $bahan->stok_awal) }}">
                                    </div>
                                    @error('stok_awal')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('admin.bahan.index') }}" class="btn btn-light btn-block btn-lg font-weight-bold text-muted" style="border-radius: 12px; padding: 15px;">
                                    <i class="fas fa-times mr-2"></i> Batalkan
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold shadow-sm" style="border-radius: 12px; padding: 15px; background-color: #10b981; border: none;">
                                    <i class="fas fa-check-circle mr-2"></i> Simpan Data
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card mt-4 border-0 shadow-sm" style="border-radius: 15px; background-color: #fff;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-lightbulb text-warning mr-3 fa-lg"></i>
                        <p class="mb-0 text-muted small"><b>Tips:</b> Masukkan stok awal jika saat ini barang sudah tersedia di gudang fisik.</p>
                    </div>
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