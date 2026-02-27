@extends('layouts.main')

@section('title', 'Tambah Stok Masuk')
@section('page', 'Tambah Stok Masuk')

@section('content')

<div class="card shadow-sm border-0"> <div class="card-header bg-white py-3">
        <h3 class="card-title font-weight-bold text-dark">
            <i class="fas fa-arrow-down text-success mr-2"></i> Form Stok Masuk
        </h3>
    </div>

    <form action="{{ route('admin.stok-masuk.store') }}" method="POST">
        @csrf

        <div class="card-body bg-white">
            <div class="row">
                <div class="col-md-8 mx-auto"> <div class="form-group mb-4">
                        <label class="font-weight-bold">Bahan</label>
                        <select name="bahan_id" class="form-control form-control-lg @error('bahan_id') is-invalid @enderror" style="border-radius: 8px;">
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

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Jumlah</label>
                        <input type="number" 
                               name="jumlah" 
                               class="form-control form-control-lg @error('jumlah') is-invalid @enderror" 
                               value="{{ old('jumlah') }}" 
                               placeholder="Masukkan jumlah"
                               style="border-radius: 8px;">

                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Tanggal</label>
                        <input type="date" 
                               name="tanggal" 
                               class="form-control form-control-lg @error('tanggal') is-invalid @enderror" 
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               style="border-radius: 8px;">

                        @error('tanggal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer bg-light py-3 d-flex justify-content-end">
            <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-outline-secondary px-4 mr-2" style="border-radius: 10px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            
            <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 10px; background-color: #28a745; border: none;">
                <i class="fas fa-save mr-1"></i> Simpan Data
            </button>
        </div>
    </form>
</div>

@endsection