@extends('layouts.main')

@section('title', 'Dashboard Outlet')
@section('page', 'Dashboard Outlet')

@section('content')
<div class="row">

    <!-- TOTAL STOK -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalStok ?? 0 }}</h3>
                <p>Stok Outlet</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <!-- PEMAKAIAN HARI INI -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pemakaianHariIni ?? 0 }}</h3>
                <p>Pemakaian Bahan Hari Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
        </div>
    </div>

    <!-- TOTAL DISTRIBUSI -->
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $distribusi ?? 0 }}</h3>
                <p>Total Distribusi</p>
            </div>
            <div class="icon">
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>

</div>

<!-- DETAIL TERBARU 5 STOK -->
<div class="row mt-4">
    <div class="col-12">
        <h5>Detail Stok Terakhir</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Bahan</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stokOutlets as $stok)
                <tr>
                    <td>{{ $stok->bahan->nama ?? '-' }}</td>
                    <td>{{ $stok->stok }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center">Belum ada data stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- PEMAKAIAN TERBARU 5 HARI -->
<div class="row mt-4">
    <div class="col-12">
        <h5>Pemakaian Terakhir</h5>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Bahan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemakaians as $pem)
                <tr>
                    <td>{{ $pem->bahan->nama ?? '-' }}</td>
                    <td>{{ $pem->jumlah }}</td>
                    <td>{{ optional($pem->tanggal)->format('d-m-Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada data pemakaian</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection