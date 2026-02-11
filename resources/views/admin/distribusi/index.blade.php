@extends('layouts.main')

@section('title', 'Data Distribusi')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between mb-3">
        <h4>Data Distribusi</h4>
        <a href="{{ route('admin.distribusi.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Distribusi
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th>Bahan</th>
                        <th>Outlet</th>
                        <th>Jumlah</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($distribusis as $item)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>{{ $item->bahan->nama_bahan }}</td>
                            <td>{{ $item->outlet->nama_outlet }}</td>
                            <td class="text-center">{{ $item->jumlah }}</td>
                            <td class="text-center">{{ $item->tanggal }}</td>
                            <td class="text-center">
                                <span class="badge badge-success">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Data distribusi belum ada
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
