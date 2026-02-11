@extends('layouts.main')

@section('title', 'Stok Masuk')
@section('page', 'Stok Masuk')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Data Stok Masuk</h3>

        <a href="{{ route('admin.stok-masuk.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Stok
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($stokMasuks as $stok)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $stok->bahan->nama_bahan }}</td>
                        <td class="text-center">
                            {{ $stok->jumlah }} {{ $stok->bahan->satuan }}
                        </td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($stok->tanggal)->format('d-m-Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Data stok masuk belum ada
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
