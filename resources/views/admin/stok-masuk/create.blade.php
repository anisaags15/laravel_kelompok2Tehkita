@extends('layouts.main')

@section('title', 'Tambah Stok Masuk')
@section('page', 'Tambah Stok Masuk')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Form Stok Masuk</h3>
    </div>

    <form action="{{ route('admin.stok-masuk.store') }}" method="POST">
        @csrf

        <div class="card-body">

            <div class="form-group">
                <label>Bahan</label>
                <select name="bahan_id" class="form-control @error('bahan_id') is-invalid @enderror">
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($bahans as $bahan)
                        <option value="{{ $bahan->id }}" {{ old('bahan_id') == $bahan->id ? 'selected' : '' }}>
                            {{ $bahan->nama_bahan }} ({{ $bahan->satuan }})
                        </option>
                    @endforeach
                </select>

                @error('bahan_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Jumlah</label>
                <input type="number"
                       name="jumlah"
                       class="form-control @error('jumlah') is-invalid @enderror"
                       value="{{ old('jumlah') }}"
                       placeholder="Masukkan jumlah">

                @error('jumlah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date"
                       name="tanggal"
                       class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal', date('Y-m-d')) }}">

                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-secondary">
                Kembali
            </a>
            <button class="btn btn-dark">
                Simpan
            </button>
        </div>

    </form>

</div>

@endsection
