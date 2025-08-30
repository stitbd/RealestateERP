@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Vendor Project List
                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Vendor Project
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('vendor-project-print')}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('vendor-project-pdf')}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                  <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info text-center">
                            <th>ID</th>
                            <th>Vendor</th>
                            <th>Company</th>
                            <th>Project</th>
                            <th>Agreement Amount</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendor_project_data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->vendor->name }}</td>
                            <td>{{ $item->company->name }}</td>
                            <td>{{ $item->project->name }}</td>
                            <td>{{ $item->amount }}</td>
                            <td>{{ $item->remarks }}</td>
                            <td>
                                <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->vendor_id}}','{{$item->project_id}}','{{$item->amount}}','{{$item->remarks}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</button>
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
          <h4 class="modal-title">Add Vendor Project</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-vendor-project') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Vendor</label>
                        <select name="vendor_id" class="form-control">
                            <option value="">Select One</option>
                            @foreach ($vendor_data as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach 
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Project</label>
                        <select name="project_id" class="form-control">
                            <option value="">Select One</option>
                            @foreach ($project_data as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach 
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Agreement Amount</label>
                        <input type="text" class="form-control" name="amount">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" class="form-control" name="remarks"
                            placeholder="Remarks">
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
          <h4 class="modal-title">Update Vendor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('update-vendor-project') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="vendor_project_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Vendor</label>
                        <select name="vendor_id"  id="vendor_id" class="form-control">
                            <option value="">Select One</option>
                            @foreach ($vendor_data as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach 
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Project</label>
                        <select name="project_id"  id="project_id" class="form-control">
                            <option value="">Select One</option>
                            @foreach ($project_data as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach 
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Agreement Amount</label>
                        <input type="text" id="amount" class="form-control" name="amount">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" id="remarks" class="form-control" name="remarks"
                            placeholder="Remarks">
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
    function load_edit_body(vendor_project_id,vendor_id,project_id,amount,remarks){
        $('#vendor_project_id').val(vendor_project_id);
        $('#vendor_id').val(vendor_id);
        $('#project_id').val(project_id);
        $('#amount').val(amount);
        $('#remarks').val(remarks);
    }
</script>
@endsection

