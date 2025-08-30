@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Revoke List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>SL</th>
                <th> Revoke Date </th>
                <th> Asset Code </th>
                <th> Asset Group </th>
                <th> Asset </th>
                <th> Asset Category </th>
                <th> Asset Type </th>
                <th> Employee </th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
            @endphp
                @foreach ($revokes as $revoke)
              
                    <tr>
                        <td class="text-center">{{ ++$serial }}</td>
                        <td class="text-center">{{ $revoke->revoke_date ?? '' }}</td>
                        <td class="text-center">{{ $revoke->asset->asset_code ?? '' }}</td>
                        <td class="text-center">{{ $revoke->asset->asset_group->name ?? '' }}</td>
                        <td class="text-center">{{ $revoke->asset->name ?? '' }}</td>
                        <td class="text-center">{{ $revoke->asset->asset_group->asset_category->name ?? '' }}</td>
                        <td class="text-center">{{ $revoke->asset->asset_type ?? '' }}</td>
                        <td class="text-center"><b>{{ $revoke->employee->name ?? '' }}</b>
                            ({{ $revoke->employee->department->name ?? '' }},
                            {{ $revoke->employee->designation->name ?? '' }})</td>
                    </tr>
                @endforeach
        </tbody>
    </table>
@endsection
