@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Licenses List

                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Licenses
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('licenses-print')}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('licenses-pdf')}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>
                    
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Start Date</th>
                                <th>Expire Date</th>
                                <th>Renew Amount</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($licenses_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->start_date)) }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->expire_date)) }}</td>
                                <td>{{ $item->renew_amount }}</td>
                                <td>{!! $item->remarks !!}</td>
                                <td>
                                    <a href="{{asset($item->attachment)}}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file-pdf"></i> File</a>
                                    <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->name}}','{{$item->start_date}}','{{$item->expire_date}}','{{$item->renew_amount}}','{{$item->remarks}}')" data-target="#modal-edit" class="btn btn-sm btn-info" > <i class="fa fa-pencil"></i> Edit</button>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>

                    
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
          <h4 class="modal-title">Add Licenses</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-licenses') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                </div>
                
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Expire Date</label>
                        <input type="date" name="expire_date" class="form-control">
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Renew Amount</label>
                        <input type="text" name="renew_amount" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" name="remarks" class="form-control">
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
          <h4 class="modal-title">Update Licenses Report</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('update-licenses') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="licenses_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" id="name" name="name" class="form-control">
                    </div>
                </div>
                
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" id="start_date" name="start_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Expire Date</label>
                        <input type="date" id="expire_date" name="expire_date" class="form-control">
                    </div>
                </div>
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Renew Amount</label>
                        <input type="text" id="renew_amount" name="renew_amount" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <input type="text" id="remarks" name="remarks" class="form-control">
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
    function load_edit_body(licenses_id,name,start_date,expire_date,renew_amount,remarks){
        $('#licenses_id').val(licenses_id);
        $('#name').val(name);
        $('#start_date').val(start_date);
        $('#expire_date').val(expire_date);
        $('#renew_amount').val(renew_amount);
        $('#remarks').val(remarks);
    }
</script>
@endsection