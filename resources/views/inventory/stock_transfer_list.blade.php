@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Stock Transfer List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('stock-transfer-list') }}" method="get">
                    <div class="row pb-3">
                        <div class="col-lg-3">
                            <label for="Project">From Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Project">To Company</label>
                            <select name="to_company_id" id="to_company_id" onchange="select_project_data(this.value)" class="form-control" required>
                                <option value="">Select One</option>
                                @foreach ($company_data as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Project">To Project</label>
                            <select name="to_project_id" id="to_project_id" class="form-control" required>
                                <option value="">Select One</option>
                                
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Project">Item</label>
                            <select name="item_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($item_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date"/>
                        </div>
                        <div class="col-lg-2">
                            <label for="start_date">End Date</label>
                            <input type="date" class="form-control" name="end_date"/>
                        </div>
                        
                        <div class="col-lg-2">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('stock-transfer-print?project_id='.request()->get('project_id').'&to_project_id='.request()->get('to_project_id').'&to_company_id='.request()->get('to_company_id').'&item_id='.request()->get('item_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('stock-transfer-pdf?project_id='.request()->get('project_id').'&to_project_id='.request()->get('to_project_id').'&to_company_id='.request()->get('to_company_id').'&item_id='.request()->get('item_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
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
                                <th>To Project</th>
                                <th>Item</th>
                                <th>QTY</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stock_transfer_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->out_date)) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->to_project->name.'('.$item->to_company->name.')' }}</td>
                                <td>@if($item->item){{ $item->item->name }} @endif</td>
                                <td>{{ $item->qty }}</td>
                                <td>{{ $item->remarks }}</td>
                            </tr>
                            
                            @endforeach
                            
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$stock_transfer_data->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection