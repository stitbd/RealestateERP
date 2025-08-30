@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Leave Entry
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{route('save-leave')}}" method="post" enctype="multipart/form-data"> 
                        @csrf
                        <div class="form-group">
                            <label for="employee">Employee</label>
                            <select name="employee_id" id="employee" class="form-control">
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                <option value="{{$employee->id}}">{{$employee->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="leave_type">Leave type</label>
                            <select name="leave_type_id" id="leave_type" class="form-control">
                                <option value="">Select Leave Type</option>
                                @foreach($leave_types as $leave_type)
                                <option value="{{$leave_type->id}}">{{$leave_type->leave_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date</label>
                            <input type="date" name="start_date" id="start_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="end_date">End Date</label>
                            <input type="date" name="end_date" id="end_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="file">Attachment</label>
                            <input type="file" name="attachment" id="file" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
</div>





@endsection

