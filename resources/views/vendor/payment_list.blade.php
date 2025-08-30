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
                    <form action="{{ route('vendor-payment-list') }}" method="get">
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
                            <label for="Project">Vendor</label>
                            <select name="vendor_id" class="form-control">
                                <option value="">Select One</option>
                                @foreach ($vendor_data as $item)
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
                        
                        <div class="col-lg-3">
                            <label for="action">Action</label> <br/>
                            <button class="btn btn-success btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                    </form>
                    <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('vendor-payment-print?vendor_id='.request()->get('vendor_id').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('vendor-payment-pdf?vendor_id='.request()->get('vendor_id').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Project</th>
                                <th>Vendor</th>
                                <th>Fund</th>
                                <th>Amount</th>
                                <th>Attachment</th>
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
                                <td>{{ $item->project->name }}</td>
                                <td>{{ $item->vendor->name }}</td>
                                <td>{{ $item->fund->name }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                <td>
                                    @if ($item->payment_details->attachment)
                                        <a href="{{ asset($item->payment_details->attachment) }}" target="_blank">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <small>
                                    {!!($item->payment_details->receiver_name!=null)?'Receiver Name:'.$item->payment_details->receiver_name.'<br/>':''!!}
                                    {!!($item->payment_details->mobile_no!=null)?'Receiver Mobile No:'.$item->payment_details->mobile_no.'<br/>':''!!}
                                    {!!($item->payment_details->nid!=null)?'Receiver NID:'.$item->payment_details->nid.'<br/>':''!!}
                                    {!!($item->payment_details->address!=null)?'Receiver Address:'.$item->payment_details->address.'<br/>':''!!}
                                    {!!($item->payment_details->check_number!=null)?'Check Number:'.$item->payment_details->check_number.'<br/>':''!!}
                                    {!!($item->payment_details->check_issue_date!=null)?'Check Issue Date:'.$item->payment_details->check_issue_date.'<br/>':''!!}
                                    {!!($item->payment_details->bank_name!=null)?'Bank Name:'.$item->payment_details->bank_name.'<br/>':''!!}
                                    {!!($item->payment_details->bank_account_no!=null)?'Bank Account Name:'.$item->payment_details->bank_account_no.'<br/>':''!!}
                                    {!!($item->payment_details->account_holder_name!=null)?'Bank Holder Name:'.$item->payment_details->account_holder_name.'<br/>':''!!}
                                    {!!($item->payment_details->payment_note!=null)?'Payent Note:'.$item->payment_details->payment_note.'<br/>':''!!}
                                    {!!($item->payment_details->remarks!=null)?'Remarks:'.$item->payment_details->remarks.'<br/>':''!!}
                                    </small>
                                </td>
                            </tr>
                            @php
                                $total += $item->amount;
                            @endphp
                            @endforeach
                            <tr>
                                <th colspan="5">Total</th>
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