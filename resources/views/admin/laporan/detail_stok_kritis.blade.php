@extends('layouts.main')
@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Detail Stok Kritis: <strong>{{ $outlet->nama_outlet }}</strong></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Bahan Baku</th>
                            <th class="text-center">Sisa Stok</th>
                            <th>Satuan</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailStok as $s)
                        <tr>
                            <td>{{ $s->bahan->nama_bahan }}</td>
                            <td class="text-center text-danger fw-bold">{{ $s->stok }}</td>
                            <td>{{ strtoupper($s->bahan->satuan) }}</td>
                            <td>
                                <a href="{{ route('admin.distribusi.create') }}" class="btn btn-success btn-xs">Kirim Stok</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.laporan.stok-kritis') }}" class="btn btn-light mt-3">Kembali</a>
        </div>
    </div>
</div>
@endsection