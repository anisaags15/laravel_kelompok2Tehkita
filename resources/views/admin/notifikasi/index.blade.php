@extends('layouts.main')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h3 class="fw-bold text-dark"><i class="fas fa-bell text-warning mr-2"></i>Pusat Perhatian Admin</h3>
            <p class="text-muted small">Semua aktivitas sistem yang memerlukan tindakan Anda hari ini.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        
                        {{-- 1. STOK KRITIS --}}
                        @foreach($stokKritis as $s)
                        <li class="list-group-item list-group-item-action py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-light-danger text-danger mr-3" style="width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#fff5f5;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 font-weight-bold">⚠️ Stok Kritis di {{ $s->outlet->nama_outlet }}</h6>
                                    <small class="text-muted">Bahan <b>{{ $s->bahan->nama_bahan }}</b> tersisa <b>{{ $s->stok }} {{ $s->bahan->satuan }}</b> segera restock!</small>
                                </div>
                                <div>
                                    <a href="{{ route('admin.laporan.stok-kritis') }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">Atasi</a>
                                </div>
                            </div>
                        </li>
                        @endforeach

                        {{-- 2. WASTE BARU --}}
                        @foreach($wasteBaru as $w)
                        <li class="list-group-item list-group-item-action py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-light-warning text-warning mr-3" style="width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#fffdf2;">
                                    <i class="fas fa-trash-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 font-weight-bold">🚮 Laporan Waste Baru</h6>
                                    <small class="text-muted"><b>{{ $w->outlet->nama_outlet }}</b> melaporkan kerusakan <b>{{ $w->jumlah }} {{ $w->bahan->nama_bahan }}</b>.</small>
                                </div>
                                <div>
                                    <a href="{{ route('admin.waste.index') }}" class="btn btn-sm btn-outline-warning rounded-pill px-3 text-dark">Review</a>
                                </div>
                            </div>
                        </li>
                        @endforeach

                        {{-- 3. CHAT MASUK --}}
                        @foreach($unreadMessages as $m)
                        <li class="list-group-item list-group-item-action py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-light-primary text-primary mr-3" style="width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#f0f7ff;">
                                    <i class="fas fa-comment-dots"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 font-weight-bold">💬 Pesan dari {{ $m->sender->name }}</h6>
                                    <small class="text-muted">"{{ Str::limit($m->message, 50) }}"</small>
                                </div>
                                <div>
                                    <a href="{{ route('chat.show', $m->sender_id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Balas</a>
                                </div>
                            </div>
                        </li>
                        @endforeach

                        {{-- 4. KONFIRMASI STOK --}}
                        @foreach($distribusiTerbaru as $d)
                        <li class="list-group-item list-group-item-action py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-light-success text-success mr-3" style="width:45px; height:45px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#f6fff9;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 font-weight-bold">✅ Pengiriman Selesai</h6>
                                    <small class="text-muted">Outlet <b>{{ $d->outlet->nama_outlet }}</b> telah menerima pengiriman #{{ $d->id }}.</small>
                                </div>
                                <div>
                                    <a href="{{ route('admin.distribusi.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3">Detail</a>
                                </div>
                            </div>
                        </li>
                        @endforeach

                        @if($stokKritis->isEmpty() && $wasteBaru->isEmpty() && $unreadMessages->isEmpty())
                        <li class="list-group-item py-5 text-center text-muted">
                            <i class="fas fa-smile-beam fa-3x mb-3 opacity-20"></i>
                            <p>Semua beres! Tidak ada notifikasi mendesak saat ini.</p>
                        </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection