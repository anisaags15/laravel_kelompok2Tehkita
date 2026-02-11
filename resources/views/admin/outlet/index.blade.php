@extends('layouts.main')

@section('title', 'Data Outlet')

@section('content')


<div class="card">

    <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Daftar Outlet</h3>

        <a href="{{ route('admin.outlet.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Outlet
        </a>
    </div>

    <div class="card-body">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-striped">

            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Nama Outlet</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Admin Outlet</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                @forelse($outlets as $outlet)

                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $outlet->nama_outlet }}</td>
                    <td>{{ $outlet->alamat }}</td>
                    <td>{{ $outlet->no_hp }}</td>

                    <td>
                        @if($outlet->users->count())
                            @foreach($outlet->users as $user)
                                <span class="badge badge-info">
                                    {{ $user->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="badge badge-secondary">
                                Belum ada admin
                            </span>
                        @endif
                    </td>

                    <td class="text-center">

                        <a href="{{ route('admin.outlet.edit', $outlet->id) }}"
                           class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>

                        <form action="{{ route('admin.outlet.destroy', $outlet->id) }}"
                              method="POST"
                              class="d-inline"
                              onsubmit="return confirm('Yakin hapus outlet ini?')">

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
                    <td colspan="6" class="text-center">
                        Data outlet belum ada
                    </td>
                </tr>

                @endforelse

            </tbody>

        </table>

    </div>
</div>

@stop
