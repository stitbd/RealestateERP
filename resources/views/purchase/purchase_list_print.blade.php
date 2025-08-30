@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Purchase List</h1>
<table class="table table-bordered" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Project</th>
            <th>Supplier</th>
            <th>Invoice Amount</th>
            <th>Status</th>
            <th>Last Modified Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($purchase as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ date('d/m/Y',strtotime($item->purchase_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->supplier->name }}</td>
            <td  class="text-right">Tk. {{ $item->invoice_amount }}</td>
            <td>
                @if ($item->status == '2')
                    <span class="btn btn-block btn-sm btn-outline-info">Pending</span>
                @elseif ($item->status == '1')
                    <span class="btn btn-block btn-sm btn-outline-success">Received</span>
                @elseif ($item->status == '3')
                    <span class="btn btn-block btn-sm btn-outline-success">Ordered</span>
                @elseif ($item->status == '0')
                    <span class="btn btn-block btn-sm btn-outline-danger">Canceled</span>
                @endif
            </td>
            <td>{{ date('d/m/Y',strtotime($item->updated_at)) }}</td>
        </tr>
        
        @endforeach
        
    </tbody>
</table>
@endsection