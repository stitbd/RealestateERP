@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Asset Expense List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <form action="{{ route('asset_expense') }}" method="get">
                            <div class="row pb-3">
                               
                                <div class="col-lg-3">
                                    <label for="asset_group_id">Asset Group<i class="text-danger">*</i></label>
                                <select name="asset_group_id" class="form-control form-select">
                                    <option value="" selected>Select Group</option>
                                    @foreach ($asset_groups as $asset_group)
                                        <option value="{{ $asset_group->id }}">{{ $asset_group->name }}
                                            ({{ $asset_group->asset_category->name }})
                                        </option>
                                    @endforeach
                                </select>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Asset</label>
                                    <select name="asset_id" class="form-control form-select">
                                        <option value="" selected>Select Asset</option>
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->name }} ({{ $asset->asset_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-3">
                                    <label for="asset_code">Asset Code</label>
                                    <input type="text" class="form-control" name="asset_code" placeholder="Asset Code">
                                </div>
                                {{-- <div class="col-lg-3">
                                    <label for="name">Asset</label>
                                    <input type="text" class="form-control" name="name" placeholder="Asset Name">
                                </div> --}}
                                <div class="col-lg-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date"/>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date"/>
                                </div>
                                
                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br/>
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            </form>

                            <div class="row">
                                <div class="col-12 text-right">
                                    <a href="{{url('asset_expense_print?asset_code='.request()->get('asset_code').'&asset_id='.request()->get('asset_id').'&asset_group_id='.request()->get('asset_group_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                        <i class="fa fa-print" aria-hidden="true"></i> Print 
                                    </a>
                                    {{-- <a href="{{url('project-received-pdf?project_id='.request()->get('project_id').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                        <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                                    </a> --}}
                                </div>
                            </div>
                        
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th> Asset Code </th>
                                    <th> Asset Group </th>
                                    <th> Asset </th>
                                    <th> Purchase Date </th>
                                    <th> Unit Price </th>
                                    <th> Quantity</th>
                                    <th> Lost Quantity</th>
                                    <th> Damage Quantity</th>
                                    <th> Depreciation Expense (Per Year)</th>
                                    <th> Depreciation Expense (Per Month)</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                    $currentDate = date('Y-m-d');
                                @endphp
                                {{-- @foreach ($asset_purchase as $asset_purchased) --}}
                                    @foreach ($assets as $a_p)
                                    {{-- @dd($a_p) --}}
                                        @php
                                            $purchaseDate = $a_p->purchase_date;
                                            $lifetime = $a_p->life_time;

                                            $newDate = date(
                                                            'Y-m-d',
                                                            strtotime("+$lifetime months", strtotime($purchaseDate))
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
                                            <td class="text-center">{{ $a_p->asset_code ?? '' }}</td>
                                            <td class="text-center">{{ $a_p->asset_group->name ?? '' }}</td>
                                            <td class="text-center">{{ $a_p->name ?? '' }}</td>
                                            <td class="text-center">{{ $a_p->purchase_date ?? '' }}</td>
                                            <td class="text-center">{{ $a_p->unit_price ?? '' }}</td>
                                            <td class="text-center">{{ $a_p->quantity ?? '' }}</td>
                                            <td class="text-center">{{ $lostAsset->quantity ?? '' }}</td>
                                            <td class="text-center">{{ $damageAsset->quantity ?? '' }}</td>
                                            <td class="text-center" style="color: red;">
                                                @if ($lostAsset && $damageAsset)
                                                    {{ number_format((($a_p->quantity - $lostAsset->quantity - $damageAsset->quantity) * $a_p->unit_price) / ($a_p->life_time/12)) }}
                                                @elseif ($lostAsset)
                                                    {{ number_format((($a_p->quantity - $lostAsset->quantity) * $a_p->unit_price) / ($a_p->life_time/12)) }}
                                                @elseif ($damageAsset)
                                                    {{ number_format((($a_p->quantity - $damageAsset->quantity) * $a_p->unit_price) / ($a_p->life_time/12)) }}
                                                @else
                                                    {{ number_format(($a_p->quantity * $a_p->unit_price) /($a_p->life_time/12)) }}
                                                @endif
                                                Per Year
                                            </td>
                                            <td class="text-center" style="color: red;">
                                                @if ($lostAsset && $damageAsset)
                                                    {{ number_format((($a_p->quantity - $lostAsset->quantity - $damageAsset->quantity) * $a_p->unit_price) / $a_p->life_time , 2) }}
                                                @elseif ($lostAsset)
                                                    {{ number_format((($a_p->quantity - $lostAsset->quantity) * $a_p->unit_price) / $a_p->life_time , 2) }}
                                                @elseif ($damageAsset)
                                                    {{ number_format((($a_p->quantity - $damageAsset->quantity) * $a_p->unit_price) / $a_p->life_time , 2) }}
                                                @else
                                                    {{ number_format(($a_p->quantity * $a_p->unit_price) / $a_p->life_time , 2) }}
                                                @endif
                                                Per Month
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                {{-- @endforeach --}}
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{$asset_purchase->links();}}
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
@endsection
