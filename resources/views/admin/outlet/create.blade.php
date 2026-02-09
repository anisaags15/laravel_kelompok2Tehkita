@extends('adminlte::page')

@section('title', 'Tambah Outlet')

@section('content_header')
    <h1>Tambah Outlet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.outlet.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Outlet</label>
                <input type="text" name="nama_outlet" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control" required></textarea>
            </div>

            <div class="form-group">
    <label>No HP</label>
    <input type="text"
           name="no_hp"
           class="form-control"
           placeholder="08xxxxxxxxxx">
</div>


            <button type="submit" class="btn btn-success mt-2">Simpan</button>
            <a href="{{ route('admin.outlet.index') }}" class="btn btn-secondary mt-2">Kembali</a>
        </form>
    </div>
</div>
@stop
