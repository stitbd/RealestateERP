<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ Session::get('company_name') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
  <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ public_path('plugins/fontawesome-free/css/all.min.css') }}"> 
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ public_path('css/admin_css/adminlte.min.css') }}"> 
  <style>
      @media print {
            table {
                border: solid #000 !important;
                border-width: 1px 0 0 1px !important;
            }
            th, td {
                border: solid #000 !important;
                border-width: 0 1px 1px 0 !important;
            }
        }

        @media all{
        th{
                border:1px solid black;
        }

        table{
            border-collapse: collapse;
        }

        tr{
            border:1px solid black;   
        }

        td{
            border:1px solid black;   
        }

        }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header text-center" style="text-align:center !important">
            {{ Session::get('company_name') }}
        </h2>
      </div>
      <!-- /.col -->
    </div>
    

    <!-- Table row -->
    <div class="row">
      <div class="col-12">
        @yield('content') 
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    
    <h6 style="text-align:center !important;">Print Date: {{date('d/m/Y')}}</h6>
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
  window.addEventListener("load", window.print());
  window.onafterprint = window.close;
</script>
</body>
</html>
