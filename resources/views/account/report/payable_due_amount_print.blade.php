@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Payable Due Amount</h1>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title text-align">
                    Vendor Due List
                </h3> 
            </div> <!-- /.card-body -->
            <div class="card-body">
                
                    
                <table class="table table-bordered table-striped" style="width:100%">
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
                <h3 class="card-title text-align">
                    Supplier Due List
                </h3> 
            </div> <!-- /.card-body -->
            <div class="card-body p-3">
                <table class="table table-bordered" style="width:100%">
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
@endsection