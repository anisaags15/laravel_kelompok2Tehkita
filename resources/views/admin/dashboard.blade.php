@extends('layouts.admin')

@section('content')
<div class="cards-container mt-4">

    <div class="card bg-green-500">
        <div class="text-sm">Total Bahan</div>
        <div class="text-2xl font-bold">{{ $totalBahan }}</div>
    </div>

    <div class="card bg-blue-500">
        <div class="text-sm">Total Outlet</div>
        <div class="text-2xl font-bold">{{ $totalOutlets }}</div>
    </div>

    <div class="card bg-yellow-500">
        <div class="text-sm">Stok Gudang</div>
        <div class="text-2xl font-bold">{{ $stokGudang }}</div>
    </div>

    <div class="card bg-purple-500">
        <div class="text-sm">Distribusi Hari Ini</div>
        <div class="text-2xl font-bold">{{ $distribusiHariIni }}</div>
    </div>

</div>
@endsection
