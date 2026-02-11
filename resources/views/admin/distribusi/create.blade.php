@extends('layouts.main')

@section('title', 'Tambah Distribusi')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h5>Tambah Distribusi</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('admin.distribusi.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Bahan</label>
                    <select name="bahan_id" class="form-control" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($bahans as $bahan)
                            <option value="{{ $bahan->id }}">
                                {{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok_awal }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Outlet</label>
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
                    <input type="number" name="jumlah" class="form-control" min="1" required>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" required>
                </div>

                <div class="text-right">
                    <a href="{{ route('admin.distribusi.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>
                    <button class="btn btn-primary">
                        Simpan Distribusi
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
