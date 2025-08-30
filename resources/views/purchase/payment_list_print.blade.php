@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Vendor Payment List</h1>

<table class="table table-bordered table-striped" style="width: 100% !important">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Supplier</th>
            <th>Order</th>
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
            <td>{{ $item->order->name }}</td>
            <td>{{ $item->fund->name }}</td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
        </tr>
        <tr>
            <td colspan="7" class="text-center" style="text-align: center">
                <small>
                    {!!($item->payment_details->receiver_name!=null)?'Receiver Name:'.$item->payment_details->receiver_name.'<br/>':''!!}
                    {!!($item->payment_details->mobile_no!=null)?'Receiver Mobile No:'.$item->payment_details->mobile_no.'<br/>':''!!}
                    {!!($item->payment_details->check_number!=null)?'Check Number:'.$item->payment_details->check_number.'<br/>':''!!}
                    {!!($item->payment_details->check_issue_date!=null)?'Check Issue Date:'.date('d/m/Y',strtotime($item->payment_details->check_issue_date)).'<br/>':''!!}
                    {!!($item->payment_details->bank_id !=null)?'Bank Name:'.$item->payment_details->bank->name.'<br/>':''!!}
                    {!!($item->payment_details->account_id!=null)?'Bank Account Name:'.$item->payment_details->account->account_no.'<br/>':''!!}
                    {!!($item->payment_details->account_holder_name!=null)?'Bank Holder Name:'.$item->payment_details->account_holder_name.'<br/>':''!!}
                    {!!($item->payment_details->payment_note!=null)?'Payent Note:'.$item->payment_details->payment_note.'<br/>':''!!}
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
@endsection