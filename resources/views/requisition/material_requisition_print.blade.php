@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Purchase List</h1>
<table class="table table-bordered" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Project</th>
            <th>Item</th>
            <th>Unit</th>
            <th>Estimated Qty</th>
            <th>Additional Qty</th>
            <th>Total Required Qty</th>
            <th>Total Received Qty</th>
            <th>Total Consumed Qty</th>
            <th>Total Stock Qty</th>
            <th>Balance Required Qty</th>
            <th>Progress Of Work Based On Material Received</th>
            <th>Remarks</th>
            <th>Status</th>
            <th>Approval Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requisitions as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ date('d/m/Y',strtotime($item->requisition_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->item->name.'-'.$item->item->size_type }}</td>
            <td>{{$item->item->unit}}</td>
            <td>{{ $item->estimated_qty }}</td>
            <td>{{ $item->additional_qty }}</td>
            <td>{{ $item->total_required_qty }}</td>
            <td>{{ $item->received_qty }}</td>
            <td>{{ $item->consumed_qty }}</td>
            <td>{{ $item->stock_qty }}</td>
            <td>{{ $item->balance_required_qty }}</td>
            <td>{{ $item->work_progress }}</td>
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
                    <b class="text-success">{{date('d/m/Y',strtotime($item->approved_date))}}</b>
                @endif
            </td>
        </tr>
        
        @endforeach
        
    </tbody>
</table>
@endsection