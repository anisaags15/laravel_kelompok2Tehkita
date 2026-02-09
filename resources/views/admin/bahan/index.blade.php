@extends('adminlte::page')

@section('title','Data Bahan')

@section('content_header')
<h1>Data Bahan</h1>
@stop

@section('content')

{{-- ALERT SUKSES --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('admin.bahan.create') }}"
   class="btn btn-primary mb-3">
Tambah Bahan
</a>

<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Satuan</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>

@foreach($bahans as $i => $b)
<tr>

<td>{{ $i+1 }}</td>
<td>{{ $b->nama_bahan }}</td>
<td>{{ $b->satuan }}</td>
<td>{{ $b->stok_awal }}</td>

<td>

<a href="{{ route('admin.bahan.edit',$b->id) }}"
   class="btn btn-warning btn-sm">
Edit
</a>

<form action="{{ route('admin.bahan.destroy',$b->id) }}"
      method="POST"
      style="display:inline">

@csrf
@method('DELETE')

<button class="btn btn-danger btn-sm"
        onclick="return confirm('Hapus data ini?')">
Hapus
</button>

</form>

</td>

</tr>
@endforeach

</tbody>
</table>

@stop
