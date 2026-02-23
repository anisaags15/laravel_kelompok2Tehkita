@extends('layouts.main')

@section('title', 'Laporan Distribusi')
@section('page', 'Laporan Distribusi')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="font-weight-bold text-dark mb-0">
            Laporan Distribusi
        </h5>
    </div>

    {{-- TABEL LAPORAN --}}
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h6 class="card-title font-weight-bold">
                Data Distribusi Bahan ke Outlet
            </h6>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-striped mb-0">
                <thead class="bg-light text-center">
                    <tr>
                        <th width="60">No</th>
                        <th width="120">Tanggal</th>
                        <th>Outlet Tujuan</th>
                        <th>Bahan</th>
                        <th width="120">Jumlah</th>
                        <th width="200">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($distribusis as $d)
                        <tr>
                            <td class="text-center">
                                {{ $loop->iteration }}
                            </td>
                            <td class="text-center">
                                {{ \Carbon\Carbon::parse($d->tanggal)->format('d-m-Y') }}
                            </td>
                            <td>
                                {{ $d->outlet->nama_outlet }}
                            </td>
                            <td>
                                {{ $d->bahan->nama_bahan }}
                            </td>
                            <td class="text-center">
                                {{ $d->jumlah }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.laporan.distribusi.detail', $d->id) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye"></i> Detail
                                </a>

                                <a href="{{ route('admin.laporan.distribusi.cetak', $d->id) }}"
                                   class="btn btn-outline-danger btn-sm"
                                   target="_blank">
                                    <i class="fas fa-file-pdf"></i> Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Data distribusi belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection