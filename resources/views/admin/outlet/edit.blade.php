@extends('layouts.main')

@section('title', 'Edit Outlet')

@section('content')

<div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
        <h3 class="card-title">Edit Outlet: {{ $outlet->nama_outlet }}</h3>
    </div>

    <form action="{{ route('admin.outlet.update', $outlet->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">
            <div class="row">
                <div class="col-md-6 form-group mb-3">
                    <label class="fw-bold">Nama Outlet</label>
                    <input type="text" name="nama_outlet" class="form-control @error('nama_outlet') is-invalid @enderror"
                           value="{{ old('nama_outlet', $outlet->nama_outlet) }}">
                    @error('nama_outlet')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                    <label class="fw-bold">Nomor HP</label>
                    <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror"
                           value="{{ old('no_hp', $outlet->no_hp) }}">
                    @error('no_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group mb-3">
                <label class="fw-bold">Alamat</label>
                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $outlet->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="fw-bold text-primary"><i class="fas fa-bullseye"></i> Target Pemakaian Harian (Unit)</label>
                <input type="number" name="target_pemakaian_harian" 
                       class="form-control @error('target_pemakaian_harian') is-invalid @enderror"
                       value="{{ old('target_pemakaian_harian', $outlet->target_pemakaian_harian) }}">
                <small class="text-muted">Mengubah angka ini akan merubah batas progress bar di dashboard outlet terkait.</small>
                @error('target_pemakaian_harian')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="card-footer text-right bg-light">
            <a href="{{ route('admin.outlet.index') }}" class="btn btn-secondary btn-sm mr-1">Kembali</a>
            <button type="submit" class="btn btn-dark btn-sm">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection