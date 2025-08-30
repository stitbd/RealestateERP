@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Collected Invest Amount List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>#</th>
                <th>Tentative Receivable Date</th>
                <th>Client / Company</th>
                <th>Fund Type</th>
                <th>Bank Name</th>
                <th>Account</th>
                <th>Return Amount</th>
                <th>Profit</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($return_invests as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->invest->tentative_receivable_date)) }}</td>
                    <td>{{ $item->invest->client_name ?? '' }}</td>
                    <td>{{ $item->fund->name ?? '' }}</td>
                    <td>{{ $item->bank->name ?? '' }}</td>
                    <td>{{ $item->account->account_no ?? '' }}</td>
                    <td>{{ $item->return_amount }} Tk.</td>
                    @if ($item->profit)
                        <td>{{ $item->profit }} Tk.</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{ $item->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
