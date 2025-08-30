@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Daily Status</h1>

<table class="table table-bordered table-striped" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Fund</th>
            <th>Payment Type</th>
            <th>Particulars</th>
            <th>Details</th>
            <th>Deposit</th>
            <th>Payment</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total_diposit = 0;
            $total_payment = 0;
        @endphp
        @foreach ($fund_log as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ date('d/m/Y',strtotime($item->transection_date)) }}</td>
            <td>{{ $item->fund->name }}</td>
            <td>{{ $item->payment_type }}</td>
            <td>{{ $item->transection_type }}</td>
            <td>
                @if($item->transection_type == 'supplier_payment')
                    {{$item->sapplier_payment->supplier->name}} <br/>
                    ({{$item->sapplier_payment->project->name}})
                @elseif($item->transection_type == 'vendor_payment')
                    {{$item->vendor_payment->vendor->name}} <br/>
                    ({{$item->vendor_payment->project->name}})
                @elseif($item->transection_type == 'salary_payment')
                    {{$item->salary_payment->employee->name}}
                @elseif($item->transection_type == 'requisition_payment')
                    {{$item->requisition_payment->project->name}}
                @elseif($item->transection_type == 'deposit')
                    {{$item->diposit->payment_type}}
                @elseif($item->transection_type == 'expense')
                        {{$item->expense->particulars}}
                @endif
                
            </td>
            <td class="text-right">
                Tk. {{ ($item->type==1)?$item->amount:'-' }}
                @if($item->type==1)
                @php
                    $total_diposit += $item->amount;
                @endphp
                @endif
            </td>
            <td class="text-right">
                Tk. {{ ($item->type==2)?$item->amount:'-' }}
                @if($item->type==2)
                @php
                    $total_payment += $item->amount;
                @endphp
                @endif
            </td>
        </tr>
        
        
        @endforeach
        <tr>
            <th colspan="6">Total</th>
            <th class="text-right">Tk. {{$total_diposit}}</th>
            <th class="text-right">Tk. {{$total_payment}}</th>
        </tr>
    </tbody>
</table>
@endsection