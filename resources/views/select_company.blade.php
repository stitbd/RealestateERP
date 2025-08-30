<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Real Estate Company</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo" style="font-size: 20px; font-weight: 900;">
       @php
                $system = \App\Models\SystemSetting::first();
                $logo = $system ? asset($system->logo) : '';
            @endphp
            <img src="{{ $logo }}" width="150"><br>
        Select your required company
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">


        <div class="input-group mb-3">
            @foreach ($companies as $item)
            <a href="{{ route('select-company',$item->id) }}" class="btn btn-info btn-block mb-2" >

                <i class="fa fa-arrow-right"></i>
                <!-- <img src="{{ asset('upload_images/company_logo/'.$item->logo) }}"  width="50px"/> -->
                {{$item->name}}
            </a>
            @endforeach

        </div>




    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('js/admin_js/adminlte.min.js') }}"></script>
</body>
</html>


