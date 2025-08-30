<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{Session::get('company_name')}} | Monthly Attendance Report</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&amp;display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <div class="row invoice-info">
        <div class="col-12 table-responsive">
            <h3 class="text-center text-bold">{{Session::get('company_name')}}</h3>
            <h3 class="text-center text-bold"> Department: {{($department!=null)?$department->name:'All'}} </h3>
            <h5 class="text-center text-bold"> Salary ID: {{$salary_id}}</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr class="bg-info text-center">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Month</th>
                        <th>Date</th>
                        <th>Gross Salary</th>
                        <th>Allowance</th>
                        <th>Deduction</th>
                        <th>Total Salary</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($salary_data as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->employee->name }}</td> 
                        <td>{{ $item->month }}</td> 
                        <td>{{ date('d/m/Y',strtotime($item->start_date)).' to '.date('d/m/Y',strtotime($item->end_date)) }}</td>                       
                        <td>{{ $item->gross_salary }}</td> 
                        <td>{{ $item->addition }}</td>
                        <td>{{ $item->deduction }}</td>
                        <td>{{ $item->total_salary }}</td>
                        <td>{{ $item->remakrs }}</td>
                    </tr>
                    @endforeach
                </tbody>
              </table>
        </div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html>
