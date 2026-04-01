@extends('layouts.main')

@section('page', 'Notifikasi Outlet')

@section('content')
<div class="container-fluid py-4">

    {{-- Header Section --}}
    <div class="row mb-4">
        <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-center card p-4 shadow-sm border-0" style="border-radius: 15px;">
            <div class="mb-3 mb-md-0 text-center text-md-left">
                <h3 class="font-weight-bold mb-1">
                    <i class="fas fa-bell text-warning mr-2"></i>Pusat Notifikasi Outlet
                </h3>
                <p class="text-muted mb-0">Pantau stok kritis dan status pengiriman barang untuk outlet Anda.</p>
            </div>
            
            @if($notifications->count() > 0)
            <form action="{{ route('user.notifikasi.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-success btn-sm rounded-pill px-4 font-weight-bold shadow-sm">
                    <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
                </button>
            </form>
            @endif
        </div>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- List Notifikasi --}}
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        
                        @forelse($notifications as $n)
                            @php
                                $type = $n->data['type'] ?? 'info';
                                $config = match($type) {
                                    'stok_kritis' => [
                                        'icon'  => 'fa-exclamation-triangle',
                                        'color' => 'danger',
                                        'bg'    => 'rgba(220, 53, 69, 0.15)',
                                        'label' => 'URGENT'
                                    ],
                                    'chat' => [
                                        'icon'  => 'fa-comment-dots',
                                        'color' => 'primary',
                                        'bg'    => 'rgba(0, 123, 255, 0.15)',
                                        'label' => 'CHAT'
                                    ],
                                    'info' => [
                                        'icon'  => 'fa-truck',
                                        'color' => 'success',
                                        'bg'    => 'rgba(40, 167, 69, 0.15)',
                                        'label' => 'PENGIRIMAN'
                                    ],
                                    default => [
                                        'icon'  => 'fa-info-circle',
                                        'color' => 'info',
                                        'bg'    => 'rgba(23, 162, 184, 0.15)',
                                        'label' => 'INFO'
                                    ]
                                };
                                $isUnread = $n->read_at === null;
                            @endphp

                            <div class="list-group-item list-group-item-action py-3 px-4 border-bottom {{ $isUnread ? 'bg-unread' : 'bg-transparent' }}">
                                <div class="row align-items-center">

                                    {{-- Kolom 1: Icon Box --}}
                                    <div class="col-auto">
                                        <div class="icon-box d-flex align-items-center justify-content-center shadow-sm" 
                                             style="width: 50px; height: 50px; background: {{ $config['bg'] }}; border-radius: 12px;">
                                            <i class="fas {{ $config['icon'] }} text-{{ $config['color'] }}" style="font-size: 1.2rem;"></i>
                                        </div>
                                    </div>

                                    {{-- Kolom 2: Konten --}}
                                    <div class="col px-md-3 mt-2 mt-md-0">
                                        <div class="d-flex align-items-center mb-1 flex-wrap">
                                            <span class="badge badge-pill badge-{{ $config['color'] }} px-2 mr-2" style="font-size: 0.6rem; letter-spacing: 0.5px;">
                                                {{ $config['label'] }}
                                            </span>
                                            <small class="text-muted font-weight-bold">
                                                <i class="far fa-clock mr-1"></i>{{ $n->created_at->diffForHumans() }}
                                            </small>
                                            @if($isUnread)
                                                <span class="ml-2 badge badge-danger badge-pill animate-pulse" style="padding: 4px; height: 8px; width: 8px;"></span>
                                            @endif
                                        </div>
                                        <h6 class="mb-1 font-weight-bold {{ $isUnread ? 'text-primary' : '' }}">{{ $n->data['title'] }}</h6>
                                        <p class="mb-0 text-muted small text-truncate d-none d-md-block" style="max-width: 600px;">
                                            {{ $n->data['message'] }}
                                        </p>
                                    </div>

                                    {{-- Kolom 3: Aksi --}}
                                    <div class="col-auto d-flex align-items-center mt-3 mt-md-0">
                                        <a href="{{ $n->data['url'] ?? '#' }}" 
                                           class="btn btn-sm rounded-pill px-3 font-weight-bold btn-action shadow-sm mr-2">
                                            Lihat Detail
                                        </a>
                                        
                                        {{-- HAPUS - Sekarang pake class form-delete --}}
                                        <form action="{{ route('user.notifikasi.destroy', $n->id) }}" method="POST" class="form-delete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-link text-danger p-2 shadow-none hover-scale btn-delete-notif">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="text-center py-5 my-5">
                                <div class="mb-3">
                                    <i class="fas fa-check-circle text-success" style="font-size: 4rem; opacity: 0.25;"></i>
                                </div>
                                <h5 class="font-weight-bold">Kotak Masuk Kosong</h5>
                                <p class="text-muted">Semua aman! Tidak ada notifikasi baru untuk outlet Anda.</p>
                            </div>
                        @endforelse

                    </div>
                </div>

                @if($notifications->hasPages())
                <div class="card-footer border-top py-4 bg-transparent d-flex justify-content-center">
                    {{ $notifications->links('pagination::bootstrap-4') }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Logic Hapus dengan SweetAlert2 (Anti Localhost-says)
    document.querySelectorAll('.btn-delete-notif').forEach(button => {
        button.addEventListener('click', function(e) {
            const form = this.closest('.form-delete');
            
            Swal.fire({
                title: 'Hapus Notifikasi?',
                text: "Data ini akan dihapus permanen dari daftar Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                border: 'none',
                borderRadius: '15px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<style>
    /* Styling Dasar */
    .bg-unread { background-color: rgba(0, 123, 255, 0.05) !important; border-left: 4px solid #007bff !important; }
    .list-group-item { transition: all 0.2s ease; border-left: 4px solid transparent; }
    .list-group-item:hover { background-color: rgba(0,0,0,0.02); transform: translateX(5px); }
    .btn-action { background: #f8f9fa; color: #007bff; border: 1px solid #dee2e6; }
    .btn-action:hover { background: #007bff; color: white; }
    .hover-scale:hover { transform: scale(1.2); }

    /* Animasi */
    .animate-pulse { animation: pulse-red 2s infinite; }
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }

    /* Custom SweetAlert Style agar menyatu dengan Tema */
    .swal2-popup {
        border-radius: 20px !important;
        font-family: 'Poppins', sans-serif !important;
    }
</style>
@endsection