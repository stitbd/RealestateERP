@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Work Order List
                        </h3>
                        <a href="{{ route('create_work_order') }}" class="text-end col-sm-2 btn btn-success btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> New Work Order
                        </a>
                    </div> <!-- /.card-body -->
                    <div class="card-body pt-3 px-2">
                        <form action="{{ route('work-order-list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="supplier">Supplier</label>
                                    <select name="supplier_id" class="form-control">
                                        <option value="">Select Supplier </option>
                                        @foreach ($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="type">Type</label>
                                    <select name="type" class="form-control">
                                        <option value="">Select a Type </option>
                                        <option value="Service">Service</option>
                                        <option value="Product">Product</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">From</label>
                                    <input type="date" class="form-control" name="start_date">
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_date">To</label>
                                    <input type="date" class="form-control" name="end_date">
                                </div>

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('work-order-print?supplier_id=' . request()->get('supplier_id') . '&type=' . request()->get('type') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                {{-- <a href="{{ url('loan-pdf?project_id=' . request()->get('project_id') . '&loan_date=' . request()->get('loan_date') . '&bank_id=' . request()->get('bank_id') . '&valid_date=' . request()->get('valid_date')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a> --}}
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>Order Date</th>
                                    <th>Supplier</th>
                                    <th>Type</th>
                                    <th>Service/Product Name</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Price</th>
                                    <th>Note</th>
                                    <th>Order Status</th>
                                    <th>Payment Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($work_orders as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                        <td>{{ $item->supplier->name ?? '' }}</td>
                                        <td>{{ $item->type ?? '' }}</td>
                                        <td>@if($item->type == 'Service'){{ $item->name }} @else {{ $item->name }} @endif </td>
                                        <td>{{ $item->unit_price ?? '' }}</td>
                                        @if($item->s_quantity)
                                        <td>{{ $item->s_quantity ?? '' }}</td>
                                        @else
                                        <td>{{ $item->p_quantity ?? '' }}</td>
                                        @endif
                                        <td>{{ $item->amount ?? '' }} Tk.</td>
                                        <td>{{$item->note ?? ''}}</td>
                                        <td>
                                            @if ($item->status == '1')
                                                <span class="btn btn-block btn-sm btn-outline-info">Pending</span>
                                            @elseif ($item->status == '2')
                                                <span class="btn btn-block btn-sm btn-outline-success"> Ordered </span>
                                            @elseif ($item->status == '3')
                                                <span class="btn btn-block btn-sm btn-outline-success">Received</span>
                                            @elseif ($item->status == '4')
                                                <span class="btn btn-block btn-sm btn-outline-danger">Canceled</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->payment_status == '1')
                                                <span class="btn btn-block btn-sm btn-outline-info">Unpaid</span>
                                            @elseif ($item->payment_status == '2')
                                                <span class="btn btn-block btn-sm btn-outline-success">Partial </span>
                                            @elseif ($item->payment_status == '3')
                                                <span class="btn btn-block btn-sm btn-outline-success">Paid</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == '1')
                                                <a class="btn btn-sm btn-success m-1" href="{{url("work-order-status/$item->id/2")}}"><i class="fa fa-check"></i> Order</a>
                                                <a class="btn btn-sm btn-success m-1" href="{{url("work-order-status/$item->id/3")}}"><i class="fa fa-check"></i> Receive</a>
                                                <a class="btn btn-sm btn-danger m-1" href="{{url("work-order-status/$item->id/4")}}"><i class="fa fa-trash"></i> Cancel</a>
                                            @endif
        
                                            @if ($item->status == '2')
                                            <a class="btn btn-sm btn-success m-1" href="{{url("work-order-status/$item->id/3")}}"><i class="fa fa-check"></i> Receive</a>
                                            <a class="btn btn-sm btn-danger m-1" href="{{url("work-order-status/$item->id/4")}}"><i class="fa fa-trash"></i> Cancel</a>
                                            @endif
                                            <a href="{{route('edit_work_order',$item->id)}}" class="text-info pl-3"><i class="fa fa-edit"></i></a>

                                            <a onclick="view_invoice('{{$item->id}}')" class="pl-2 text-success" data-toggle="modal" data-target="#modal-view">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                            </a>                                        
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $work_orders->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
<div class="modal fade" id="modal-view">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Invoice Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body" id="order_details">
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endsection

<script>
    function view_invoice(order_id){
        url = '{{ route('order-invoice', ":order_id") }}';
        url = url.replace(':order_id', order_id);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#order_details').html(response);
            }
        })
    }
</script>
