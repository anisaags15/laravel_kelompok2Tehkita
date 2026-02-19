@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3 class="fw-bold mb-4"><i class="fas fa-bell text-primary mr-2"></i> Notifikasi Outlet</h3>

    @if($stokAlert->isEmpty() && $pemakaianHariIni->isEmpty() && $unreadMessages->isEmpty())
        <div class="alert alert-info border-0 shadow-sm">
            <i class="fas fa-check-circle mr-2"></i> Tidak ada notifikasi atau aktivitas mendesak saat ini.
        </div>
    @endif

    {{-- 1. NOTIFIKASI STOK MAU HABIS --}}
    @foreach($stokAlert as $item)
        <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center">
            <i class="fas fa-exclamation-triangle mr-3 fa-lg"></i>
            <div>
                {{-- REVISI: Pakai nama_bahan bukan nama --}}
                Stok <strong>{{ $item->bahan->nama_bahan ?? 'Bahan' }}</strong> kritis! Tersisa hanya <strong>{{ $item->stok }}</strong>. Segera lapor pusat!
            </div>
        </div>
    @endforeach

    {{-- 2. NOTIFIKASI PESAN BARU --}}
    @foreach($unreadMessages as $msg)
        <div class="alert alert-primary border-0 shadow-sm">
            <i class="fas fa-envelope mr-2"></i> Anda memiliki pesan baru dari <strong>Admin Pusat</strong>.
        </div>
    @endforeach

    {{-- 3. NOTIFIKASI AKTIVITAS HARI INI --}}
    @foreach($pemakaianHariIni as $item)
        <div class="alert alert-success border-0 shadow-sm">
            <i class="fas fa-info-circle mr-2 text-success"></i>
            {{-- REVISI: Pakai nama_bahan bukan nama --}}
            Hari ini Anda telah mencatat pemakaian <strong>{{ $item->bahan->nama_bahan ?? 'Bahan' }}</strong> sebanyak {{ $item->jumlah }} unit.
        </div>
    @endforeach
</div>
@endsection