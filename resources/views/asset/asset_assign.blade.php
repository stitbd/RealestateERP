@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Asset Assign List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Asset Assign (Fixed)
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <form action="{{ route('asset_assign_list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-3">
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" class="form-control">
                                        <option value="" selected>Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}
                                                ({{ $employee->department->name }}, {{ $employee->designation->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="asset_group_id">Asset Group</label>
                                    <select name="asset_group_id" class="form-control">
                                        <option value="" selected>Select Group</option>
                                        @foreach ($asset_group as $group)
                                            {{-- @if ($category->asset_type == 'Fixed') --}}
                                            <option value="{{ $group->id }}">{{ $group->name }}
                                                ({{ $group->asset_category->name }})
                                            </option>
                                            {{-- @endif --}}
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
                                        @foreach ($assets as $asset)
                                            {{-- @if ($category->asset_type == 'Fixed') --}}
                                            <option value="{{ $asset->id }}">{{ $asset->name }}
                                            </option>
                                            {{-- @endif --}}
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
                                    <label for="action">--</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                {{-- <a href="{{ url('asset_assign_print?employee_id=' . request()->get('employee_id') . '&asset_group_id=' . request()->get('asset_group_id') . '&asset_code=' . request()->get('asset_code') . '&asset_id=' . request()->get('asset_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a> --}}
                                {{-- <a href="{{url('project-received-pdf?project_id='.request()->get('project_id').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn  btn-danger float-end m-2">
                                        <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf 
                                    </a> --}}
                            </div>
                        </div>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th> Assign Date </th>
                                    <th> Assigned User </th>
                                    <th> Asset Code </th>
                                    <th> Asset Group</th>
                                    <th> Asset </th>
                                    <th> Asset Quantity </th>
                                    <th> Asset Category </th>
                                    <th> Action </th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($asset_assigned as $asset_assign)
                                    @if ($asset_assign->status != 'revoke')
                                        <tr>
                                            <td class="text-center">{{ ++$serial }}</td>
                                            <td class="text-center">{{ $asset_assign->assign_date ?? '' }}</td>
                                            <td class="text-center"><b>{{ $asset_assign->user_name ?? '' }}</b>
                                                ({{ $asset_assign->employee->department->name ?? '' }},
                                                {{ $asset_assign->employee->designation->name ?? '' }})
                                            </td>
                                            <td class="text-center">{{ $asset_assign->asset->asset_code ?? '' }}</td>
                                            <td class="text-center">{{ $asset_assign->asset->asset_group->name ?? '' }}
                                            </td>
                                            <td class="text-center">{{ $asset_assign->asset->name ?? '' }}
                                                ({{ $asset_assign->asset->asset_type ?? '' }})</td>
                                            <td class="text-center">{{ $asset_assign->quantity ?? '' }}</td>
                                            <td class="text-center">
                                                {{ $asset_assign->asset->asset_group->asset_category->name ?? '' }}</td>
                                            <td width="25%" class="text-center">
                                                <a data-toggle="modal" data-target=".view-modal-{{ $asset_assign->id }}"><i
                                                        class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>

                                                <a data-toggle="modal" data-target=".update-modal-{{ $asset_assign->id }}"
                                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a data-toggle="modal" data-target=".revoke-modal-{{ $asset_assign->id }}"
                                                    style="padding:2px; color:white" class="btn btn-xs btn-dark  mr-1">
                                                    <i class="fas fa-user-edit"></i>Revoke Asset
                                                </a>
                                                <a href="{{ route('asset_assign_delete', $asset_assign->id) }}"
                                                    onclick="return confirm('Are you sure you want to delete?');"
                                                    style="padding: 2px;" class="delete btn btn-xs btn-danger  mr-1">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $asset_assigned->links() }}
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- Revoke Modal -->
    @foreach ($asset_assigned as $asset_assign)
        {{-- @dd($asset_assign) --}}
        <div class="modal fade revoke-modal-{{ $asset_assign->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Revoke Asset</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="col-lg-12">
                        @php
                            $purchaseAssetId = App\Models\AssetPurchaseDetails::with('asset_purchase')
                                ->where('asset_id', $asset_assign->asset_id)
                                ->first();
                            // dd($purchaseAssetId);
                            $stockAssetId = App\Models\AssetStock::where('asset_id', $asset_assign->asset_id)->first();
                        @endphp
                        <div class="mb-3">
                            {{-- <label class="form-label">Employee: {{ $asset_assign->employee->name }}
                                ({{ $asset_assign->employee->department->name }},
                                {{ $asset_assign->employee->designation->name }})
                            </label> --}}
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asset: {{ $asset_assign->asset->name ?? '' }}
                                ({{ $asset_assign->asset->asset_code ?? '' }},
                                {{ $asset_assign->asset->asset_type ?? '' }})
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asset Category:
                                {{ $asset_assign->asset->asset_group->asset_category->name ?? '' }}</label>
                        </div>
                        {{-- <div class="mb-3">
                            <label class="form-label">Current Stock: <span
                                    style="color: red;">{{ $stockAssetId->quantity }}</span></label>
                        </div> --}}
                    </div>
                    <form action="{{ route('save_revoke_asset', $asset_assign->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="asset_id" value="{{ $asset_assign->asset_id }}">
                                {{-- <input type="hidden" name="employee_id" value="{{ $asset_assign->employee_id }}"> --}}
                                <input type="hidden" name="asset_group_id" value="{{ $asset_assign->asset_group_id }}">
                                {{-- @php
                                    $assetId = $asset_assign->asset_id;
                                    $employeeId = $asset_assign->employee_id;
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

                                    $remainingQuantity = $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;
                                @endphp --}}

                                {{-- <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">After Damage or Lost (Remaining Quantity)</label>
                                        <input type="text" class="form-control" value="{{ $remainingQuantity }}"
                                            placeholder="Quantity" required readonly>
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Assigned Quantity</label>
                                        <input type="text" class="form-control" name="quantity"
                                            value="{{ $asset_assign->quantity }}" placeholder="Quantity" required
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Reason</label>
                                        <textarea type="text" cols="4" rows="4" class="form-control" id="reason" name="reason"
                                            placeholder="Reason"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Revoke Date</label>
                                        <input type="date" class="form-control" id="revoke_date" name="revoke_date"
                                            value="{{ date('Y-m-d') }}" data-date-format="yyyy-mm-dd">
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
            </div>
        </div>
    @endforeach
    <!-- Revoke Modal End-->

    <!-- View Modal -->
    @foreach ($asset_assigned as $asset_assign)
        {{-- @dd($asset_d) --}}
        <div class="modal fade view-modal-{{ $asset_assign->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Assigned Asset</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Assign Date: </th>
                                    <td>{{ $asset_assign->assign_date }}</td>

                                    <th width="20%">Assigned Quantity: </th>
                                    <td style="color: red;">{{ $asset_assign->quantity }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    {{-- <th width="20%">Assigned Employee: </th>
                                    <td><b>{{ $asset_assign->employee->name }}</b>
                                        ({{ $asset_assign->employee->department->name }},
                                        {{ $asset_assign->employee->designation->name }})
                                    </td> --}}
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Asset Code: </th>
                                    <td>{{ $asset_assign->asset->asset_code ?? '' }}</td>

                                    <th width="20%">Asset Group: </th>
                                    <td>{{ $asset_assign->asset->asset_group->name ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Asset: </th>
                                    <td>{{ $asset_assign->asset->name ?? '' }}</td>
                                </tr>
                            </table>

                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Asset Category: </th>
                                    <td>{{ $asset_assign->asset->asset_group->asset_category->name ?? '' }}</td>

                                    <th width="20%">Asset Type: </th>
                                    <td>{{ $asset_assign->asset->asset_type ?? '' }}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <!-- Add Modal -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Asset Assign</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="asset_assign" action="{{ route('save_asset_assign') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="user_name">Assign User Name<i class="text-danger">*</i></label>
                                <input type="text" name="user_name" class="form-control">
                                {{-- <select name="employee_id" id="employee_id" class="form-control form-select" required>
                                    <option value="" selected>Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                            ({{ $employee->department->name }}, {{ $employee->designation->name }})
                                        </option>
                                    @endforeach
                                </select> --}}
                            </div>
                            <div class="col-lg-12">
                                <label for="asset_group_id">Asset Group<i class="text-danger">*</i></label>
                                <select name="asset_group_id" id="asset_group_id" class="form-control form-select"
                                    required>
                                    <option value="" selected>Select Group</option>
                                    @foreach ($asset_group as $group)
                                        {{-- @if ($category->asset_type == 'Fixed') --}}
                                        <option value="{{ $group->id }}">{{ $group->name }}
                                            ({{ $group->name }})
                                        </option>
                                        {{-- @endif --}}
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Asset<i class="text-danger">*</i></label>
                                    <select name="asset_id" id="asset_id" class="form-control form-select" required>
                                        <option value="" selected>Select Asset</option>
                                        @foreach ($assets as $asset)
                                                <option value="{{ $asset->id }}">{{ $asset->name }}
                                                </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <b>Left Quantity</b> <input type="number" name="quantity" id="stock_quantity"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Assign Quantity<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="assign_quantity" name="quantity"
                                        min="1" placeholder="Quantity Of Asset">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Assign Date</label>
                                    <input type="date" class="form-control" id="assign_date" name="assign_date"
                                        value="{{ date('Y-m-d') }}" data-date-format="yyyy-mm-dd">
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
    <!-- /.modal -->



    <!-- Edit Modal -->
    @foreach ($asset_assigned as $asset_assign)
        {{-- @dd($asset_d) --}}
        <div class="modal fade update update-modal-{{ $asset_assign->id }}" id="exampleModal" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Asset Assign</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form id="asset_assign_update" action="{{ route('update_asset_assign', $asset_assign->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                {{-- <div class="col-lg-12">
                                    <label for="employee_id">Employee<i class="text-danger">*</i></label>
                                    <select name="employee_id" id="employee_id" class="form-control form-select"
                                        required>
                                        <option value="" selected>Select Employee</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                @if ($employee->id == $asset_assign->employee_id) selected @endif>{{ $employee->name }}
                                                ({{ $employee->department->name }}, {{ $employee->designation->name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <div class="col-lg-12">
                                    <label for="asset_group_id">Asset Group<i class="text-danger">*</i></label>
                                    <select name="asset_group_id" id="asset_group_id-{{ $asset_assign->id }}"
                                        class="form-control form-select" required>
                                        <option value="" selected>Select Group</option>
                                        @foreach ($asset_group as $group)
                                            {{-- @if ($category->asset_type == 'Fixed') --}}
                                            <option value="{{ $group->id }}"
                                                @if ($group->id == $asset_assign->asset_group_id) selected @endif>{{ $group->name }}
                                                ({{ $group->asset_category->name }})
                                            </option>
                                            {{-- @endif --}}
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Asset<i class="text-danger">*</i></label>
                                        <select name="asset_id" id="asset_id-{{ $asset_assign->id }}"
                                            class="form-control form-select" required>
                                            <option value="" selected>Select Asset</option>
                                            {{-- @dd($assets) --}}
                                            @foreach ($assets as $asset)
                                                @if ($asset->asset_type == 'Fixed')
                                                    <option value="{{ $asset->id }}"
                                                        @if ($asset->id == $asset_assign->asset_id) selected @endif>
                                                        {{ $asset->name }} ({{ $asset->asset_type }})
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    @php
                                        $assetId = $asset_assign->asset_id;
                                        // dd($assetId);
                                        $assetStock = App\Models\AssetStock::where('asset_id', $assetId)->first();
                                        $assignedQuantity = App\Models\AssetAssign::where('asset_id', $assetId)->sum(
                                            'quantity',
                                        );
                                        $quantity = $assetStock->quantity - $assignedQuantity;

                                    @endphp
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <b>Left Quantity</b> <input type="number" name="quantity"
                                                id="stock_quantity-{{ $asset_assign->id }}" readonly
                                                value="{{ $quantity }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Assign Quantity<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" id="quantity_update"
                                            value="{{ $asset_assign->quantity }}" name="quantity" min="0">
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Assign Date</label>
                                        <input type="date" class="form-control" id="assign_date" name="assign_date"
                                            value="{{ $asset_assign->assign_date }}" data-date-format="yyyy-mm-dd">
                                    </div>
                                </div>
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
    <!-- /.modal -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- <script>
    $(document).ready(function() {
        $('[id^=asset_group_id]').change(function() {
            var asset_group_id = $(this).val();
            var id = $(this).attr('id').split('-').pop();

            $.ajax({
                url: '/getStockAsset',
                type: 'GET',
                data: {
                    asset_group_id: asset_group_id
                },
                success: function(response) {
                    // console.log(response);
                    var assets = response;
                    var options = '<option value="" selected>Select Asset</option>';

                    $.each(assets, function(index, asset) {
                        // if (asset.asset_type === 'Fixed') {
                            options += '<option value="' + asset.id + '">' + asset
                                .name '</option>';
                        // } 
                    });

                    $('#asset_id-' + id).html(options);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script> --}}
<script>
    $(document).ready(function() {
        $('#asset_group_id').change(function() {
            var asset_group_id = $(this).val();

            $.ajax({
                url: '/getStockAsset',
                type: 'GET',
                data: {
                    asset_group_id: asset_group_id
                },
                success: function(response) {
                    console.log(response);
                    var assets = response;
                    var options = '<option value="" selected>Select Asset</option>';

                    $.each(assets, function(index, asset) {
                        // if (asset.asset_type === 'Fixed') {
                            options += '<option value="' + asset.id + '">' + asset
                                .name +'</option>';
                        // }
                    });

                    $('#asset_id').html(options);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        });

        $('#asset_id').change(function() {
            var assetId = $(this).val();
            console.log(assetId);
            if (assetId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getStockQuantity') }}",
                    data: {
                        asset_id: assetId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            $('#stock_quantity').val(response.quantity);
                        } else {
                            $('#stock_quantity').val('');
                        }
                    }
                });
            } else {
                $('#stock_quantity').val('');
            }
        });

        $('select[name="asset_id"]').change(function() {
            var assetId = $(this).val();
            var assignId = $(this).attr('id').split('-')[1];
            var stockQuantityInput = $('#stock_quantity-' + assignId);
            if (assetId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getStockQuantity') }}",
                    data: {
                        asset_id: assetId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            stockQuantityInput.val(response.quantity);
                        } else {
                            stockQuantityInput.val('');
                        }
                    }
                });
            } else {
                stockQuantityInput.val('');
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#assign_quantity').on('input', function() {
            var assignQuantity = parseInt($(this).val());
            var stockQuantity = parseInt($('#stock_quantity').val());

            if (isNaN(assignQuantity)) {
                $(this).addClass('is-invalid');
                return;
            }

            if (assignQuantity <= stockQuantity) {
                $(this).removeClass('is-invalid');
            } else {
                $(this).addClass('is-invalid');
            }
        });

        $('#asset_assign').submit(function(event) {
            var assignQuantity = parseInt($('#assign_quantity').val());
            var stockQuantity = parseInt($('#stock_quantity').val());

            if (isNaN(assignQuantity) || assignQuantity > stockQuantity) {
                event.preventDefault();
                $('#assign_quantity').addClass('is-invalid');
                return false;
            }
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#quantity_update').on('input', function() {
            var assignQuantity = parseInt($(this).val());
            var stockQuantity = parseInt($(
                '#stock_quantity-{{ isset($asset_assign) ? $asset_assign->id : '' }}').val());

            if (isNaN(assignQuantity)) {
                $(this).addClass('is-invalid');
                return;
            }

            if (assignQuantity <= stockQuantity) {
                $(this).removeClass('is-invalid');
            } else {
                $(this).addClass('is-invalid');
            }
        });

        $('#asset_assign_update').submit(function(event) {
            var assignQuantity = parseInt($('#quantity_update').val());
            var stockQuantity = parseInt($(
                '#stock_quantity-{{ isset($asset_assign) ? $asset_assign->id : '' }}').val());

            if (isNaN(assignQuantity) || assignQuantity > stockQuantity) {
                event.preventDefault();
                $('#quantity_update').addClass('is-invalid');
                return false;
            }
        });
    });
</script>
