@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Receivable Invest List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Client / Company</th>
                <th class="text-center">Invest Date</th>
                <th class="text-center">Invest Amount</th>
                <th class="text-center">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receivable_invests as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $item->invest->client_name ?? '' }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->invest->invest_date)) }}</td>
                    <td class="text-center">{{ $item->invest->amount ?? '' }}</td>
                    <td class="text-center">{{ $item->current_amount }} Tk.</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
