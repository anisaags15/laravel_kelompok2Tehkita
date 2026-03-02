@extends('layouts.main')

@section('content')
<style>
    .transition-all { transition: all 0.3s ease; }
    .card-hover:hover { transform: translateY(-5px); }
    .foto-zoom:hover { transform: scale(1.1); transition: 0.3s; z-index: 10; }
    .bg-soft-danger { background-color: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .bg-soft-success { background-color: rgba(40, 167, 69, 0.1); color: #28a745; }
    .bg-soft-primary { background-color: rgba(78, 115, 223, 0.1); color: #4e73df; }
    .table thead th { letter-spacing: 0.5px; border-top: 0 !important; }
</style>

<div class="content-header p-0" style="margin-top: -25px;">
    <div class="container-fluid">
        <div class="row pt-4 pb-3 align-items-center">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold text-dark" style="letter-spacing: -1.2px; font-size: 2.2rem;">
                    <i class="fas fa-shield-alt text-primary mr-2"></i>Pusat Kendali Waste
                </h1>
                <p class="text-muted mb-0 mt-1">Monitoring laporan kerusakan bahan baku secara real-time.</p>
            </div>
            <div class="col-sm-6 text-right d-none d-md-block">
                <div class="badge badge-white shadow-sm px-3 py-2 border" style="border-radius: 10px;">
                    <i class="fas fa-circle text-success mr-1 small"></i> Sistem Aktif: {{ date('H:i') }} WIB
                </div>
            </div>
        </div>
    </div>
</div>

<section class="content mt-2">
    <div class="container-fluid">
        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <div class="bg-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width:30px; height:30px;">
                        <i class="fas fa-check text-success"></i>
                    </div>
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm transition-all card-hover {{ $totalPending > 0 ? 'bg-gradient-danger' : 'bg-gradient-success' }} text-white" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="small font-weight-bold text-uppercase mb-1 opacity-75">Pending Review</p>
                                <h2 class="mb-0 font-weight-bold" style="font-size: 2.5rem;">{{ $totalPending }}</h2>
                                <p class="mb-0 small mt-2"><i class="fas fa-exclamation-circle mr-1"></i> Perlu tindakan segera</p>
                            </div>
                            <div class="opacity-25">
                                <i class="fas fa-clock fa-4x {{ $totalPending > 0 ? 'anim-pulse' : '' }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 shadow-sm transition-all card-hover bg-white" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between text-dark">
                            <div>
                                <p class="small font-weight-bold text-muted text-uppercase mb-1">Total Waste (Bulan Ini)</p>
                                <h2 class="mb-0 font-weight-bold text-primary" style="font-size: 2.5rem;">{{ $totalWaste }}</h2>
                                <p class="mb-0 small text-muted mt-2"><i class="fas fa-arrow-up text-danger mr-1"></i> Akumulasi sistem</p>
                            </div>
                            <div class="text-light">
                                <i class="fas fa-chart-line fa-4x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm transition-all card-hover bg-white" style="border-radius: 16px;">
                    <div class="card-body p-4 text-center d-flex align-items-center justify-content-center border-left border-primary" style="border-width: 6px !important;">
                        <div class="w-100">
                             <p class="small font-weight-bold text-muted text-uppercase mb-1">Status Gudang</p>
                             <h5 class="font-weight-bold text-success mb-0"><i class="fas fa-check-double mr-2"></i>Terintegrasi</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Table Card --}}
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px;">
            <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h3 class="card-title font-weight-bold text-dark">
                    <span class="bg-soft-primary p-2 rounded mr-2"><i class="fas fa-list-ul"></i></span>
                    Daftar Masuk Kerusakan
                </h3>
                <div class="card-tools">
                    <form action="" method="GET">
                        <div class="input-group input-group-sm shadow-sm" style="width: 280px;">
                            <input type="text" name="search" class="form-control border-0 bg-light py-4 pl-3" placeholder="Cari outlet atau bahan..." value="{{ request('search') }}" style="border-radius: 10px 0 0 10px;">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary px-3" style="border-radius: 0 10px 10px 0;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-body p-0 mt-3">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="pl-4 py-3">Outlet & Tanggal</th>
                                <th class="py-3">Bahan Baku</th>
                                <th class="py-3 text-center">Bukti Foto</th>
                                <th class="py-3 text-center">Jumlah</th>
                                <th class="py-3">Status Laporan</th>
                                <th class="pr-4 py-3 text-right">Aksi Manajemen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allWastes as $w)
                            <tr>
                                <td class="pl-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-lg mr-3 d-flex align-items-center justify-content-center bg-soft-primary" style="width: 42px; height:42px; border-radius: 12px;">
                                            <i class="fas fa-store-alt"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark">{{ $w->outlet->nama_outlet }}</div>
                                            <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i>{{ $w->created_at->translatedFormat('d M Y') }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3 font-weight-bold text-dark text-capitalize">
                                    {{ $w->bahan->nama_bahan }}
                                    <div class="small text-muted font-weight-normal">{{ Str::limit($w->keterangan, 35) }}</div>
                                </td>
                                <td class="py-3 text-center">
                                    @if($w->foto)
                                        <img src="{{ asset('storage/' . $w->foto) }}" 
                                             class="img-thumbnail rounded shadow-sm foto-zoom" 
                                             style="width: 45px; height: 45px; object-fit: cover; cursor: pointer; border-radius: 10px;"
                                             data-toggle="modal" data-target="#modalFoto{{ $w->id }}">
                                    @else
                                        <span class="text-muted small"><i class="fas fa-image-slash"></i></span>
                                    @endif
                                </td>
                                <td class="py-3 text-center">
                                    <span class="badge badge-pill px-3 py-2 bg-soft-danger font-weight-bold" style="font-size: 0.85rem;">
                                        -{{ $w->jumlah }} {{ $w->bahan->satuan }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($w->status == 'pending')
                                        <span class="badge badge-warning text-white px-3 py-2 shadow-sm" style="border-radius: 8px;">
                                            <i class="fas fa-spinner fa-spin mr-1 small"></i> Menunggu
                                        </span>
                                    @elseif($w->status == 'verified')
                                        <span class="badge badge-success px-3 py-2 shadow-sm" style="border-radius: 8px;">
                                            <i class="fas fa-check-circle mr-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2 shadow-sm" style="border-radius: 8px;">
                                            <i class="fas fa-ban mr-1"></i> Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-right">
                                    @if($w->status == 'pending')
                                        <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm font-weight-bold transition-all" data-toggle="modal" data-target="#modalVerif{{ $w->id }}">
                                            Review <i class="fas fa-chevron-right ml-1 small"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-light rounded-pill px-3 disabled border text-muted">
                                            <i class="fas fa-lock small mr-1"></i> Terarsip
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal Foto Tetap Sama Namun Perbaiki UI Cardnya --}}
                            @if($w->foto)
                            <div class="modal fade" id="modalFoto{{ $w->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                                        <div class="modal-header border-0 pb-0">
                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                        </div>
                                        <div class="modal-body p-4 pt-0 text-center">
                                            <img src="{{ asset('storage/' . $w->foto) }}" class="img-fluid rounded shadow mb-3" style="max-height: 400px;">
                                            <h5 class="font-weight-bold mb-1">{{ $w->bahan->nama_bahan }}</h5>
                                            <p class="text-muted">{{ $w->keterangan }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Modal Verifikasi Terupdate --}}
                            <div class="modal fade" id="modalVerif{{ $w->id }}" tabindex="-1" role="dialog">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 24px;">
                                        <div class="modal-body text-center p-5">
                                            <div class="bg-soft-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 90px; height: 90px;">
                                                <i class="fas fa-file-signature fa-2x"></i>
                                            </div>
                                            <h4 class="font-weight-bold text-dark">Validasi Laporan</h4>
                                            <p class="text-muted px-3">Konfirmasi pemotongan stok <b>{{ $w->jumlah }} {{ $w->bahan->satuan }}</b> untuk <b>{{ $w->outlet->nama_outlet }}</b>?</p>
                                            
                                            <div class="d-flex justify-content-center mt-4">
                                                <form action="{{ route('admin.waste.verify', $w->id) }}" method="POST" class="mr-2">
                                                    @csrf
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="btn btn-light px-4 py-2 rounded-pill border">Tolak</button>
                                                </form>
                                                
                                                <form action="{{ route('admin.waste.verify', $w->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="verified">
                                                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm rounded-pill font-weight-bold">Setujui</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="py-5">
                                        <img src="https://cdn-icons-png.flaticon.com/512/5089/5089731.png" style="width: 80px; opacity: 0.2;" class="mb-3">
                                        <h6 class="text-muted font-weight-normal">Tidak ada laporan waste yang ditemukan.</h6>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($allWastes->hasPages())
            <div class="card-footer bg-white border-top-0 py-4">
                <div class="d-flex justify-content-center">
                    {{ $allWastes->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection