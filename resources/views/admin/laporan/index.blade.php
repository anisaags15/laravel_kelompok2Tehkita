@extends('layouts.main')

@section('title', 'Ringkasan Laporan Bulanan')
@section('page', 'Ringkasan Laporan Bulanan')

@section('content')
<div class="container-fluid">

    {{-- SUB HEADER (TIDAK DOUBLE LAGI) --}}
    <div class="mb-4">
        <p class="text-muted mb-0">
            Ikhtisar resmi aktivitas stok dan distribusi seluruh outlet.
        </p>
    </div>


    {{-- FILTER BULAN & TAHUN --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body bg-light">
            <form method="GET" action="{{ route('admin.laporan.index') }}" class="form-inline align-items-end">

                <div class="form-group mr-3">
                    <label for="bulan" class="mr-2 font-weight-bold text-dark">Bulan</label>
                    <select name="bulan" id="bulan" class="form-control form-control-sm">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ request('bulan', now()->month) == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="form-group mr-3">
                    <label for="tahun" class="mr-2 font-weight-bold text-dark">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control form-control-sm">
                        @for($y = now()->year - 2; $y <= now()->year; $y++)
                            <option value="{{ $y }}" {{ request('tahun', now()->year) == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <button type="submit" class="btn btn-primary btn-sm mr-2">
                    <i class="fas fa-filter"></i> Tampilkan
                </button>

                <a href="{{ route('admin.laporan.index', ['bulan' => request('bulan', now()->month), 'tahun' => request('tahun', now()->year)]) }}" 
                   class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-file-pdf"></i> Cetak PDF
                </a>

            </form>
        </div>
    </div>


    {{-- STATISTIK UTAMA --}}
    <div class="row mb-4">
        @php
            $stats = [
                ['label'=>'Total Distribusi Bulan Ini','value'=>$totalDistribusi ?? 0,'color'=>'primary'],
                ['label'=>'Stok Masuk Bulan Ini','value'=>$stokMasuk ?? 0,'color'=>'success'],
                ['label'=>'Outlet Aktif','value'=>$outletAktif ?? 0,'color'=>'info'],
                ['label'=>'Bahan Stok Menipis','value'=>$stokMenipis ?? 0,'color'=>'danger'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-left-{{ $stat['color'] }} border-0 h-100">
                <div class="card-body text-center">
                    <h6 class="text-muted text-uppercase small mb-2">
                        {{ $stat['label'] }}
                    </h6>
                    <h3 class="font-weight-bold text-dark mb-0">
                        {{ number_format($stat['value']) }}
                    </h3>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    {{-- DETAIL RINGKASAN --}}
    <div class="row mb-4">

        {{-- OUTLET TERAKTIF --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 font-weight-bold">
                        Outlet Teraktif Bulan Ini
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>Nama Outlet</th>
                                <th>Jumlah Distribusi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($outletTeraktif ?? [] as $item)
                            <tr>
                                <td>{{ $item->nama_outlet }}</td>
                                <td class="text-center font-weight-bold">
                                    {{ $item->total }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">
                                    Data belum tersedia
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- BAHAN TERBANYAK --}}
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0 font-weight-bold">
                        Bahan Paling Sering Dikirim
                    </h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="bg-light text-center">
                            <tr>
                                <th>Nama Bahan</th>
                                <th>Total Kirim</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bahanTerbanyak ?? [] as $item)
                            <tr>
                                <td>{{ $item->nama_bahan }}</td>
                                <td class="text-center font-weight-bold">
                                    {{ $item->total }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">
                                    Data belum tersedia
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>


    {{-- TOMBOL AKSI --}}
    <div class="card shadow-sm border-0">
        <div class="card-body text-center bg-light">

            <a href="{{ route('admin.laporan.stok-outlet') }}" 
               class="btn btn-outline-primary btn-sm px-4 mr-2">
                <i class="fas fa-box"></i> Laporan Stok Outlet
            </a>

            <a href="{{ route('admin.laporan.distribusi') }}" 
               class="btn btn-outline-success btn-sm px-4">
                <i class="fas fa-truck"></i> Laporan Distribusi
            </a>

        </div>
    </div>

</div>
@endsection