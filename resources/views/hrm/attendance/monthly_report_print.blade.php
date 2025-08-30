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
            <table class="table table-bordered table-striped" style="width: 100% !important">
                <thead class="bg-info">
                    <tr>
                        <th colspan="500">
                            <h3 class="text-center text-bold">{{Session::get('company_name')}}</h3>
                            <h3 class="text-center text-bold"> Department: {{($department!=null)?$department->name:'All'}} </h3>
                            <h5 class="text-center text-bold"> {{date('d/m/Y',strtotime($start_date))}} To  {{date('d/m/Y',strtotime($end_date))}}</h5>
                        </th>
                    </tr>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        @php
                            $now = strtotime($end_date); // or your date as well
                            $your_date = strtotime($start_date);
                            $datediff = $now - $your_date;
                            $day_count = round($datediff / (60 * 60 * 24));
                            
                            for($i=0; $i<=$day_count; $i++){
                                $date = date('Y-m-d', strtotime($start_date. ' + '.$i.' days'));
                                echo '<th>'.date('d M Y',strtotime($date)).'</th>';
                            }
                        @endphp 
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee_data as $item)
                        <tr>
                            <th>{{ $item->id }}</th>
                            <th>{{ $item->name }}</th>
                            @php
                                for($i=0; $i<=$day_count; $i++){
                                    $date = date('Y-m-d', strtotime($start_date. ' + '.$i.' days'));
            
                                    $attendance_status = App\Models\EmployeeAttendance::attendance_status($item->id,$date);
                                    if($attendance_status != null){
                                        echo '<td class="bg-success">P</td>';
                                    }
                                    else {
                                        $leave_status = App\Models\EmployeeAttendance::leave_status($item->id,$date);
                                        if($leave_status != null){
                                            echo '<td class="bg-warning">L</td>';
                                        }
                                        else{
                                            $holiday_status = App\Models\EmployeeAttendance::holiday_status($date);
                                            if($holiday_status != null){
                                                echo '<td class="bg-info">H</td>';
                                            }
                                            else{
                                                echo '<td class="bg-danger">A</td>';
                                            } 
                                        }
                                    }
                                }
                            @endphp 
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
