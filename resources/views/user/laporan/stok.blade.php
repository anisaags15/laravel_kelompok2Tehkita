@extends('layouts.main')

@section('title', 'Laporan Stok Outlet')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 fw-bold text-success">
                    Laporan Stok Outlet
                </h5>
                <small class="text-muted">
                    {{ auth()->user()->outlet->nama_outlet }}
                </small>
            </div>

            <a href="{{ route('user.laporan.stok.pdf') }}" 
               class="btn btn-success btn-sm px-4 shadow-sm">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-success">
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Bahan</th>
                            <th width="20%">Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stok as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="text-start">{{ $item->bahan->nama_bahan }}</td>
                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    {{ $item->stok }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-muted">
                                Tidak ada data stok.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div class="card-footer text-end text-muted small">
            Dicetak pada: {{ now()->format('d M Y') }}
        </div>
    </div>

</div>
@endsection