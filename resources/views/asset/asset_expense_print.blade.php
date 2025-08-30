@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Asset Expense List
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
                <th> Purchase Date </th>
                <th> Purchase Code </th>
                <th> Unit Price </th>
                <th> Purchased Quantity</th>
                <th> Lost Quantity</th>
                <th> Damage Quantity</th>
                <th> Depreciation Expense (Per Year)</th>
                <th> Depreciation Expense (Per Month)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
                $currentDate = date('Y-m-d');
            @endphp
            @foreach ($asset_purchase as $asset_purchased)
                @foreach ($asset_purchased->asset_details as $a_p)
                {{-- @dd($a_p) --}}
                    @php
                        $purchaseDate = $a_p->asset_purchase->purchase_date;
                        $lifetime = $a_p->asset->life_time;

                        $newDate = date(
                            'Y-m-d',
                            strtotime("+$lifetime years", strtotime($purchaseDate)),
                        );
                        // dd($newDate);
                        $lostAsset = App\Models\AssetLost::where(
                            'asset_id',
                            $a_p->asset_id,
                        )->first();
                        // dd($lostAsset);
                        $damageAsset = App\Models\AssetLost::where(
                            'asset_id',
                            $a_p->asset_id,
                        )->first();
                    @endphp
                    {{-- @dd($a_p) --}}
                    <tr>
                        @if (strtotime($currentDate) <= strtotime($newDate))
                        <td class="text-center">{{ ++$serial }}</td>
                        <td class="text-center">{{ $a_p->asset->asset_code ?? '' }}</td>
                        <td class="text-center">{{ $a_p->asset->asset_group->name ?? '' }}</td>
                        <td class="text-center">{{ $a_p->asset->name ?? '' }}</td>
                        <td class="text-center">{{ $a_p->asset->asset_group->asset_category->name ?? '' }}</td>
                        <td class="text-center">{{ $a_p->asset->asset_type ?? '' }}</td>
                        <td class="text-center">{{ $a_p->asset_purchase->purchase_date ?? '' }}</td>
                        <td class="text-center">{{ $a_p->asset_purchase->p_code ?? '' }}</td>
                        <td class="text-center">{{ $a_p->unit_price ?? '' }}</td>
                        <td class="text-center">{{ $a_p->quantity ?? '' }}</td>
                        <td class="text-center">{{ $lostAsset->quantity ?? '' }}</td>
                        <td class="text-center">{{ $damageAsset->quantity ?? '' }}</td>
                        <td class="text-center" style="color: red;">
                            @if ($lostAsset && $damageAsset)
                                {{ number_format((($a_p->quantity - $lostAsset->quantity - $damageAsset->quantity) * $a_p->unit_price) / $a_p->asset->life_time) }}
                            @elseif ($lostAsset)
                                {{ number_format((($a_p->quantity - $lostAsset->quantity) * $a_p->unit_price) / $a_p->asset->life_time) }}
                            @elseif ($damageAsset)
                                {{ number_format((($a_p->quantity - $damageAsset->quantity) * $a_p->unit_price) / $a_p->asset->life_time) }}
                            @else
                                {{ number_format(($a_p->quantity * $a_p->unit_price) / $a_p->asset->life_time) }}
                            @endif
                            Per Year
                        </td>
                        <td class="text-center" style="color: red;">
                            @if ($lostAsset && $damageAsset)
                                {{ number_format((($a_p->quantity - $lostAsset->quantity - $damageAsset->quantity) * $a_p->unit_price) / $a_p->asset->life_time / 12, 2) }}
                            @elseif ($lostAsset)
                                {{ number_format((($a_p->quantity - $lostAsset->quantity) * $a_p->unit_price) / $a_p->asset->life_time / 12, 2) }}
                            @elseif ($damageAsset)
                                {{ number_format((($a_p->quantity - $damageAsset->quantity) * $a_p->unit_price) / $a_p->asset->life_time / 12, 2) }}
                            @else
                                {{ number_format(($a_p->quantity * $a_p->unit_price) / $a_p->asset->life_time / 12, 2) }}
                            @endif
                            Per Month
                        </td>
                        @endif
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
