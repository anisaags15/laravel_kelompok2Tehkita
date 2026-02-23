@extends('layouts.main')

@section('title', 'Tambah Outlet')

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h3 class="card-title">Tambah Outlet Baru</h3>
    </div>

    <form action="{{ route('admin.outlet.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label class="fw-bold">Nama Outlet</label>
                    <input type="text" name="nama_outlet" class="form-control @error('nama_outlet') is-invalid @enderror"
                           value="{{ old('nama_outlet') }}" placeholder="Contoh: Outlet Tebet">
                    @error('nama_outlet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label class="fw-bold">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp') }}" placeholder="0812xxxx">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="fw-bold">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror"
                          placeholder="Alamat outlet">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="fw-bold text-primary"><i class="fas fa-bullseye"></i> Target Pemakaian Harian (Unit)</label>
                <input type="number" name="target_pemakaian_harian" 
                       class="form-control @error('target_pemakaian_harian') is-invalid @enderror"
                       value="{{ old('target_pemakaian_harian', 100) }}" placeholder="Contoh: 100">
                <small class="text-muted">Target ini akan muncul di dashboard progress bar outlet.</small>
                @error('target_pemakaian_harian')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="card-footer text-right bg-light">
            <a href="{{ route('admin.outlet.index') }}" class="btn btn-secondary btn-sm mr-1">Kembali</a>
            <button type="submit" class="btn btn-dark btn-sm">
                <i class="fas fa-save"></i> Simpan Outlet
            </button>
        </div>
    </form>
</div>

@endsection