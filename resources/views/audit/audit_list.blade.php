@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Audit List

                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Audit Report
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('audit-list') }}" method="get">
                    <div class="row pb-3">
                        <div class="col-lg-3">
                            <label for="Project">Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="start_date">End Date</label>
                            <input type="date" class="form-control" name="end_date"/>
                        </div>
                        
                        <div class="col-lg-3">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('audit-print?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('audit-pdf?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Type</th>
                                <th>Description</th>
                                <th>Audit Person</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($audit_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->audit_date)) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->type }}</td>
                                <td>{!! $item->description !!}</td>
                                <td>{{ $item->audit_person }}</td>
                                <td>
                                    @if ($item->attachment != null)
                                    <a href="{{asset($item->attachment)}}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file-pdf"></i> File</a>
                                    @endif
                                    <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->audit_date}}','{{$item->project_id}}','{{$item->type}}','{{$item->description}}','{{$item->audit_person}}')" data-target="#modal-edit" class="btn btn-sm btn-info" > <i class="fa fa-pencil"></i> Edit</button>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$audit_data->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Add Modal -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Add Audit Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-audit') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select name="type" id="" class="form-control" required>
                            <option value="">Select One</option>
                            <option value="1">Top Management Objection Report</option>
                            <option value="2">Company Work Order</option>
                            <option value="3">Authority Status</option>
                            <option value="4">Approval History</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-control">
                            <option value="">Select One</option>
                            @foreach ($project_data as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Decription</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" name="audit_date" class="form-control">
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Audit Person</label>
                        <input type="text" name="audit_person" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="attachment" >
                    </div>
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


  
<!-- Edit Modal -->
<div class="modal fade" id="modal-edit">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Update Audit Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('update-audit') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="audit_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Report Type</label>
                        <select name="type" id="type" class="form-control" required>
                            <option value="">Select One</option>
                            <option value="1">Top Management Objection Report</option>
                            <option value="2">Company Work Order</option>
                            <option value="3">Authority Status</option>
                            <option value="4">Approval History</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Project</label>
                        <select name="project_id" id="project_id" class="form-control">
                            <option value="">Select One</option>
                            @foreach ($project_data as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Decription</label>
                        <textarea name="description" id="description" class="form-control"></textarea>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" id="audit_date" name="audit_date" class="form-control">
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Audit Person</label>
                        <input type="text" id="audit_person" name="audit_person" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" name="attachment" >
                    </div>
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
    function load_edit_body(audit_id,audit_date,project_id,type,description,audit_person){
        $('#audit_id').val(audit_id);
        $('#audit_date').val(audit_date);
        $('#project_id').val(project_id);
        $('#type').val(type);
        $('#description').val(description);
        $('#audit_person').val(audit_person);
    }
</script>
@endsection