@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Completion Project Received List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('completion-project-received') }}" method="get">
                    <div class="row pb-3">
                        
                        <div class="col-lg-2">
                            <label for="Project">Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
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
                            <a href="{{url('completion-project-received-print?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('completion-project-received-pdf?project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Submitted Date</th>
                                <th>Project</th>
                                <th>Bill No</th>
                                <th>Authority</th>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($bills as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ date('d/m/Y',strtotime($item->bill_date)) }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->bill_submitted_date)) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->bill_no}}</td>
                                <td>{{ $item->authority_name}}</td>
                                <td>{{ $item->description}}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                @php
                                    $total += $item->amount;    
                                @endphp
                            </tr>
                            
                            
                            @endforeach
                            <tr>
                                <th colspan="7">Total</th>
                                <th class="text-right">Tk. {{$total}}</th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$bills->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection