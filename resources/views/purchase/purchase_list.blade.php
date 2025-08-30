@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Purchase List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('purchase') }}" method="get">
                    <div class="row pb-3">
                        <div class="col-lg-3">
                            <label for="Project">Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Project">Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($supplier_data as $item)
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
                            <a href="{{url('purchase-print?supplier_id='.request()->get('supplier_id').'&project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('purchase-pdf?supplier_id='.request()->get('supplier_id').'&project_id='.request()->get('project_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                {{-- <th>Project</th> --}}
                                <th>Supplier</th>
                                <th>Invoice Amount</th>
                                <th>Status</th>
                                <th>Payment Status</th>
                                <th>Last Modified Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @php $payment = 0; @endphp --}}
                            @foreach ($purchase as $item)
                            @php 
                                $payment = App\Models\Payment::where('purchase_id',$item->id)->sum('amount');
                            @endphp
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->purchase_date)) }}</td>
                                {{-- <td>{{ $item->project->name }}</td> --}}
                                <td>{{ $item->supplier->name }}</td>
                                <td  class="text-right">Tk. {{ $item->invoice_amount }}</td>
                                <td>
                                    @if ($item->status == '2')
                                        <span class="btn btn-block btn-sm btn-outline-info">Pending</span>
                                    @elseif ($item->status == '1')
                                        <span class="btn btn-block btn-sm btn-outline-success">Received</span>
                                    @elseif ($item->status == '3')
                                        <span class="btn btn-block btn-sm btn-outline-success">Ordered</span>
                                    @elseif ($item->status == '0')
                                        <span class="btn btn-block btn-sm btn-outline-danger">Canceled</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($payment == 0 )
                                        <span class="btn btn-block btn-sm btn-outline-warning">Unpaid</span>
                                    @elseif ($payment < $item->invoice_amount)
                                        <span class="btn btn-block btn-sm btn-outline-primary">Partial Paid</span>
                                    @elseif ($payment == $item->invoice_amount)
                                        <span class="btn btn-block btn-sm btn-outline-success">Paid</span>
                                    @endif
                                </td>
                                <td>{{ date('d/m/Y',strtotime($item->updated_at)) }}</td>
                                
                                <td>
                                    @if ($item->status == '2')
                                    <a class="btn btn-sm btn-success m-1" href="{{url("change-purchase-status/$item->id/3")}}"><i class="fa fa-check"></i> Order</a>
                                    <a class="btn btn-sm btn-success m-1" href="{{url("change-purchase-status/$item->id/1")}}"><i class="fa fa-check"></i> Receive</a>
                                    <a class="btn btn-sm btn-danger m-1" href="{{url("change-purchase-status/$item->id/0")}}"><i class="fa fa-trash"></i> Cancel</a>
                                    @endif

                                    @if ($item->status == '3')
                                    <a class="btn btn-sm btn-success m-1" href="{{url("change-purchase-status/$item->id/1")}}"><i class="fa fa-check"></i> Receive</a>
                                    <a class="btn btn-sm btn-danger m-1" href="{{url("change-purchase-status/$item->id/0")}}"><i class="fa fa-trash"></i> Cancel</a>
                                    @endif

                                    <button onclick="view_invoice('{{$item->id}}')" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-view">
                                        <i class="fa fa-print" aria-hidden="true"></i> View Invoice
                                    </button>
                                    @if ($payment < $item->invoice_amount)
                                    <button onclick="generatePayment('{{$item->id}}')" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-create">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Create Payment
                                    </button>
                                    @endif

                                    <button onclick="viewPaymentDetails('{{$item->id}}')" class="btn btn-sm btn-info" data-toggle="modal" data-target="#modal-details">
                                        <i class="fa fa-eye" aria-hidden="true"></i> Payment Details
                                    </button>
                                </td>
                            </tr>
                            
                            @endforeach
                            
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$purchase->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- View Modal -->
<div class="modal fade" id="modal-view">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Invoice Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body" id="invoice_details">
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
  <!-- /.modal -->

  <!-- /Payment modal -->

  <div class="modal fade" id="modal-create">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Generate Payment</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body" id="payment_create">
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>

</div>
  <!-- / End of Payment modal -->

  <!-- / Payment Details modal -->
  <div class="modal fade" id="modal-details">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Payment Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body" id="payment_details">
            
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>

</div>
  <!-- / End of Payment modal -->




  <script>
    function view_invoice(purchase_id){
        url = '{{ route('purchaseInvoice', ":purchase_id") }}';
        url = url.replace(':purchase_id', purchase_id);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#invoice_details').html(response);
            }
        })
    }

    function generatePayment(purchase_id){
        url = '{{ route('generatePayment', ":purchase_id") }}';
        url = url.replace(':purchase_id', purchase_id);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#payment_create').html(response);
            }
        })
    }

    function viewPaymentDetails(purchase_id){
        url = '{{ route('payment-details-view', ":purchase_id") }}';
        url = url.replace(':purchase_id', purchase_id);
        //alert(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#payment_details').html(response);
            }
        })
    }

   
</script>
@endsection