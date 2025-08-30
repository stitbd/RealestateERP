@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Work Order List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>#</th>
                <th>Work Order Date</th>
                <th>Supplier</th>
                <th>Type</th>
                <th>Service Name</th>
                <th>Product Name</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($work_orders as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                    <td>{{ $item->supplier->name ?? '' }}</td>
                    <td>{{ $item->type ?? '' }}</td>
                    <td>{{ $item->service_name ?? '' }}</td>
                    <td>{{ $item->product_name ?? '' }}</td>
                    <td>{{ $item->unit_price ?? '' }}</td>
                    @if ($item->s_quantity)
                        <td>{{ $item->s_quantity ?? '' }}</td>
                    @else
                        <td>{{ $item->p_quantity ?? '' }}</td>
                    @endif
                    <td>{{ $item->total_price ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
