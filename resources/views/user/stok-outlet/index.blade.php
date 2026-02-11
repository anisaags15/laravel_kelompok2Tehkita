@extends('layouts.main')

@section('title', 'Stok Bahan Outlet')

@section('content')

<div class="card">

    <div class="card-header">
        <h3 class="card-title">Stok Bahan Outlet Saya</h3>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Stok Tersedia</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($stokOutlets as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->bahan->nama_bahan ?? '-' }}</td>
                        <td class="text-center">{{ $item->stok }}</td>
                        <td class="text-center">
                            @if ($item->stok == 0)
                                <span class="badge bg-danger">Habis</span>
                            @elseif ($item->stok <= 10)
                                <span class="badge bg-warning text-dark">Hampir Habis</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">
                            Data stok belum tersedia
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
