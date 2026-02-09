@extends('adminlte::page')

@section('title','Edit Bahan')

@section('content_header')
<h1>Edit Bahan</h1>
@stop

@section('content')

<div class="card">
<div class="card-body">

@if ($errors->any())
<div class="alert alert-danger">
<ul class="mb-0">
@foreach ($errors->all() as $e)
<li>{{ $e }}</li>
@endforeach
</ul>
</div>
@endif

<form action="{{ route('admin.bahan.update',$bahan->id) }}"
      method="POST">

@csrf
@method('PUT')

<div class="form-group mb-3">
<label>Nama Bahan</label>
<input type="text"
       name="nama_bahan"
       class="form-control"
       value="{{ old('nama_bahan',$bahan->nama_bahan) }}"
       required>
</div>

<div class="form-group mb-3">
<label>Satuan</label>
<input type="text"
       name="satuan"
       class="form-control"
       value="{{ old('satuan',$bahan->satuan) }}"
       required>
</div>

<div class="form-group mb-3">
<label>Stok Awal</label>
<input type="number"
       name="stok_awal"
       class="form-control"
       value="{{ old('stok_awal',$bahan->stok_awal) }}"
       required>
</div>

<button class="btn btn-success">
Update
</button>

<a href="{{ route('admin.bahan.index') }}"
   class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>

@stop
