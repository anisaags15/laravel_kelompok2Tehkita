@extends('layouts.main')

@section('title', 'Data Outlet')
@section('page', 'Kelola Data Outlet')

@section('content')
<div class="container-fluid">
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-4 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold text-dark mb-1">Daftar Outlet</h4>
                    <p class="text-muted small mb-0">Manajemen lokasi dan informasi kontak seluruh cabang outlet.</p>
                </div>
                <a href="{{ route('admin.outlet.create') }}" class="btn btn-success px-4" style="border-radius: 8px; font-weight: 600;">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Outlet
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="text-center py-3 px-4 text-uppercase small font-weight-bold text-muted" width="5%">No</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted">Informasi Outlet</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted">Alamat</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="15%">No. HP</th>
                            <th class="py-3 text-uppercase small font-weight-bold text-muted text-center" width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($outlets as $outlet)
                        <tr style="transition: all 0.2s ease;">
                            <td class="text-center text-muted font-weight-500">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-info-light text-info p-2 rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                         style="background: #e0f7fa; width: 40px; height: 40px;">
                                        <i class="fas fa-store-alt"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold text-dark text-capitalize" style="font-size: 1rem;">
                                            {{ $outlet->nama_outlet }}
                                        </div>
                                        <small class="text-muted text-uppercase">ID: OTL-{{ str_pad($outlet->id, 3, '0', STR_PAD_LEFT) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="align-middle">
                                <span class="text-muted small">
                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $outlet->alamat }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge px-3 py-2" style="background: #f0f2f5; color: #007bff; border-radius: 6px; font-weight: 600;">
                                    <i class="fas fa-phone-alt mr-1 small"></i> {{ $outlet->no_hp }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center">
                                    <a href="{{ route('admin.outlet.edit', $outlet->id) }}" 
                                       class="btn btn-sm btn-light text-warning border mr-2 action-btn" 
                                       title="Edit Data">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    
                                    <form id="delete-outlet-{{ $outlet->id }}" action="{{ route('admin.outlet.destroy', $outlet->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-light text-danger border action-btn" 
                                                onclick="confirmDelete('delete-outlet-{{ $outlet->id }}')" 
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
                                    <i class="fas fa-store-slash fa-3x text-muted mb-3"></i>
                                </div>
                                <p class="text-muted font-italic">Belum ada data outlet yang terdaftar.</p>
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