@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Project Received List</h1>
<table class="table table-bordered table-striped" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Project</th>
            <th>Fund</th>
            <th>Details</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($requisition_payment as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ date('d/m/Y',strtotime($item->payment_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->fund->name }}</td>
            <td>
                @if ($item->details != null)
                    {{ $item->details->receiver_name }} <br/>
                    {{ $item->details->mobile_no }}
                @endif
            </td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
            @php
                $total += $item->amount;    
            @endphp
        </tr>
        
        
        @endforeach
        <tr>
            <th colspan="5">Total</th>
            <th class="text-right">Tk. {{$total}}</th>
        </tr>
    </tbody>
</table>
@endsection