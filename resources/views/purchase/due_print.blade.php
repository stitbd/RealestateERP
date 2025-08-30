@extends('layouts.print')
@section('content')
<h1 class="text-center"  style="text-align:center !important">Supplier Due List</h1>

<table class="table table-bordered" style="width: 100%">
    <thead class="bg-info">
        <tr>
            <th>Project</th>
            <th>Supplier</th>
            <th>Due Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($due_data as $item)
        <tr>
            <td>{{ $item->project->name }}</td>
            <td>{{ $item->supplier->name }}</td>
            <td  class="text-right">Tk. {{ $item->due_amount }}</td>
        </tr>
        @endforeach
        
    </tbody>
</table>
@endsection