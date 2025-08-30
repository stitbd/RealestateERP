@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Vendor Project List</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr class="bg-info text-center">
            <th>ID</th>
            <th>Vendor</th>
            <th>Company</th>
            <th>Project</th>
            <th>Agreement Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vendor_project_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->vendor->name }}</td>
            <td>{{ $item->company->name }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->amount }}</td>
            <td>{{ $item->remarks }}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
@endsection