@extends('layouts.main')

@section('title', 'Riwayat Pemakaian Lengkap')
@section('page', 'Riwayat Pemakaian')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-body">
                <form action="{{ route('user.riwayat_pemakaian') }}" method="GET" class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama bahan (contoh: gula aren)..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-search me-1"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

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
                        <thead style="background-color: #e8f5e9;"> <tr>
                                <th class="ps-4">Nama Bahan</th>
                                <th>Jumlah Pemakaian</th>
                                <th>Tanggal Transaksi</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $item) <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $item->bahan->nama_bahan ?? '-' }}</td>
                                <td><span class="badge bg-light text-dark border px-3">{{ $item->jumlah }} unit</span></td>
                                <td><i class="far fa-calendar-alt me-1 text-muted"></i> {{ $item->tanggal->format('d M Y') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-soft-success text-success">
                                        <i class="fas fa-check-circle me-1"></i>Verified
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-info-circle d-block mb-2 fa-2x"></i>
                                    Belum ada riwayat pemakaian yang ditemukan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-muted small mb-0">Menampilkan {{ $riwayat->firstItem() }} - {{ $riwayat->lastItem() }} dari {{ $riwayat->total() }} data</p>
                    {{ $riwayat->appends(request()->input())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection