@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Land Stock List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('purchase') }}" method="get">
                    {{-- <div class="row pb-3">
                        <div class="col-lg-3">
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
                    </div> --}}
                    </form>

                        {{-- <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('purchase-print?supplier_id='.request()->get('supplier_id').'&project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </a>
                            <a href="{{url('purchase-pdf?supplier_id='.request()->get('supplier_id').'&project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                            </a>
                        </div>
                    </div> --}}

                        <table class="table table-bordered">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>Project</th>
                                    <th>Total Stock(শতক)</th>
                                    <th>Total Amount</th>
                                    <th>Total Plot</th>
                                    <th>Total Plot Land(শতক)</th>
                                    <th>Remain Purchase Land(শতক)</th>
                                    <th>Total Plot Sales</th>
                                    <th>Total Sales Land(শতক)</th>
                                    <th>Remain Sales Land(শতক)</th>
                                    {{-- <th class="text-center">Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($land_stock as $item)
                                @php
                                    $totalPlot     = 0;
                                    $totalPlotLand = 0;
                                    $remainLand    = 0;
                                    $totalPlotSales = 0;
                                    $totalSalesLand = 0;
                                    $remainPlotLand = 0;

                                    $sales = App\Models\LandSale::where(['project_id'=>$item->project_id,'type' => 'plot'])->get();

                                    foreach($sales as $sale){
                                       ++$totalPlotSales;
                                        $totalSalesLand += $totalPlotSales * ($sale->plot->plot_size);
                                    }


                                    $plots = App\Models\Plot::where('project_id',$item->project_id)->get();

                                    foreach($plots as $plot){
                                       ++$totalPlot;
                                        $totalPlotLand += $totalPlot * ($plot->plotType->percentage);
                                    }

                                    $remainLand = $item->total_stock_land - $totalPlotLand;
                                    $remainPlotLand = $totalPlotLand -  $totalSalesLand;

                                @endphp
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $item->project->name ?? ''}}</td>
                                        <td>{{ $item->total_stock_land }} (শতক)</td>
                                        <td>{{ $item->total_amount }} Tk.</td>
                                        <td>{{ $totalPlot }}</td>
                                        <td>{{ $totalPlotLand }}</td>
                                        <td>{{ $remainLand }}</td>
                                        <td>{{ $totalPlotSales }}</td>
                                        <td>{{ $totalSalesLand }}</td>
                                        <td>{{ $remainPlotLand }}</td>

                                        {{-- <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $item->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a href=""><i class="fa fa-edit" style="color: rgb(28, 145,199)"></i></a>
                                            <a href=""
                                                onclick="return confirm('Are you sure you want to delete this item?');"><i
                                                    class="fa fa-trash text-danger" style=""></i></a>
                                        </td> --}}
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $land_stock->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
