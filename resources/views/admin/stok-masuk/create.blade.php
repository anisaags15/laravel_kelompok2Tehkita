@extends('layouts.main')

@section('title', 'Tambah Stok Masuk')
@section('page', 'Tambah Stok Masuk')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 20px; padding: 40px;">
                
                <div class="text-center mb-4">
                    <div class="bg-success d-inline-block p-3 rounded-lg shadow-sm mb-3" style="border-radius: 15px !important;">
                        <i class="fas fa-truck-loading fa-2x text-white"></i>
                    </div>
                    <h3 class="font-weight-bold" style="color: #2c3e50;">Tambah Stok Masuk</h3>
                    <p class="text-muted">Masukkan detail pengadaan bahan baku yang baru masuk ke gudang pusat.</p>
                </div>

                <form action="{{ route('admin.stok-masuk.store') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-muted small text-uppercase">Bahan Baku</label>
                            <div class="input-group input-group-alternative">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                        <i class="fas fa-tag text-success"></i>
                                    </span>
                                </div>
                                <select name="bahan_id" class="form-control form-control-lg border-left-0 @error('bahan_id') is-invalid @enderror" style="border-radius: 0 12px 12px 0; background-color: #f8f9fa; font-size: 1rem;">
                                    <option value="">Pilih Bahan Baku...</option>
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

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted small text-uppercase">Jumlah Masuk</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                                <i class="fas fa-balance-scale text-success"></i>
                                            </span>
                                        </div>
                                        <input type="number" name="jumlah" class="form-control form-control-lg border-left-0 @error('jumlah') is-invalid @enderror" style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" value="{{ old('jumlah') }}" placeholder="Contoh: 50">
                                    </div>
                                    @error('jumlah')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-muted small text-uppercase">Tanggal Masuk</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light border-right-0" style="border-radius: 12px 0 0 12px;">
                                                <i class="fas fa-calendar-alt text-success"></i>
                                            </span>
                                        </div>
                                        <input type="date" name="tanggal" class="form-control form-control-lg border-left-0 @error('tanggal') is-invalid @enderror" style="border-radius: 0 12px 12px 0; background-color: #f8f9fa;" value="{{ old('tanggal', date('Y-m-d')) }}">
                                    </div>
                                    @error('tanggal')
                                        <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6 mb-2">
                                <a href="{{ route('admin.stok-masuk.index') }}" class="btn btn-light btn-block btn-lg font-weight-bold text-muted" style="border-radius: 12px; padding: 15px;">
                                    <i class="fas fa-times mr-2"></i> Batalkan
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold shadow-sm" style="border-radius: 12px; padding: 15px; background-color: #10b981; border: none;">
                                    <i class="fas fa-check-circle mr-2"></i> Simpan Stok
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
                        <p class="mb-0 text-muted small"><strong>Tips:</strong> Periksa kembali jumlah stok dan satuan sebelum menyimpan data untuk menghindari selisih laporan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection