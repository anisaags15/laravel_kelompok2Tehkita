@extends('layouts.main')

@section('title', 'Jadwal Distribusi')

@push('css')
<link rel="stylesheet" href="{{ asset('templates/dist/css/custom-admin.css') }}">
<style>
    .jadwal-hero {
        background: linear-gradient(135deg, #1a7a3c 0%, #2ecc71 100%);
        border-radius: 16px;
        padding: 28px 32px;
        color: white;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
    }
    .jadwal-hero::before {
        content: '';
        position: absolute;
        top: -50px; right: -50px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,0.07);
        pointer-events: none;
    }
    .jadwal-hero::after {
        content: '';
        position: absolute;
        bottom: -70px; right: 60px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
        pointer-events: none;
    }
    .hero-icon-wrap {
        width: 56px; height: 56px;
        background: rgba(255,255,255,0.18);
        border: 1.5px solid rgba(255,255,255,0.3);
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        backdrop-filter: blur(4px);
    }
    .hero-icon-wrap i { font-size: 1.5rem; }
    .jadwal-hero h2 { font-size: 1.4rem; font-weight: 700; margin: 0 0 4px; line-height: 1.3; }
    .jadwal-hero p  { opacity: 0.82; margin: 0; font-size: 0.88rem; }
    .stat-pill {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 50px; padding: 6px 18px;
        font-size: 0.82rem; font-weight: 600;
        white-space: nowrap;
        position: relative; z-index: 1;
    }
    .stat-pill .dot {
        width: 6px; height: 6px; border-radius: 50%;
        background: rgba(255,255,255,0.7);
        display: inline-block; flex-shrink: 0;
    }

    /* Form card */
    .form-card {
        border-radius: 16px !important;
        border: 1px solid #e8f5e9 !important;
        box-shadow: 0 4px 20px rgba(46,204,113,0.08) !important;
        overflow: hidden;
    }
    .form-card .card-header {
        background: linear-gradient(135deg, #f0faf4, #e8f5e9) !important;
        border-bottom: 1px solid #d4edda !important;
        padding: 18px 24px !important;
    }
    .form-card .form-control {
        border-radius: 10px !important;
        border: 1.5px solid #e0e0e0 !important;
        font-size: 0.88rem !important;
        padding: 10px 14px !important;
        transition: border-color 0.2s, box-shadow 0.2s !important;
    }
    .form-card .form-control:focus {
        border-color: #2ecc71 !important;
        box-shadow: 0 0 0 3px rgba(46,204,113,0.12) !important;
    }
    .btn-tambah {
        background: linear-gradient(135deg, #1a7a3c, #2ecc71) !important;
        border: none !important; border-radius: 10px !important;
        font-weight: 700 !important; padding: 12px !important;
        color: white !important; letter-spacing: 0.3px !important;
        transition: transform 0.15s, box-shadow 0.15s !important;
    }
    .btn-tambah:hover {
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 20px rgba(46,204,113,0.35) !important;
    }

    /* Table card */
    .table-card {
        border-radius: 16px !important;
        border: 1px solid #e8f5e9 !important;
        box-shadow: 0 4px 20px rgba(46,204,113,0.08) !important;
        overflow: hidden;
    }
    .table-card .card-header {
        background: white !important;
        border-bottom: 2px solid #e8f5e9 !important;
        padding: 18px 24px !important;
    }
    .table thead th {
        font-size: 0.75rem !important; text-transform: uppercase !important;
        letter-spacing: 0.6px !important; color: #666 !important;
        font-weight: 700 !important; background: #f8fffe !important;
        border-bottom: 2px solid #e8f5e9 !important; padding: 14px 16px !important;
    }
    .table tbody tr { transition: background 0.15s !important; }
    .table tbody tr:hover { background: #f0fff6 !important; }
    .table tbody td {
        padding: 16px !important; vertical-align: middle !important;
        border-bottom: 1px solid #f0f0f0 !important;
    }
    .keterangan-text { font-weight: 700; font-size: 0.92rem; }
    .badge-upcoming {
        background: #fff8e1; color: #e65100;
        border: 1.5px solid #ffcc02; border-radius: 50px;
        padding: 5px 14px; font-size: 0.78rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-selesai {
        background: #e8f5e9; color: #1b5e20;
        border: 1.5px solid #66bb6a; border-radius: 50px;
        padding: 5px 14px; font-size: 0.78rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .badge-count {
        background: linear-gradient(135deg, #1a7a3c, #2ecc71);
        color: white; border-radius: 50px;
        padding: 4px 14px; font-size: 0.8rem; font-weight: 700;
    }
    .btn-selesai {
        background: #e8f5e9 !important; color: #1a7a3c !important;
        border: 1.5px solid #a5d6a7 !important; border-radius: 8px !important;
        padding: 6px 14px !important; font-size: 0.8rem !important;
        font-weight: 600 !important; transition: all 0.2s !important;
    }
    .btn-selesai:hover { background: #1a7a3c !important; color: white !important; border-color: #1a7a3c !important; }
    .btn-hapus {
        background: #fff5f5 !important; color: #c62828 !important;
        border: 1.5px solid #ffcdd2 !important; border-radius: 8px !important;
        padding: 6px 12px !important; font-size: 0.8rem !important;
        transition: all 0.2s !important;
    }
    .btn-hapus:hover { background: #c62828 !important; color: white !important; border-color: #c62828 !important; }
    .empty-state { padding: 50px 20px; text-align: center; color: #aaa; }
    .empty-state i { font-size: 3rem; margin-bottom: 12px; opacity: 0.25; display: block; }

    /* ===================== CUSTOM MODAL ===================== */
    .modal-custom-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(3px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .modal-custom-overlay.show {
        display: flex;
    }
    .modal-custom-box {
        background: white;
        border-radius: 20px;
        padding: 36px 32px 28px;
        max-width: 400px;
        width: 90%;
        box-shadow: 0 24px 60px rgba(0,0,0,0.18);
        text-align: center;
        transform: scale(0.9);
        opacity: 0;
        transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1), opacity 0.2s ease;
    }
    .modal-custom-overlay.show .modal-custom-box {
        transform: scale(1);
        opacity: 1;
    }
    .modal-icon-wrap {
        width: 72px; height: 72px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px;
        font-size: 1.8rem;
    }
    .modal-icon-wrap.warning {
        background: #fff8e1;
        border: 2.5px solid #ffc107;
        color: #e65100;
    }
    .modal-icon-wrap.danger {
        background: #fdecea;
        border: 2.5px solid #ef9a9a;
        color: #c62828;
    }
    .modal-custom-box h5 {
        font-size: 1.1rem;
        font-weight: 800;
        margin-bottom: 8px;
        color: #222;
    }
    .modal-custom-box p {
        color: #888;
        font-size: 0.88rem;
        margin-bottom: 16px;
        line-height: 1.6;
    }
    .modal-info-chip {
        display: inline-block;
        background: #fff8e1;
        border: 1.5px solid #ffe082;
        border-radius: 50px;
        padding: 5px 18px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #e65100;
        margin-bottom: 24px;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .modal-info-chip.danger {
        background: #fdecea;
        border-color: #ef9a9a;
        color: #c62828;
    }
    .modal-btn-group {
        display: flex;
        gap: 10px;
    }
    .btn-modal-cancel {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        border: 1.5px solid #e0e0e0;
        background: #f5f5f5;
        color: #555;
        font-weight: 600;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-modal-cancel:hover { background: #e8e8e8; }
    .btn-modal-confirm-warning {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #f57f17, #ffc107);
        color: white;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(255,193,7,0.35);
    }
    .btn-modal-confirm-warning:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(255,193,7,0.45);
    }
    .btn-modal-confirm-danger {
        flex: 1;
        padding: 12px;
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #c62828, #ef5350);
        color: white;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 14px rgba(198,40,40,0.3);
    }
    .btn-modal-confirm-danger:hover {
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(198,40,40,0.4);
    }

    /* Dark mode */
    .dark-mode .jadwal-hero { background: linear-gradient(135deg, #145c2d 0%, #1a7a3c 100%); }
    .dark-mode .form-card,
    .dark-mode .table-card { border-color: #2d4a37 !important; background: #1e2d24 !important; }
    .dark-mode .form-card .card-header { background: linear-gradient(135deg, #1a2e20, #1e3526) !important; border-color: #2d4a37 !important; }
    .dark-mode .table-card .card-header { background: #1e2d24 !important; border-color: #2d4a37 !important; }
    .dark-mode .form-card .form-control { background: #253320 !important; border-color: #3a5c40 !important; color: #e0e0e0 !important; }
    .dark-mode .table thead th { background: #1a2820 !important; color: #aaa !important; border-color: #2d4a37 !important; }
    .dark-mode .table tbody tr:hover { background: #1e3028 !important; }
    .dark-mode .table tbody td { border-color: #253320 !important; }
    .dark-mode .keterangan-text { color: #e0e0e0; }
    .dark-mode .modal-custom-box { background: #1e2d24; }
    .dark-mode .modal-custom-box h5 { color: #e0e0e0; }
    .dark-mode .modal-custom-box p { color: #aaa; }
    .dark-mode .btn-modal-cancel { background: #253320; border-color: #3a5c40; color: #bbb; }
    .dark-mode .btn-modal-cancel:hover { background: #2d3d28; }
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1 class="m-0">Jadwal Distribusi</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Jadwal Distribusi</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">

        {{-- HERO --}}
        <div class="jadwal-hero">
            <div class="d-flex align-items-center w-100">
                <div class="hero-icon-wrap me-3">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h2>Manajemen Jadwal Distribusi</h2>
                    <p>Atur jadwal pengiriman bulanan ke seluruh outlet Teh Kita</p>
                </div>
                <div class="ms-auto ps-3">
                    <div class="stat-pill">
                        <i class="fas fa-clock" style="font-size:0.75rem;"></i>
                        {{ $jadwals->where('status', 'upcoming')->count() }} Akan Datang
                        <span class="dot"></span>
                        <i class="fas fa-check-circle" style="font-size:0.75rem;"></i>
                        {{ $jadwals->where('status', 'selesai')->count() }} Selesai
                    </div>
                </div>
            </div>
        </div>

        {{-- ALERT --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                 style="border-radius:12px; border:none; box-shadow: 0 4px 14px rgba(46,204,113,0.2);">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row">

            {{-- FORM TAMBAH --}}
            <div class="col-lg-4 mb-4">
                <div class="card form-card">
                    <div class="card-header">
                        <h6 class="fw-bold mb-0" style="color:#1a7a3c;">
                            <i class="fas fa-calendar-plus me-2"></i> Tambah Jadwal Baru
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('admin.jadwal-distribusi.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted"
                                       style="text-transform:uppercase; letter-spacing:0.5px;">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control"
                                    placeholder="cth: Distribusi Rutin April 2026"
                                    value="{{ old('keterangan') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted"
                                       style="text-transform:uppercase; letter-spacing:0.5px;">Tanggal Rencana</label>
                                <input type="date" name="tanggal_rencana" class="form-control"
                                    value="{{ old('tanggal_rencana') }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted"
                                       style="text-transform:uppercase; letter-spacing:0.5px;">
                                    Catatan <span class="fw-normal text-lowercase">(opsional)</span>
                                </label>
                                <textarea name="catatan" class="form-control" rows="3"
                                    placeholder="Catatan untuk outlet...">{{ old('catatan') }}</textarea>
                            </div>
                            <button type="submit" class="btn btn-tambah w-100">
                                <i class="fas fa-plus me-2"></i> Tambah Jadwal
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="col-lg-8 mb-4">
                <div class="card table-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0">
                            <i class="fas fa-list-alt text-success me-2"></i> Daftar Jadwal
                        </h6>
                        <span class="badge-count">{{ $jadwals->where('status', 'upcoming')->count() }} Akan Datang</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Keterangan</th>
                                        <th>Tanggal Rencana</th>
                                        <th>Catatan</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jadwals as $jadwal)
                                    <tr>
                                        <td class="ps-4">
                                            <span class="keterangan-text">{{ $jadwal->keterangan }}</span>
                                        </td>
                                        <td>
                                            <div style="font-weight:600; font-size:0.88rem;">
                                                <i class="far fa-calendar-alt text-success me-1"></i>
                                                {{ $jadwal->tanggal_rencana->format('d M Y') }}
                                            </div>
                                            @if($jadwal->tanggal_rencana->isPast() && $jadwal->status == 'upcoming')
                                                <small class="text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Sudah lewat!
                                                </small>
                                            @elseif($jadwal->status == 'upcoming')
                                                <small class="text-muted">
                                                    {{ $jadwal->tanggal_rencana->diffForHumans() }}
                                                </small>
                                            @endif
                                        </td>
                                        <td style="max-width:200px;">
                                            <small class="text-muted">{{ $jadwal->catatan ?? '-' }}</small>
                                        </td>
                                        <td>
                                            @if($jadwal->status == 'upcoming')
                                                <span class="badge-upcoming">
                                                    <i class="fas fa-clock"></i> Akan Datang
                                                </span>
                                            @else
                                                <span class="badge-selesai">
                                                    <i class="fas fa-check-circle"></i> Selesai
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if($jadwal->status == 'upcoming')
                                                    {{-- Hidden form selesai --}}
                                                    <form id="form-selesai-{{ $jadwal->id }}"
                                                          action="{{ route('admin.jadwal-distribusi.selesai', $jadwal->id) }}"
                                                          method="POST" style="display:none;">
                                                        @csrf
                                                        @method('PATCH')
                                                    </form>
                                                    <button type="button" class="btn btn-selesai"
                                                        onclick="showSelesaiModal({{ $jadwal->id }}, '{{ addslashes($jadwal->keterangan) }}')">
                                                        <i class="fas fa-check me-1"></i> Selesai
                                                    </button>
                                                @endif

                                                {{-- Hidden form hapus --}}
                                                <form id="form-hapus-{{ $jadwal->id }}"
                                                      action="{{ route('admin.jadwal-distribusi.destroy', $jadwal->id) }}"
                                                      method="POST" style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button" class="btn btn-hapus"
                                                    onclick="showHapusModal({{ $jadwal->id }}, '{{ addslashes($jadwal->keterangan) }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <i class="fas fa-calendar-times"></i>
                                                <p class="mb-1 fw-bold">Belum ada jadwal distribusi</p>
                                                <small>Tambahkan jadwal baru di form sebelah kiri</small>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ===================== MODAL SELESAI — KUNING WARNING ===================== --}}
<div class="modal-custom-overlay" id="modalSelesai">
    <div class="modal-custom-box">
        <div class="modal-icon-wrap warning">
            <i class="fas fa-check-circle"></i>
        </div>
        <h5>Tandai Selesai?</h5>
        <p>Jadwal berikut akan ditandai sebagai <strong>selesai</strong> dan tidak bisa diubah lagi.</p>
        <div class="modal-info-chip" id="chipSelesaiLabel">—</div>
        <div class="modal-btn-group">
            <button class="btn-modal-cancel" onclick="closeModal('modalSelesai')">
                <i class="fas fa-times me-1"></i> Batal
            </button>
            <button class="btn-modal-confirm-warning" id="btnConfirmSelesai">
                <i class="fas fa-check me-1"></i> Ya, Selesai!
            </button>
        </div>
    </div>
</div>

{{-- ===================== MODAL HAPUS — MERAH DANGER ===================== --}}
<div class="modal-custom-overlay" id="modalHapus">
    <div class="modal-custom-box">
        <div class="modal-icon-wrap danger">
            <i class="fas fa-trash-alt"></i>
        </div>
        <h5>Hapus Jadwal?</h5>
        <p>Jadwal ini akan <strong>dihapus permanen</strong> dan tidak bisa dikembalikan.</p>
        <div class="modal-info-chip danger" id="chipHapusLabel">—</div>
        <div class="modal-btn-group">
            <button class="btn-modal-cancel" onclick="closeModal('modalHapus')">
                <i class="fas fa-times me-1"></i> Batal
            </button>
            <button class="btn-modal-confirm-danger" id="btnConfirmHapus">
                <i class="fas fa-trash me-1"></i> Ya, Hapus!
            </button>
        </div>
    </div>
</div>

@push('js')
<script>
    function showSelesaiModal(id, label) {
        document.getElementById('chipSelesaiLabel').textContent = label;
        document.getElementById('btnConfirmSelesai').onclick = function () {
            document.getElementById('form-selesai-' + id).submit();
        };
        openModal('modalSelesai');
    }

    function showHapusModal(id, label) {
        document.getElementById('chipHapusLabel').textContent = label;
        document.getElementById('btnConfirmHapus').onclick = function () {
            document.getElementById('form-hapus-' + id).submit();
        };
        openModal('modalHapus');
    }

    function openModal(id) {
        const overlay = document.getElementById(id);
        overlay.classList.add('show');
        overlay.addEventListener('click', function handler(e) {
            if (e.target === overlay) {
                closeModal(id);
                overlay.removeEventListener('click', handler);
            }
        });
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // Tutup dengan ESC
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal('modalSelesai');
            closeModal('modalHapus');
        }
    });
</script>
@endpush
@endsection