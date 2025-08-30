@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Completion Project Received List</h1>
<table class="table table-bordered table-striped" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Submitted Date</th>
            <th>Project</th>
            <th>Bill No</th>
            <th>Authority</th>
            <th>Description</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($bills as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ date('d/m/Y',strtotime($item->bill_date)) }}</td>
            <td>{{ date('d/m/Y',strtotime($item->bill_submitted_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->bill_no}}</td>
            <td>{{ $item->authority_name}}</td>
            <td>{{ $item->description}}</td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
            @php
                $total += $item->amount;    
            @endphp
        </tr>
        
        
        @endforeach
        <tr>
            <th colspan="7">Total</th>
            <th class="text-right">Tk. {{$total}}</th>
        </tr>
    </tbody>
</table>
@endsection