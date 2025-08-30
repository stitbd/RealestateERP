@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        PG Amount List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('pg-list') }}" method="get">
                    <div class="row pb-3">
                       
                        <div class="col-lg-2">
                            <label for="Project">Project</label>
                            <select class="form-control" name="project_id">
                                <option value="">Select One</option>
                                @foreach ($projects as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label for="Project">Bank Name</label>
                            <input type="text" name="bank_name" class="form-control" placeholder="Bank Name">
                        </div>
                        <div class="col-lg-2">
                            <label for="garanty_date">Date</label>
                            <input type="date" class="form-control" name="garanty_date"/>
                        </div>
                        <div class="col-lg-2">
                            <label for="start_date">Valid Date</label>
                            <input type="valid_date" class="form-control" name="valid_date"/>
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
                            <a href="{{url('pg-print?project_id='.request()->get('project_id').'&garanty_date='.request()->get('garanty_date').'&bank_name='.request()->get('bank_name').'&valid_date='.request()->get('valid_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('pg-pdf?project_id='.request()->get('project_id').'&garanty_date='.request()->get('garanty_date').'&bank_name='.request()->get('bank_name').'&valid_date='.request()->get('valid_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Bank Name</th>
                                <th>Description</th>
                                <th>Reference</th>
                                <th>Amount</th>
                                <th>Bank Credit Limit</th>
                                <th>Purpose</th>
                                <th>Valid Date</th>
                                <th>Remarks</th>
                                <th>Attachment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($pg_amounts as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{ date('d/m/Y',strtotime($item->pg_date)) }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->bank_name }}</td>
                                <td>{{ $item->description }}</td>
                                <td>{{ $item->reference }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                <td>Tk. {{ $item->bank_credit_limit }}</td>
                                <td>{{$item->purpose}}</td>
                                <td>{{$item->valid_date}}</td>
                                <td>{{$item->remarks}}</td>
                                
                                <td>
                                    @if ($item->attachment != null)
                                        <a href="{{ asset($item->attachment) }}" target="_blank" class="btn btn-info">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            
                            @endforeach
                            
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$pg_amounts->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection