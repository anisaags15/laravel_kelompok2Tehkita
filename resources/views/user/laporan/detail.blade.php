@extends('layouts.main')

@section('title', 'Detail Distribusi — Teh Kita')

@section('content')
<style>
    /* FORMAL & CLEAN THEME - TEH KITA */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

    .tk-laporan-wrapper { 
        font-family: 'Inter', sans-serif !important; 
        background-color: #f8fafc; 
        min-height: 100vh; 
        padding: 2.5rem 0;
    }

    /* Navigasi Formal */
    .tk-breadcrumb { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; margin-bottom: 8px; }
    .tk-judul { font-weight: 800; letter-spacing: -1px; color: #0f172a; margin-bottom: 4px; }
    
    /* Tombol Kembali Formal (Outline Style) */
    .btn-kembali-formal {
        background: #ffffff;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 8px 18px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
        text-decoration: none !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .btn-kembali-formal i { font-size: 12px; margin-right: 8px; color: #10b981; }
    .btn-kembali-formal:hover { 
        background: #f1f5f9; 
        border-color: #94a3b8;
        color: #0f172a;
        transform: translateY(-1px);
    }

    /* Card & Tabel */
    .tk-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .tk-card-header {
        padding: 20px 24px;
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .custom-table-formal thead th {
        background: #f8fafc;
        padding: 16px 24px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        letter-spacing: 0.5px;
    }

    .custom-table-formal tbody td { 
        padding: 18px 24px; 
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 14px;
    }

    /* Kalender Minimalis */
    .tgl-tile {
        width: 46px; height: 46px; border-radius: 8px;
        border: 1px solid #e2e8f0; overflow: hidden; text-align: center;
        background: #fff;
    }
    .tgl-tile .bln { background: #059669; color: white; font-size: 9px; font-weight: 800; padding: 2px 0; }
    .tgl-tile .tgl { font-size: 16px; font-weight: 700; color: #1e293b; padding-top: 1px; }

    /* Inisial Outlet Bulat */
    .outlet-icon {
        width: 34px; height: 34px; border-radius: 50%;
        background: #ecfdf5; color: #059669;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; border: 1px solid #d1fae5;
    }

    /* Kolom Jumlah */
    .jumlah-box { font-weight: 700; font-size: 15px; color: #0f172a; }

    /* Status Bahasa Indonesia */
    .status-terima {
        background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 6px;
        padding: 4px 12px; display: inline-block; text-align: right;
    }
    .status-proses {
        background: #fffbeb; border: 1px solid #fef3c7; border-radius: 6px;
        padding: 4px 12px; display: inline-flex; align-items: center; color: #d97706;
    }

    .dot-animasi {
        width: 6px; height: 6px; background: #fbbf24; border-radius: 50%;
        margin-right: 8px; position: relative;
    }
    .dot-animasi::after {
        content: ''; position: absolute; width: 100%; height: 100%; top: 0; left: 0;
        background: #fbbf24; border-radius: 50%; animation: pulse 1.5s infinite;
    }
    @keyframes pulse { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(3); opacity: 0; } }
</style>

<div class="tk-laporan-wrapper">
    <div class="container-fluid px-lg-5">
        
        <div class="row mb-4 align-items-end">
            <div class="col-md-7">
                <div class="tk-breadcrumb">Laporan Operasional / <span class="text-success">Detail Distribusi</span></div>
                <h2 class="tk-judul">Logistik Distribusi Bahan</h2>
                <p class="text-muted mb-0 font-weight-medium">
                    <i class="far fa-calendar-alt text-success mr-2"></i>
                    Periode Laporan: <b>{{ \Carbon\Carbon::createFromFormat('Y-m', $periode)->translatedFormat('F Y') }}</b>
                </p>
            </div>
            <div class="col-md-5 text-md-right mt-3 mt-md-0">
                <a href="{{ route('user.laporan.distribusi') }}" class="btn-kembali-formal">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>

        <div class="tk-card">
            <div class="tk-card-header">
                <div class="d-flex align-items-center">
                    <div class="mr-3 text-success">
                        <i class="fas fa-list-ul"></i>
                    </div>
                    <h6 class="mb-0 font-weight-bold text-dark">Data Pengiriman Barang</h6>
                </div>
                <span class="badge badge-light border text-muted px-3 py-2">
                    Total: <b class="text-dark">{{ count($distribusi) }} Baris Data</b>
                </span>
            </div>

            <div class="table-responsive">
                <table class="table custom-table-formal mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" width="70">No</th>
                            <th>Jadwal Keluar</th>
                            <th>Outlet Penerima</th>
                            <th>Bahan / Item</th>
                            <th class="text-center">Jumlah Stok</th>
                            <th class="text-right">Status Konfirmasi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distribusi as $i => $item)
                        <tr>
                            <td class="text-center text-muted font-weight-bold">
                                {{ str_pad($i+1, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="tgl-tile mr-3 shadow-sm">
                                        <div class="bln text-uppercase">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('M') }}</div>
                                        <div class="tgl">{{ \Carbon\Carbon::parse($item->created_at)->format('d') }}</div>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('l') }}</div>
                                        <small class="text-muted"><i class="far fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="outlet-icon mr-3">
                                        {{ substr($item->outlet->nama_outlet ?? 'O', 0, 1) }}
                                    </div>
                                    <span class="font-weight-bold">{{ $item->outlet->nama_outlet ?? '-' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="font-weight-bold text-dark mb-0">{{ $item->bahan->nama_bahan ?? '-' }}</div>
                                <small class="text-muted">No. Distribusi: #{{ $item->id }}</small>
                            </td>
                            <td class="text-center">
                                <span class="jumlah-box">{{ number_format($item->jumlah, 0, ',', '.') }}</span>
                            </td>
                            <td class="text-right">
                                @if($item->status == 'diterima')
                                    <div class="status-terima">
                                        <div class="font-weight-bold text-dark small">{{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y') }}</div>
                                        <div class="small text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i> BERHASIL DITERIMA</div>
                                    </div>
                                @else
                                    <div class="status-proses border">
                                        <span class="dot-animasi"></span>
                                        <span class="small font-weight-bold">DALAM PERJALANAN</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <span class="text-muted fw-bold">Belum ada data distribusi bulan ini.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 bg-light border-top d-flex justify-content-between align-items-center">
                <div class="small text-muted font-weight-bold">
                    Petugas Pemeriksa: <span class="text-dark">{{ auth()->user()->nama }}</span>
                </div>
                <div class="small text-muted">
                    Dicetak secara sistem pada: {{ now()->translatedFormat('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection