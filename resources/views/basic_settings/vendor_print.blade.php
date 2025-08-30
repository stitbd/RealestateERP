@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Vendor List</h1>

<table class="table table-bordered table-striped">
    <thead>
        <tr class="bg-info text-center">
            <th>ID</th>
            <th>Name</th>
            <th>Company</th>
            <th>Mobile</th>
            <th>Address</th>
            <th>Other Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($vendor_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->company->name }}</td>
            <td>{{ $item->mobile }}</td>
            <td>{{ $item->address }}</td>
            <td>{{ $item->other_details }}</td>
        </tr>
        @endforeach
    </tbody>
  </table>
@endsection