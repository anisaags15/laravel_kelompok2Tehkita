@extends('layouts.main')

@section('title', 'Laporan Waste Bahan Baku')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER SECTION --}}
    <div class="card border-0 shadow-sm overflow-hidden mb-4" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="bg-danger p-4 d-flex justify-content-between align-items-center text-white">
                <div>
                    <h3 class="fw-bold mb-1"><i class="fas fa-trash-alt me-2"></i>Laporan Waste</h3>
                    <p class="mb-0 opacity-75">Rekapitulasi bahan baku rusak & kadaluwarsa: <strong>{{ auth()->user()->outlet->nama_outlet }}</strong></p>
                </div>
                <div class="text-end">
                    {{-- Pastikan route ini sesuai di web.php --}}
                    <a href="{{ route('user.laporan.waste.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-light shadow-sm px-4 text-danger fw-bold">
                        <i class="fas fa-file-pdf me-2"></i>Cetak Laporan PDF
                    </a>
                </div>
            </div>
            <div class="bg-white px-4 py-2 d-flex justify-content-between align-items-center">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 bg-transparent p-0 small">
                        <li class="breadcrumb-item"><a href="{{ route('user.laporan.index') }}" class="text-danger text-decoration-none">Laporan</a></li>
                        <li class="breadcrumb-item active">Laporan Waste</li>
                    </ol>
                </nav>
                <div class="text-muted small">
                    {{-- Menampilkan periode berdasarkan filter --}}
                    Periode: <strong>{{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS SUMMARY --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100 py-2" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
                <div class="card-body">
                    <h6 class="text-muted mb-1 small text-uppercase fw-bold">Total Insiden Waste</h6>
                    <h2 class="fw-bold mb-0 text-danger">{{ $wasteData->count() }}</h2>
                    <small class="text-muted">Kasus tercatat bulan ini</small>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-8">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background-color: #fff5f5;">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x text-danger opacity-50"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-danger mb-1">Pentingnya Pencatatan Waste</h6>
                        <p class="text-muted small mb-0">Pusat memantau setiap laporan kerusakan untuk menjaga kualitas standar bahan baku dan meminimalkan kerugian finansial outlet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DATA TABLE --}}
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-clipboard-list text-danger me-2"></i>Rincian Barang Rusak</h6>
                <span class="badge bg-danger-subtle text-danger px-3 py-2" style="font-size: 0.75rem;">Terverifikasi Sistem</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" width="5%">No</th>
                            <th width="15%">Tanggal</th>
                            <th width="25%">Bahan Baku</th>
                            <th width="15%" class="text-center">Jumlah</th>
                            <th width="25%">Alasan / Keterangan</th>
                            <th class="text-end pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($wasteData as $key => $item)
                        <tr>
                            <td class="ps-4">{{ $key+1 }}</td>
                            {{-- Gunakan kolom 'tanggal' karena di PemakaianController kamu simpan ke kolom tanggal --}}
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                            <td>
                                {{-- REVISI: Langsung ke relasi bahan, tambahkan null safe operator ?? untuk jaga-jaga --}}
                                <div class="fw-bold text-dark">{{ $item->bahan->nama_bahan ?? 'Bahan Tidak Ditemukan' }}</div>
                                <small class="text-muted">ID: #WST-{{ $item->id }}</small>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-danger">{{ $item->jumlah }}</span> 
                                <small class="text-muted">{{ $item->bahan->satuan ?? '' }}</small>
                            </td>
                            <td>
                                <span class="text-muted small italic">"{{ $item->keterangan ?? '-' }}"</span>
                            </td>
                            <td class="text-end pe-4">
                                @if($item->status == 'verified')
                                    <span class="badge bg-success-subtle text-success px-2 py-1">
                                        <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning px-2 py-1">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-box-open fa-3x text-light mb-3"></i>
                                <p class="text-muted mb-0">Tidak ada laporan waste untuk periode ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-center py-3">
            <small class="text-muted">Menampilkan data laporan waste periode {{ \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F Y') }}</small>
        </div>
    </div>
</div>

<style>
    .bg-danger-subtle { background-color: #fceaea; }
    .bg-success-subtle { background-color: #e8fadf; }
    .bg-warning-subtle { background-color: #fff9e6; }
    .badge-dot {
        background: transparent;
        color: #666;
        font-weight: 500;
        font-size: 0.8rem;
    }
    table th {
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #555;
    }
    .table-hover tbody tr:hover {
        background-color: #fffafa;
    }
</style>
@endsection