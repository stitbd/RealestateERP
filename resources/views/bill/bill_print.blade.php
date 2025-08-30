@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Bill List</h1>
@if(request()->get('project_id') != null)
    <h3 class="text-center" style="text-align:center !important">Project: {{$project->name}}</h3>
@endif
@if(request()->get('bill_date') != null)
    <h3 class="text-center" style="text-align:center !important">Bill Date: {{date('d/m/Y',strtotime(request()->get('bill_date')))}}</h3>
@endif
@if(request()->get('bill_submitted_date') != null)
    <h3 class="text-center" style="text-align:center !important">Bill Submitted Date: {{date('d/m/Y',strtotime(request()->get('bill_submitted_date')))}}</h3>
@endif
<table class="table table-bordered table-striped">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Bill Status</th>
            <th>Bill Date</th>
            <th>Bill Submitted Date</th>
            <th>Project</th>
            <th>IPC/Bill Number</th>
            <th>Amount</th>
            <th>Authority Name</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bill_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{!! ($item->bill_status==1)?'<b class="text-success">Received</b>':'<b class="text-danger">Pending</b>' !!}</td>
            <td>{{ date('d/m/Y',strtotime($item->bill_date)) }}</td>
            <td>{{ date('d/m/Y',strtotime($item->bill_submitted_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->bill_no }}</td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
            <td>{{ $item->authority_name }}</td>
            <td>{!! $item->description !!}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>
@endsection