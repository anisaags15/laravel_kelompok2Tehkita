@extends('layouts.main')

@section('title', 'Edit Outlet')
@section('page', 'Edit Outlet')

@section('content')

<div class="card shadow-sm border-0"> <div class="card-header bg-white py-3"> <h3 class="card-title font-weight-bold text-dark">
            <i class="fas fa-store-alt text-success mr-2"></i> Edit Outlet: {{ $outlet->nama_outlet }}
        </h3>
    </div>

    <form action="{{ route('admin.outlet.update', $outlet->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body bg-white">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark">Nama Outlet</label>
                        <input type="text" name="nama_outlet" 
                               class="form-control form-control-lg @error('nama_outlet') is-invalid @enderror"
                               value="{{ old('nama_outlet', $outlet->nama_outlet) }}"
                               style="border-radius: 8px;">
                        @error('nama_outlet')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-dark">Nomor HP</label>
                        <input type="text" name="no_hp" 
                               class="form-control form-control-lg @error('no_hp') is-invalid @enderror"
                               value="{{ old('no_hp', $outlet->no_hp) }}"
                               style="border-radius: 8px;">
                        @error('no_hp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold text-dark">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" 
                          class="form-control @error('alamat') is-invalid @enderror"
                          style="border-radius: 8px;">{{ old('alamat', $outlet->alamat) }}</textarea>
                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label class="font-weight-bold text-success">
                    <i class="fas fa-bullseye mr-1"></i> Target Pemakaian Harian (Unit)
                </label>
                <input type="number" name="target_pemakaian_harian" 
                       class="form-control form-control-lg @error('target_pemakaian_harian') is-invalid @enderror"
                       value="{{ old('target_pemakaian_harian', $outlet->target_pemakaian_harian) }}" 
                       style="border-radius: 8px;">
                <small class="text-muted italic">Mengubah angka ini akan merubah batas progress bar di dashboard outlet terkait.</small>
                @error('target_pemakaian_harian')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="card-footer bg-light py-3 d-flex justify-content-end">
            <a href="{{ route('admin.outlet.index') }}" class="btn btn-outline-secondary px-4 mr-2" style="border-radius: 10px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            
            <button type="submit" class="btn btn-success px-4 shadow-sm" style="border-radius: 10px; background-color: #28a745; border: none;">
                <i class="fas fa-check-circle mr-1"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection