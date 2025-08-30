@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Stock Report</h1>
<table class="table table-bordered" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Project</th>
            <th>Item</th>
            <th>QTY</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($stock_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->item->name }}</td>
            <td>{{ $item->qty }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection