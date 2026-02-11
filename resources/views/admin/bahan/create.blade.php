@extends('layouts.main')

@section('title', 'Tambah Bahan')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Tambah Bahan</h3>
    </div>

    <form action="{{ route('admin.bahan.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Nama Bahan</label>
                <input type="text"
                       name="nama_bahan"
                       class="form-control @error('nama_bahan') is-invalid @enderror"
                       value="{{ old('nama_bahan') }}"
                       placeholder="Contoh: Gula Aren">

                @error('nama_bahan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Satuan</label>
                <input type="text"
                       name="satuan"
                       class="form-control @error('satuan') is-invalid @enderror"
                       value="{{ old('satuan') }}"
                       placeholder="kg / pcs / liter">

                @error('satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Stok Awal</label>
                <input type="number"
                       name="stok_awal"
                       class="form-control @error('stok_awal') is-invalid @enderror"
                       value="{{ old('stok_awal', 0) }}">

                @error('stok_awal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('admin.bahan.index') }}" class="btn btn-secondary btn-sm mr-1">
                Kembali
            </a>
            <button type="submit" class="btn btn-dark btn-sm">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>

    </form>

</div>

@endsection
