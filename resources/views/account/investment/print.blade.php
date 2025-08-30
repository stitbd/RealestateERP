@extends('layouts.print')
<style>
    .table-content {
        font-size: 12px;
    }
</style>

@section('content')
    <h2 class="text-center" style="text-align:center !important">
        Investment List
    </h2>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th class="table-content">#</th>
                <th>Invest Date</th>
                <th>Tentative Receivable Date</th>
                <th>Fund Type</th>
                <th>Client / Company's Information</th>
                <th>Invest Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($investments as $item)
            <tr>
                <td class="text-center table-content">{{ $loop->iteration }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($item->invest_date)) }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($item->tentative_receivable_date)) ?? '' }}</td>
                <td class="text-center">{{ $item->fund->name ?? '' }}</td>
                <td>{!! $item->client_name != null ? '<strong>Name:</strong>' . $item->client_name . '<br/>' : '' !!}
                    {!! $item->address != null ? '<strong>Address:</strong>' . $item->address . '<br/>' : '' !!}
                    {!! $item->phone != null ? '<strong>Mobile No:</strong>' . $item->phone . '<br/>' : '' !!}
                    {!! $item->email != null ? '<strong>Email:</strong>' . $item->email . '<br/>' : '' !!}       
                    {!! $item->purpose != null ? '<strong>Remarks:</strong>'.  $item->purpose . '<br/>' : '' !!}
                    {!! $item->nid != null ? '<strong>NID:</strong>'. '<a href="' . asset('nid/' . $item->nid) . '" target="_blank">' . $item->nid . '</a>' . '<br/>' : '' !!}</td>
                <td class="text-center">{{ $item->amount }} Tk.</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endsection
