@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">
    FDR Amount List
</h1>
<table class="table table-bordered table-striped" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>#</th>
            <th>FDR Date</th>
            <th>Project</th>
            <th>Bank Name</th>
            <th>Description</th>
            <th>Reference</th>
            <th>Garanty Amount</th>
            <th>Bank Credit Limit</th>
            <th>Valid Date</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($fdr_amounts as $item)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{ date('d/m/Y',strtotime($item->fdr_date)) }}</td>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->bank_name }}</td>
            <td>{{ $item->description }}</td>
            <td>{{ $item->reference }}</td>
            <td class="text-right">Tk. {{ $item->amount }}</td>
            <td>Tk. {{ $item->bank_credit_limit }}</td>
            <td>{{$item->valid_date}}</td>
            <td>{{$item->remarks}}</td>
        </tr>
        
        @endforeach
        
    </tbody>
</table>
@endsection