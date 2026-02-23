@extends('layouts.main')

@section('content')
<div class="content-header p-0" style="margin-top: -25px;">
    <div class="container-fluid">
        <div class="row pt-0 pb-3">
            <div class="col-sm-12">
                <h1 class="m-0 font-weight-bold text-dark" style="letter-spacing: -1.5px; font-size: 2.2rem;">
                    <i class="fas fa-shield-alt text-primary mr-2"></i>Pusat Kendali Waste
                </h1>
                <p class="text-muted small mb-0 mt-1">Monitoring laporan kerusakan bahan baku dari seluruh outlet cabang.</p>
            </div>
        </div>
    </div>
</div>

<section class="content mt-2">
    <div class="container-fluid">
        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm {{ $totalPending > 0 ? 'bg-danger' : 'bg-success' }} text-white transition-all">
                    <div class="card-body">
                        <h6 class="small font-weight-bold opacity-75">PENDING REVIEW</h6>
                        <h2 class="mb-0 font-weight-bold">{{ $totalPending }} <small>Laporan</small></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-body border-left border-primary" style="border-width: 4px !important;">
                        <h6 class="small font-weight-bold text-muted">TOTAL WASTE (BULAN INI)</h6>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ $totalWaste }} <small class="text-secondary font-weight-normal">Item</small></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pt-4 px-4">
                        <h3 class="card-title font-weight-bold text-dark"><i class="fas fa-stream mr-2 text-primary"></i>Daftar Masuk Kerusakan</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0">
                                <thead class="bg-light text-muted small uppercase">
                                    <tr>
                                        <th class="border-0 pl-4 py-3">OUTLET</th>
                                        <th class="border-0 py-3">ITEM</th>
                                        <th class="border-0 py-3 text-center">JUMLAH</th>
                                        <th class="border-0 py-3">ALASAN</th>
                                        <th class="border-0 py-3">STATUS</th>
                                        <th class="border-0 py-3 pr-4 text-right">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($allWastes as $w)
                                    <tr>
                                        <td class="pl-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary-soft rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height:35px; background: #eef2ff">
                                                    <i class="fas fa-store text-primary small"></i>
                                                </div>
                                                <span class="font-weight-bold text-dark">{{ $w->outlet->nama_outlet }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 text-capitalize">{{ $w->bahan->nama_bahan }}</td>
                                        <td class="py-3 text-center">
                                            <span class="badge badge-danger-soft px-3 py-2 text-danger font-weight-bold" style="background: #fff5f5">
                                                {{ $w->jumlah }} {{ $w->bahan->satuan }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-muted small italic"><i class="far fa-comment-dots mr-1"></i> {{ $w->keterangan }}</span>
                                        </td>
                                        <td class="py-3">
                                            @if($w->status == 'pending')
                                                <span class="badge badge-warning px-2 py-1"><i class="fas fa-clock mr-1"></i> Menunggu</span>
                                            @elseif($w->status == 'verified')
                                                <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Terverifikasi</span>
                                            @else
                                                <span class="badge badge-secondary px-2 py-1">Ditolak</span>
                                            @endif
                                        </td>
                                        <td class="py-3 pr-4 text-right">
                                            @if($w->status == 'pending')
                                                <button class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm mr-1" data-toggle="modal" data-target="#modalVerif{{ $w->id }}">
                                                    <i class="fas fa-check mr-1"></i> Verifikasi
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-light rounded-pill px-3 disabled">Selesai</button>
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- MODAL VERIFIKASI --}}
                                    <div class="modal fade" id="modalVerif{{ $w->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-body text-center p-5">
                                                    <i class="fas fa-question-circle text-primary mb-4" style="font-size: 4rem;"></i>
                                                    <h4 class="font-weight-bold">Verifikasi Laporan?</h4>
                                                    <p class="text-muted small">Anda akan menyetujui penghapusan stok <b>{{ $w->jumlah }} {{ $w->bahan->satuan }} {{ $w->bahan->nama_bahan }}</b> dari outlet <b>{{ $w->outlet->nama_outlet }}</b>.</p>
                                                    
                                                    <div class="d-flex justify-content-center mt-4">
                                                        <form action="{{ route('admin.waste.verify', $w->id) }}" method="POST" class="mr-2">
                                                            @csrf
                                                            <input type="hidden" name="status" value="rejected">
                                                            <button type="submit" class="btn btn-light px-4">Tolak & Balikin Stok</button>
                                                        </form>
                                                        
                                                        <form action="{{ route('admin.waste.verify', $w->id) }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="status" value="verified">
                                                            <button type="submit" class="btn btn-primary px-4 shadow">Setujui (Verified)</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">Tidak ada laporan kerusakan hari ini.</td>
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
</section>
@endsection