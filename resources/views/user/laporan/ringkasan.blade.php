@extends('layouts.main')

@section('title', 'Ringkasan Bulanan Outlet')

@section('content')
<div class="container-fluid">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="fw-bold text-warning mb-0">
                    Ringkasan Bulanan Outlet
                </h5>
                <small class="text-muted">
                    {{ auth()->user()->outlet->nama_outlet }}
                </small>
            </div>

            <a href="{{ route('user.laporan.ringkasan.pdf') }}" 
               class="btn btn-warning btn-sm px-4 shadow-sm">
                <i class="fas fa-file-pdf"></i> Cetak PDF
            </a>
        </div>

        <div class="card-body">

            <div class="row g-4 text-center">

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Item Stok</h6>
                            <h3 class="fw-bold text-success">
                                {{ $totalStok }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Total Distribusi Bulan Ini</h6>
                            <h3 class="fw-bold text-primary">
                                {{ $totalDistribusi }}
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Stok Menipis</h6>
                            <h3 class="fw-bold text-danger">
                                {{ $stokMenipis }}
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer text-end text-muted small">
            Data diperbarui otomatis setiap transaksi.
        </div>

    </div>

</div>
@endsection