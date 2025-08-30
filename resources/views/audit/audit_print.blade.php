@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Audit List</h1>
@if(request()->get('project_id') != null)
    <h3 class="text-center" style="text-align:center !important">Project: {{$project->name}}</h3>
@endif
@if(request()->get('start_date') != null)
    <h3 class="text-center" style="text-align:center !important">From Date: {{date('d/m/Y',strtotime(request()->get('start_date')))}}</h3>
@endif
@if(request()->get('end_date') != null)
    <h3 class="text-center" style="text-align:center !important">To Date: {{date('d/m/Y',strtotime(request()->get('end_date')))}}</h3>
@endif

<table class="table table-bordered table-striped" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Project</th>
            <th>Type</th>
            <th>Description</th>
            <th>Audit Person</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($audit_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ date('d/m/Y',strtotime($item->audit_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->type }}</td>
            <td>{!! $item->description !!}</td>
            <td>{{ $item->audit_person }}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>
@endsection