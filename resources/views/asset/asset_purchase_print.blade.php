@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Purchased Asset List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>SL</th>
                <th> Asset Group </th>
                <th> Asset </th>
                <th> Purchase Code </th>
                <th> Purchase Date </th>
                <th> Purchase Quantity </th>
                <th> Total Amount </th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
            @endphp
            @foreach ($asset_purchased as $asset_purchase)
                {{-- @dd($asset_purchase->asset_details) --}}
                @foreach ($asset_purchase->asset_details as $purchase_details)
                    <tr>
                        <td class="text-center">{{ ++$serial }}</td>
                        <td class="text-center">{{ $purchase_details->asset->asset_group->name ?? '' }}
                        </td>
                        <td class="text-center">{{ $purchase_details->asset->name ?? '' }}
                            ({{ $purchase_details->asset->asset_group->asset_category->name ?? '' }},
                            {{ $purchase_details->asset->asset_type ?? '' }})
                        </td>
                        <td class="text-center">{{ $purchase_details->asset_purchase->p_code ?? '' }}
                        </td>
                        <td class="text-center">
                            {{ $purchase_details->asset_purchase->purchase_date ?? '' }}
                        </td>
                        <td class="text-center">{{ $purchase_details->quantity ?? '' }}</td>
                        <td class="text-center">
                            {{ $purchase_details->asset_purchase->total_amount ?? '' }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
