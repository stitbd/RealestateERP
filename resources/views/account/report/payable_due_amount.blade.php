@extends('layouts.app')

@section('content')
<div class="container mt-2">
    
                    
    <div class="row">
        <div class="col-12 text-right">
            <a href="{{url('payable-due-amount-print')}}" target="_blank" class="btn btn-warning float-end m-2">
                <i class="fa fa-print" aria-hidden="true"></i> Print 
            </a>
            <a href="{{url('payable-due-amount-pdf')}}" target="_blank" class="btn  btn-danger float-end m-2">
                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
            </a>
        </div>
    </div>
    
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Vendor Due List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    
                        
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>ID</th>
                                <th>Vendor</th>
                                <th>Project</th>
                                <th>Due Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendor_due_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->vendor->name }}</td>
                                <td>{{ $item->project->name }}</td>
                                <td class="text-right">Tk. {{ $item->due_amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- /.card-body -->
              </div>
        </div>
        <div class="col-md-6">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Supplier Due List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th>Project</th>
                                <th>Supplier</th>
                                <th>Due Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($due_data as $item)
                            <tr>
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->supplier->name }}</td>
                                <td  class="text-right">Tk. {{ $item->due_amount }}</td>
                                
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

