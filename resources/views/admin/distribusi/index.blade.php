@extends('adminlte::page')

@section('title', 'Distribusi')

@section('content_header')
    <h1>Distribusi Stok ke Outlet</h1>
@stop

@section('content')

<div class="card">

    {{-- HEADER --}}
    <div class="card-header">

        {{-- ADMIN PUSAT: TAMBAH --}}
        @if(auth()->user()->role == 'admin')
            <a href="{{ route('admin.distribusi.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Distribusi
            </a>
        @endif

    </div>


    {{-- BODY --}}
    <div class="card-body">

        {{-- ALERT --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif


        {{-- TABLE --}}
        <table class="table table-bordered table-hover">

            <thead class="bg-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Outlet</th>
                    <th>Bahan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th width="180">Aksi</th>
                </tr>
            </thead>

            <tbody>

            @forelse($distribusi as $row)

                <tr>

                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $row->tanggal }}</td>
                    <td>{{ $row->outlet->nama_outlet }}</td>
                    <td>{{ $row->bahan->nama_bahan }}</td>
                    <td>{{ $row->jumlah }}</td>

                    {{-- STATUS --}}
                    <td>
                        @if($row->status == 'dikirim')
                            <span class="badge bg-warning">Dikirim</span>
                        @else
                            <span class="badge bg-success">Diterima</span>
                        @endif
                    </td>


                    {{-- AKSI --}}
                    <td>

                        {{-- ADMIN OUTLET: TERIMA --}}
                        @if(
                            auth()->user()->role == 'user'
                            && $row->status == 'dikirim'
                        )

                            <form
                                action="{{ route('user.distribusi.terima', $row->id) }}"
                                method="POST"
                                style="display:inline"
                            >
                                @csrf
                                @method('PUT')

                                <button class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i> Terima
                                </button>
                            </form>

                        @endif


                        {{-- ADMIN PUSAT: HAPUS --}}
                        @if(auth()->user()->role == 'admin')

                            <form
                                action="{{ route('admin.distribusi.destroy', $row->id) }}"
                                method="POST"
                                style="display:inline"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Hapus distribusi?')"
                                >
                                    <i class="fas fa-trash"></i> Hapus
                                </button>

                            </form>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center">
                        Belum ada distribusi
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>
</div>

@stop
