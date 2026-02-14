@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h3>Notifikasi</h3>

    @if($stokAlert->isEmpty() && $pemakaianHariIni->isEmpty())
        <div class="alert alert-info">Tidak ada notifikasi saat ini.</div>
    @endif

    @foreach($stokAlert as $item)
        <div class="alert alert-warning">
            Stok <strong>{{ $item->bahan->nama ?? 'Bahan' }}</strong> tersisa {{ $item->stok }}.
        </div>
    @endforeach

    @foreach($pemakaianHariIni as $item)
        <div class="alert alert-success">
            Pemakaian <strong>{{ $item->bahan->nama ?? 'Bahan' }}</strong>: {{ $item->jumlah }}
        </div>
    @endforeach
</div>
@endsection
