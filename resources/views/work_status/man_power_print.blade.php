@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Man Power List</h1>

<table class="table table-bordered" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Company</th>
            <th>Project</th>
            <th>Vendor</th>
            <th>Work Nature</th>
            <th>Completed Work</th>
            <th>Total Manpower Cost</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($work_status as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->company->name }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->vendor->name }}</td>
            <td>{{ $item->work_nature }}</td>
            <td class="text-center text-bold btn-outline-info">{{ $item->complete_work }} %</td>
            <td class="text-right">Tk. {{ $item->total_manpower_cost }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection