@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Invoice List</h1>
<table class="table table-bordered" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Project</th>
            <th>Customer Details</th>
            <th>Item Details</th>
            <th>Payment Details</th>
            <th>Invoice Amount</th>
            <th>Paid Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($sales as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ date('d/m/Y',strtotime($item->sales_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>
                <ul>
                    <li>{{ $item->customer_name }}</li>
                    <li>{{ $item->customer_address }}</li>
                    <li>{{ $item->customer_mobile }}</li>
                    <li>{{ $item->custome_email }}</li>
                </ul>
            </td>
            <td>
                <ul>
                    @foreach ($item->sales_details as $item_data)
                        <li>{{ $item_data->item->name }}</li>
                    @endforeach
                </ul>
            </td>
            <td>
                <ul>
                    
                    @foreach ($item->sales_payment as $payment_data)
                        <li>{!! $payment_data->amount .'<br/>('.$payment_data->payment_type.')'.'<br/>('.$payment_data->fund->name.')' !!}</li>
                    @endforeach
                </ul>
            </td>
            <td  class="text-right">Tk. {{ $item->sales_amount }}</td>
            <td  class="text-right">Tk. {{ $item->paid_amount }}</td>
            
        </tr>
        
        @endforeach
        
    </tbody>
</table>
@endsection