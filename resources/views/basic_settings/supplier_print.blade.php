@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Supplier List</h1>

<table class="table table-bordered table-striped" width="100%">
    <thead>
        <tr class="bg-info text-center">
            <th>ID</th>
            <th>Name</th>
            <th>Company</th>
            <th>Mobile</th>
            <th>Contact Person Name</th>
            <th>Contact Person Mobile</th>
            <th>Email</th>
            <th>Address</th>
            <th>Other Details</th>
            <th>Note</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($supplier_data as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->company->name }}</td>
            <td>{{ $item->mobile }}</td>
            <td>{{ $item->contact_person_name }}</td>
            <td>{{ $item->contact_person_mobile }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->address }}</td>
            <td>{{ $item->other_details }}</td>
            <td>{{ $item->note }}</td>
            <td>
                @if ($item->status == '1')
                    Active
                @else
                    Inactive
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection