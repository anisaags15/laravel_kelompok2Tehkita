@extends('layouts.main')

@section('title', 'Detail Distribusi')
@section('page', 'Detail Distribusi')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="font-weight-bold text-dark mb-0">
            Detail Distribusi Bahan
        </h5>

        <a href="{{ route('admin.laporan.distribusi.cetak', $distribusi->id) }}"
           class="btn btn-outline-danger btn-sm"
           target="_blank">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
    </div>

    {{-- INFORMASI DISTRIBUSI --}}
    <div class="card card-outline card-secondary mb-3">
        <div class="card-header">
            <h6 class="card-title font-weight-bold">
                Informasi Distribusi
            </h6>
        </div>

        <div class="card-body">
            <table class="table table-borderless mb-0">
                <tr>
                    <th width="200">Tanggal Distribusi</th>
                    <td>: {{ \Carbon\Carbon::parse($distribusi->tanggal)->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th>Outlet Tujuan</th>
                    <td>: {{ $distribusi->outlet->nama_outlet }}</td>
                </tr>
                <tr>
                    <th>Alamat Outlet</th>
                    <td>: {{ $distribusi->outlet->alamat ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    {{-- DETAIL BAHAN --}}
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h6 class="card-title font-weight-bold">
                Detail Bahan Terdistribusi
            </h6>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="bg-light text-center">
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Bahan</th>
                        <th width="150">Jumlah</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td class="text-center">1</td>
                        <td>{{ $distribusi->bahan->nama_bahan }}</td>
                        <td class="text-center">{{ $distribusi->jumlah }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection