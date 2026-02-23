@extends('layouts.main')

@section('title', 'Pemakaian Bahan')
@section('page', 'Pemakaian Bahan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="card-title font-weight-bold">Tambah Pemakaian</h3>
                </div>
                <div class="card-body">

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                        </div>
                    @endif

                    <form action="{{ route('user.pemakaian.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="font-weight-bold">Pilih Bahan</label>
                            <select name="bahan_id" class="form-control select2" required>
                                <option value="">-- Pilih Bahan --</option>
                                @forelse($stokOutlets as $stok)
                                    @if($stok->bahan)
                                        <option value="{{ $stok->bahan->id }}">
                                            {{ $stok->bahan->nama_bahan }} (Tersedia: {{ $stok->stok }})
                                        </option>
                                    @endif
                                @empty
                                    <option value="" disabled>Stok kosong, silakan hubungi admin</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Jumlah Dipakai</label>
                            <div class="input-group">
                                <input type="number" name="jumlah" class="form-control" min="1" placeholder="Masukkan jumlah..." required>
                                <div class="input-group-append">
                                    <span class="input-group-text">Unit</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between">
                            {{-- Gunakan route yang sudah pasti ada di web.php --}}
                            <a href="{{ route('user.pemakaian.index') }}" class="btn btn-light border">Kembali</a>
                            <button type="submit" class="btn btn-success px-4 font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Pemakaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        {{-- Info Panel Kecil --}}
        <div class="col-md-4">
            <div class="card bg-info shadow-sm text-white border-0">
                <div class="card-body">
                    <h5><i class="fas fa-info-circle"></i> Info Stok</h5>
                    <p class="small">Pastikan jumlah pemakaian tidak melebihi stok yang tersedia di outlet Anda.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection