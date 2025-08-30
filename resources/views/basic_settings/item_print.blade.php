@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Item List</h1>

<table class="table table-bordered table-striped" style="width: 100%">
    <thead>
        <tr class="bg-info text-center">
            <th>SL</th>
            <th>Code No.</th>
            <th>Company</th>
            <th>Name</th>
            <th>Category</th>
            <th>Size/Type</th>
            <th>Unit</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 0; @endphp
        @foreach ($item_data as $item)
        <tr>
            <td> @php
                $i = ($item_data instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($loop->iteration + ($item_data->perPage() * ($item_data->currentPage() - 1)))  : ++$i;
            @endphp {{$i}}
            </td>
            <td>{{ $item->code_no }}</td>
            <td>{{ $item->company->name ?? ''}}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->category->category_name??'' }}</td>
            <td>{{ $item->size_type }}</td>
            <td>{{ $item->unit }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection