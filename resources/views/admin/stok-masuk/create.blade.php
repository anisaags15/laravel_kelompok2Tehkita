@extends('layouts.main')

@section('title', 'Tambah Stok Masuk')
@section('page', 'Tambah Stok Masuk')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header py-3">
        <h3 class="card-title font-weight-bold">
            <i class="fas fa-arrow-down text-success mr-2"></i> Form Stok Masuk
        </h3>
    </div>

    <form action="{{ route('admin.stok-masuk.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Bahan</label>
                        <div class="input-group-modern @error('bahan_id') border-danger @enderror">
                            <div class="input-icon">
                                <i class="fas fa-box"></i>
                            </div>
                            <select name="bahan_id" class="form-control input-modern select2 @error('bahan_id') is-invalid @enderror">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahans as $bahan)
                                    <option value="{{ $bahan->id }}" {{ old('bahan_id') == $bahan->id ? 'selected' : '' }}>
                                        {{ $bahan->nama_bahan }} ({{ $bahan->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bahan_id')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Jumlah</label>
                        <div class="input-group-modern @error('jumlah') border-danger @enderror">
                            <div class="input-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <input type="number" name="jumlah" class="form-control input-modern" 
                                   value="{{ old('jumlah') }}" placeholder="Masukkan jumlah">
                        </div>
                        @error('jumlah')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Tanggal</label>
                        <div class="input-group-modern @error('tanggal') border-danger @enderror">
                            <div class="input-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <input type="date" name="tanggal" class="form-control input-modern" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}">
                        </div>
                        @error('tanggal')
                            <small class="text-danger mt-1">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="card-footer py-3 d-flex justify-content-end bg-transparent">
            <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-outline-secondary px-4 mr-2" style="border-radius: 10px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            
            <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 10px;">
                <i class="fas fa-save mr-1"></i> Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection