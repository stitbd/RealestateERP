@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Manage Employee
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row mb-2 p-2">
                        <div class="col-md-3">
                            <label>Company</label>
                            <select name="company_id" id="company_id" class="form-control" onchange="load_department(this.value); load_section(this.value); load_branch(this.value);">
                                <option value="all">All</option>
                                @foreach ($company as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Department</label>
                            <select name="department_id" id="department_id" class="form-control">
                                <option value="all">All</option>
                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Section</label>
                            <select name="section_id" id="section_id" class="form-control">
                                <option value="all">All</option>
                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Branch Office</label>
                            <select name="branch_id" id="branch_id" class="form-control">
                                <option value="all">All</option>
                                
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Designation</label>
                            <select name="designation_id" id="designation_id" class="form-control">
                                <option value="all">All</option>
                                @foreach ($designation as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Grade</label>
                            <select name="grade_id" id="grade_id" class="form-control">
                                <option value="all">All</option>
                                @foreach ($grade as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Schedule</label>
                            <select name="schedule_id" id="schedule_id" class="form-control">
                                <option value="all">All</option>
                                @foreach ($schedule as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Payment Type</label>
                            <select name="paymnet_type_id" id="paymnet_type_id" class="form-control">
                                <option value="all">All</option>
                                @foreach ($paymentType as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button onclick="search();" class="btn btn-success btn-block">
                                <i class="fa fa-search"></i>
                                Search
                            </button>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info">
                                <th>#</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Section</th>
                                <th>Designation</th>
                                <th>Joining Date</th>
                                <th>Sallary</th>
                                <th>Company</th>
                                <th>Branch</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="details_table_body">
                            @foreach ($employee as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>
                                    @if ($item->department !=  null)
                                        {{ $item->department->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->section !=  null)
                                        {{ $item->section->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->designation !=  null)
                                        {{ $item->designation->name }}
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y',strtotime($item->joining_date)) }}</td>
                                <td>Tk. {{ $item->gross_salary }}</td>
                                <td>
                                    @if ($item->company !=  null)
                                        {{ $item->company->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->branch !=  null)
                                        {{ $item->branch->name }}
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == '1')
                                        <a href="{{ route('change-employee-status',['0',$item->id]) }}" class="text-success text-bold">Active</a>
                                    @else
                                        <a href="{{ route('change-employee-status',['1',$item->id]) }}" class="text-danger text-bold">Inactive</a>
                                    @endif
                                </td>
                                <td>
                                    <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}')" data-target="#modal-view" class="btn btn-sm btn-success"><i class="fa fa-search"></i></button>
                                    <a href="{{ route('edit-employee',[$item->id]) }}" class="btn btn-sm btn-info"><i class="fa fa-edit"></i></a>
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
<div class="modal fade" id="modal-view">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Employee Profile</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="view_modal">
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
  <!-- /.modal -->
  
<script>
    function search(){
        var company_id = $('#company_id').val();
        var department_id = $('#department_id').val();
        var section_id = $('#section_id').val();
        var branch_id = $('#branch_id').val();
        var designation_id = $('#designation_id').val();
        var grade_id = $('#grade_id').val();
        var schedule_id = $('#schedule_id').val();
        var paymnet_type_id = $('#paymnet_type_id').val();

        url = '{{ route("search-employee") }}';
        $.ajax({
          cache   : false,
          type    : "post",
          data    : {
                        company_id,
                        department_id,
                        section_id,
                        branch_id,
                        designation_id,
                        grade_id,
                        schedule_id,
                        paymnet_type_id
                    },
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
              $('#details_table_body').html(response);
          }
      })
    }
    function load_edit_body(employee_id){
        url = '{{ route('load_employee_view', ":employee_id") }}';
        url = url.replace(':employee_id', employee_id);
        $.ajax({
          cache   : false,
          type    : "GET",
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
              $('#view_modal').html(response);
          }
      })
    }

    function load_department(company_id){
        url = '{{ route('load_department_by_company_id', ":company_id") }}';
        url = url.replace(':company_id', company_id);
        //alert(url);
        $.ajax({
          cache   : false,
          type    : "GET",
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
            response_data = JSON.parse(response);
            if(response_data.status == 'success'){
              $('#department_id').html(response_data.options);
              
            }
          }
      })
    }
    function load_branch(company_id){
        url = '{{ route('load_branch_by_company_id', ":company_id") }}';
        url = url.replace(':company_id', company_id);
        //alert(url);
        $.ajax({
          cache   : false,
          type    : "GET",
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
            response_data = JSON.parse(response);
            if(response_data.status == 'success'){
              $('#branch_id').html(response_data.options);
            }
          }
      })
    }
    function load_section(company_id){
        url = '{{ route('load_section_by_company_id', ":company_id") }}';
        url = url.replace(':company_id', company_id);
        //alert(url);
        $.ajax({
          cache   : false,
          type    : "GET",
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
            response_data = JSON.parse(response);
            if(response_data.status == 'success'){
              $('#section_id').html(response_data.options);
              
            }
          }
      })
    }
</script>
@endsection

