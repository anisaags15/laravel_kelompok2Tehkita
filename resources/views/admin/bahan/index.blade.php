@extends('layouts.main')

@section('title', 'Data Bahan')
@section('page', 'Master Data Bahan Baku')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold text-dark mb-1">Stok Bahan Baku</h4>
                    <p class="text-muted small mb-0">Pantau dan kelola ketersediaan bahan utama operasional.</p>
                </div>
                <a href="{{ route('admin.bahan.create') }}" class="btn btn-success px-4" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Bahan
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
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="15%">Satuan</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="20%">Stok Saat Ini</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bahans as $bahan)
                        <tr style="transition: all 0.2s ease;">
                            <td class="text-center text-muted font-weight-500">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- IKON DIGANTI KE BOX (STOK) --}}
                                    <div class="bg-primary-light text-primary p-2 rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                         style="background: #e3f2fd; width: 40px; height: 40px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark text-capitalize" style="font-size: 1rem;">
                                            {{ $bahan->nama_bahan }}
                                        </div>
                                        <small class="text-muted text-uppercase">SKU: BHN-0{{ $bahan->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge px-3 py-2" style="background: #f0f2f5; color: #4a5568; border-radius: 6px; font-weight: 600;">
                                    {{ $bahan->satuan }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-block px-3 py-1" style="border-radius: 20px; background: #f8f9fa; border: 1px solid #edf2f7;">
                                    <span class="font-weight-bold {{ $bahan->stok_awal <= 10 ? 'text-danger' : 'text-primary' }}" style="font-size: 1.1rem;">
                                        {{ number_format($bahan->stok_awal, 0, ',', '.') }}
                                    </span>
                                </div>
                                @if($bahan->stok_awal <= 10)
                                    <div class="d-block mt-1">
                                        <small class="text-danger font-italic" style="font-size: 0.7rem;">
                                            <i class="fas fa-exclamation-triangle"></i> Stok Hampir Habis!
                                        </small>
                                    </div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.bahan.edit', $bahan->id) }}" 
                                       class="btn btn-sm btn-light text-warning border mr-2 action-btn" 
                                       title="Edit Data">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    
                                    <form id="delete-bahan-{{ $bahan->id }}" action="{{ route('admin.bahan.destroy', $bahan->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger border action-btn" 
                                                onclick="confirmDelete('delete-bahan-{{ $bahan->id }}')" 
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
                                <div class="opacity-5">
                                    <i class="fas fa-layer-group fa-3x text-muted mb-3"></i>
                                </div>
                                <p class="text-muted font-italic">Data bahan baku belum tersedia.</p>
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