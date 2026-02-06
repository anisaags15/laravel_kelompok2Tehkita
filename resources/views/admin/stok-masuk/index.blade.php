@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Stok Masuk</h2>
    <a href="{{ route('admin.stok-masuk.create') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg shadow hover:bg-green-600">Tambah Stok</a>
</div>

<div class="overflow-x-auto bg-white rounded-lg shadow">
    <table class="min-w-full table-auto">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Tanggal</th>
                <th class="px-4 py-2 text-left">Bahan</th>
                <th class="px-4 py-2 text-left">Jumlah</th>
                <th class="px-4 py-2 text-left">Outlet</th>
                <th class="px-4 py-2 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stokMasuks as $stok)
            <tr class="border-b">
                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                <td class="px-4 py-2">{{ $stok->tanggal }}</td>
                <td class="px-4 py-2">{{ $stok->bahan->nama }}</td>
                <td class="px-4 py-2">{{ $stok->jumlah }}</td>
                <td class="px-4 py-2">{{ $stok->outlet->nama }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('admin.stok-masuk.edit', $stok->id) }}" class="text-blue-500 hover:underline">Edit</a>
                    <form action="{{ route('admin.stok-masuk.destroy', $stok->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-2 text-center text-gray-400">Data stok belum ada.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
