@extends('adminlte::page')

@section('title', 'Tambah Stok Masuk')

@section('content_header')
    <h1>Tambah Stok Masuk</h1>
@stop

@section('content')

{{-- ERROR VALIDATION --}}
@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="card">

    <div class="card-body">

        <form action="{{ route('admin.stok-masuk.store') }}" method="POST">

            @csrf

            {{-- BAHAN --}}
            <div class="form-group">
                <label>Bahan</label>

                <select name="bahan_id"
                        class="form-control"
                        required>

                    <option value="">-- Pilih Bahan --</option>

                    @foreach($bahans as $b)
                        <option value="{{ $b->id }}"
                            {{ old('bahan_id') == $b->id ? 'selected' : '' }}>

                            {{ $b->nama_bahan }}

                        </option>
                    @endforeach

                </select>
            </div>

            {{-- JUMLAH --}}
            <div class="form-group">
                <label>Jumlah</label>

                <input type="number"
                       name="jumlah"
                       class="form-control"
                       value="{{ old('jumlah') }}"
                       min="1"
                       required>
            </div>

            {{-- TANGGAL --}}
            <div class="form-group">
                <label>Tanggal</label>

                <input type="date"
                       name="tanggal"
                       class="form-control"
                       value="{{ old('tanggal') }}"
                       required>
            </div>

            {{-- BUTTON --}}
            <div class="form-group">

                <button class="btn btn-success">
                    Simpan
                </button>

                <a href="{{ route('admin.stok-masuk.index') }}"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </div>

        </form>

    </div>
</div>

@stop
