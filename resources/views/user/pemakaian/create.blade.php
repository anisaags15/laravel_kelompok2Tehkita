@extends('layouts.main')

@section('title', 'Pemakaian Bahan')
@section('page', 'Pemakaian Bahan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Tambah Pemakaian</h3>
    </div>
    <div class="card-body">

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('user.pemakaian.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Pilih Bahan</label>
                <select name="bahan_id" class="form-control" required>
                    <option value="">-- Pilih Bahan --</option>
                    @forelse($stokOutlets as $stok)
                        @if($stok->bahan && $stok->stok > 0)
                            <option value="{{ $stok->bahan->id }}">
                                {{ $stok->bahan->nama_bahan }} (Stok: {{ $stok->stok }})
                            </option>
                        @endif
                    @empty
                        <option value="">Stok kosong, hubungi admin</option>
                    @endforelse
                </select>
            </div>

            <div class="form-group">
                <label>Jumlah Dipakai</label>
                <input type="number" name="jumlah" class="form-control" min="1" required>
            </div>

            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Simpan</button>
        </form>

    </div>
</div>
@endsection
