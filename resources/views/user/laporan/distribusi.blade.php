@extends('layouts.main')

@push('styles')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporn-user.css') }}">
@endpush

@section('title', 'Laporan Distribusi Outlet')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            {{-- HEADER & ACTION --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h3 class="fw-bold text-dark mb-1">Rekap Laporan Distribusi</h3>
                    <p class="text-muted mb-0">
                        <i class="fas fa-file-invoice me-2 text-success"></i>Periode: 
                        <span class="badge bg-soft-success text-success px-3">
                            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->translatedFormat('d/m/Y') : 'Awal' }} 
                            - 
                            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->translatedFormat('d/m/Y') : 'Sekarang' }}
                        </span>
                    </p>
                </div>
                <div class="btn-group shadow-sm">
                    <a href="{{ route('user.laporan.distribusi.pdf', request()->query()) }}" class="btn btn-success px-4 fw-bold">
                        <i class="fas fa-file-pdf me-2"></i>Export PDF
                    </a>
                </div>
            </div>

            
            {{-- STATISTIC CARDS --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-success me-3">
                                <i class="fas fa-boxes fa-lg text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block small">Total Item Diterima</small>
                                <h4 class="fw-bold mb-0">{{ number_format($distribusi->sum('jumlah'), 0, ',', '.') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-success me-3">
                                <i class="fas fa-truck-loading fa-lg text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block small">Frekuensi Distribusi</small>
                                <h4 class="fw-bold mb-0">{{ $distribusi->count() }} Transaksi</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-soft-success me-3">
                                <i class="fas fa-calendar-check fa-lg text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block small">Update Terakhir</small>
                                <h4 class="fw-bold mb-0" style="font-size: 1.1rem;">
                                    {{ $distribusi->first() ? \Carbon\Carbon::parse($distribusi->first()->tanggal)->diffForHumans() : '-' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER BOX --}}
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3">
                    <form action="{{ route('user.laporan.distribusi') }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Mulai Tanggal</label>
                            <input type="date" name="start_date" class="form-control form-control-sm border-light" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control form-control-sm border-light" value="{{ request('end_date') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-success btn-sm w-100 fw-bold">
                                <i class="fas fa-filter me-1"></i> Saring Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- TABLE SECTION --}}
            <div class="card border-0 shadow-sm overflow-hidden">

                <div class="card-header bg-success bg-gradient text-white py-3">
                    <h6 class="m-0 fw-bold">
                        <i class="fas fa-truck me-2"></i>Log Distribusi Bulanan
                    </h6>
                </div>
                
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted" width="5%">No</th>
                                    <th class="py-3 text-muted" width="20%">Periode Bulan</th>
                                    <th class="py-3 text-muted">Unit Outlet</th>
                                    <th class="py-3 text-muted text-center" width="20%">Jumlah Barang Dikirim</th>
                                    <th class="pe-4 py-3 text-muted text-end" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>

                                @php
                                    $grouped = $distribusi->groupBy(function($item){
                                        return \Carbon\Carbon::parse($item->tanggal)->format('F Y');
                                    });
                                @endphp

                                @forelse ($grouped as $periode => $items)
                                <tr>
                                    <td class="ps-4 text-muted small">{{ $loop->iteration }}</td>

                                    <td>
                                        <span class="fw-bold text-success">
                                            {{ \Carbon\Carbon::parse($items->first()->tanggal)->translatedFormat('F Y') }}
                                        </span>
                                    </td>

                                    <td class="fw-semibold text-dark">
    {{ $items->first()->outlet->nama_outlet ?? '-' }}
</td>

                                    <td class="text-center">
                                        <span class="badge bg-soft-success text-success px-3 py-2">
                                            {{ number_format($items->sum('jumlah'),0,',','.') }} Item
                                        </span>
                                    </td>

                                   <td class="pe-4 text-end">

    @php
        $periodeParam = \Carbon\Carbon::parse($items->first()->tanggal)->format('Y-m');
    @endphp

    {{-- DETAIL --}}
    <a href="{{ route('user.laporan.distribusi.detail', $periodeParam) }}" 
       class="btn btn-sm btn-outline-success me-2">
        <i class="fas fa-eye me-1"></i>
    </a>

    {{-- PDF PER BULAN --}}
    <a href="{{ route('user.laporan.distribusi.detail.pdf', $periodeParam) }}" 
       class="btn btn-sm btn-success">
        <i class="fas fa-file-pdf me-1"></i>
    </a>

</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-5 text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-box-open fa-3x text-light"></i>
                                        </div>
                                        <p class="text-muted">Ops! Belum ada data distribusi untuk periode ini.</p>
                                        <a href="{{ route('user.laporan.distribusi') }}" class="btn btn-sm btn-outline-secondary">Reset Filter</a>
                                    </td>
                                </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-muted italic">Dicetak oleh: {{ auth()->user()->nama }}</span>
                        <span class="small text-muted fw-bold">{{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection