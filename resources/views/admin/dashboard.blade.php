@extends('layouts.admin')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- Total Bahan --}}
    <div class="bg-green-500 text-white p-6 rounded-lg shadow">
        <div class="text-sm">Total Bahan</div>
        <div class="text-2xl font-bold">{{ $totalBahan }}</div>
    </div>

    {{-- Total Outlet --}}
    <div class="bg-blue-500 text-white p-6 rounded-lg shadow">
        <div class="text-sm">Total Outlet</div>
        <div class="text-2xl font-bold">{{ $totalOutlets }}</div>
    </div>

    {{-- Stok Gudang --}}
    <div class="bg-yellow-500 text-white p-6 rounded-lg shadow">
        <div class="text-sm">Stok Gudang</div>
        <div class="text-2xl font-bold">{{ $stokGudang }}</div>
    </div>

    {{-- Distribusi Hari Ini --}}
    <div class="bg-purple-500 text-white p-6 rounded-lg shadow">
        <div class="text-sm">Distribusi Hari Ini</div>
        <div class="text-2xl font-bold">{{ $distribusiHariIni }}</div>
    </div>

</div>
@endsection
