@extends('layouts.main')

@section('title', 'Dashboard Admin')
@section('page', 'Dashboard Admin')

@section('content')

<div class="row">

  <!-- Outlet -->
  <div class="col-lg-3 col-6">

    <div class="small-box bg-info">

      <div class="inner">
        <h3>{{ $outlet ?? 0 }}</h3>
        <p>Outlet</p>
      </div>

      <div class="icon">
        <i class="fas fa-store"></i>
      </div>

      <a href="{{ route('admin.outlet.index') }}" class="small-box-footer">
        Lihat Data <i class="fas fa-arrow-circle-right"></i>
      </a>

    </div>

  </div>


  <!-- Bahan -->
  <div class="col-lg-3 col-6">

    <div class="small-box bg-success">

      <div class="inner">
        <h3>{{ $bahan ?? 0 }}</h3>
        <p>Bahan</p>
      </div>

      <div class="icon">
        <i class="fas fa-box"></i>
      </div>

      <a href="{{ route('admin.bahan.index') }}" class="small-box-footer">
        Lihat Data <i class="fas fa-arrow-circle-right"></i>
      </a>

    </div>

  </div>


  <!-- Stok Masuk -->
  <div class="col-lg-3 col-6">

    <div class="small-box bg-warning">

      <div class="inner">
        <h3>{{ $stokMasuk ?? 0 }}</h3>
        <p>Stok Masuk</p>
      </div>

      <div class="icon">
        <i class="fas fa-arrow-down"></i>
      </div>

      <a href="{{ route('admin.stok-masuk.index') }}" class="small-box-footer">
        Lihat Data <i class="fas fa-arrow-circle-right"></i>
      </a>

    </div>

  </div>


  <!-- Distribusi -->
  <div class="col-lg-3 col-6">

    <div class="small-box bg-danger">

      <div class="inner">
        <h3>{{ $distribusi ?? 0 }}</h3>
        <p>Distribusi</p>
      </div>

      <div class="icon">
        <i class="fas fa-truck"></i>
      </div>

      <a href="{{ route('admin.distribusi.index') }}" class="small-box-footer">
        Lihat Data <i class="fas fa-arrow-circle-right"></i>
      </a>

    </div>

  </div>

</div>


<!-- Welcome Card -->
<div class="row">

  <div class="col-12">

    <div class="card">

      <div class="card-header bg-primary">
        <h3 class="card-title text-white">
          Selamat Datang
        </h3>
      </div>

      <div class="card-body">

        <h5>Halo, {{ auth()->user()->name }} 👋</h5>

        <p>
          Kamu login sebagai <b>Administrator</b>.
        </p>

        <p>
          Gunakan menu di samping untuk mengelola data sistem.
        </p>

      </div>

    </div>

  </div>

</div>

@endsection
