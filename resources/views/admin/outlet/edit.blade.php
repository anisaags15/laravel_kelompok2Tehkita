@extends('adminlte::page')

@section('title', 'Edit Outlet')

@section('content_header')
    <h1>Edit Outlet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        <form action="{{ route('admin.outlet.update', $outlet->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nama Outlet</label>
                <input type="text"
                       name="nama_outlet"
                       class="form-control"
                       value="{{ $outlet->nama_outlet }}"
                       required>
            </div>

            <div class="form-group">
                <label>Alamat</label>
                <textarea name="alamat"
                          class="form-control"
                          required>{{ $outlet->alamat }}</textarea>
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text"
                       name="no_hp"
                       class="form-control"
                       value="{{ $outlet->no_hp }}">
            </div>

            <button type="submit" class="btn btn-success mt-2">
                Update
            </button>

            <a href="{{ route('admin.outlet.index') }}"
               class="btn btn-secondary mt-2">
               Kembali
            </a>

        </form>

    </div>
</div>
@stop
