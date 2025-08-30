@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Licenses List</h1>
<table class="table table-bordered table-striped" style="width:100% !important" border="1px">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Start Date</th>
            <th>Expire Date</th>
            <th>Renew Amount</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($licenses_data as $item)
        <tr>
            <td style="text-align:center !important">{{ $item->id }}</td>
            <td style="text-align:center !important">{{ $item->name }}</td>
            <td style="text-align:center !important">{{ date('d/m/Y',strtotime($item->start_date)) }}</td>
            <td style="text-align:center !important">{{ date('d/m/Y',strtotime($item->expire_date)) }}</td>
            <td style="text-align:center !important">{{ $item->renew_amount }}</td>
            <td style="text-align:center !important">{!! $item->remarks !!}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>

  
@endsection