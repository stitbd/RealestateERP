@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Asset Stock List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>SL</th>
                <th> Asset Code </th>
                <th> Asset Group </th>
                <th> Asset </th>
                <th> Asset Category </th>
                <th> Asset Type </th>
                <th> Current Stock </th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
            @endphp
            @foreach ($asset_stocks as $asset_stock)
                <tr>
                    <td class="text-center">{{ ++$serial }}</td>
                    <td class="text-center">{{ $asset_stock->asset->asset_code ?? '' }}</td>
                    <td class="text-center">{{ $asset_stock->asset->asset_group->name ?? '' }}</td>
                    <td class="text-center">{{ $asset_stock->asset->name ?? '' }}</td>
                    <td class="text-center">{{ $asset_stock->asset->asset_group->asset_category->name ?? '' }}</td>
                    <td class="text-center">{{ $asset_stock->asset->asset_type ?? '' }}</td>
                    <td class="text-center" style="color: red;">{{ $asset_stock->quantity ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
