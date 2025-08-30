@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Deposit List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('deposit-list') }}" method="get">
                    <div class="row pb-3">
                       
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
                            <a href="{{url('deposit-print?fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                            <a href="{{url('deposit-pdf?fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                            </a>
                        </div>
                    </div>

                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Fund</th>
                                <th>Amount</th>
                                <th>Attachment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($deposit_data as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->payment_date)) }}</td>
                                <td>{{ $item->payment_type }}</td>
                                <td>{{ $item->fund->name }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                <td>
                                    @if ($item->diposit_details->attachment != null)
                                        <a href="{{ asset($item->diposit_details->attachment) }}" target="_blank" class="btn btn-info">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-center">
                                    <small>
                                    {!!($item->diposit_details->receiver_name!=null)?'Depositor Name:'.$item->diposit_details->receiver_name.'<br/>':''!!}
                                    {!!($item->diposit_details->mobile_no!=null)?'Depositor Mobile No:'.$item->diposit_details->mobile_no.'<br/>':''!!}
                                    {!!($item->diposit_details->nid!=null)?'Depositor NID:'.$item->diposit_details->nid.'<br/>':''!!}
                                    {!!($item->diposit_details->address!=null)?'Depositor Address:'.$item->diposit_details->address.'<br/>':''!!}
                                    {!!($item->diposit_details->check_number!=null)?'Check Number:'.$item->diposit_details->check_number.'<br/>':''!!}
                                    {!!($item->diposit_details->check_issue_date!=null)?'Check Issue Date:'.$item->diposit_details->check_issue_date.'<br/>':''!!}
                                    {!!($item->diposit_details->bank_name!=null)?'Bank Name:'.$item->diposit_details->bank_name.'<br/>':''!!}
                                    {!!($item->diposit_details->bank_account_no!=null)?'Bank Account Name:'.$item->diposit_details->bank_account_no.'<br/>':''!!}
                                    {!!($item->diposit_details->account_holder_name!=null)?'Bank Holder Name:'.$item->diposit_details->account_holder_name.'<br/>':''!!}
                                    {!!($item->diposit_details->payment_note!=null)?'Payent Note:'.$item->diposit_details->payment_note.'<br/>':''!!}
                                    {!!($item->diposit_details->remarks!=null)?'Remarks:'.$item->diposit_details->remarks.'<br/>':''!!}
                                    </small>
                                </td>
                            </tr>
                            @php
                                $total += $item->amount;
                            @endphp
                            @endforeach
                            <tr>
                                <th colspan="4">Total</th>
                                <th class="text-right">Tk. {{$total}}</th>
                            </tr>
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$deposit_data->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection