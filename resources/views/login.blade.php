<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Warung Sate | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/layout/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/layout/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/layout/dist/css/adminlte.min.css">
  <style>
    body {
        background-image: url("/layout/img/bg-login.jpeg");
        background-repeat: no-repeat;
        background-size: cover;
    }
  </style>
</head>
<body class="hold-transition login-page">
@if (Session::get('errors'))
      <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
    </div>
  @endif
  @if ($msg = Session::get('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      {{ $msg }}
    </div>
    @endif
    @if ($msg = Session::get('error'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {{ $msg }}
    </div>
  @endif
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card" style="border-radius: 15px;">
    <div class="card-body login-card-body">
    <div class="login-logo">
    <img src="/layout/img/logo.jpeg" alt="" class="w-50">
  </div>
      <h4 class="login-box-msg">LOGIN</h4>

      <form action="/login" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="email" class="form-control" placeholder="Email" name="email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" placeholder="Password" name="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4"></div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-warning btn-block">Login</button>
          </div>
          <div class="col-4"></div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1 mt-1">
        <a href="#" style="color: orange;" class="text-center">Lupa Password? Klik disini</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="/layout/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/layout/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/layout/dist/js/adminlte.min.js"></script>
</body>
</html>
