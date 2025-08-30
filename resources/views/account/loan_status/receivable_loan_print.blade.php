@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Receivable Loan List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>#</th>
                <th class="text-center">Employee</th>
                <th class="text-center">Loan Date</th>
                <th class="text-center">Loan Amount</th>
                <th class="text-center">Due Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receivable_loans as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-center">{{ $item->loan->loanee_name ?? '' }} ({{ $item->loan->designation ?? '' }})</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($item->loan->loan_date)) }}</td>
                    <td class="text-center">{{ $item->loan->amount }} Tk.</td>
                    <td class="text-center">{{ $item->current_amount }} Tk.</td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
