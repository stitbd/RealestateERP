@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Project List</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr class="bg-info text-center">
            <th>ID</th>
            <th>Company</th>
            <th>Name</th>
            <th>Location</th>
            <th>Description</th>
            <th>Authority</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Project Amount</th>
            <th>Estimated Cost</th>
            <th>Estimated Profit</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($project_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->company->name }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->location }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->authority }}</td>
            <td>{{ date('d/m/Y',strtotime($item->start_date)) }}</td>
            <td>{{ date('d/m/Y',strtotime($item->end_date)) }}</td>
            <td>{{ $item->project_amount }}</td>
            <td>{{ $item->estimated_cost }}</td>
            <td>{{ $item->estimated_profit }}</td>
            <td>
                @if ($item->status == '1')
                    <span class="btn btn-block btn-outline-info">Not Started</span>
                @elseif ($item->status == '2')
                    <span class="btn btn-block btn-outline-primary">In Progress</span>
                @elseif ($item->status == '3')
                    <span class="btn btn-block btn-outline-warning">On Hold</span>
                @elseif ($item->status == '4')
                    <span class="btn btn-block btn-outline-danger">Canceled</span>
                @elseif ($item->status == '5')
                    <span class="btn btn-block btn-outline-success">Completed</span>
                @endif
            </td>
            
        </tr>
        @endforeach
    </tbody>
  </table>
@endsection