@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                       Attendance Entry
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{ route('attendance-save') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Employee</label>
                                    <select name="employee_id" class="form-control">
                                        <option value="">Select One</option>
                                        @foreach ($employee as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Comment</label>
                                    <input type="text" name="comment" class="form-control">
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <label for="attendance_date">Attendance Date</label>
                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="attendance_date" required>
                            </div>
                            <div class="col-lg-4">
                                <label for="publish_date">In Time<small class="text-danger">(Time in 24 hours)</small></label>
                                <br/>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <input type="text" id="time" class="form-control" value="{{ date('H:i:s') }}" name="attendance_time">
                                    </div>
                                    <div class="col-lg-4">
                                        <button type="button" class="btn btn-danger" onclick="stopWatch()">Stop</button>
                                    </div>
                                </div>
                                
                            </div>

                            <div class="col-lg-4">
                                <label for="publish_date">Out Time<small class="text-danger">(Time in 24 hours)</small></label>
                                <br/>
                                <div class="row">
                                    <div class="col-lg-8">
                                        <input type="text" id="out_time" class="form-control" value="{{ date('H:i:s') }}" name="attendance_out_time">
                                    </div>
                                   
                                </div>
                                
                            </div>
                          
                            <button type="submit" class="btn btn-primary w-100 mt-5"><i class="fa fa-check"></i> Save Attendance</button>
                        </div>
                    </form>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
</div>

<script>
    var myVar = setInterval(myTimer, 1000);
    
    function myTimer() {
        var d = new Date();
        var time =  [
                    /* d.getFullYear(),
                    '-',
                    d.getMonth() + 1,
                    '-',
                    d.getDate(),
                    ' ', */
                    d.getHours(),
                    ':',
                    d.getMinutes(),
                    ':',
                    d.getSeconds()
                ].join('');
    
                document.getElementById("time").value = time;
                document.getElementById("out_time").value = time;
        
        }
    
    function stopWatch() {
        //if($('#stop').is(":checked")){
            clearInterval(myVar);
        /* }else{
            setInterval(myTimer, 1000);
        } */
    }
    
</script>

@endsection

