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
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#28a745" viewBox="0 0 16 16"><path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/></svg>
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
                             <h5 class="font-weight-bold text-success mb-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="mr-2" viewBox="0 0 16 16"><path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/></svg>
                                Terintegrasi
                             </h5>
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
                                <th class="py-3 text-center">Status Laporan</th>
                                <th class="pr-4 py-3 text-center" style="width: 1%; white-space: nowrap;">Aksi Manajemen</th>
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
                                <td class="py-3 text-center">
                                    @if($w->status == 'pending')
                                        <span class="badge badge-warning text-white px-3 py-2 shadow-sm d-inline-flex align-items-center" style="border-radius: 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="mr-1" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                                            Menunggu
                                        </span>
                                    @elseif($w->status == 'verified')
                                        <span class="badge badge-success px-3 py-2 shadow-sm d-inline-flex align-items-center" style="border-radius: 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="mr-1" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2 shadow-sm d-inline-flex align-items-center" style="border-radius: 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="mr-1" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/></svg>
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 pr-4 text-center" style="white-space: nowrap;">
                                    @if($w->status == 'pending')
                                        <button class="btn btn-sm btn-primary rounded-pill px-4 shadow-sm font-weight-bold transition-all d-inline-flex align-items-center" data-toggle="modal" data-target="#modalVerif{{ $w->id }}">
                                            Review 
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="ml-2" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0z"/>
                                                <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8zm8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7z"/>
                                            </svg>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-light rounded-pill px-3 disabled border text-muted d-inline-flex align-items-center" style="cursor: not-allowed; opacity: 0.8;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="mr-2" viewBox="0 0 16 16">
                                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                            </svg>
                                            Terarsip
                                        </button>
                                    @endif
                                </td>
                            </tr>

                            {{-- Modal Foto --}}
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

                            {{-- Modal Verifikasi --}}
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