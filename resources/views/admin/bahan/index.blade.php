@extends('layouts.main')

@section('title', 'Data Bahan')

@section('content')

<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Data Bahan</h3>

        <a href="{{ route('admin.bahan.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Bahan
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Bahan</th>
                    <th>Satuan</th>
                    <th>Stok Awal</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($bahans as $bahan)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $bahan->nama_bahan }}</td>
                        <td class="text-center">{{ $bahan->satuan }}</td>
                        <td class="text-center">{{ $bahan->stok_awal }}</td>
                        <td class="text-center">

                            <!-- <a href="{{ route('admin.bahan.edit', $bahan->id) }}"
                               class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a> -->

                            <form action="{{ route('admin.bahan.destroy', $bahan->id) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin hapus bahan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">
                            Data bahan belum ada
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>
</div>

@endsection
