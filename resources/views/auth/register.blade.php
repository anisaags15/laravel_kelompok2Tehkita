<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Register | Teh Kita</title>

<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<link rel="stylesheet"
href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">

<link rel="stylesheet"
href="{{ asset('templates/dist/css/adminlte.min.css') }}">

</head>

<body class="hold-transition register-page">

<div class="register-box">

<div class="register-logo">
<b>Teh</b>Kita
</div>

<div class="card">
<div class="card-body register-card-body">

<p class="login-box-msg">Daftar Akun Baru</p>


{{-- ERROR --}}
@if($errors->any())
<div class="alert alert-danger">
@foreach($errors->all() as $error)
{{ $error }} <br>
@endforeach
</div>
@endif


<form method="POST" action="{{ route('register') }}">
@csrf


<!-- Nama -->
<div class="input-group mb-3">
<input type="text" name="name" class="form-control"
placeholder="Nama Lengkap"
value="{{ old('name') }}" required>

<div class="input-group-append">
<div class="input-group-text">
<i class="fas fa-user"></i>
</div>
</div>
</div>


<!-- Email -->
<div class="input-group mb-3">
<input type="email" name="email" class="form-control"
placeholder="Email"
value="{{ old('email') }}" required>

<div class="input-group-append">
<div class="input-group-text">
<i class="fas fa-envelope"></i>
</div>
</div>
</div>


<!-- Username -->
<div class="input-group mb-3">
<input type="text" name="username" class="form-control"
placeholder="Username"
value="{{ old('username') }}" required>

<div class="input-group-append">
<div class="input-group-text">
<i class="fas fa-user-tag"></i>
</div>
</div>
</div>


<!-- No HP -->
<div class="input-group mb-3">
<input type="text" name="no_hp" class="form-control"
placeholder="No HP"
value="{{ old('no_hp') }}" required>

<div class="input-group-append">
<div class="input-group-text">
<i class="fas fa-phone"></i>
</div>
</div>
</div>


<!-- Outlet -->
<div class="input-group mb-3">
<select name="outlet_id" class="form-control" required>
<option value="">-- Pilih Outlet --</option>

@foreach($outlets as $outlet)
<option value="{{ $outlet->id }}">
{{ $outlet->nama_outlet }}
</option>
@endforeach

</select>
</div>


<!-- Password -->
<div class="input-group mb-3">
<input type="password" name="password"
class="form-control"
placeholder="Password" required>

<div class="input-group-append">
<div class="input-group-text">
<i class="fas fa-lock"></i>
</div>
</div>
</div>


<!-- Confirm -->
<div class="input-group mb-3">
<input type="password"
name="password_confirmation"
class="form-control"
placeholder="Ulangi Password" required>

<div class="input-group-append">
<div class="input-group-text">
<i class="fas fa-lock"></i>
</div>
</div>
</div>


<div class="row">

<div class="col-8">
<a href="{{ route('login') }}">
Sudah punya akun?
</a>
</div>

<div class="col-4">
<button type="submit"
class="btn btn-primary btn-block">
Register
</button>
</div>

</div>

</form>

</div>
</div>
</div>


<script src="{{ asset('templates/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('templates/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
