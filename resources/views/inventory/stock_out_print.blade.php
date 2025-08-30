@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Stock Out List</h1>
<table class="table table-bordered">
    <thead class="bg-info" style="width: 100%">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Project</th>
            <th>Item</th>
            <th>QTY</th>
            <th>Reason</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stock_out_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ date('d/m/Y',strtotime($item->out_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->item->name }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ $item->reason }}</td>
            <td>{{ $item->remarks }}</td>
        </tr>
        
        @endforeach
        
    </tbody>
</table>
@endsection