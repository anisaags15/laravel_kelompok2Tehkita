@extends('layouts.main')

@section('title', 'Tambah Stok Masuk')
@section('page', 'Tambah Stok Masuk')

@section('content')
<div class="card shadow-sm border-0 custom-card-theme" style="border-radius: 20px;">
    <div class="card-header border-0 py-4 bg-transparent">
        <h3 class="card-title font-weight-bold m-0 heading-theme">
            <i class="fas fa-arrow-down text-success mr-2"></i> Form Stok Masuk
        </h3>
    </div>

    <form action="{{ route('admin.stok-masuk.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    
                    <div class="form-group mb-4">
                        <label class="font-weight-bold label-theme">Bahan Baku</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text custom-addon">
                                    <i class="fas fa-box text-success"></i>
                                </span>
                            </div>
                            <select name="bahan_id" class="form-control form-control-lg input-custom select2 @error('bahan_id') is-invalid @enderror">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahans as $bahan)
                                    <option value="{{ $bahan->id }}" {{ old('bahan_id') == $bahan->id ? 'selected' : '' }}>
                                        {{ $bahan->nama_bahan }} ({{ $bahan->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bahan_id')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold label-theme">Jumlah Masuk</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text custom-addon">
                                    <i class="fas fa-calculator text-success"></i>
                                </span>
                            </div>
                            <input type="number" name="jumlah" 
                                   class="form-control form-control-lg input-custom @error('jumlah') is-invalid @enderror" 
                                   value="{{ old('jumlah') }}" placeholder="Masukkan jumlah stok">
                        </div>
                        @error('jumlah')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold label-theme">Tanggal Masuk</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text custom-addon">
                                    <i class="fas fa-calendar-alt text-success"></i>
                                </span>
                            </div>
                            <input type="date" name="tanggal" 
                                   class="form-control form-control-lg input-custom @error('tanggal') is-invalid @enderror" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}">
                        </div>
                        @error('tanggal')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        <div class="card-footer border-0 py-4 d-flex justify-content-end bg-transparent">
            <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-back-custom px-4 mr-3">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            
            <button type="submit" class="btn btn-success px-4 shadow-sm btn-save-custom">
                <i class="fas fa-save mr-1"></i> Simpan Stok
            </button>
        </div>
    </form>
</div>
@endsection