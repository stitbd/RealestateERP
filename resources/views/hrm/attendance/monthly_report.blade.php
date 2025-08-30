@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                       Attendance Report
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="0">Select One</option>
                                    @foreach ($department_data as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" class="form-control" value="{{ date('Y-m-d') }}" name="attendance_date" required>
                        </div>

                        <div class="col-lg-3">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}" name="attendance_date" required>
                        </div>

                        <div class="col-lg-3">
                            <label for="attendance_date">Action</label> <br/>
                            <button type="button" onclick="select_monthly_report()" class="btn btn-primary w-100"><i class="fa fa-search"></i> Search Report</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="monthly_report">

                        </div>
                    </div>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
</div>


<script>
    function select_monthly_report(){
        var department_id   = $('#department_id').val();
        var start_date      = $('#start_date').val();
        var end_date        = $('#end_date').val();

        url = '{{ url('monthly_attendance_report_ajax/:department_id/:start_date/:end_date') }}';
        url = url.replace(':department_id', department_id);
        url = url.replace(':start_date', start_date);
        url = url.replace(':end_date', end_date);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#monthly_report').html(response);
            }
        })
    }
</script>
@endsection

