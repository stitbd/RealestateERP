@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Loan Collection List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>#</th>
                <th>Loan Collection Date</th>
                <th>Employee</th>
                <th>Fund Type</th>
                <th>Bank Name</th>
                <th>Account</th>
                <th>Collected Amount</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loan_collections as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
                    <td>{{ $item->loan->loanee_name ?? '' }}</td>
                    <td>{{ $item->fund->name ?? '' }}</td>
                    <td>{{ $item->bank->name ?? '' }}</td>
                    <td>{{ $item->loan->account->account_no ?? '' }}</td>
                    <td>{{ $item->collect_amount }} Tk.</td>
                    <td>{{ $item->note }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
