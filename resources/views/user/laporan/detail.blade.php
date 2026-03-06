@extends('layouts.main')

@section('title','Detail Distribusi')

@section('content')

<div class="container-fluid py-4">

<!-- NAVIGASI -->
<div class="mb-3">

<div class="small text-muted mb-1">
Home
</div>

<a href="{{ route('user.laporan.distribusi') }}" class="back-link">
<i class="fas fa-arrow-left me-2"></i>
Kembali ke Distribusi
</a>

</div>


<div class="card shadow-sm border-0">

<!-- HEADER -->
<div class="card-header bg-success text-white">

<h5 class="mb-0 fw-bold">
Detail Distribusi Bulan 
{{ \Carbon\Carbon::createFromFormat('Y-m',$periode)->translatedFormat('F Y') }}
</h5>

</div>


<!-- BODY -->
<div class="card-body p-0">

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="bg-light">
<tr>
<th width="5%">No</th>
<th width="20%">Tanggal Pengiriman</th>
<th width="20%">Outlet</th>
<th>Nama Bahan</th>
<th width="15%">Jumlah</th>
<th width="20%">Tanggal Diterima</th>
</tr>
</thead>

<tbody>

@forelse($distribusi as $i => $item)

<tr>

<td>{{ $i+1 }}</td>

<!-- TANGGAL PENGIRIMAN -->
<td>

<div class="tanggal">
{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y') }}
</div>

<div class="jam">
{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }} WIB
</div>

</td>

<!-- OUTLET -->
<td class="fw-semibold">

<i class="fas fa-store outlet-icon"></i>

{{ $item->outlet->nama_outlet ?? '-' }}

</td>

<!-- BAHAN -->
<td>

<i class="fas fa-box text-secondary me-2"></i>

{{ $item->bahan->nama_bahan ?? '-' }}

</td>

<!-- JUMLAH -->
<td>

<span class="badge bg-success px-3 py-2">

<i class="fas fa-cubes me-1"></i>

{{ number_format($item->jumlah,0,',','.') }}

</span>

</td>

<!-- TANGGAL DITERIMA -->
<td>

@if($item->status == 'diterima')

<div class="tanggal">
{{ \Carbon\Carbon::parse($item->updated_at)->translatedFormat('d M Y') }}
</div>

<div class="jam">
{{ \Carbon\Carbon::parse($item->updated_at)->format('H:i') }} WIB
</div>

@else
-
@endif

</td>

</tr>

@empty

<tr>
<td colspan="6" class="text-center py-5">

<i class="fas fa-box-open fa-2x text-muted mb-2"></i>

<p class="text-muted mb-0">Tidak ada data distribusi</p>

</td>
</tr>

@endforelse

</tbody>

</table>

</div>

</div>


<!-- FOOTER -->
<div class="card-footer bg-white">

<div class="d-flex justify-content-between">

<span class="text-muted small">
Dicetak oleh: {{ auth()->user()->nama }}
</span>

<span class="text-muted small">
{{ now()->translatedFormat('d M Y, H:i') }} WIB
</span>

</div>

</div>

</div>

</div>

@endsection


<style>

.tanggal{
font-weight:600;
font-size:14px;
}

.jam{
font-size:13px;
color:#2563eb;
}

.outlet-icon{
color:#16a34a;
margin-right:6px;
}

/* link panah kembali */
.back-link{
display:inline-block;
font-size:14px;
color:#16a34a;
font-weight:600;
text-decoration:none;
}

.back-link:hover{
text-decoration:underline;
color:#15803d;
}

</style>