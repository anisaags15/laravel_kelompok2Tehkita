@extends('layouts.main')

@section('content')
{{-- ZONA ATAS: Ringkasan --}}
<div class="content-header p-0" style="margin-top: -25px;">
    <div class="container-fluid">
        <div class="row pt-0 pb-3">
            <div class="col-sm-12">
                <h1 class="m-0 font-weight-bold text-dark" style="letter-spacing: -1.5px; font-size: 2.2rem;">
                    <i class="fas fa-microscope text-primary mr-2"></i>Pusat Kendali Waste
                </h1>
                <p class="text-muted small mb-0 mt-1">Monitoring laporan kerusakan bahan baku dari seluruh outlet cabang.</p>
            </div>
        </div>
    </div>
</div>

<section class="content mt-2">
    <div class="container-fluid">
        {{-- Summary Cards (Atas) --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-danger text-white">
                    <div class="card-body">
                        <h6 class="small font-weight-bold">PENDING REVIEW</h6>
                        <h2 class="mb-0 font-weight-bold">{{ $totalPending }} <small>Laporan</small></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-white">
                    <div class="card-body">
                        <h6 class="small font-weight-bold text-muted">TOTAL WASTE (BULAN INI)</h6>
                        <h2 class="mb-0 font-weight-bold text-dark">{{ $totalWaste }} <small>Item</small></h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- ZONA BAWAH: Tabel Detail --}}
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-header bg-white border-bottom-0 pt-4">
                        <h3 class="card-title font-weight-bold"><i class="fas fa-list mr-2"></i>Daftar Masuk Kerusakan</h3>
                        <div class="card-tools">
                             {{-- Tempat Filter nanti --}}
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-items-center mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0 pl-4">OUTLET</th>
                                        <th class="border-0">ITEM</th>
                                        <th class="border-0 text-center">JUMLAH</th>
                                        <th class="border-0">ALASAN</th>
                                        <th class="border-0">STATUS</th>
                                        <th class="border-0 pr-4">AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allWastes as $w)
                                    <tr>
                                        <td class="pl-4">
                                            <span class="font-weight-bold text-dark">{{ $w->outlet->nama_outlet }}</span>
                                        </td>
                                        <td>{{ $w->bahan->nama_bahan }}</td>
                                        <td class="text-center font-weight-bold text-danger">{{ $w->jumlah }} {{ $w->bahan->satuan }}</td>
                                        <td><small class="badge badge-light border">{{ $w->keterangan }}</small></td>
                                        <td>
                                            @if($w->status == 'pending')
                                                <span class="badge badge-warning">Menunggu</span>
                                            @else
                                                <span class="badge badge-success text-xs">Verified</span>
                                            @endif
                                        </td>
                                        <td class="pr-4">
                                            <button class="btn btn-sm btn-primary shadow-sm rounded-pill px-3">Verifikasi</button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection