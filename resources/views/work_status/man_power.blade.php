@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Man Power Status
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('man-power') }}" method="get">
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
                            <label for="vendor_id">Vendor</label>
                            <select name="vendor_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($vendor_data as $item)
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
                            <a href="{{url('man-power-print?vendor_id='.request()->get('vendor_id').'&project_id='.request()->get('project_id'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('man-power-pdf?vendor_id='.request()->get('vendor_id').'&project_id='.request()->get('project_id'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>
                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Company</th>
                                <th>Project</th>
                                <th>Vendor</th>
                                <th>Work Nature</th>
                                <th>Completed Work</th>
                                <th>Total Manpower Cost</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($work_status as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->company->name }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->vendor->name }}</td>
                                <td>{{ $item->work_nature }}</td>
                                <td class="text-center text-bold btn-outline-info">{{ $item->complete_work }} %</td>
                                <td class="text-right">Tk. {{ $item->total_manpower_cost }}</td>
                                <td>
                                    <a href="{{route('man-power-log',$item->id)}}" class="btn btn-sm btn-info">View Log</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$work_status->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection