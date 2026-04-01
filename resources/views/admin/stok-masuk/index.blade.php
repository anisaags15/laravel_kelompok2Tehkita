@extends('layouts.main')

@section('title', 'Stok Masuk')
@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h4 class="font-weight-bold text-dark mb-1" style="letter-spacing: -0.5px;">Riwayat Stok Masuk</h4>
            <p class="text-muted small mb-0">Manajemen pencatatan bahan baku masuk ke gudang pusat.</p>
        </div>
        <div class="col-md-6 text-md-right mt-3 mt-md-0">
            <a href="{{ route('admin.stok-masuk.create') }}" class="btn btn-success px-4 shadow-sm" style="border-radius: 10px; font-weight: 600; background-color: #10b981; border: none;">
                <i class="fas fa-plus-circle mr-2"></i> Tambah Stok
            </a>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <form action="{{ route('admin.stok-masuk.index') }}" method="GET">
                <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                    <input type="text" name="search" class="form-control border-0" placeholder="Cari nama bahan..." value="{{ request('search') }}" style="height: 45px;">
                    <div class="input-group-append">
                        <button class="btn btn-primary border-0" type="submit" style="background-color: #10b981; width: 50px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th class="text-center py-3 px-4 text-uppercase small font-weight-bold text-muted" width="7%">No</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted">Bahan Baku</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Jumlah Masuk</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center">Waktu Input</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stokMasuks as $index => $stok)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            {{-- Penomoran otomatis yang mendukung pagination --}}
                            <td class="text-center text-muted font-weight-bold">{{ $stokMasuks->firstItem() + $index }}</td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-success p-2 rounded-lg mr-3 d-flex align-items-center justify-content-center" 
                                         style="width: 42px; height: 42px; border-radius: 10px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">
                                            {{ $stok->bahan->nama_bahan }}
                                        </div>
                                        <span class="badge badge-light text-muted border-0 p-0" style="font-size: 0.7rem;">
                                            BATCH #IN-{{ str_pad($stok->id, 4, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="d-inline-block px-3 py-1" style="background: #ecfdf5; color: #059669; border-radius: 8px; font-weight: 700;">
                                    + {{ number_format($stok->jumlah, 0, ',', '.') }} 
                                    <span class="small font-weight-normal ml-1">{{ $stok->bahan->satuan }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="text-dark font-weight-bold" style="font-size: 0.9rem;">
                                    {{ \Carbon\Carbon::parse($stok->tanggal)->format('d M Y') }}
                                </div>
                                <div class="text-muted" style="font-size: 0.75rem;">
                                    <i class="far fa-clock mr-1 text-xs"></i> Pukul {{ $stok->created_at->format('H.i') }} WIB
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.stok-masuk.edit', $stok->id) }}" 
                                       class="btn btn-sm btn-white text-warning shadow-sm border mr-2" 
                                       style="border-radius: 8px; background: white;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    <form id="delete-stok-{{ $stok->id }}" action="{{ route('admin.stok-masuk.destroy', $stok->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-white text-danger shadow-sm border" 
                                                style="border-radius: 8px; background: white;"
                                                onclick="confirmDelete('delete-stok-{{ $stok->id }}')" 
                                                title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-50 mb-3">
                                    <i class="fas fa-search fa-4x text-muted"></i>
                                </div>
                                <p class="text-muted font-italic">Data tidak ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-4 px-2">
        <div class="text-muted small">
            Menampilkan <strong>{{ $stokMasuks->firstItem() ?? 0 }}</strong> - <strong>{{ $stokMasuks->lastItem() ?? 0 }}</strong> dari <strong>{{ $stokMasuks->total() }}</strong> data
        </div>
        <div>
            {{ $stokMasuks->appends(request()->query())->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<script>
    function confirmDelete(formId) {
        if(confirm('Apakah Anda yakin ingin menghapus catatan stok ini?')) {
            document.getElementById(formId).submit();
        }
    }
</script>

<style>
    .pagination { margin-bottom: 0; }
    .page-item.active .page-link { 
        background-color: #10b981 !important; 
        border-color: #10b981 !important; 
        color: white !important;
    }
    .page-link { 
        color: #10b981; 
        border-radius: 8px !important; 
        margin: 0 2px; 
    }
</style>
@endsection