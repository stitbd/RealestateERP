@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Invoice List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="" method="get">
                    <div class="row pb-3">
                        <div class="col-lg-3">
                            <label for="Project">Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
                                    <option value="{{$item->id}}"> {{$item->name}} </option>
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
                            <a href="{{url('invoice-print?start_date='.request()->get('start_date').'&end_date='.request()->get('end_date').'&project_id='.request()->get('project_id'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('invoice-pdf?start_date='.request()->get('start_date').'&end_date='.request()->get('end_date').'&project_id='.request()->get('project_id'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Ref. No.</th>
                                <th>Customer Details</th>
                                <th>Item Details</th>
                                <th>Grand Total</th>
                                <th>Paid </th>
                                <th>Due </th>
                                <th>Payment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->sales_date)) }}</td>
                                <td>{{ $item->ref_no }}</td>
                                <td>
                                    {{$item->customer_name}}
                                    {{-- <ul>
                                        <li>{{ $item->customer_name }}</li>
                                        <li>{{ $item->customer_address }}</li>
                                        <li>{{ $item->customer_mobile }}</li>
                                        <li>{{ $item->custome_email }}</li>
                                    </ul> --}}
                                </td>
                                <td>
                                    <ul>
                                        @foreach ($item->sales_details as $item_data)
                                            <li>{{ $item_data->item->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                {{-- <td>
                                    <ul>
                                        
                                        @foreach ($item->sales_payment as $payment_data)
                                            <li>{!! $payment_data->amount .'<br/>('.$payment_data->payment_type.')'.'<br/>('.$payment_data->fund->name.')' !!}</li>
                                        @endforeach
                                    </ul>
                                </td> --}}
                                <td  class="text-right">Tk. {{ $item->sales_amount }}</td>
                                <td  class="text-right">Tk. {{ $item->paid_amount }}</td>
                                <td  class="text-right">Tk. {{ $item->due_amount }}</td>
                                <td>
                                    @if ($item->paid_status == '2')
                                        <span class="btn btn-block btn-sm btn-outline-info">Due</span>
                                    @elseif ($item->status == '1')
                                        <span class="btn btn-block btn-sm btn-outline-success">Paid</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-info btn-rounded dropdown-toggle" id="dropdownMenuButton" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -88px, 0px);">
                                                <a class="dropdown-item" onclick="view_invoice('{{$item->id}}')" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-view"> Sales Details</a>
                                                <a class="dropdown-item Show_Payments cursor-pointer" onclick="view_payment('{{$item->id}}')" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-payment" id="8"> Show Payments</a>
                                                <a class="dropdown-item New_Payment cursor-pointer" onclick="create_payment('{{$item->id}}')" class="btn btn-sm btn-success" data-toggle="modal" data-target="#modal-create-payment" id="8"> Create Payment</a>
                                     
                                        </div>
                                    {{-- <select name="" id="" class="border-primary col-6">
                                        <option value="">Action</option>
                                        <option value="" disabled>Action</option>
                                        <option value="edit">Edit</option>
                                        <option value="payment">Create Payment</option>
                                        <option value="details">Sales Details</option>
                                        <option value="invoice">Invoice</option>
                                    </select> --}}
                                </td>
                                
                            </tr>
                            
                            @endforeach
                            
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$sales->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Sales view Modal -->
<div class="modal fade" id="modal-view">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Sales Invoice Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="sales_invoice_details">
            
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

<!-- Sales Payment Details Modal -->
<div class="modal fade" id="modal-payment">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Sales Payment Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="sales_payment">
            
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

<!-- Sales Payment Details Modal -->
<div class="modal fade" id="modal-create-payment">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h4 class="modal-title">Sales Payment Details</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="sales_payment_create">
            
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


  <script>
    function view_invoice(sales_id){
        // console.log(sales_id);
        url = '{{ route('salesInvoice', ":sales_id") }}';
        url = url.replace(':sales_id', sales_id);
        // console.log(url);
        $.ajax({
            cache   : false,
            type    : "GET",
            error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
            url : url,
            success : function(response){
                $('#sales_invoice_details').html(response);
            }
        })
    }

    function view_payment(sales_id){
        url = '{{route('salesPayments',":sales_id")}}'
        url = url.replace(':sales_id',sales_id);

        $.ajax({
             cache:false,
             type:"GET",
             url:url,
             error:function(er){alert('Something Wrong:' + er.status +" "+er.statusText);},
             success:function(response){
                $('#sales_payment').html(response);
             }
        });
    }

    function create_payment(sales_id){
        url = '{{route('payment-create',":sales_id")}}'
        url = url.replace(':sales_id',sales_id);
        
        $.ajax({
             cache:false,
             type:"GET",
             url:url,
             error:function(er){alert('Something Wrong:' + er.status +" "+er.statusText);},
             success:function(response){
                $('#sales_payment_create').html(response);
             }
        });
    }

    

</script>
@endsection