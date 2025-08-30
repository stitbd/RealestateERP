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
            <h1 class="text-center text-bold">{{Session::get('company_name')}}</h1>
            <h2 class="text-center text-bold">{{$employee_data->name}}</h2>
            <h3 class="text-center text-bold"> Department: {{($employee_data->department!=null)?$employee_data->department->name:'All'}} </h3>
            <h5 class="text-center text-bold"> {{date('d/m/Y',strtotime($start_date))}} To  {{date('d/m/Y',strtotime($end_date))}}</h5>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>In</th>
                        <th>Late</th>
                        <th>Out</th>
                        <th>Work Hour</th>
                        <th>Overtime</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $now        = strtotime($end_date); // or your date as well
                        $your_date  = strtotime($start_date);
                        $datediff   = $now - $your_date;
                        $day_count  = round($datediff / (60 * 60 * 24));
                        $total_present = 0;
                        $total_late = 0;
                        $total_overtime = 0;
                        $total_absent = 0;
                        $total_leave = 0;
                        $total_holiday = 0;
                        $total_work_hour = 0;

                        for($i=0; $i<=$day_count; $i++){
                            $date = date('Y-m-d', strtotime($start_date. ' + '.$i.' days'));
                            $attendance_status = App\Models\EmployeeAttendance::attendance_status($employee_data->id,$date);
                            if($attendance_status != null){
                                $status = 'P';
                                echo '<tr class="bg-sucess">';
                                $total_present++;
                            }
                            else {
                                $leave_status = App\Models\EmployeeAttendance::leave_status($employee_data->id,$date);
                                if($leave_status != null){
                                    $status = 'L';
                                    echo '<tr class="bg-warning">';
                                    $total_leave++;
                                }
                                else{
                                    $holiday_status = App\Models\EmployeeAttendance::holiday_status($date);
                                    if($holiday_status != null){
                                        $status = 'H';
                                        echo '<tr class="bg-info">';
                                        $total_holiday++;
                                    }
                                    else{
                                        $status = 'A';
                                        echo '<tr class="bg-danger">';
                                        $total_absent++;
                                    } 
                                }
                            }

                            
                            echo '<td>'.($i+1).'</td>';
                            echo '<td>'.date('d/m/Y',strtotime($date)).'</td>';
                            echo '<td>'.date('D',strtotime($date)).'</td>';
                            if($attendance_status != null){
                                echo '<td>'.$attendance_status->check_in_time.'</td>';
                                if($attendance_status->check_in_time>$employee_data->schedule->start_time){
                                    $difference = round(abs(strtotime($attendance_status->check_in_time) - strtotime($employee_data->schedule->start_time)) / 3600,2);
                                    echo '<td>'.$difference.' H </td>';
                                    $total_late += $difference;
                                }
                                else{
                                    echo '<td>0 H</td>';
                                }
                                echo '<td>'.$attendance_status->check_out_time.'</td>';
                                $difference = round(abs(strtotime($attendance_status->check_in_time) - strtotime($attendance_status->check_out_time)) / 3600,2);
                                echo '<td>'.$difference.' H </td>';
                                $total_work_hour += $difference;
                                if($attendance_status->check_out_time>$employee_data->schedule->end_time){
                                    $difference = round(abs(strtotime($attendance_status->check_out_time) - strtotime($employee_data->schedule->end_time)) / 3600,2);
                                    echo '<td>'.$difference.' H </td>';
                                    $total_overtime += $difference;
                                }
                                else{
                                    echo '<td>0 H</td>';
                                }
                            }
                            else{
                                echo '<td> - </td>';
                                echo '<td> - </td>';
                                echo '<td> - </td>';
                                echo '<td> - </td>';
                                echo '<td> - </td>';
                            }
                            echo '<td>'.$status.'</td>';
                            echo '</tr>';
                        }
                    @endphp 
                </tbody>
                    
            </table>

            <table class="table table-bordered mt-5 bg-info">
                <thead>
                    <tr>
                        <th colspan="4">Summery</th>
                    </tr>
                    <tr>
                        <th>Total Days</th>
                        <th>{{$day_count}}</th>
                        <th>Total Present</th>
                        <th>{{$total_present}}</th>
                    </tr>
                    <tr>
                        <th>Total Leave</th>
                        <th>{{$total_leave}}</th>
                        <th>Total Holiday</th>
                        <th>{{$total_holiday}}</th>
                    </tr>
                    <tr>
                        <th>Total Absent</th>
                        <th>{{$total_absent}}</th>
                        <th>Total Late</th>
                        <th>{{$total_late}} H</th>
                    </tr>
                    <tr>
                        <th>Total Work Hour</th>
                        <th>{{$total_work_hour}} H</th>
                        <th>Total Overtime</th>
                        <th>{{$total_overtime}} H</th>
                    </tr>
                </thead>
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
