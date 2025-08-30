@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Damage Asset List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-warning btn-sm" data-toggle="modal"
                            data-target="#modal-add-usable">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Usable Damage Asset
                        </button>
                        <button class="text-end col-sm-2 btn btn-danger btn-sm" data-toggle="modal"
                            data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add Fixed Damage Asset
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form action="{{ route('damage_asset_list') }}" method="get">
                            <div class="row pb-3">

                                {{-- <div class="col-lg-3">
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" class="form-control">
                                        <option value="" selected>Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}
                                                ({{ $employee->department->name }}, {{ $employee->designation->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-lg-3">
                                    <label for="asset_group_id">Asset Group</label>
                                    <select name="asset_group_id" class="form-control">
                                        <option value="" selected>Select Group</option>
                                        @foreach ($asset_groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}
                                                ({{ $group->asset_category->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="asset_code">Asset Code</label>
                                    <input type="text" class="form-control" name="asset_code" placeholder="Asset Code">
                                </div>
                                <div class="col-lg-3">
                                    <label for="asset_id">Asset</label>
                                    <select name="asset_id" class="form-control">
                                        <option value="" selected>Select Asset</option>
                                        @foreach ($asset as $asset_id)
                                            <option value="{{ $asset_id->id }}">{{ $asset_id->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('asset_damage_print?employee_id=' . request()->get('employee_id') . '&asset_group_id=' . request()->get('asset_group_id') . '&asset_code=' . request()->get('asset_code') . '&asset_id=' . request()->get('asset_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
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
                                    <th> SL </th>
                                    <th> Damage Occured/ Reported Date </th>
                                    <th> Assigned Employee </th>
                                    <th> Asset Code </th>
                                    <th> Asset </th>
                                    <th> Asset Group</th>
                                    <th> Asset Category </th>
                                    <th> Asset Type </th>
                                    <th> Remaining / Assigned Quantity </th>
                                    <th> Damage Quantity</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($asset_damages as $asset_damage)
                                    @php
                                        $assetId = $asset_damage->asset_id;
                                        $employeeId = $asset_damage->employee_id;
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
                                            $totalLostQuantity = App\Models\LostActivityLog::where(
                                                'lost_id',
                                                $lostQuantity->id,
                                            )->sum('quantity');
                                        }

                                        $remainingQuantity =
                                            $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;
                                    @endphp

                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $asset_damage->date }}</td>
                                        {{-- @if ($asset_damage->employee_id)
                                            <td class="text-center">{{ $asset_damage->employee->name ?? '' }}
                                                ({{ $asset_damage->employee->department->name ?? '' }},
                                                {{ $asset_damage->employee->designation->name ?? '' }})
                                            </td>
                                        @else --}}
                                            <td class="text-center"></td>
                                        {{-- @endif --}}
                                        <td class="text-center">{{ $asset_damage->asset->asset_code ?? '' }}</td>
                                        <td class="text-center">{{ $asset_damage->asset->name ?? '' }}</td>
                                        <td class="text-center">{{ $asset_damage->asset->asset_group->name ?? '' }}</td>
                                        <td class="text-center">
                                            {{ $asset_damage->asset->asset_group->asset_category->name ?? '' }}</td>
                                        <td class="text-center">{{ $asset_damage->asset->asset_type ?? '' }}</td>
                                        @if ($asset_damage->employee_id && $asset_damage->status != 'revoke')
                                            <td class="text-center" style="color: red;">{{ $remainingQuantity ?? '' }} /
                                                {{ $assignedQuantity ?? '' }}</td>
                                        @elseif($asset_damage->employee_id && $asset_damage->status == 'revoke')
                                            <td class="text-center" style="color: black;"><b>Revoked</b></td>
                                        @else
                                            <td class="text-center"></td>
                                        @endif

                                        <td class="text-center" style="color: red;">{{ $asset_damage->quantity ?? '' }}
                                        </td>
                                        <td width="10%" class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $asset_damage->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            @if ($asset_damage->status == 'revoke')
                                            @else
                                                <a data-toggle="modal" data-target=".update-modal-{{ $asset_damage->id }}"
                                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    {{-- @dd($asset_damages) --}}
    @foreach ($asset_damages as $asset_damage)
        {{-- @dd($asset_d) --}}
        <div class="modal fade view-modal-{{ $asset_damage->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Damage Asset</h5>
                        <div class="col-8 text-right">
                            <button type="button" name="print" id="printBtn" onclick="printPage()"
                                class="btn btn-warning m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </button>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body" id="printArea">
                        <div class="row">
                            {{-- @dd($asset_damage) --}}
                            @if (!empty($asset_damage->asset->asset_type) && $asset_damage->asset->asset_type == 'Fixed')
                                <table class="table table-sm">

                                    <tr>
                                        <th width="20%">Assigned Employee: </th>
                                        <td>{{ $asset_damage->employee->name ?? '' }}
                                            ({{ $asset_damage->employee->department->name ?? '' }},
                                            {{ $asset_damage->employee->designation->name ?? '' }})
                                        </td>

                                        <th width="20%">Asset Code: </th>
                                        <td>{{ $asset_damage->asset->asset_code ?? '' }}</td>
                                    </tr>


                                    <tr>
                                        <th width="20%">Asset: </th>
                                        <td>{{ $asset_damage->asset->name ?? '' }}</td>

                                        <th width="20%">Asset Group: </th>
                                        <td>{{ $asset_damage->asset->asset_group->name ?? '' }}</td>
                                    </tr>

                                    <tr>
                                        <th width="20%">Asset Category: </th>
                                        <td>{{ $asset_damage->asset->asset_group->asset_category->name ?? '' }}</td>

                                        <th width="20%">Asset Type: </th>
                                        <td>{{ $asset_damage->asset->asset_type ?? '' }}</td>
                                    </tr>
                                </table>
                                <table class="table table-sm">
                                    <thead>
                                        <tr class="bg-info text-center">
                                            <th> SL</th>
                                            <th> Damage Occured/ Reported Date</th>
                                            <th> Damage Quantity </th>
                                            <th> Damage Note </th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $serial = 0;
                                        @endphp
                                        {{-- @dd($asset_damage->damageActivityLogs) --}}

                                        @foreach ($asset_damage->damageActivityLogs as $activityLog)
                                            <tr>
                                                <td class="text-center">{{ ++$serial }}</td>
                                                <td class="text-center">{{ $activityLog->date }}</td>
                                                <td class="text-center">{{ $activityLog->quantity }}</td>
                                                <td class="text-center"
                                                    style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $activityLog->damage_note }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            @elseif(!empty($asset_damage->asset->asset_type) && $asset_damage->asset->asset_type == 'Usable')
                                <table class="table table-sm">

                                    <tr>
                                        <th width="20%">Asset Code: </th>
                                        <td>{{ $asset_damage->asset->asset_code }}</td>
                                    </tr>


                                    <tr>
                                        <th width="20%">Asset: </th>
                                        <td>{{ $asset_damage->asset->name ?? '' }}</td>

                                        <th width="20%">Asset Group: </th>
                                        <td>{{ $asset_damage->asset->asset_group->name ?? '' }}</td>
                                    </tr>

                                    <tr>
                                        <th width="20%">Asset Category: </th>
                                        <td>{{ $asset_damage->asset->asset_group->asset_category->name ?? '' }}</td>

                                        <th width="20%">Asset Type: </th>
                                        <td>{{ $asset_damage->asset->asset_type ?? '' }}</td>
                                    </tr>

                                </table>

                                <table class="table table-sm">
                                    <thead>
                                        <tr class="bg-info text-center">
                                            <th> SL</th>
                                            <th> Damage Occured/ Reported Date</th>
                                            <th> Damage Quantity </th>
                                            <th> Damage Note </th>
                                        </tr>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $serial = 0;
                                        @endphp
                                        {{-- @dd($asset_damage->damageActivityLogs) --}}

                                        @foreach ($asset_damage->damageActivityLogs as $activityLog)
                                            <tr>
                                                <td class="text-center">{{ ++$serial }}</td>
                                                <td class="text-center">{{ $activityLog->date }}</td>
                                                <td class="text-center">{{ $activityLog->quantity }}</td>
                                                <td class="text-center"
                                                    style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $activityLog->damage_note }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            @endif

                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <!-- Fixed Damage Asset Modal -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Fixed Damage Asset</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="fixedAsset" action="{{ route('save_fixed_asset_damage') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="employee_id">Employee<i class="text-danger">*</i></label>
                                <select name="employee_id" id="employee_id" class="form-control form-select" required>
                                    <option value="" selected>Select Employee</option>
                                    {{-- @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                            ({{ $employee->department->name }}, {{ $employee->designation->name }})
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <label for="asset_group_id">Asset Group<i class="text-danger">*</i></label>
                                <select name="asset_group_id" id="asset_group_id" class="form-control form-select"
                                    required>
                                    <option value="" selected>Select Group</option>
                                    @foreach ($asset_group as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}
                                            ({{ $group->asset_category->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Asset<i class="text-danger">*</i></label>
                                    <select name="asset_id" id="asset_id" class="form-control form-select" required>
                                        <option value="" selected>Select Asset</option>
                                        {{-- @dd($assets) --}}
                                        @foreach ($assets as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->name }}
                                                ({{ $asset->asset_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="mb-3">
                                    <b>After Damage or Lost (Remaining Quantity) / Assigned Quantity </b><input
                                        type="text" class="form-control" id="assigned_quantity" name="quantity"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Damage Quantity<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="damage_quantity" name="quantity"
                                        placeholder="Damage Quantity" required min="1">
                                    <div class="invalid-feedback" id="damage_quantity_feedback"></div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Damage Occured/ Reported Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                        class="form-control" data-date-format="yyyy-mm-dd">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Damage Note</label>
                                    <textarea type="text" cols="4" rows="4" name="damage_note" id="damage_note" class="form-control"
                                        placeholder="Damage Note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Fixed Damage Asset Modal End -->


    <!-- Usable Damage Asset Modal -->
    <div class="modal fade" id="modal-add-usable">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Usable Damage Asset</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_usable_asset_damage') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="asset_group_id">Asset Group<i class="text-danger">*</i></label>
                                <select name="asset_group_id" id="asset_group_id_usable" class="form-control form-select"
                                    required>
                                    <option value="" selected>Select Group</option>
                                    @foreach ($asset_groupU as $group)
                                        <option value="{{ $group->id }}">{{ $group->name }}
                                            ({{ $group->asset_category->name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Asset<i class="text-danger">*</i></label>
                                    <select name="asset_id" id="asset_id_usable" class="form-control form-select"
                                        required>
                                        <option value="" selected>Select Asset</option>
                                        {{-- @dd($assets) --}}
                                        @foreach ($assetsU as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->name }}
                                                ({{ $asset->asset_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Damage Quantity<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                        placeholder="Damage Quantity" required min="1">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Damage Occured/ Reported Date</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                        class="form-control" data-date-format="yyyy-mm-dd">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Damage Note</label>
                                    <textarea type="text" cols="4" rows="4" name="damage_note" id="damage_note" class="form-control"
                                        placeholder="Damage Note"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- Usable Damage Asset Modal End -->

    <!-- Edit Modal -->
    {{-- @dd($asset_damages) --}}
    @foreach ($asset_damages as $asset_damage)
        @foreach ($asset_damage->damageActivityLogs as $damageActivityLog)
            <div class="modal fade update update-modal-{{ $asset_damage->id }}" id="exampleModal" tabindex="-1"
                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-center">
                            <h5>Update Damage Asset</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="fixedAsset-{{ $asset_damage->id }}"
                            action="{{ route('update_damage_asset', $asset_damage->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="asset_id" value="{{ $asset_damage->asset_id }}">
                                    <input type="hidden" name="asset_group_id"
                                        value="{{ $asset_damage->asset_group_id }}">
                                    <input type="hidden" name="employee_id" value="{{ $asset_damage->employee_id }}">
                                    <input type="hidden" name="id" value="{{ $damageActivityLog->id }}">
                                    @if (!empty($asset_damage->asset->asset_type) && $asset_damage->asset->asset_type == 'Fixed')
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Asset: {{ $asset_damage->asset->name }}
                                                    ({{ $asset_damage->asset->asset_code }},
                                                    {{ $asset_damage->asset->asset_type }})
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Asset Group:
                                                    {{ $asset_damage->asset->asset_group->name ?? '' }}
                                                    ({{ $asset_damage->asset->asset_group->asset_category->name ?? '' }})</label>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Employee:
                                                    {{ $asset_damage->employee->name ?? '' }}
                                                    ({{ $asset_damage->employee->department->name ?? '' }},
                                                    {{ $asset_damage->employee->designation->name ?? '' }})
                                                </label>
                                            </div>

                                        </div>

                                        @php
                                            $assetId = $asset_damage->asset_id;
                                            $employeeId = $asset_damage->employee_id;
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
                                                $totalLostQuantity = App\Models\LostActivityLog::where(
                                                    'lost_id',
                                                    $lostQuantity->id,
                                                )->sum('quantity');
                                            }

                                            $remainingQuantity =
                                                $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;
                                        @endphp
                                        <div class="col-lg-7">
                                            <div class="mb-3">
                                                <b>After Damage or Lost (Remaining Quantity) / Assigned Quantity </b>
                                                <input type="text" class="form-control"
                                                    id="assigned_quantity_{{ $asset_damage->id }}" name="quantity"
                                                    value="{{ $remainingQuantity }} / {{ $assignedQuantity }}" readonly
                                                    data-asset-id="{{ $asset_damage->id }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Damage Quantity<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" class="form-control"
                                                    id="damage_quantity-{{ $asset_damage->id }}" name="quantity"
                                                    value="{{ $asset_damage->quantity }}" placeholder="Damage Quantity"
                                                    required min="0">
                                                <div class="invalid-feedback"
                                                    id="damage_quantity_feedback_{{ $asset_damage->id }}"></div>
                                            </div>
                                        </div>

                                        {{-- <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Damage Quantity<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" id="quantity" name="quantity"
                                                value="{{ $asset_damage->quantity }}" placeholder="Damage Quantity"
                                                required min="0">
                                        </div>
                                    </div> --}}
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Damage Occured/ Reported Date</label>
                                                <input type="date" name="date" value="{{ $asset_damage->date }}"
                                                    class="form-control" data-date-format="yyyy-mm-dd">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Damage Note</label>
                                                <textarea type="text" cols="4" rows="4" name="damage_note" id="damage_note" class="form-control"
                                                    placeholder="Damage Note">{{ $asset_damage->damage_note }}</textarea>
                                            </div>
                                        </div>
                                    @elseif(!empty($asset_damage->asset->asset_type) && $asset_damage->asset->asset_type == 'Usable')
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Asset: {{ $asset_damage->asset->name }}
                                                    ({{ $asset_damage->asset->asset_code }},
                                                    {{ $asset_damage->asset->asset_type }})
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Asset Group:
                                                    {{ $asset_damage->asset->asset_group->name ?? '' }}
                                                    ({{ $asset_damage->asset->asset_group->asset_category->name ?? '' }})</label>
                                            </div>

                                        </div>

                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Damage Quantity<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" class="form-control" id="quantity"
                                                    name="quantity" value="{{ $asset_damage->quantity }}"
                                                    placeholder="Damage Quantity" required min="0">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Damage Occured/ Reported Date</label>
                                                <input type="date" name="date" value="{{ $asset_damage->date }}"
                                                    class="form-control" data-date-format="yyyy-mm-dd">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Damage Note</label>
                                                <textarea type="text" cols="4" rows="4" name="damage_note" id="damage_note" class="form-control"
                                                    placeholder="Damage Note">{{ $asset_damage->damage_note }}</textarea>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>



                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
    <!-- Edit Modal End-->
@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('[id^=employee_id]').change(function() {
            var employeeId = $(this).val();
            var modalId = $(this).attr('id').split('-')[1];
            $.ajax({
                type: "GET",
                url: "{{ route('getEmployeeWiseGroup') }}",
                data: {
                    employee_id: employeeId
                },
                dataType: "json",
                success: function(response) {
                    $('#asset_group_id-' + modalId).empty().append(
                        '<option value="" selected>Select Group</option>');
                    $.each(response, function(index, group) {
                        $('#asset_group_id-' + modalId).append(
                            '<option value="' + group.asset_group_id +
                            '">' + group.asset_group.name + '(' + group
                            .asset_group.asset_category.name + ')</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('[id^=asset_group_id]').change(function() {
            var groupId = $(this).val();
            var modalId = $(this).attr('id').split('-')[1];
            var employeeId = $('#employee_id-' + modalId).val();

            $.ajax({
                type: "GET",
                url: "{{ route('getEmployeeWiseGroupAsset') }}",
                data: {
                    group_id: groupId,
                    employee_id: employeeId
                },
                dataType: "json",
                success: function(response) {
                    $('#asset_id-' + modalId).empty().append(
                        '<option value="" selected>Select Asset</option>');
                    $.each(response, function(index, asset) {
                        $('#asset_id-' + modalId).append('<option value="' + asset
                            .asset_id + '">' + asset.asset.name + ' (' +
                            asset.asset.asset_type + ')</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('[id^=asset_group_id_usable]').change(function() {
            var groupId = $(this).val();
            var modalId = $(this).attr('id').split('-')[1];

            $.ajax({
                type: "GET",
                url: "{{ route('getGroupWiseAssetUsable') }}",
                data: {
                    group_id: groupId,
                },
                dataType: "json",
                success: function(response) {
                    $('#asset_id_usable-' + modalId).empty().append(
                        '<option value="" selected>Select Asset</option>');
                    $.each(response, function(index, asset) {
                        $('#asset_id_usable-' + modalId).append('<option value="' +
                            asset.asset_id + '">' + asset.asset.name +
                            ' (' + asset.asset.asset_type + ')</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#employee_id').change(function() {
            var employeeId = $(this).val();
            // console.log(employeeId);

            $.ajax({
                type: "GET",
                url: "{{ route('getEmployeeWiseGroup') }}",
                data: {
                    employee_id: employeeId
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $('#asset_group_id').empty().append(
                        '<option value="" selected>Select Group</option>');
                    $.each(response, function(index, group) {
                        var categoryName = group.asset_group.asset_category.name;
                        // console.log(categoryName);
                        $('#asset_group_id').append('<option value="' + group
                            .asset_group_id + '">' + group.asset_group.name +
                            '(' + categoryName + ')</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert("An error occurred while fetching data. Please try again later.");
                }
            });

        });

        $('#asset_group_id').change(function() {
            var groupId = $(this).val();
            var employeeId = $('#employee_id').val();

            $.ajax({
                type: "GET",
                url: "{{ route('getEmployeeWiseGroupAsset') }}",
                data: {
                    group_id: groupId,
                    employee_id: employeeId
                },
                dataType: "json",
                success: function(response) {
                    $('#asset_id').empty().append(
                        '<option value="" selected>Select Asset</option>');
                    $.each(response, function(index, asset) {
                        $('#asset_id').append('<option value="' + asset.asset_id +
                            '">' + asset.asset.name + ' (' + asset.asset
                            .asset_type + ')</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('#asset_group_id_usable').change(function() {
            var groupId = $(this).val();
            // console.log(groupId);

            $.ajax({
                type: "GET",
                url: "{{ route('getGroupWiseAssetUsable') }}",
                data: {
                    group_id: groupId,
                },
                dataType: "json",
                success: function(response) {
                    $('#asset_id_usable').empty().append(
                        '<option value="" selected>Select Asset</option>');
                    $.each(response, function(index, asset) {
                        $('#asset_id_usable').append('<option value="' + asset
                            .asset_id + '">' + asset.asset.name + ' (' + asset
                            .asset.asset_type + ')</option>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('#fixedAsset').submit(function(event) {
            var damageQuantity = $('#damage_quantity').val();
            var remainingQuantity = $('#assigned_quantity').val().split('/')[
                0];

            if (parseInt(damageQuantity) > parseInt(remainingQuantity)) {
                $('#damage_quantity').addClass('is-invalid');
                $('#damage_quantity_feedback').text('Damage quantity cannot exceed remaining quantity');
                event.preventDefault();
            } else {
                $('#damage_quantity').removeClass('is-invalid');
                $('#damage_quantity_feedback').text('');
            }
        });



        $('#asset_id').change(function() {
            var assetId = $(this).val();
            if (assetId) {
                var employeeId = $('#employee_id').val();

                $.ajax({
                    type: "GET",
                    url: "{{ route('getAssignedQuantity') }}",
                    data: {
                        asset_id: assetId,
                        employee_id: employeeId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            var assignedQuantity = response.quantity;
                            $('#assigned_quantity').val(assignedQuantity);
                        } else {
                            $('#assigned_quantity').val('');
                        }
                    }
                });
            } else {
                $('#assigned_quantity').val('');
            }
        });


        $('[id^=asset_id]').change(function() {
            var assetId = $(this).val();
            var assetDamageId = $(this).attr('id').split('-').pop();
            var assignedQuantityId = '#assigned_quantity_' + assetDamageId;

            if (assetId) {
                var employeeId = $('#employee_id-' + assetDamageId).val();

                $.ajax({
                    type: "GET",
                    url: "{{ route('getAssignedQuantity') }}",
                    data: {
                        asset_id: assetId,
                        employee_id: employeeId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            var assignedQuantity = response.quantity;
                            $(assignedQuantityId).val(assignedQuantity);
                        } else {
                            $(assignedQuantityId).val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $(assignedQuantityId).val('');
            }
        });


        $('[id^=fixedAsset]').submit(function(event) {
            var formValid = true;

            $(this).find('[id^=damage_quantity]').each(function() {
                var damageQuantity = $(this).val();
                var assetDamageId = $(this).attr('id').split('-').pop();
                var assignedQuantityElement = $('#assigned_quantity_' + assetDamageId);

                // console.log("Asset Damage ID: " + assetDamageId);
                // console.log("Assigned Quantity Element:", assignedQuantityElement);

                if (assignedQuantityElement.length > 0) {
                    var remainingQuantity = assignedQuantityElement.val().split('/')[0];

                    // console.log("Remaining Quantity: " + remainingQuantity);

                    if (parseInt(damageQuantity) > parseInt(remainingQuantity)) {
                        $(this).addClass('is-invalid');
                        $('#damage_quantity_feedback_' + assetDamageId).text(
                            'Damage quantity cannot exceed remaining quantity');
                        formValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                        $('#damage_quantity_feedback_' + assetDamageId).text('');
                    }
                } else {
                    console.error(
                        "Assigned Quantity Element not found for ID: assigned_quantity_" +
                        assetDamageId);
                }
            });

            // console.log("Form Valid: " + formValid);

            if (!formValid) {
                // console.log("Form submission prevented");
                event.preventDefault();
            }
        });

    });
</script>

<script>
    function printPage() {
        var printContents = document.getElementById('printArea').innerHTML;
        var currentDate = new Date().toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        var originalContents = document.body.innerHTML;
        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style>@media print {body {-webkit-print-color-adjust: exact;}' +
            'table {width: 100%; border-collapse: collapse;}' +
            'table, th, td {border: 1px solid black; padding: 8px;}' +
            'th {background-color: #f2f2f2;}' +
            'td {text-align: center;}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div style="text-align: center;">Date: ' + currentDate + '</div>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
        document.body.innerHTML = originalContents;

        window.addEventListener('afterprint', function() {
            closePrintModal();
        });
    }

    function closePrintModal() {
        $('.modal').modal('hide');
    }
</script>
