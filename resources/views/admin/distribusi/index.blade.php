@extends('layouts.main')

@section('title', 'Distribusi Barang')
@section('page', 'Monitoring Distribusi')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold text-dark mb-1">Monitoring Distribusi Barang</h4>
                    <p class="text-muted small mb-0">Kelola dan pantau pengiriman logistik ke seluruh outlet.</p>
                </div>
                <a href="{{ route('admin.distribusi.create') }}" class="btn btn-success px-4" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Pengiriman
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3 px-4 text-uppercase small font-weight-bold text-muted" width="5%">No</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted">Informasi Bahan</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Outlet Tujuan</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Jumlah</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Tanggal Kirim</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Status</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($distribusis as $item)
                        <tr style="transition: all 0.2s ease;">
                            <td class="text-center text-muted font-weight-500">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success-light text-success p-2 rounded mr-3" style="background: #e8f5e9;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark text-capitalize">{{ $item->bahan->nama_bahan }}</div>
                                        <small class="text-muted">ID: #DIST-{{ $item->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="badge badge-outline-primary px-3 py-2" style="border: 1px solid #007bff; color: #007bff; background: transparent; border-radius: 6px; font-weight: 500;">
                                    <i class="fas fa-store-alt mr-1"></i> {{ $item->outlet->nama_outlet }}
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-soft-primary px-3 py-2" style="background: #e3f2fd; color: #0d47a1; border-radius: 6px; font-size: 0.9rem; font-weight: 700;">
                                    {{ $item->jumlah }} <small class="font-weight-normal">{{ $item->bahan->satuan }}</small>
                                </span>
                            </td>
                            <td class="text-center text-dark">
                                <div class="font-weight-500">{{ date('d M Y', strtotime($item->tanggal)) }}</div>
                            </td>
                            <td class="text-center">
                                @if($item->status == 'dikirim')
                                    <span class="badge w-100 py-2" style="background: #fff3e0; color: #e65100; border-radius: 6px; font-weight: 600;">
                                        <i class="fas fa-clock mr-1"></i> Sedang Dikirim
                                    </span>
                                @else
                                    <span class="badge w-100 py-2" style="background: #e8f5e9; color: #2e7d32; border-radius: 6px; font-weight: 600;">
                                        <i class="fas fa-check-circle mr-1"></i> Telah Diterima
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item->status == 'dikirim')
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.distribusi.edit', $item->id) }}" class="btn btn-sm btn-light text-warning border mr-1" title="Edit Data">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form id="delete-dist-{{ $item->id }}" action="{{ route('admin.distribusi.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-light text-danger border" onclick="confirmDelete('delete-dist-{{ $item->id }}')" title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small"><i class="fas fa-check-double text-success"></i> Konfirmasi Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/box.svg" alt="Empty" style="width: 150px; opacity: 0.5;">
                                <p class="text-muted mt-3 font-italic">Belum ada data distribusi yang tercatat.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
            </div>
    </div>
</div>
@endsection