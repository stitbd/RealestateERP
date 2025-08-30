@extends('layouts.print')
@section('content')

<table class="table table-bordered table-striped" width="100%">
    <thead class="bg-info">
        <tr>
            <th>SL NO.</th>
            <th>Date</th>
            <th>Particulars</th>
            <th>Type</th>
            <th>Fund</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $i = 0
        @endphp
        @foreach ($income_data as $item)
        <tr>
            <td> @php
                $i = ($income_data instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($loop->iteration + ($income_data->perPage() * ($income_data->currentPage() - 1)))  : ++$i;
            @endphp {{$i}}
            </td>
            <td>{{ date('d/m/Y',strtotime($item->payment_date)) }}</td>
            <td>
                {{ $item->remarks ?  $item->remarks : ''}} 
            </td>
            <td>{{ $item->payment_type }}</td>
            <td>{{ $item->fund->name ?? ''}}</td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
        </tr>
        <tr>
            <td colspan="6"  class="text-center" style="padding-x: 10%; text-align:center;">
                @if($item->receive_by  !=null){!!($item->receive_by !=null)?'Receiver Name:'.$item->receive_by.'<br/>':''!!}@endif
                {{-- @if($item->receive_by){!!($item->receiver->mobile_no!=null)?'Receiver Mobile No:'.$item->receiver->mobile_no.'<br/>':''!!}@endif --}}
                {{-- @if($item->receive_by){!!($item->receiver->nid!=null)?'Receiver NID:'.$item->receiver->nid.'<br/>':''!!}@endif --}}
                {{-- @if($item->receive_by){!!($item->receiver->address!=null)?'Receiver Address:'.$item->receiver->address.'<br/>':''!!}@endif --}}
                @if($item->cheque_no){!!($item->cheque_no!=null)?'Check Number:'.$item->cheque_no.'<br/>':''!!}@endif
                @if($item->cheque_issue_date){!!($item->cheque_issue_date!=null)?'Check Issue Date:'.$item->cheque_issue_date.'<br/>':''!!}@endif
                @if($item->bank){!!($item->bank->name!=null)?'Bank Name:'.$item->bank->name.'<br/>':''!!}@endif
                @if($item->account){!!($item->account_id !=null)?'Bank Account Name:'.$item->account->account_no.'<br/>':''!!}@endif
                @if($item->account){!!($item->account_id !=null)?'Bank Holder Name:'.$item->account->account_holder_name.'<br/>':''!!}@endif
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