@extends('layouts.main')

@section('title', 'Detail Stok Outlet')
@section('page', 'Detail Stok Outlet')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="font-weight-bold text-dark mb-0">
            Laporan Detail Stok Outlet
        </h5>

        <a href="{{ route('admin.laporan.stok-outlet.cetak', $outlet->id) }}"
           class="btn btn-outline-danger btn-sm">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    {{-- INFORMASI OUTLET --}}
    <div class="card card-outline card-secondary mb-4">
        <div class="card-header">
            <h6 class="card-title font-weight-bold">
                Informasi Outlet
            </h6>
        </div>

        <div class="card-body p-0">
            <table class="table table-sm table-bordered mb-0">
                <tr>
                    <th width="200">Nama Outlet</th>
                    <td>{{ $outlet->nama_outlet }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $outlet->alamat ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- DATA STOK --}}
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h6 class="card-title font-weight-bold">
                Data Stok Bahan
            </h6>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="bg-light text-center">
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Bahan</th>
                        <th width="160">Tanggal Terakhir Diterima</th>
                        <th width="120">Jumlah Stok</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($stok as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->bahan->nama_bahan }}</td>
                            <td class="text-center">
                                {{ $item->tanggal_pengiriman
                                    ? \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d-m-Y')
                                    : '-' }}
                            </td>
                            <td class="text-center">
                                {{ $item->stok }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Data stok outlet belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection