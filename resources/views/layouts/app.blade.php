<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="base_url" content="{{ url('/') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>  {{ Session::get('company_name') }}  </title>

    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}">
    @stack('style_css')
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini {{ isset($collapse) ? $collapse : ''}}">
<div class="wrapper">

    @include('layouts.admin_header')

        @include('layouts.admin_user_sidebar')

        {{-- @if(auth()->guard('admin')->user()->type == 1 )
            @include('layouts.admin_layout.admin_user_sidebar')
        @elseif(auth()->guard('admin')->user()->type == 2 )
            @include('layouts.admin_layout.admin_user_sidebar')
        @else
            @include('layouts.admin_layout.admin_user_sidebar')
        @endif --}}

    <div class="content-wrapper">
      @include('layouts.admin_session_alert')
      @yield('content')
    </div>
    @include('layouts.admin_footer')
</div>
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('js/admin_js/adminlte.min.js') }}"></script>
<script src="{{ asset('js/admin_js/main.js') }}"></script>
@stack('script_js')

@if(session()->has('message'))
    <script>
        @if(session('type') == 'success')
            toastr.success('{{ session('message') }}','',10000);
        @endif

        @if(session('type') == 'danger')
            toastr.error('{{ session('message') }}','',10000);
        @endif
    </script>
@endif

</body>
</html>
<script>
    let idleTime = 0;
    window.setInterval(() => {
        idleTime++;
        if (idleTime >= 20) { 
            window.location.href = "{{ route('login') }}";
        }
    }, 60000);

    document.addEventListener('mousemove', resetIdleTime);
    document.addEventListener('keypress', resetIdleTime);

    function resetIdleTime() {
        idleTime = 0;
    }

</script>
