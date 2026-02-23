@extends('layouts.main')

@section('title', 'Laporan Distribusi Outlet')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-primary mb-0">
                    Laporan Distribusi
                </h5>
                <small class="text-muted">
                    {{ auth()->user()->outlet->nama_outlet }}
                </small>
            </div>

            <a href="{{ route('user.laporan.distribusi.pdf') }}" 
               class="btn btn-primary btn-sm px-4 shadow-sm">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped text-center align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Bahan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($distribusi as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                            <td class="text-start">{{ $item->bahan->nama_bahan }}</td>
                            <td>
                                <span class="badge bg-info px-3 py-2">
                                    {{ $item->jumlah }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">Tidak ada data distribusi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection