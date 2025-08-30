@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Payment List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('supplier-payment-list') }}" method="get">
                    <div class="row pb-3">
                        {{-- <div class="col-lg-3">
                            <label for="Project">Project</label>
                            <select name="project_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($project_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-lg-3">
                            <label for="Project">Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($supplier_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Project">Fund</label>
                            <select name="fund_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($fund_data as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="start_date">Start Date</label>
                            <input type="date" class="form-control" name="start_date"/>
                        </div>
                        <div class="col-lg-3">
                            <label for="start_date">End Date</label>
                            <input type="date" class="form-control" name="end_date"/>
                        </div>
                        
                        <div class="col-lg-3 pt-2">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    </form>
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('supplier-payment-print?supplier_id='.request()->get('supplier_id').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('supplier-payment-pdf?supplier_id='.request()->get('supplier_id').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Work Order/Purchase</th>
                                <th>Particulars</th>
                                <th>Fund</th>
                                <th>Amount</th>
                                <th>Attachment</th>
                                <th>View & Print</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($payments as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->payment_date)) }}</td>
                                {{-- <td>{{ $item->project->name }}</td> --}}
                                <td>{{ $item->supplier->name }}</td>
                                <td>@if($item->work_order_id) {{ $item->order->invoice_no }} @else {{ $item->purchase->reference_no }} @endif</td>
                                <td>{{ $item->remarks }}</td>
                                <td>{{ $item->fund->name }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                <td>
                                    @if ($item->payment_details->attachment != null)
                                        <a href="{{ asset('attachment/'.$item->payment_details->attachment)}}" target="_blank" class="btn btn-info">
                                                <i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center">
                                    @if($item->payment_details->receiver_name){!!($item->payment_details->receiver_name != null)?'Receiver Name:'.$item->payment_details->receiver_name.'<br/>':''!!}@endif
                                    @if($item->payment_details->mobile_no){!!($item->payment_details->mobile_no!=null)?'Receiver Mobile No:'.$item->payment_details->mobile_no.'<br/>':''!!}@endif
                                    @if($item->payment_details->check_number){!!($item->payment_details->check_number!=null)?'Check Number:'.$item->payment_details->check_number.'<br/>':''!!}@endif
                                    @if($item->payment_details->check_issue_date){!!($item->payment_details->check_issue_date!=null)?'Check Issue Date:'.date('d/m/Y',strtotime($item->payment_details->check_issue_date)).'<br/>':''!!}@endif
                                    @if($item->payment_details->bank_id){!!($item->payment_details->bank_id !=null)?'Bank Name:'.$item->payment_details->bank->name.'<br/>':''!!}@endif
                                    @if($item->payment_details->account_id){!!($item->payment_details->account_id!=null)?'Bank Account Name:'.$item->payment_details->account->account_no.'<br/>':''!!}@endif
                                    @if($item->payment_details->account_holder_name){!!($item->payment_details->account_holder_name!=null)?'Bank Holder Name:'.$item->payment_details->account_holder_name.'<br/>':''!!}@endif

                                </td>
                            </tr>
                            @php
                                $total += $item->amount;
                            @endphp
                            @endforeach
                            <tr>
                                <th colspan="6">Total</th>
                                <th class="text-right">Tk. {{$total}}</th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$payments->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection