@extends('layouts.main')

@section('title', 'Laporan Inventori Wilayah')
@section('page', 'Laporan Stok Outlet')

@push('css')
<style>
    :root {
        --success-premium: #15803d;
        --bg-soft-success: #f0fdf4;
    }

    .header-gradient-stok {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        border-radius: 20px;
        padding: 2.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }

    .card-stats-premium {
        border: none;
        border-radius: 18px;
        transition: transform 0.3s ease;
        background: #ffffff;
    }

    .card-stats-premium:hover {
        transform: translateY(-5px);
    }

    .table-premium thead th {
        background-color: #f8fafc;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 1.25rem;
        border: none;
    }

    .table-premium tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    .avatar-box {
        width: 45px;
        height: 45px;
        background: var(--success-premium);
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.2rem;
        box-shadow: 0 4px 10px rgba(21, 128, 61, 0.2);
    }

    .badge-count-premium {
        background: var(--bg-soft-success);
        color: var(--success-premium);
        padding: 0.6rem 1.2rem;
        border-radius: 50px;
        font-weight: 700;
        display: inline-block;
        border: 1px solid #dcfce7;
    }

    .btn-action-custom {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        margin: 0 3px;
    }

    .btn-detail { background: #e0e7ff; color: #4338ca; }
    .btn-detail:hover { background: #4338ca; color: white; }
    
    .btn-print { background: #fee2e2; color: #b91c1c; }
    .btn-print:hover { background: #b91c1c; color: white; }

    .search-wrapper {
        position: relative;
        max-width: 300px;
    }
    
    .search-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-wrapper input {
        padding-left: 40px;
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER & GLOBAL ACTION --}}
    <div class="header-gradient-stok">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h1 class="font-weight-bold mb-1">Stock Control Center</h1>
                <p class="opacity-75 mb-0">Pemantauan ketersediaan inventaris di seluruh cabang outlet Teh Kita secara real-time.</p>
            </div>
            <div class="col-md-5 text-md-right mt-3 mt-md-0">
                {{-- TOMBOL EKSPOR SEMUA (PDF REKAP) --}}
                <a href="{{ route('admin.laporan.stok-outlet.cetak-semua') }}" 
                   class="btn btn-success btn-lg px-4 shadow-sm font-weight-bold" 
                   style="border-radius: 12px; border: 2px solid rgba(255,255,255,0.2);">
                    <i class="fas fa-file-pdf mr-2"></i> Ekspor Rekap Wilayah
                </a>
            </div>
        </div>
    </div>

    {{-- MINI STATS --}}
    <div class="row mb-4">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-stats-premium shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-soft-success text-success p-3 rounded-lg mr-3">
                            <i class="fas fa-store-alt fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold">CABANG TERDAFTAR</span>
                            <h3 class="font-weight-bold mb-0">{{ $outlets->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card card-stats-premium shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-light text-primary p-3 rounded-lg mr-3">
                            <i class="fas fa-boxes fa-lg"></i>
                        </div>
                        <div>
                            <span class="text-muted small font-weight-bold">TOTAL SKU ITEM</span>
                            <h3 class="font-weight-bold mb-0">{{ $outlets->sum(fn($o) => $o->stokOutlet->count()) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
        <div class="card-header bg-white py-4 border-0">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h5 class="font-weight-bold text-dark mb-0">Daftar Inventaris Outlet</h5>
                    <p class="text-muted small mb-0">Klik ikon grafik untuk rincian stok atau ikon PDF untuk laporan per cabang.</p>
                </div>
                {{-- FITUR SEARCH SEDERHANA --}}
                <div class="search-wrapper mt-3 mt-md-0">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama outlet...">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-premium mb-0" id="outletTable">
                    <thead>
                        <tr>
                            <th class="text-center" width="8%">RANK</th>
                            <th>IDENTITAS OUTLET</th>
                            <th class="text-center">VARIASI BAHAN</th>
                            <th class="text-center">STATUS SISTEM</th>
                            <th class="text-center" width="150px">OPSI LAPORAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($outlets as $outlet)
                        <tr>
                            <td class="text-center">
                                <span class="text-muted font-weight-bold">#{{ $loop->iteration }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-box mr-3">
                                        {{ substr($outlet->nama_outlet, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-bold h6 mb-0">{{ $outlet->nama_outlet }}</div>
                                        <small class="text-muted">Kode: <span class="text-primary">TCK-{{ 100 + $outlet->id }}</span></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="badge-count-premium">
                                    {{ $outlet->stokOutlet->count() }} <small>Items</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill badge-success py-2 px-3">
                                    <i class="fas fa-check-circle mr-1"></i> Terhubung
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.laporan.stok-outlet.detail', $outlet->id) }}" 
                                   class="btn-action-custom btn-detail" 
                                   title="Rincian Stok">
                                    <i class="fas fa-chart-pie"></i>
                                </a>
                                
                                <a href="{{ route('admin.laporan.stok-outlet.cetak', $outlet->id) }}" 
                                   target="_blank"
                                   class="btn-action-custom btn-print" 
                                   title="Download PDF Cabang">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-5 text-center">
                                <img src="{{ asset('images/empty-box.png') }}" alt="Empty" style="width: 80px; opacity: 0.5;">
                                <h6 class="mt-3 text-muted font-italic">Belum ada data outlet yang tersedia untuk laporan.</h6>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="card-footer bg-light border-0 py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 small text-muted">Menampilkan {{ $outlets->count() }} outlet aktif di seluruh wilayah.</p>
                </div>
                <div class="col-md-6 text-md-right">
                    <span class="small font-weight-bold text-success">
                        <i class="fas fa-clock mr-1"></i> Terakhir Diperbarui: {{ now()->format('H:i') }} WIB
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT SEARCH SEDERHANA --}}
@push('js')
<script>
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let filter = this.value.toUpperCase();
        let rows = document.querySelector("#outletTable tbody").rows;
        
        for (let i = 0; i < rows.length; i++) {
            let outletName = rows[i].cells[1].textContent.toUpperCase();
            if (outletName.indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }      
        }
    });
</script>
@endpush
@endsection