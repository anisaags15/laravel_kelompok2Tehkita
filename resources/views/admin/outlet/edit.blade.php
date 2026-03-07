@extends('layouts.main')

@section('title', 'Edit Outlet')
@section('page', 'Edit Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-sm border-0" style="border-radius: 20px; padding: 40px;">
                
                <div class="text-center mb-4">
                    <div class="bg-success d-inline-block p-3 shadow-sm mb-3" style="border-radius: 15px;">
                        <i class="fas fa-store-alt fa-2x text-white"></i>
                    </div>
                    <h3 class="font-weight-bold" style="color: #2c3e50;">Edit Data Outlet</h3>
                    <p class="text-muted">Memperbarui informasi untuk outlet: <strong>{{ $outlet->nama_outlet }}</strong></p>
                </div>

                <form action="{{ route('admin.outlet.update', $outlet->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted small text-uppercase">Nama Outlet</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                                <i class="fas fa-id-badge text-success"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="nama_outlet" class="form-control form-control-lg border-left-0 @error('nama_outlet') is-invalid @enderror" style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" value="{{ old('nama_outlet', $outlet->nama_outlet) }}">
                                    </div>
                                    @error('nama_outlet')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted small text-uppercase">Nomor HP</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                                <i class="fas fa-phone text-success"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="no_hp" class="form-control form-control-lg border-left-0 @error('no_hp') is-invalid @enderror" style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" value="{{ old('no_hp', $outlet->no_hp) }}">
                                    </div>
                                    @error('no_hp')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase">Alamat Lengkap</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px; align-items: flex-start; padding-top: 12px;">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                    </span>
                                </div>
                                <textarea name="alamat" rows="3" class="form-control border-left-0 @error('alamat') is-invalid @enderror" style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;">{{ old('alamat', $outlet->alamat) }}</textarea>
                            </div>
                            @error('alamat')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-success small text-uppercase">Target Pemakaian Harian (Unit)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                        <i class="fas fa-bullseye text-success"></i>
                                    </span>
                                </div>
                                <input type="number" name="target_pemakaian_harian" class="form-control form-control-lg border-left-0 @error('target_pemakaian_harian') is-invalid @enderror" style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" value="{{ old('target_pemakaian_harian', $outlet->target_pemakaian_harian) }}">
                            </div>
                            @error('target_pemakaian_harian')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row mt-5">
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('admin.outlet.index') }}" class="btn btn-light btn-block btn-lg font-weight-bold text-muted" style="border-radius: 12px; padding: 15px;">
                                    <i class="fas fa-times mr-2"></i> Batalkan
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold shadow-sm" style="border-radius: 12px; padding: 15px; background-color: #10b981; border: none;">
                                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card mt-4 border-0 shadow-sm" style="border-radius: 15px; background-color: #fff;">
                <div class="card-body py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle text-info mr-3 fa-lg"></i>
                        <p class="mb-0 text-muted small"><strong>Tips:</strong> Pastikan data outlet sudah sesuai agar laporan distribusi barang tepat sasaran.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection