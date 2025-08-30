@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Liquidation Asset List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>SL</th>
                <th> Liquidation Date </th>
                <th> Asset Group</th>
                <th> Asset </th>
                <th> Asset Category </th>
                <th> Asset Type </th>
                <th> Liquidation Quantity </th>
                <th> Liquidation Amount </th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
            @endphp
            @foreach ($assets_liquidation as $asset_liquidation)
                <tr>
                    <td class="text-center">{{ ++$serial }}</td>
                    <td class="text-center">{{ $asset_liquidation->liquidation_date }}</td>
                    <td class="text-center">{{ $asset_liquidation->asset->asset_group->name }}</td>
                    <td class="text-center">{{ $asset_liquidation->asset->name }}
                        ({{ $asset_liquidation->asset->asset_code }})
                    </td>
                    <td class="text-center">{{ $asset_liquidation->asset->asset_group->asset_category->name }}</td>
                    <td class="text-center">{{ $asset_liquidation->asset->asset_type }}
                    </td>
                    <td class="text-center">{{ $asset_liquidation->quantity }}</td>
                    <td class="text-center" style="color: red;">
                        {{ $asset_liquidation->liquidation_amount }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
