@extends('layouts.print')
<style>
    .table-content {
        font-size: 12px;
    }
</style>

@section('content')
    <h2 class="text-center" style="text-align:center !important">
        Loan Amount List
    </h2>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th class="table-content">#</th>
                <th class="table-content">Loan Provide Date</th>
                <th class="table-content">Loan Provider</th>
                <th class="table-content">Fund Type</th>
                <th class="table-content">Bank Name</th>
                <th class="table-content">Account No.</th>
                <th class="table-content">Description</th>
                <th class="table-content">Employee's Information</th>
                <th class="table-content">Loan Amount</th>
                <th class="table-content">Valid Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loans as $item)
                <tr>
                    <td class="text-center table-content">{{ $loop->iteration }}</td>
                    <td class="text-center table-content">{{ date('d/m/Y', strtotime($item->loan_date)) }}</td>
                    <td class="text-center table-content">{{ $item->loan_provider ?? '' }}</td>
                    <td class="text-center table-content">{{ $item->fund->name ?? '' }}</td>
                    <td class="text-center table-content">{{ $item->bank->name ?? '' }}</td>
                    <td class="text-center table-content">{{ $item->account->account_no ?? '' }}</td>
                    <td class="text-center table-content">{{ $item->description ?? '' }}</td>
                    <td class="table-content">{!! $item->loanee_name != null ? '<strong>Name:</strong>' . $item->loanee_name . '<br/>' : '' !!}
                        {!! $item->department != null ? '<strong>Department:</strong>' . $item->department . '<br/>' : '' !!}
                        {!! $item->designation != null ? '<strong>Designation:</strong>' . $item->designation . '<br/>' : '' !!}
                        {!! $item->company->name != null ? '<strong>Company:</strong>' . $item->company->name . '<br/>' : '' !!}
                        {!! $item->address != null ? '<strong>Address:</strong>' . $item->address . '<br/>' : '' !!}
                        {!! $item->phone != null ? '<strong>Mobile No:</strong>' . $item->phone . '<br/>' : '' !!}
                        {!! $item->email != null ? '<strong>Email:</strong>' . $item->email . '<br/>' : '' !!}
                        {!! $item->remarks != null ? '<strong>Remarks:</strong>' . $item->remarks . '<br/>' : '' !!}
                        {!! $item->nid != null ? '<strong>NID:</strong>'. '<a href="' . asset('nid/' . $item->nid) . '" target="_blank">' . $item->nid . '</a>' . '<br/>' : '' !!}
                    </td>
                    <td class="text-center table-content">{{ $item->amount }} Tk.</td>
                    <td class="text-center table-content">{{ date('d/m/Y', strtotime($item->valid_date)) }}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
@endsection
