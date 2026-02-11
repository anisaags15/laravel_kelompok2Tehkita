<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Login | Teh Kita</title>

<link rel="stylesheet"
href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

<link rel="stylesheet"
href="{{ asset('templates/plugins/fontawesome-free/css/all.min.css') }}">

<link rel="stylesheet"
href="{{ asset('templates/dist/css/adminlte.min.css') }}">

</head>

<body class="hold-transition login-page">

<div class="login-box">

<div class="login-logo">
<b>Teh</b>Kita
</div>


<div class="card">

<div class="card-body login-card-body">

<p class="login-box-msg">Silakan Login</p>


@if(session('status'))
<div class="alert alert-success">
{{ session('status') }}
</div>
@endif


@if($errors->any())
<div class="alert alert-danger">
@foreach($errors->all() as $error)
{{ $error }} <br>
@endforeach
</div>
@endif


<form method="POST" action="{{ route('login') }}">
@csrf


<!-- Email -->
<div class="input-group mb-3">

<input type="email"
name="email"
class="form-control"
placeholder="Email"
value="{{ old('email') }}"
required>

<div class="input-group-append">
<div class="input-group-text">
<span class="fas fa-envelope"></span>
</div>
</div>

</div>


<!-- Password -->
<div class="input-group mb-3">

<input type="password"
name="password"
class="form-control"
placeholder="Password"
required>

<div class="input-group-append">
<div class="input-group-text">
<span class="fas fa-lock"></span>
</div>
</div>

</div>


<div class="row">

<div class="col-8">

<div class="icheck-primary">

<input type="checkbox"
id="remember"
name="remember">

<label for="remember">
Remember Me
</label>

</div>

</div>


<div class="col-4">

<button type="submit"
class="btn btn-primary btn-block">

Login

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
