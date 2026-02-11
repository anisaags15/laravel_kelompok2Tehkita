@extends('layouts.main')

@section('title', 'Edit Outlet')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Edit Outlet</h3>
    </div>

    <form action="{{ route('admin.outlet.update', $outlet->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            <div class="form-group">
                <label>Nama Outlet</label>
                <input type="text"
                       name="nama_outlet"
                       class="form-control @error('nama_outlet') is-invalid @enderror"
                       value="{{ old('nama_outlet', $outlet->nama_outlet) }}"
                       placeholder="Nama outlet">

                @error('nama_outlet')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat"
                          rows="3"
                          class="form-control @error('alamat') is-invalid @enderror"
                          placeholder="Alamat outlet">{{ old('alamat', $outlet->alamat) }}</textarea>

                @error('alamat')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="card-footer text-right">
            <a href="{{ route('admin.outlet.index') }}" class="btn btn-secondary btn-sm mr-1">
                Kembali
            </a>
            <button type="submit" class="btn btn-dark btn-sm">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>

    </form>

</div>

@endsection
