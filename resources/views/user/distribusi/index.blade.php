@extends('layouts.main')

@section('title', 'Riwayat Distribusi')

@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <h3 class="card-title font-weight-bold">Riwayat Distribusi Barang</h3>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <table class="table table-bordered table-striped table-hover">
            <thead class="bg-light">
                <tr class="text-center">
                    <th width="50">No</th>
                    <th>Tanggal</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($distribusis as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}
                        </td>
                        <td>{{ $item->bahan->nama_bahan ?? '-' }}</td>
                        <td class="text-center font-weight-bold">{{ $item->jumlah }}</td>
                        <td class="text-center">
                            @if ($item->status === 'dikirim')
                                <span class="badge bg-warning text-dark px-3 py-2">
                                    <i class="fas fa-shipping-fast mr-1"></i> Dikirim
                                </span>
                            @else
                                <span class="badge bg-success px-3 py-2">
                                    <i class="fas fa-check-circle mr-1"></i> Diterima
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->status === 'dikirim')
                                {{-- Tombol ini akan mengaktifkan fungsi terima() di controller --}}
                                <form action="{{ route('user.distribusi.terima', $item->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary shadow-sm" onclick="return confirm('Apakah Anda yakin barang sudah sampai dengan jumlah yang benar?')">
                                        Konfirmasi Terima
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-outline-secondary disabled">
                                    <i class="fas fa-check-double"></i> Selesai
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="50" class="mb-2 opacity-50"><br>
                            Belum ada data distribusi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection