@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                       Attendance List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{ route('attendance-list') }}" method="get" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-4">
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

                            <div class="col-lg-4">
                                <label for="attendance_date">Attendance Date</label>
                                <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="attendance_date" required>
                            </div>

                            <div class="col-lg-4">
                                <label for="attendance_date">Action</label> <br/>
                                <button type="submit" class="btn btn-primary w-100"><i class="fa fa-search"></i> Search Attendance</button>
                            </div>
                        </div>
                    </form>

                    <table class="table table-bordered table-striped mt-5">
                        <thead class="bg-info">
                            <tr>
                                <th>#</th>
                                <th>Employee Name</th>
                                <th>Department</th>
                                <th>Attendance Date</th>
                                <th>In Time</th>
                                <th>Out Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendance_data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->employee->name }}</td>
                                    <td>{{ $item->employee->department->name }}</td>
                                    <td>{{ date('d/m/Y',strtotime($item->attendance_date)) }}</td>
                                    <td>{{ $item->check_in_time }}</td>
                                    <td>{{ $item->check_out_time }}</td>
                                    <td>
                                        <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->attendance_date}}','{{$item->check_in_time}}','{{$item->check_out_time}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
</div>



 
<!-- Edit Modal -->
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Update Attendance</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('attendance-update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="attendance_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-4">
                    <label for="attendance_date">Attendance Date</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}" id="attendance_date" name="attendance_date" required>
                </div>
                <div class="col-lg-4">
                    <label for="publish_date">In Time<small class="text-danger">(Time in 24 hours)</small></label>
                    
                    <input type="text" id="attendance_time" class="form-control" value="{{ date('H:i:s') }}" name="check_in_time">
                </div>

                <div class="col-lg-4">
                    <label for="publish_date">Out Time<small class="text-danger">(Time in 24 hours)</small></label>
                    
                    <input type="text" id="attendance_out_time" class="form-control" value="{{ date('H:i:s') }}" name="check_out_time">
                    
                </div>
        
                
            </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
  <!-- /.modal -->


    
<script>
    function load_edit_body (attendance_id,attendance_date,attendance_time,attendance_out_time){
        $('#attendance_id').val(attendance_id);
        $('#attendance_date').val(attendance_date);
        $('#attendance_time').val(attendance_time);
        $('#attendance_out_time').val(attendance_out_time);
    }
</script>
@endsection

