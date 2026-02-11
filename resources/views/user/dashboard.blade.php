@extends('layouts.main')

@section('title', 'Dashboard User')
@section('page', 'Dashboard User')

@section('content')

<div class="row">

  <!-- Card 1 -->
  <div class="col-lg-4 col-6">

    <div class="small-box bg-info">

      <div class="inner">
        <h3>Welcome</h3>
        <p>User Panel</p>
      </div>

      <div class="icon">
        <i class="fas fa-user"></i>
      </div>

    </div>

  </div>


  <!-- Card 2 -->
  <div class="col-lg-4 col-6">

    <div class="small-box bg-success">

      <div class="inner">
        <h3>Data</h3>
        <p>Lihat Data</p>
      </div>

      <div class="icon">
        <i class="fas fa-database"></i>
      </div>

    </div>

  </div>


  <!-- Card 3 -->
  <div class="col-lg-4 col-6">

    <div class="small-box bg-warning">

      <div class="inner">
        <h3>Profile</h3>
        <p>Akun Saya</p>
      </div>

      <div class="icon">
        <i class="fas fa-id-card"></i>
      </div>

    </div>

  </div>

</div>

@endsection
