@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Material Requisition
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('material-requisition') }}" method="get">
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
                            <a href="{{url('material-requisition-print?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('material-requisition-pdf?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
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
                                <th>Item</th>
                                <th>Unit</th>
                                <th>Estimated Qty</th>
                                <th>Additional Qty</th>
                                <th>Total Required Qty</th>
                                <th>Total Received Qty</th>
                                <th>Total Consumed Qty</th>
                                <th>Total Stock Qty</th>
                                <th>Balance Required Qty</th>
                                <th>Progress Of Work Based On Material Received</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Approval Details</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requisitions as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->requisition_date)) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->item->name.'-'.$item->item->size_type }}</td>
                                <td>{{$item->item->unit}}</td>
                                <td>{{ $item->estimated_qty }}</td>
                                <td>{{ $item->additional_qty }}</td>
                                <td>{{ $item->total_required_qty }}</td>
                                <td>{{ $item->received_qty }}</td>
                                <td>{{ $item->consumed_qty }}</td>
                                <td>{{ $item->stock_qty }}</td>
                                <td>{{ $item->balance_required_qty }}</td>
                                <td>{{ $item->work_progress }}</td>
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
                                        <b class="text-success">{{date('d/m/Y',strtotime($item->approved_date))}}</b>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->status == '2')
                                    <a class="btn btn-sm btn-success m-1" href="{{url("change-requisition-status/$item->id/1")}}"><i class="fa fa-check"></i> Approve</a>
                                    <a class="btn btn-sm btn-danger m-1" href="{{url("change-requisition-status/$item->id/0")}}"><i class="fa fa-trash"></i> Cancel</a>
                                    @endif
                                </td>
                            </tr>
                            
                            @endforeach
                            
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



@endsection