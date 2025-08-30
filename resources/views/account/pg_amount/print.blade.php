@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">
    PG Amount List
</h1>
<table class="table table-bordered table-striped">
    <thead class="bg-info">
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Project</th>
            <th>Bank Name</th>
            <th>Description</th>
            <th>Reference</th>
            <th>Amount</th>
            <th>Bank Credit Limit</th>
            <th>Purpose</th>
            <th>Valid Date</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($pg_amounts as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ date('d/m/Y',strtotime($item->pg_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->bank_name }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->reference }}</td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
            <td>Tk. {{ $item->bank_credit_limit }}</td>
            <td>{{$item->purpose}}</td>
            <td>{{$item->valid_date}}</td>
            <td>{{$item->remarks}}</td>
        </tr>
        
        @endforeach
        
    </tbody>
</table>
@endsection