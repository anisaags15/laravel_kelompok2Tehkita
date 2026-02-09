@extends('adminlte::page')

@section('title', 'Stok Masuk')

@section('content_header')
    <h1>Data Stok Masuk</h1>
@stop

@section('content')

{{-- ALERT SUCCESS --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card">

    <div class="card-header">
        <a href="{{ route('admin.stok-masuk.create') }}"
           class="btn btn-primary btn-sm">
            + Tambah Stok
        </a>
    </div>

    <div class="card-body">

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Bahan</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th width="120">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($stokMasuks as $s)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $s->bahan->nama_bahan ?? '-' }}</td>
                    <td>{{ $s->jumlah }}</td>
                    <td>{{ $s->tanggal }}</td>

                    <td class="text-center">

                        <form action="{{ route('admin.stok-masuk.destroy',$s->id) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin hapus data?')">

                            @csrf
                            @method('DELETE')

                            <button class="btn btn-danger btn-xs">
                                Hapus
                            </button>

                        </form>

                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        Data masih kosong
                    </td>
                </tr>
            @endforelse

            </tbody>

        </table>

    </div>
</div>

@stop
