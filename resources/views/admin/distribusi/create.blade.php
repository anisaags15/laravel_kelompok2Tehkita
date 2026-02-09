@extends('adminlte::page')

@section('title', 'Tambah Distribusi')

@section('content_header')
    <h1>Tambah Distribusi Stok</h1>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.distribusi.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.distribusi.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Bahan</label>
                <select name="bahan_id" class="form-control" required>
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($bahans as $bahan)
                        <option value="{{ $bahan->id }}">
                            {{ $bahan->nama_bahan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Outlet Tujuan</label>
                <select name="outlet_id" class="form-control" required>
                    <option value="">-- Pilih Outlet --</option>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}">
                            {{ $outlet->nama_outlet }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah</label>
                <input
                    type="number"
                    name="jumlah"
                    class="form-control"
                    min="1"
                    required
                >
            </div>

            <div class="form-group">
                <label>Tanggal Distribusi</label>
                <input
                    type="date"
                    name="tanggal"
                    class="form-control"
                    required
                >
            </div>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Simpan Distribusi
                </button>
            </div>

        </form>
    </div>
</div>

@stop
