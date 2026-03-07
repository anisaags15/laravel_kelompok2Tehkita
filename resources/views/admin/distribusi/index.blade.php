@extends('layouts.main')

@section('title', 'Distribusi Barang')
@section('page', 'Monitoring Distribusi')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="font-weight-bold text-dark mb-1" style="letter-spacing: -0.5px;">Monitoring Distribusi Barang</h4>
            <p class="text-muted small mb-0">Kelola dan pantau pengiriman logistik ke seluruh outlet secara real-time.</p>
        </div>
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
            <a href="{{ route('admin.distribusi.create') }}" class="btn btn-success px-4 shadow-sm" style="border-radius: 10px; font-weight: 600; background-color: #10b981; border: none;">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Pengiriman
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th class="text-center py-3 px-4 text-uppercase small font-weight-bold text-muted" width="5%">No</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted">Informasi Bahan</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Outlet Tujuan</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Jumlah</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Tanggal Kirim</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Status</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distribusis as $item)
                        <tr style="transition: all 0.2s ease; border-bottom: 1px solid #f1f5f9;">
                            <td class="text-center text-muted font-weight-bold">{{ $loop->iteration }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-success p-2 rounded-lg mr-3 d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px; border-radius: 10px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark text-capitalize" style="font-size: 0.95rem;">
                                            {{ $item->bahan->nama_bahan }}
                                        </div>
                                        <small class="text-muted font-weight-bold" style="font-size: 0.7rem;">ID: #DIST-{{ $item->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-block px-3 py-1" style="border: 1.5px solid #e2e8f0; color: #475569; border-radius: 8px; font-weight: 600; font-size: 0.85rem;">
                                    <i class="fas fa-store-alt mr-1 text-primary"></i> {{ $item->outlet->nama_outlet }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-pill px-3 py-2" style="background: #f0f9ff; color: #0369a1; font-size: 0.9rem; font-weight: 700; border: 1px solid #e0f2fe;">
                                    {{ number_format($item->jumlah, 0, ',', '.') }} <small class="font-weight-normal ml-1">{{ $item->bahan->satuan }}</small>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="text-dark font-weight-bold" style="font-size: 0.9rem;">
                                    {{ date('d M Y', strtotime($item->tanggal)) }}
                                </div>
                                <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                    <i class="far fa-clock mr-1 text-xs"></i> Pukul {{ $item->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="text-center">
                                @if($item->status == 'dikirim')
                                    <span class="badge px-3 py-2 w-100" style="background: #fff7ed; color: #9a3412; border-radius: 8px; font-weight: 600; border: 1px solid #ffedd5;">
                                        <i class="fas fa-shipping-fast mr-1"></i> Sedang Dikirim
                                    </span>
                                @else
                                    <span class="badge px-3 py-2 w-100" style="background: #f0fdf4; color: #166534; border-radius: 8px; font-weight: 600; border: 1px solid #dcfce7;">
                                        <i class="fas fa-check-circle mr-1"></i> Telah Diterima
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 'dikirim')
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.distribusi.edit', $item->id) }}" 
                                           class="btn btn-sm btn-white text-warning shadow-sm border mr-2" 
                                           style="border-radius: 8px; background: white;" title="Edit Data">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form id="delete-dist-{{ $item->id }}" action="{{ route('admin.distribusi.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-sm btn-white text-danger shadow-sm border" 
                                                    style="border-radius: 8px; background: white;"
                                                    onclick="confirmDelete('delete-dist-{{ $item->id }}')" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-success small font-weight-bold">
                                        <i class="fas fa-check-double mr-1"></i> Konfirmasi Selesai
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="opacity-50 mb-3">
                                    <i class="fas fa-truck-loading fa-4x text-muted"></i>
                                </div>
                                <p class="text-muted font-italic">Belum ada data distribusi yang tercatat hari ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(formId) {
        if(confirm('Apakah Anda yakin ingin menghapus data distribusi ini?')) {
            document.getElementById(formId).submit();
        }
    }
</script>
@endsection