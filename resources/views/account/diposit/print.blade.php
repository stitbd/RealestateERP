@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">
    Deposit List
</h1>
<table class="table table-bordered table-striped" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Type</th>
            <th>Fund</th>
            <th>Amount</th>
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
        </tr>
        <tr>
            <td colspan="5" class="text-center">
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
@endsection