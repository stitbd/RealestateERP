@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Man Power Status Log
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <h4 class="bg-info text-center p-2 text-bold">
                        Project: {{$work_status->project->name}}
                    </h4>
                    <h5 class="bg-success text-center p-2 text-bold">
                        Vendor: {{$work_status->vendor->name}}
                    </h5>
                    <table class="table mb-5 text-center">
                        <tr>
                            <th class="bg-dark">Company : </th>
                            <th class="bg-light">{{$work_status->company->name}}</th>
                            <th class="bg-dark">Total Man power Cost : </th>
                            <th class="bg-light text-right">Tk. {{$work_status->total_manpower_cost}}</th>
                        </tr>
                        <tr>
                            <th class="bg-dark">Work Nature : </th>
                            <th class="bg-light">{{$work_status->work_nature}}</th>
                            <th class="bg-dark">Completed Work : </th>
                            <th class="bg-light text-right">{{$work_status->complete_work}} %</th>
                            
                        </tr>
                    </table>

                    <table class="table table-striped text-center">
                        <tr class="bg-dark">
                            <th>Date</th>
                            <th>Today Manpower</th>
                            <th>Description</th>
                            <th>Per Manpower Cost</th>
                            <th>Total Manpower Cost</th>
                            <th>Remarks</th>
                        </tr>
                        @foreach ($work_status_log as $item)
                            <tr>
                                <td>{{date('d/m/Y',strtotime($item->log_date))}}</td>
                                <td>{{$item->man_power}}</td>
                                <td>{{$item->description}}</td>
                                <td>{{$item->per_manpower_cost}}</td>
                                <td>{{$item->total_manpower_cost}}</td>
                                <td>{{$item->remarks}}</td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection