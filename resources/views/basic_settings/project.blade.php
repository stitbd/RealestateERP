@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Project
                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Project
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('project-print')}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('project-pdf')}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                  <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info text-center">
                            <th>ID</th>
                            <th>Company</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Description</th>
                            <th>Authority</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Project Amount</th>
                            <th>Estimated Cost</th>
                            <th>Estimated Profit</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($project_data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->company->name }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->location }}</td>
                            <td>{{ $item->description }}</td>
                            <td>{{ $item->authority }}</td>
                            <td>{{ date('d/m/Y',strtotime($item->start_date)) }}</td>
                            <td>{{ date('d/m/Y',strtotime($item->end_date)) }}</td>
                            <td>{{ $item->project_amount }}</td>
                            <td>{{ $item->estimated_cost }}</td>
                            <td>{{ $item->estimated_profit }}</td>
                            <td>
                                @if ($item->status == '1')
                                    <span class="btn btn-block btn-outline-info">Not Started</span>
                                @elseif ($item->status == '2')
                                    <span class="btn btn-block btn-outline-primary">In Progress</span>
                                @elseif ($item->status == '3')
                                    <span class="btn btn-block btn-outline-warning">On Hold</span>
                                @elseif ($item->status == '4')
                                    <span class="btn btn-block btn-outline-danger">Canceled</span>
                                @elseif ($item->status == '5')
                                    <span class="btn btn-block btn-outline-success">Completed</span>
                                @endif
                            </td>
                            <td>
                                <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->name}}','{{$item->location}}','{{$item->description}}','{{$item->authority}}','{{$item->start_date}}','{{$item->end_date}}','{{$item->project_amount}}','{{$item->estimated_cost}}','{{$item->estimated_profit}}','{{$item->status}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</button>
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





<!-- Add Modal -->
<div class="modal fade" id="modal-add">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Add Project</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-project') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                            placeholder="Your project name" required>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Locatoion</label>
                        <input type="text" class="form-control" required name="location"
                            placeholder="Your location">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" required name="description"
                            placeholder="Your description name">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Authority</label>
                        <input type="text" class="form-control" required name="authority"
                            placeholder="Your authority">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" required name="start_date"
                            placeholder="Your start date">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" required name="end_date"
                            placeholder="Your end date">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Project Amount</label>
                        <input type="text" class="form-control" required name="project_amount"
                            placeholder="Your Project Amount">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Estimated Cost Amount</label>
                        <input type="text" class="form-control" required name="estimated_cost"
                            placeholder="Your Estimated Cost Amount">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Estimated Profit</label>
                        <input type="text" class="form-control" required name="estimated_profit"
                            placeholder="Your Estimated Profit">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" required class="form-control">
                            <option value="">Select One</option>
                            <option value="1">Not Started</option>
                            <option value="2">In Progress</option>
                            <option value="3">On Hold</option>
                            <option value="4">Canceled</option>
                            <option value="5">Completed</option>
                        </select>
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
          <h4 class="modal-title">Update Project</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('update-project') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="project_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="name"
                            placeholder="Your project name" required id="name">
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Locatoion</label>
                        <input type="text" class="form-control" name="location"
                            placeholder="Your location" required id="location">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="description"
                            placeholder="Your description name"  id="description">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Authority</label>
                        <input type="text" class="form-control" name="authority"
                            placeholder="Your authority" required id="authority">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date"
                            placeholder="Your start date"   id="start_date">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date"
                            placeholder="Your end date"  id="end_date">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Project Amount</label>
                        <input type="text" class="form-control" name="project_amount"
                            placeholder="Your Project Amount"   id="project_amount">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Estimated Cost Amount</label>
                        <input type="text" class="form-control" name="estimated_cost"
                            placeholder="Your Estimated Cost Amount"  id="estimated_cost">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Estimated Profit</label>
                        <input type="text" class="form-control" name="estimated_profit"
                            placeholder="Your Estimated Profit"   id="estimated_profit">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status"  class="form-control" required id="status">
                            <option value="">Select One</option>
                            <option value="1">Not Started</option>
                            <option value="2">In Progress</option>
                            <option value="3">On Hold</option>
                            <option value="4">Canceled</option>
                            <option value="5">Completed</option>
                        </select>
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
    function load_edit_body(project_id,name,location,description,authority,start_date,end_date,project_amount,estimated_cost,estimated_profit,status){
        $('#project_id').val(project_id);
        $('#name').val(name);
        $('#location').val(location);
        $('#description').val(description);
        $('#authority').val(authority);
        $('#start_date').val(start_date);
        $('#end_date').val(end_date);
        $('#project_amount').val(project_amount);
        $('#estimated_cost').val(estimated_cost);
        $('#estimated_profit').val(estimated_profit);
        $('#status').val(status);
    }
</script>
@endsection

