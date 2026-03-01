@extends('layouts.main')

@section('content')
<div class="content-header p-0" style="margin-top: -25px;">
    <div class="container-fluid">
        <div class="row pt-0 pb-3">
            <div class="col-sm-12">
                <h1 class="m-0 font-weight-bold text-dark" style="letter-spacing: -1.5px; font-size: 2.2rem;">
                    <i class="fas fa-shield-alt text-primary mr-2"></i>Pusat Kendali Waste
                </h1>
                <p class="text-muted small mb-0 mt-1">Monitoring laporan kerusakan bahan baku dari seluruh outlet cabang secara real-time.</p>
            </div>
        </div>
    </div>
</div>

<section class="content mt-2">
    <div class="container-fluid">
        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" style="border-radius: 10px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle mr-2 fa-lg"></i> 
                    <strong>Berhasil!</strong> {{ session('success') }}
                </div>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm {{ $totalPending > 0 ? 'bg-gradient-danger' : 'bg-gradient-success' }} text-white transition-all overflow-hidden" style="border-radius: 15px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="small font-weight-bold text-uppercase opacity-75">Pending Review</h6>
                                <h2 class="mb-0 font-weight-bold">{{ $totalPending }} <small style="font-size: 0.9rem">Laporan</small></h2>
                            </div>
                            <i class="fas fa-history fa-2x opacity-25"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white transition-all" style="border-radius: 15px;">
                    <div class="card-body p-3 border-left border-primary" style="border-width: 5px !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="small font-weight-bold text-muted text-uppercase">Total Waste (Bulan Ini)</h6>
                                <h2 class="mb-0 font-weight-bold text-dark">{{ $totalWaste }} <small class="text-secondary font-weight-normal" style="font-size: 0.9rem">Item</small></h2>
                            </div>
                            <i class="fas fa-chart-pie fa-2x text-light"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    {{-- Header Table --}}
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-stream mr-2 text-primary"></i>Daftar Masuk Kerusakan</h3>
                        <div class="card-tools">
                            <form action="" method="GET" class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" class="form-control border-light shadow-none bg-light" placeholder="Cari outlet atau bahan..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary px-3 shadow-none">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-body p-0 mt-2">
                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0">
                                <thead class="bg-light text-muted small text-uppercase">
                                    <tr>
                                        <th class="border-0 pl-4 py-3">Outlet & Tanggal</th>
                                        <th class="border-0 py-3">Bahan Baku</th>
                                        <th class="border-0 py-3 text-center">Bukti Foto</th>
                                        <th class="border-0 py-3 text-center">Jumlah</th>
                                        <th class="border-0 py-3">Alasan / Keterangan</th>
                                        <th class="border-0 py-3">Status</th>
                                        <th class="border-0 py-3 pr-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allWastes as $w)
                                    <tr>
                                        <td class="pl-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle mr-3 d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height:40px; background: #f0f3ff; color: #4e73df;">
                                                    <i class="fas fa-store"></i>
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold text-dark">{{ $w->outlet->nama_outlet }}</div>
                                                    <small class="text-muted"><i class="far fa-calendar-alt mr-1"></i>{{ $w->created_at->format('d M Y') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 font-weight-bold text-capitalize text-dark">{{ $w->bahan->nama_bahan }}</td>
                                        
                                        {{-- KOLOM FOTO --}}
                                        <td class="py-3 text-center">
                                            @if($w->foto)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset('storage/' . $w->foto) }}" 
                                                         class="img-thumbnail rounded shadow-sm foto-zoom" 
                                                         style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                         data-toggle="modal" data-target="#modalFoto{{ $w->id }}">
                                                </div>
                                            @else
                                                <span class="badge badge-light text-muted" style="font-weight: normal; font-size: 0.7rem;">
                                                    <i class="fas fa-image-slash mr-1"></i> No Image
                                                </span>
                                            @endif
                                        </td>

                                        <td class="py-3 text-center">
                                            <span class="badge badge-pill px-3 py-2 text-danger" style="background: #fff5f5; font-size: 0.85rem; border: 1px solid rgba(220,53,69,0.1)">
                                                -{{ $w->jumlah }} {{ $w->bahan->satuan }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-muted small" title="{{ $w->keterangan }}">
                                                <i class="far fa-comment-dots mr-1"></i> {{ Str::limit($w->keterangan, 30) }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            @if($w->status == 'pending')
                                                <span class="badge badge-warning text-white px-2 py-1 shadow-sm" style="font-size: 0.75rem;">
                                                    <i class="fas fa-clock mr-1"></i> Menunggu
                                                </span>
                                            @elseif($w->status == 'verified')
                                                <span class="badge badge-success px-2 py-1 shadow-sm" style="font-size: 0.75rem;">
                                                    <i class="fas fa-check-circle mr-1"></i> Terverifikasi
                                                </span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1 shadow-sm" style="font-size: 0.75rem;">
                                                    <i class="fas fa-times mr-1"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-4 text-right">
                                            @if($w->status == 'pending')
                                                <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalVerif{{ $w->id }}">
                                                    Review
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-light rounded-pill px-3 disabled border text-muted">Selesai</button>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- MODAL PREVIEW FOTO --}}
                                    @if($w->foto)
                                    <div class="modal fade" id="modalFoto{{ $w->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content bg-transparent border-0">
                                                <div class="modal-body p-0">
                                                    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                                                        <img src="{{ asset('storage/' . $w->foto) }}" class="img-fluid w-100">
                                                        <div class="card-body bg-white text-center">
                                                            <h5 class="font-weight-bold text-dark mb-1">{{ $w->bahan->nama_bahan }}</h5>
                                                            <p class="text-muted small mb-0">{{ $w->outlet->nama_outlet }} • {{ $w->created_at->format('d M Y') }}</p>
                                                            <button type="button" class="btn btn-light rounded-pill mt-3 px-4" data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- MODAL VERIFIKASI --}}
                                    <div class="modal fade" id="modalVerif{{ $w->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow" style="border-radius: 20px;">
                                                <div class="modal-body text-center p-5">
                                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                                                        <i class="fas fa-clipboard-check text-primary fa-2x"></i>
                                                    </div>
                                                    <h4 class="font-weight-bold text-dark">Verifikasi Laporan?</h4>
                                                    <p class="text-muted">Anda akan menyetujui pemotongan stok <b>{{ $w->jumlah }} {{ $w->bahan->satuan }}</b> karena kerusakan.</p>
                                                    
                                                    <div class="d-flex justify-content-center mt-4">
                                                        <form action="{{ route('admin.waste.verify', $w->id) }}" method="POST" class="mr-2">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="btn btn-light px-4 border rounded-pill">Tolak</button>
                                                        </form>
                                                        
                                                        <form action="{{ route('admin.waste.verify', $w->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="verified">
                                                            <button type="submit" class="btn btn-primary px-4 shadow rounded-pill">Setujui (Verified)</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <i class="fas fa-inbox fa-3x text-light mb-3"></i>
                                                <h6 class="text-muted font-weight-normal">Belum ada laporan waste masuk hari ini.</h6>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    {{-- Pagination --}}
                    @if($allWastes->hasPages())
                    <div class="card-footer bg-white border-top-0 py-4">
                        <div class="d-flex justify-content-center">
                            {{ $allWastes->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection