@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Vendor Due List</h1>

<table class="table table-bordered table-striped" style="width: 100% !important">
    <thead>
        <tr class="bg-info text-center">
            <th>ID</th>
            <th>Vendor</th>
            <th>Project</th>
            <th>Due Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vendor_due_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->vendor->name }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->due_amount }}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
@endsection