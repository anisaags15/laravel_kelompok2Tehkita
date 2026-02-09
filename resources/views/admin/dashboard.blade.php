@extends('adminlte::page')

@section('title', 'Dashboard Admin')

@section('content_header')
    <h1>Dashboard Admin</h1>
@stop

@section('content')

<div class="row">

    <div class="col-lg-3 col-6">
        <x-adminlte-small-box 
            title="{{ $totalBahan ?? 0 }}" 
            text="Total Bahan"
            icon="fas fa-box" 
            theme="info"/>
    </div>

    <div class="col-lg-3 col-6">
        <x-adminlte-small-box 
            title="{{ $totalOutlet ?? 0 }}" 
            text="Total Outlet"
            icon="fas fa-store" 
            theme="success"/>
    </div>

    <div class="col-lg-3 col-6">
        <x-adminlte-small-box 
            title="{{ $stokGudang ?? 0 }}" 
            text="Stok Gudang"
            icon="fas fa-warehouse" 
            theme="warning"/>
    </div>

    <div class="col-lg-3 col-6">
        <x-adminlte-small-box 
            title="{{ $distribusiHariIni ?? 0 }}" 
            text="Distribusi Hari Ini"
            icon="fas fa-truck" 
            theme="danger"/>
    </div>

</div>

{{-- STOK KRITIS --}}
<div class="row mt-3">
<div class="col-md-6">

<x-adminlte-card title="Stok Hampir Habis" theme="warning">

@php
    $stokKritis = $stokKritis ?? [];
@endphp

@if(count($stokKritis) > 0)
<ul class="list-group">
@foreach($stokKritis as $item)
<li class="list-group-item">
    {{ $item }}
</li>
@endforeach
</ul>
@else
<p class="text-muted">Semua stok aman</p>
@endif

</x-adminlte-card>
</div>

{{-- AKTIVITAS --}}
<div class="col-md-6">

<x-adminlte-card title="Aktivitas Terakhir" theme="info">

@php
    $aktivitas = $aktivitas ?? [];
@endphp

@if(count($aktivitas) > 0)
<table class="table table-sm">
@foreach($aktivitas as $a)
<tr>
    <td>{{ $a }}</td>
</tr>
@endforeach
</table>
@else
<p class="text-muted">Belum ada aktivitas</p>
@endif

</x-adminlte-card>
</div>
</div>

{{-- GRAFIK --}}
<div class="row mt-3">
<div class="col-md-12">

<x-adminlte-card title="Grafik Pemakaian Bahan">

<canvas id="grafik"></canvas>

</x-adminlte-card>

</div>
</div>

@stop


@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('grafik');

// grafik dummy dulu (AMAN)
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Teh Black', 'Gula', 'Cup'],
        datasets: [{
            label: 'Pemakaian',
            data: [10, 5, 8],
        }]
    }
});
</script>
@stop
