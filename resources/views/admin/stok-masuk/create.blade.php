@extends('layouts.admin')

@section('content')
<h2 class="text-2xl font-bold mb-6">Tambah Stok Masuk</h2>

<form action="{{ route('admin.stok-masuk.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow max-w-md">
    @csrf
    <div class="mb-4">
        <label for="tanggal" class="block mb-1 font-medium">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="mb-4">
        <label for="bahan_id" class="block mb-1 font-medium">Bahan</label>
        <select name="bahan_id" id="bahan_id" class="w-full border rounded px-3 py-2" required>
            @foreach($bahans as $bahan)
                <option value="{{ $bahan->id }}">{{ $bahan->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-4">
        <label for="jumlah" class="block mb-1 font-medium">Jumlah</label>
        <input type="number" name="jumlah" id="jumlah" class="w-full border rounded px-3 py-2" required>
    </div>
    <div class="mb-4">
        <label for="outlet_id" class="block mb-1 font-medium">Outlet</label>
        <select name="outlet_id" id="outlet_id" class="w-full border rounded px-3 py-2" required>
            @foreach($outlets as $outlet)
                <option value="{{ $outlet->id }}">{{ $outlet->nama }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600">Simpan</button>
    <a href="{{ route('admin.stok-masuk.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
</form>
@endsection
