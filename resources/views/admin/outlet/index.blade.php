@extends('adminlte::page')

@section('title', 'Data Outlet')

@section('content_header')
    <h1>Data Outlet</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('admin.outlet.create') }}" class="btn btn-primary">Tambah Outlet</a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-striped" id="table-outlet">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Outlet</th>
                    <th>Alamat</th>
                    <th>No HP</th>
                    <th>Admin Outlet</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($outlets as $outlet)
                <tr>
                    <td>{{ $loop->iteration }}</td> 
                    <td>{{ $outlet->nama_outlet }}</td>
                    <td>{{ $outlet->alamat }}</td>
                    <td>{{ $outlet->no_hp ?? '-' }}</td>
                    <td>
                        @if($outlet->users->count())
                            @foreach($outlet->users as $admin)
                                {{ $admin->name }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.outlet.edit', $outlet->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <form action="{{ route('admin.outlet.destroy', $outlet->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus outlet ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@stop

@section('js')
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#table-outlet').DataTable();
    });
</script>
@stop
