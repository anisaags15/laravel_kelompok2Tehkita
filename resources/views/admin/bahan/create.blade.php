@extends('adminlte::page')

@section('title','Tambah Bahan')

@section('content_header')
<h1>Tambah Bahan</h1>
@stop

@section('content')

<div class="card">
<div class="card-body">

{{-- TAMPILKAN ERROR --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.bahan.store') }}" method="POST">
@csrf

<div class="form-group mb-3">
    <label>Nama Bahan</label>
    <input type="text"
           name="nama_bahan"
           class="form-control"
           value="{{ old('nama_bahan') }}"
           required>
</div>

<div class="form-group mb-3">
    <label>Satuan</label>
    <input type="text"
           name="satuan"
           class="form-control"
           value="{{ old('satuan') }}"
           required>
</div>

<div class="form-group mb-3">
    <label>Stok Awal</label>
    <input type="number"
           name="stok_awal"
           class="form-control"
           value="{{ old('stok_awal') }}"
           required>
</div>

<button type="submit" class="btn btn-success">
    Simpan
</button>

<a href="{{ route('admin.bahan.index') }}"
   class="btn btn-secondary">
   Kembali
</a>

</form>

</div>
</div>

@stop
