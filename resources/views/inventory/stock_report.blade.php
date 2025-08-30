@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Stock Report
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('stock-report') }}" method="get">
                    <div class="row pb-3">
                        <div class="col-lg-4">
                            <label for="Project">Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label for="Project">Item</label>
                            <select name="item_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($item_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-lg-4">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    </form>

                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('stock-report-print?project_id='.request()->get('project_id').'&item_id='.request()->get('item_id'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('stock-report-pdf?project_id='.request()->get('project_id').'&item_id='.request()->get('item_id'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Item</th>
                                <th>QTY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($stock_data)
                            @foreach ($stock_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                {{-- <td>{{ $item->project->name }}</td> --}}
                                <td>@if($item->item){{ $item->item->name }} @endif</td>
                                <td>{{ $item->qty }}</td>
                            </tr>
                            @endforeach
                            @endif
                            
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$stock_data->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection