@extends('layouts.main')

@section('title', 'Riwayat Pemakaian')
@section('page', 'Riwayat Pemakaian')

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('user.pemakaian.create') }}" class="btn btn-primary">
            + Tambah Pemakaian
        </a>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pemakaians as $item)
                    <tr>
                        <td>{{ $item->bahan->nama_bahan }}</td>
                        <td>{{ $item->jumlah }} {{ $item->bahan->satuan }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Belum ada pemakaian</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
