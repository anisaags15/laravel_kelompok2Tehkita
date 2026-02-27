@extends('layouts.main')

@section('title', 'Stok Masuk')
@section('page', 'Data Stok Masuk')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        {{-- HEADER --}}
        <div class="card-header bg-white py-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold text-dark mb-1">Riwayat Stok Masuk</h4>
                    <p class="text-muted small mb-0">Catatan log penambahan bahan baku ke gudang pusat.</p>
                </div>
                <a href="{{ route('admin.stok-masuk.create') }}" class="btn btn-success px-4" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Stok
                </a>
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3 px-4 text-uppercase small font-weight-bold text-muted" width="5%">No</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted">Bahan Baku</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Jumlah Masuk</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Tanggal Input</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokMasuks as $stok)
                        <tr style="transition: all 0.2s ease;">
                            <td class="text-center text-muted font-weight-500">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Ikon Box Masuk --}}
                                    <div class="bg-warning-light text-warning p-2 rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                         style="background: #fff8e1; width: 40px; height: 40px;">
                                        <i class="fas fa-file-import"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark text-capitalize" style="font-size: 1rem;">
                                            {{ $stok->bahan->nama_bahan }}
                                        </div>
                                        <small class="text-muted text-uppercase small font-weight-bold">Batch: #IN-{{ $stok->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge px-3 py-2" style="background: #e8f5e9; color: #2e7d32; border-radius: 6px; font-size: 0.95rem; font-weight: 700;">
                                    + {{ number_format($stok->jumlah, 0, ',', '.') }} 
                                    <small class="font-weight-normal text-muted ml-1">{{ $stok->bahan->satuan }}</small>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="text-dark font-weight-500">
                                    {{ \Carbon\Carbon::parse($stok->tanggal)->format('d M Y') }}
                                </div>
                                <small class="text-muted" style="font-size: 0.75rem;">Terdata di sistem</small>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('admin.stok-masuk.edit', $stok->id) }}" 
                                       class="btn btn-sm btn-light text-warning border mr-2 action-btn" 
                                       title="Edit Data">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    
                                    {{-- Tombol Hapus --}}
                                    <form id="delete-stok-{{ $stok->id }}" action="{{ route('admin.stok-masuk.destroy', $stok->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger border action-btn" 
                                                onclick="confirmDelete('delete-stok-{{ $stok->id }}')" 
                                                title="Hapus Data">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-2 mb-3">
                                    <i class="fas fa-clipboard-list fa-3x text-muted"></i>
                                </div>
                                <p class="text-muted font-italic">Data riwayat stok masuk belum tersedia.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection