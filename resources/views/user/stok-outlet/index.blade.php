@extends('layouts.main')

@section('title', 'Stok Bahan Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-1" style="letter-spacing: -1px;">Stok Bahan</h2>
            <p class="text-muted mb-0">Pantau ketersediaan stok bahan baku secara real-time.</p>
        </div>
        <div class="d-none d-sm-block">
            <div class="bg-white px-4 py-2 rounded-pill shadow-sm border-0 d-flex align-items-center">
                <div class="bg-primary rounded-circle mr-2" style="width: 10px; height: 10px; box-shadow: 0 0 10px rgba(78,115,223,0.5);"></div>
                <span class="small font-weight-bold text-dark">{{ auth()->user()->outlet->nama_outlet ?? 'Outlet Aktif' }}</span>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3" style="border-radius: 15px; background: linear-gradient(45deg, #4e73df, #224abe);">
                <div class="d-flex align-items-center">
                    <div class="bg-white-50 rounded p-3 text-white">
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                    <div class="ml-3">
                        <small class="text-white-50 d-block">Total Jenis Bahan</small>
                        <h3 class="text-white font-weight-bold mb-0">{{ $stokOutlets->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-lg border-0" style="border-radius: 20px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr class="text-muted" style="background-color: #fcfcfd; border-bottom: 1px solid #f1f1f4;">
                            <th class="py-4 pl-4 font-weight-bold text-uppercase small" width="80">No</th>
                            <th class="py-4 font-weight-bold text-uppercase small">Detail Bahan</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Jumlah Stok</th>
                            <th class="py-4 font-weight-bold text-uppercase small text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stokOutlets as $item)
                        <tr class="table-row-modern">
                            <td class="pl-4">
                                <span class="text-muted font-weight-bold" style="font-size: 0.9rem;">
                                    {{ $loop->iteration }}
                                </span>
                            </td>

                            <td class="py-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-icon mr-3">
                                        {{ strtoupper(substr($item->bahan->nama_bahan ?? 'B', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 1.1rem;">
                                            {{ $item->bahan->nama_bahan ?? '-' }}
                                        </div>
                                        <div class="text-muted small">
                                            <i class="far fa-clock mr-1"></i> Update: {{ $item->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="stock-bubble {{ $item->stok <= 10 ? 'low' : 'safe' }}">
                                    <span class="amount">{{ number_format($item->stok, 0, ',', '.') }}</span>
                                    <span class="unit text-uppercase">{{ $item->bahan->satuan ?? 'Unit' }}</span>
                                </div>
                            </td>

                            <td class="text-center">
                                @if ($item->stok == 0)
                                    <span class="badge-custom bg-soft-danger text-danger">
                                        <span class="dot bg-danger"></span> Stok Habis
                                    </span>
                                @elseif ($item->stok <= 10)
                                    <span class="badge-custom bg-soft-warning text-warning">
                                        <span class="dot bg-warning"></span> Hampir Habis
                                    </span>
                                @else
                                    <span class="badge-custom bg-soft-success text-success">
                                        <span class="dot bg-success"></span> Stok Aman
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center">
                                <i class="fas fa-box-open fa-3x mb-3 d-block opacity-20"></i>
                                <h5 class="text-muted font-weight-bold">Belum Ada Data Bahan</h5>
                                <p class="small text-muted">Data stok bahan outlet akan muncul di sini setelah diinput.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection