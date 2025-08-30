@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Supplier Due List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('supplier-due-print')}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('supplier-due-pdf')}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered text-center">
                        <thead class="bg-info">
                            <tr>
                                {{-- <th>Project</th> --}}
                                <th>Supplier</th>
                                <th>Due Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($due_data as $item)
                            <tr>
                                {{-- <td>{{ $item->project->name }}</td> --}}
                                <td>{{ $item->supplier->name }}</td>
                                <td  class="">Tk. {{ $item->due_amount }}</td>
                                <td class=" text-center">
                                    <a class="btn btn-info" href="{{url('supplier-log/'.$item->supplier_id)}}">View Log</a>
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

@endsection