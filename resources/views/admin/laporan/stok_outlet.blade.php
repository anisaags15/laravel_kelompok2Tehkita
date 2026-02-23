@extends('layouts.main')

@section('title', 'Laporan Stok Outlet')
@section('page', 'Laporan Stok Outlet')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="font-weight-bold text-dark mb-1">
                Laporan Stok Outlet
            </h5>
            <small class="text-muted">
                Menampilkan ringkasan stok bahan pada setiap outlet
            </small>
        </div>
    </div>

    {{-- CARD TABEL --}}
    <div class="card card-outline card-secondary shadow-sm">
        <div class="card-header">
            <h6 class="card-title font-weight-bold mb-0">
                Daftar Outlet
            </h6>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered table-striped table-hover mb-0">
                <thead class="bg-light text-center">
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Outlet</th>
                        <th style="width: 160px;">Jumlah Item</th>
                        <th style="width: 220px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($outlets as $outlet)
                        <tr>
                            <td class="text-center align-middle">
                                {{ $loop->iteration }}
                            </td>

                            <td class="align-middle">
                                {{ $outlet->nama_outlet }}
                            </td>

                            <td class="text-center align-middle">
                                <span class="badge badge-info">
                                    {{ $outlet->stokOutlet->count() }} Item
                                </span>
                            </td>

                            <td class="text-center align-middle">
                                <a href="{{ route('admin.laporan.stok-outlet.detail', $outlet->id) }}"
                                   class="btn btn-outline-primary btn-sm mr-1">
                                    <i class="fas fa-eye"></i>
                                    Detail
                                </a>

                                <a href="{{ route('admin.laporan.stok-outlet.cetak', $outlet->id) }}"
                                   class="btn btn-outline-danger btn-sm"
                                   target="_blank">
                                    <i class="fas fa-file-pdf"></i>
                                    Cetak
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                <i class="fas fa-info-circle"></i>
                                Data outlet belum tersedia
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
@endsection