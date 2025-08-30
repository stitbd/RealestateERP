@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Supplier Log
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    {{-- <h4 class="bg-info text-center p-2 text-bold">
                        Project: {{($due_amount != null)?$due_amount->project->name:''}}
                    </h4> --}}
                    <h5 class="bg-success text-center p-2 text-bold">
                        Supplier: {{($due_amount != null)?$due_amount->supplier->name:''}}
                    </h5>
                    <table class="table mb-5">
                        <tr>
                            <th class="bg-dark">Company : </th>
                            <th class="bg-light">{{($due_amount != null)?$due_amount->company->name:''}}</th>
                            <th class="bg-dark">Due Amount : </th>
                            <th class="bg-light text-right">Tk. {{$due_amount->due_amount}}</th>
                        </tr>
                        
                    </table>

                    {{-- <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th colspan="7">
                                    Purchase List
                                </th>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>Invoice No.</th>
                                <th>Date</th>
                                <th>Order Name</th>
                                <th>Invoice Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->invoice_no }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->date)) }}</td>
                                <td>{{ $item->name }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                                <td>{{ date('d/m/Y',strtotime($item->updated_at)) }}</td>
                            </tr>
                            @endforeach
                            
                        </tbody>
                    </table> --}}


                    <table class="table table-bordered table-striped mt-3">
                        <thead class="bg-info">
                            <tr>
                                <th colspan="7">
                                    Payment List
                                </th>
                            </tr>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Ref No.</th>
                                <th>Fund</th>
                                <th>Amount</th>
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
                                <td>{{ $item->supplier->name }}</td>
                                <td>@if($item->work_order_id){{ $item->order->invoice_no}} @else {{ $item->purchase->reference_no}} @endif</td>
                                <td>{{ $item->fund->name }}</td>
                                <td class="text-right">Tk. {{ $item->amount }}</td>
                               
                            </tr>
                            <tr>
                                <td colspan="6" class="text-center">
                                    
                                    {!!($item->payment_details->receiver_name!=null)?'Receiver Name:'.$item->payment_details->receiver_name.'<br/>':''!!}
                                    {!!($item->payment_details->mobile_no!=null)?'Receiver Mobile No:'.$item->payment_details->mobile_no.'<br/>':''!!}
                                    {!!($item->payment_details->check_number!=null)?'Check Number:'.$item->payment_details->check_number.'<br/>':''!!}
                                    {!!($item->payment_details->check_issue_date!=null)?'Check Issue Date:'.date('d/m/Y',strtotime($item->payment_details->check_issue_date)).'<br/>':''!!}
                                    {!!($item->payment_details->bank_id !=null)?'Bank Name:'.$item->payment_details->bank->name.'<br/>':''!!}
                                    {!!($item->payment_details->account_id!=null)?'Bank Account Name:'.$item->payment_details->account->account_no.'<br/>':''!!}
                                    {!!($item->payment_details->account_holder_name!=null)?'Bank Holder Name:'.$item->payment_details->account_holder_name.'<br/>':''!!}
                                    {!!($item->payment_details->payment_note!=null)?'Payent Note:'.$item->payment_details->payment_note.'<br/>':''!!}
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

                </div>
            </div>
        </div>
    </div>
</div>



@endsection