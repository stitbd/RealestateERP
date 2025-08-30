@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Salary Generate  
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-lg-3">
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="all">ALL</option>
                                    @foreach ($department_data as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3">
                            <label for="start_date">Month</label>
                            <input type="month" id="month" class="form-control" value="{{ date('Y-m') }}" name="month" required>
                        </div>

                        <div class="col-lg-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" id="start_date" class="form-control" value="{{ date('Y-m-d') }}" name="start_date" required>
                        </div>

                        <div class="col-lg-2">
                            <label for="end_date">End Date</label>
                            <input type="date" id="end_date" class="form-control" value="{{ date('Y-m-d') }}" name="end_date" required>
                        </div>

                        <div class="col-lg-2">
                            <label for="attendance_date">Action</label> <br/>
                            <button type="button" onclick="select_salary_details()" class="btn btn-primary w-100"><i class="fa fa-search"></i> Search Report</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" id="salary_details">

                        </div>
                    </div>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
</div>


<script>
    function salary_calculate(id) {
        var gross_salary = $('#gross_salary_'+id).val();
        var addition = $('#addition_'+id).val();
        var deduction = $('#deduction_'+id).val();
        var total_salary = parseInt(gross_salary) + parseInt(addition) - parseInt(deduction);
        $('#total_salary_'+id).val(total_salary);
        var total_salary = 0;
        $('.total_salary').each(function(){
            total_salary += parseInt($(this).val());
        });

        $('#total_salary').html(total_salary);
    }

    function select_salary_details(){
        var department_id       = $('#department_id').val();
        var start_date          = $('#start_date').val();
        var end_date            = $('#end_date').val();
        var month               = $('#month').val();

        url = '{{ url('select_salary_details/:department_id/:start_date/:end_date/:month') }}';
        url = url.replace(':department_id', department_id);
        url = url.replace(':start_date', start_date);
        url = url.replace(':end_date', end_date);
        url = url.replace(':month', month);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#salary_details').html(response);
            }
        })
    }
</script>
@endsection

