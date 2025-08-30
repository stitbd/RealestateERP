@extends('layouts.print')
@section('content')
    <h1 class="text-center" style="text-align:center !important">
        Asset Assign List
    </h1>
    <table class="table table-bordered table-striped" style="width: 100%">
        <thead class="bg-info">
            <tr>
                <th>SL</th>
                <th> Assign Date </th>
                <th> Assigned Employee </th>
                <th> Asset Code </th>
                <th> Asset Group</th>
                <th> Asset </th>
                <th> Asset Quantity </th>
                <th> Asset Category </th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
            @endphp
           @foreach ($asset_assigned as $asset_assign)
           <tr>
               <td class="text-center">{{ ++$serial }}</td>
               <td class="text-center">{{ $asset_assign->assign_date ?? '' }}</td>
               <td class="text-center"><b>{{ $asset_assign->employee->name ?? '' }}</b>
                   ({{ $asset_assign->employee->department->name ?? '' }},
                   {{ $asset_assign->employee->designation->name ?? '' }})
               </td>
               <td class="text-center">{{ $asset_assign->asset->asset_code ?? '' }}</td>
               <td class="text-center">{{ $asset_assign->asset->asset_group->name ?? '' }}</td>
               <td class="text-center">{{ $asset_assign->asset->name ?? '' }}
                   ({{ $asset_assign->asset->asset_type ?? '' }})</td>
               <td class="text-center">{{ $asset_assign->quantity ?? '' }}</td>
               <td class="text-center">
                   {{ $asset_assign->asset->asset_group->asset_category->name ?? '' }}</td>
           </tr>
       @endforeach
        </tbody>
    </table>
@endsection
