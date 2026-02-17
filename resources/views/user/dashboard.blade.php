@extends('layouts.main')

@section('title', 'Dashboard Outlet')
@section('page', 'Dashboard Outlet')

@section('content')
<div class="row">

    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $totalStok }}</h3>
                <p>Stok Outlet</p>
            </div>
            <div class="icon">
                <i class="fas fa-box"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $pemakaian }}</h3>
                <p>Pemakaian Bahan</p>
            </div>
            <div class="icon">
                <i class="fas fa-edit"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $distribusi }}</h3>
                <p>Distribusi</p>
            </div>
            <div class="icon">
                <i class="fas fa-history"></i>
            </div>
        </div>
    </div>

</div>
@endsection