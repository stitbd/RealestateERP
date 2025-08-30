@extends('layouts.print')
@section('content')
    <h2 class="text-center" style="text-align:center !important">Rental Bill List</h1>

        <table class="table table-bordered table-striped" width="100%">
            <thead class="bg-info">
                <tr>
                    <th class="text-center">#</th>
                    <th class="text-center">Bill Type</th>
                    <th class="text-center">Property</th>
                    <th class="text-center">Unit</th>
                    <th class="text-center">Renter</th>
                    <th class="text-center">Due Amount</th>
                    <th class="text-center">Total Amount</th>
                    {{-- <th class="text-center">Current Status</th> --}}
                    <th class="text-center">Payment Status</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($bill_data as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ $item->type }}</td>
                        <td class="text-center">{{ $item->unit->property->name ?? '' }}</td>
                        <td class="text-center">{{ $item->unit->unit_name ?? '' }},
                            {{ $item->unit->floor->floor_name ?? '' }}</td>
                        <td class="text-center">{{ $item->renter->name ?? '-' }}</td>
                        <td class="text-center">{{ $item->due_amount ?? '-' }} Tk</td>
                        <td class="text-center">{{ $item->total_amount ?? '-' }} Tk</td>
                        <td class="text-center">
                            @if ($item->status == 1)
                                <span
                                    style="font-weight: bold;">Unpaid</span>
                            @elseif ($item->status == 2)
                                <span
                                    style="font-weight: bold;">Paid</span>
                            @else
                                <span
                                    style="font-weight: bold;">Partial
                                    Paid</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endsection
