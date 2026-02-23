@extends('layouts.main')

@section('title', 'Data Distribusi')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="font-weight-bold text-dark">Monitoring Distribusi Barang</h4>
        <a href="{{ route('admin.distribusi.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus"></i> Tambah Pengiriman
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light text-center">
                        <tr>
                            <th width="50">No</th>
                            <th>Bahan</th>
                            <th>Outlet Tujuan</th>
                            <th>Jumlah</th>
                            <th>Tanggal Kirim</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distribusis as $item)
                            <tr>
                                <td class="text-center">{{ ($distribusis->currentPage() - 1) * $distribusis->perPage() + $loop->iteration }}</td>
                                <td class="font-weight-bold">{{ $item->bahan->nama_bahan }}</td>
                                <td>
                                    <span class="badge badge-light p-2 border">
                                        <i class="fas fa-store mr-1 text-primary"></i> {{ $item->outlet->nama_outlet }}
                                    </span>
                                </td>
                                <td class="text-center font-weight-bold text-primary">{{ number_format($item->jumlah) }}</td>
                                <td class="text-center">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                                </td>
                                <td class="text-center">
                                    @if($item->status == 'dikirim')
                                        <span class="badge badge-warning px-3 py-2 text-dark">
                                            <i class="fas fa-truck mr-1"></i> Sedang Dikirim
                                        </span>
                                    @else
                                        <span class="badge badge-success px-3 py-2">
                                            <i class="fas fa-box-open mr-1"></i> Telah Diterima
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <p>Belum ada riwayat distribusi barang.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($distribusis->hasPages())
        <div class="card-footer bg-white">
            {{ $distribusis->links() }}
        </div>
        @endif
    </div>

</div>
@endsection