@extends('layouts.main')

@section('title', 'Detail Distribusi Bahan')

@push('styles')
<link rel="stylesheet" href="{{ asset('templates/dist/css/laporn-user.css') }}">
@endpush

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-success mb-1">
                Detail Distribusi Bahan
            </h4>
            <small class="text-muted">
                {{ \Carbon\Carbon::parse($periode)->translatedFormat('F Y') }}
            </small>
        </div>

        <a href="{{ route('user.laporan.distribusi') }}" 
           class="btn-action-custom btn-detail">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <div class="card p-4 mb-4">
        <div class="d-flex align-items-center">
            <div class="icon-box-modern me-3">
                <i class="fas fa-truck-loading"></i>
            </div>
            <div>
                <small class="text-muted">Total Bahan Dikirim</small>
                <h4 class="fw-bold mb-0">
                    {{ number_format($detail->sum('jumlah'),0,',','.') }} Item
                </h4>
            </div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-premium mb-0">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Tanggal Pengiriman</th>
                            <th>Nama Bahan</th>
                            <th width="15%" class="text-center">Jumlah</th>
                            <th width="20%" class="text-center">Tanggal Diterima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detail as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>

                            <td class="fw-semibold text-success">
                                {{ $item->bahan->nama_bahan ?? '-' }}
                            </td>

                            <td class="text-center">
                                <span class="badge-count-premium">
                                    {{ number_format($item->jumlah,0,',','.') }}
                                </span>
                            </td>

                            <td class="text-center">
                                @if($item->status == 'diterima')
                                    {{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y') }}
                                @else
                                    <span class="text-muted">Belum diterima</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Tidak ada data distribusi bahan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white py-3 d-flex justify-content-between">
            <small class="text-muted italic">
                Dicetak oleh: {{ auth()->user()->nama }}
            </small>
            <small class="text-muted fw-bold">
                {{ now()->translatedFormat('d F Y, H:i') }} WIB
            </small>
        </div>
    </div>

</div>
@endsection