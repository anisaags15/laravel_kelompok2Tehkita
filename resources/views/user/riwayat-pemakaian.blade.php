@extends('layouts.main')

@section('title', 'Riwayat Pemakaian Lengkap')
@section('page', 'Riwayat Pemakaian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <h6 class="fw-bold mb-0 text-success"><i class="fas fa-history me-2"></i>Semua Riwayat Pemakaian</h6>
                <a href="{{ route('user.dashboard') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Nama Bahan</th>
                                <th>Jumlah Pemakaian</th>
                                <th>Tanggal Transaksi</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayatLengkap as $riwayat)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $riwayat->bahan->nama_bahan ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $riwayat->jumlah }} unit</span></td>
                                <td><i class="far fa-calendar-alt me-1 text-muted"></i> {{ $riwayat->tanggal->format('d M Y') }}</td>
                                <td class="text-center"><span class="badge bg-soft-success text-success">Selesai</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">Belum ada riwayat pemakaian terdaftar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                {{ $riwayatLengkap->links() }}
            </div>
        </div>
    </div>
</div>
@endsection