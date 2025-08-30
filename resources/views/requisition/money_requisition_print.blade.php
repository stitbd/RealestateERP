@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Money Requisition List</h1>
<table class="table table-bordered" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Project</th>
            <th>Description</th>
            <th>Amount</th>
            <th>Remarks</th>
            <th>Status</th>
            <th>Approval Details</th>
            <th>Paid Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
            $approved_amount = 0;
        @endphp
        @foreach ($requisitions as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ date('d/m/Y',strtotime($item->requisition_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->description }}</td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
            <td>{{ $item->remarks }}</td>
            <td>
                @if ($item->status == '2')
                    <span class="btn btn-sm btn-outline-info">Pending</span>
                @elseif ($item->status == '1')
                    <span class="btn btn-sm btn-outline-success">Approved</span>
                @elseif ($item->status == '0')
                    <span class="btn btn-sm btn-outline-danger">Canceled</span>
                @endif
            </td>
            <td>
                @if ($item->status == '1')
                @php
                    $approved_amount += $item->approved_amount;
                @endphp
                    Approved by: <b class="text-info">Tk. {{$item->approved_user->name}} </b><br/>
                    Approved Date: <b class="text-info">{{date('d/m/Y',strtotime($item->approved_date))}} </b><br/>
                    Approved Amount: <b class="text-info">Tk. {{$item->approved_amount}}</b>
                @endif
            </td>
            <td class="text-center">
                <b class="text-success">Tk. {{$item->paid_amount}}</b>
            </td>
        </tr>
        @php
            $total += $item->amount;
        @endphp
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th class="text-right">Tk. {{$total}}</th>
            <th colspan="2">Approved Amount</th>
            <th class="text-right text-success">Tk. {{$approved_amount}}</th>
            
        </tr>
    </tbody>
</table>
@endsection