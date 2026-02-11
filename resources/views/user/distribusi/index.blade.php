@extends('layouts.main')

@section('title', 'Riwayat Distribusi')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Riwayat Distribusi Barang</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Bahan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
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

                        <td class="text-center">{{ $item->jumlah }}</td>

                        <td class="text-center">
                            @if ($item->status === 'dikirim')
                                <span class="badge bg-warning text-dark">
                                    Dikirim
                                </span>
                            @else
                                <span class="badge bg-success">
                                    Diterima
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            Belum ada data distribusi
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
