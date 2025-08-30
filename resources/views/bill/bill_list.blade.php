@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Bill List

                        <button class="btn btn-sm btn-success pull-right" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Bill
                        </button>
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('bill-list') }}" method="get">
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
                            <label for="start_date">Bill Date</label>
                            <input type="date" class="form-control" name="bill_date"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="start_date">Bill Submitted Date</label>
                            <input type="date" class="form-control" name="bill_submitted_date"/>
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
                            <a href="{{url('bill-print?project_id='.request()->get('project_id').'&bill_date='.request()->get('bill_date').'&bill_submitted_date='.request()->get('bill_submitted_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('bill-pdf?project_id='.request()->get('project_id').'&bill_date='.request()->get('bill_date').'&bill_submitted_date='.request()->get('bill_submitted_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Bill Status</th>
                                <th>Bill Date</th>
                                <th>Bill Submitted Date</th>
                                <th>Project</th>
                                <th>IPC/Bill Number</th>
                                <th>Amount</th>
                                <th>Authority Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bill_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{!! ($item->bill_status==1)?'<b class="text-success">Received</b>':'<b class="text-danger">Pending</b>' !!}</td>
                                <td>{{ date('d/m/Y',strtotime($item->bill_date)) }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->bill_submitted_date)) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->bill_no }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                <td>{{ $item->authority_name }}</td>
                                <td>{!! $item->description !!}</td>
                                <td>
                                    @if ($item->attachment != null)
                                    <a href="{{asset($item->attachment)}}" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-file-pdf"></i> File</a>
                                    @endif
                                    
                                    <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->project_id}}','{{$item->bill_status}}','{{$item->bill_no}}','{{$item->bill_date}}','{{$item->bill_submitted_date}}','{{$item->amount}}','{{$item->authority_name}}','{{$item->description}}')" data-target="#modal-edit" class="btn btn-sm btn-info" > <i class="fa fa-pencil"></i> Edit</button>
                                </td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$bill_data->links();}}
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
          <h4 class="modal-title">Add Bill</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('save-bill') }}" method="post" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Bill Status</label>
                        <select name="bill_status" id="" class="form-control" required>
                            <option value="">Select One</option>
                            <option value="1">Received</option>
                            <option value="2">Pending</option>
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
                        <label class="form-label">IPC/Bill Number</label>
                        <input type="text" name="bill_no" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Bill Date</label>
                        <input type="date" name="bill_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Bill Submitted Date</label>
                        <input type="date" name="bill_submitted_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" name="amount" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Authority Name</label>
                        <input type="text" name="authority_name" class="form-control">
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
        <form action="{{ route('update-bill') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="bill_id">
        <div class="modal-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Bill Status</label>
                        <select name="bill_status" id="bill_status" class="form-control" required>
                            <option value="">Select One</option>
                            <option value="1">Received</option>
                            <option value="2">Pending</option>
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
                        <label class="form-label">IPC/Bill Number</label>
                        <input type="text" id="bill_no" name="bill_no" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Bill Date</label>
                        <input type="date" id="bill_date" name="bill_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Bill Submitted Date</label>
                        <input type="date" id="bill_submitted_date" name="bill_submitted_date" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="text" id="amount" name="amount" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Authority Name</label>
                        <input type="text" id="authority_name" name="authority_name" class="form-control">
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
    function load_edit_body(bill_id,project_id,bill_status,bill_no,bill_date,bill_submitted_date,amount,authority_name,description){
        $('#bill_id').val(bill_id);
        $('#project_id').val(project_id);
        $('#bill_status').val(bill_status);
        $('#bill_no').val(bill_no);
        $('#bill_date').val(bill_date);
        $('#bill_submitted_date').val(bill_submitted_date);
        $('#authority_name').val(authority_name);
        $('#amount').val(amount);
        $('#description').val(description);
    }
</script>
@endsection