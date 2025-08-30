@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Lost Asset List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>SL</th>
                <th> Reported Date </th>
                <th> Report Code </th>
                <th> Assigned Employee </th>
                <th> Asset </th>
                <th> Asset Group</th>
                <th> Asset Category </th>
                <th> Asset Type </th>
                <th> Remaining / Assigned Quantity </th>
                <th> Lost Quantity </th>
                <th> Fine </th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
            @endphp
            @foreach ($assets_lost as $asset_lost)
                {{-- @dd($asset_lost) --}}
                @php
                    $assetId = $asset_lost->asset_id;
                    $employeeId = $asset_lost->employee_id;
                    $assignedQuantity = App\Models\AssetAssign::where('asset_id', $assetId)
                        ->where('employee_id', $employeeId)
                        ->sum('quantity');

                    $damageQuantity = App\Models\AssetDamage::where('employee_id', $employeeId)
                        ->where('asset_id', $assetId)
                        ->first();

                    $totalDamageQuantity = 0;
                    if ($damageQuantity) {
                        $totalDamageQuantity = App\Models\DamageActivityLog::where(
                            'damage_id',
                            $damageQuantity->id,
                        )->sum('quantity');
                    }

                    $lostQuantity = App\Models\AssetLost::where('employee_id', $employeeId)
                        ->where('asset_id', $assetId)
                        ->first();

                    $totalLostQuantity = 0;
                    if ($lostQuantity) {
                        $totalLostQuantity = App\Models\LostActivityLog::where('lost_id', $lostQuantity->id)->sum(
                            'quantity',
                        );
                    }

                    $remainingQuantity = $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;
                @endphp

                <tr>
                    <td class="text-center">{{ ++$serial }}</td>
                    <td class="text-center">{{ $asset_lost->report_date ?? '' }}</td>
                    <td class="text-center">{{ $asset_lost->report_code ?? '' }}</td>
                    @if ($asset_lost->employee_id)
                        <td class="text-center">{{ $asset_lost->employee->name ?? '' }}
                            ({{ $asset_lost->employee->department->name ?? '' }},
                            {{ $asset_lost->employee->designation->name ?? '' }})
                        </td>
                    @else
                        <td class="text-center"></td>
                    @endif
                    <td class="text-center">{{ $asset_lost->asset->name ?? '' }}
                        ({{ $asset_lost->asset->asset_code ?? '' }})
                    </td>
                    <td class="text-center">{{ $asset_lost->asset->asset_group->name ?? '' }}</td>
                    <td class="text-center">
                        {{ $asset_lost->asset->asset_group->asset_category->name ?? '' }}</td>
                    <td class="text-center">{{ $asset_lost->asset->asset_type ?? '' }}</td>
                    @if ($asset_lost->employee_id && $asset_lost->status != 'revoke')
                        <td class="text-center" style="color: red;">{{ $remainingQuantity ?? '' }} /
                            {{ $assignedQuantity ?? '' }}</td>
                    @elseif($asset_lost->employee_id && $asset_lost->status == 'revoke')
                        <td class="text-center" style="color: black;"><b>Revoked</b></td>
                    @else
                        <td class="text-center"></td>
                    @endif
                    <td class="text-center">{{ $asset_lost->quantity ?? '' }}</td>
                    <td class="text-center" style="color: red;">{{ $asset_lost->fine ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
