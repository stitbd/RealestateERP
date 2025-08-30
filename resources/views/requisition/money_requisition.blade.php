@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Money Requisition
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('money-requisition') }}" method="get">
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
                            <a href="{{url('money-requisition-print?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('money-requisition-pdf?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Approval Details</th>
                                <th>Paid Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $approved_amount = 0;
                            @endphp
                            @foreach ($requisitions as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->requisition_date)) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->description }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                <td>{{ $item->remarks }}</td>
                                <td>
                                    @if ($item->status == '2')
                                        <span class="btn btn-sm btn-outline-info">Pending</span>
                                    @elseif ($item->status == '1')
                                        <span class="btn btn-sm btn-outline-success">Approved</span>
                                    @elseif ($item->status == '0')
                                        <span class="btn btn-sm btn-outline-danger">Canceled</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == '1')
                                    @php
                                        $approved_amount += $item->approved_amount;
                                    @endphp
                                        Approved by: <b class="text-info">Tk. {{$item->approved_user->name}} </b><br/>
                                        Approved Date: <b class="text-info">{{date('d/m/Y',strtotime($item->approved_date))}} </b><br/>
                                        Approved Amount: <b class="text-info">Tk. {{$item->approved_amount}}</b>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <b class="text-success">Tk. {{$item->paid_amount}}</b>
                                </td>
                                <td>
                                    @if ($item->status == '1' && $item->paid_amount <= $item->approved_amount)
                                    <a class="btn btn-sm btn-success m-1" href="{{url("payment-money-requisition/$item->id")}}"><i class="fas fa-ruble-sign"></i> Paid</a>
                                    @endif

                                    @if ($item->status == '2')
                                    <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->amount}}')" data-target="#modal-approve" class="btn btn-sm btn-info" > <i class="fa fa-check"></i> Approve</button>
                                    <a class="btn btn-sm btn-danger m-1" href="{{url("change-money-requisition-status/$item->id/0")}}"><i class="fa fa-trash"></i> Cancel</a>
                                    @endif
                                </td>
                            </tr>
                            @php
                                $total += $item->amount;
                            @endphp
                            @endforeach
                            <tr>
                                <th colspan="4">Total</th>
                                <th class="text-right">Tk. {{$total}}</th>
                                <th colspan="2">Approved Amount</th>
                                <th class="text-right text-success">Tk. {{$approved_amount}}</th>
                                <th></th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$requisitions->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="modal-approve">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Approve Money Requisition</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('approve-money-requisition') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id"  id="money_requisition_id">
        <div class="modal-body">
            <div class="row">
                
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Requisition Amount</label>
                        <input type="text" id="amount" name="amount" class="form-control">
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <label class="form-label">Approved Amount</label>
                        <input type="text" id="approved_amount" name="approved_amount" class="form-control">
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
    function load_edit_body(money_requisition_id,amount){
        $('#money_requisition_id').val(money_requisition_id);
        $('#amount').val(amount);
        $('#approved_amount').val(amount);
    }
</script>
@endsection