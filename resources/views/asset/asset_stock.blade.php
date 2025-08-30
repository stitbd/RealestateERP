@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Asset Stock List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form action="{{ route('asset_stock') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-3">
                                    <label for="asset_code">Asset Code</label>
                                    <input type="text" class="form-control" name="asset_code" placeholder="Asset Code">
                                </div>
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
                                            <option value="{{ $asset->id }}">{{ $asset->name }}
                                                ({{ $asset->asset_type }})
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
                                <a href="{{ url('asset_stock_print?asset_code=' . request()->get('asset_code') . '&asset_id=' . request()->get('asset_id') . '&asset_group_id=' . request()->get('asset_group_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
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
                                    <th>SL</th>
                                    <th> Asset Code </th>
                                    <th> Asset Group </th>
                                    <th> Asset </th>
                                    <th> Asset Category </th>
                                    <th> Asset Type </th>
                                    <th> Current Stock </th>
                                    <th> Action </th>
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
                                        <td class="text-center">
                                            {{ $asset_stock->asset->asset_group->asset_category->name ?? '' }}</td>
                                        <td class="text-center">{{ $asset_stock->asset->asset_type ?? '' }}</td>
                                        <td class="text-center" style="color: red;">{{ $asset_stock->quantity ?? '' }}</td>
                                        <td width="25%" class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $asset_stock->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a data-toggle="modal" data-target=".lost-modal-{{ $asset_stock->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-danger  mr-1">
                                                <i class="fas fa-times"></i> Report Lost
                                            </a>
                                            <a data-toggle="modal" data-target=".liquidation-modal-{{ $asset_stock->id }}"
                                                style="padding:2px; color:rgb(44, 41, 41)"
                                                class="btn btn-xs btn-warning  mr-1">
                                                <i class="fas fa-minus"></i> Liquidation
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $asset_stocks->links() }}
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    @foreach ($asset_stocks as $asset_stock)
        <div class="modal fade view-modal-{{ $asset_stock->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Asset Stock</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Asset Code: </th>
                                    <td>{{ $asset_stock->asset->asset_code ?? '' }}</td>

                                    <th width="20%">Asset Group: </th>
                                    <td>{{ $asset_stock->asset->asset_group->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Asset: </th>
                                    <td>{{ $asset_stock->asset->name ?? '' }}</td>

                                    <th width="20%">Asset Category: </th>
                                    <td>{{ $asset_stock->asset->asset_group->asset_category->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Asset Type: </th>
                                    <td>{{ $asset_stock->asset->asset_type ?? '' }}</td>

                                    <th width="20%">Quantity: </th>
                                    <td style="color: red;">{{ $asset_stock->quantity ?? '' }}</td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <!-- Lost Modal -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    @foreach ($asset_stocks as $asset_stock)
        <div class="modal fade update lost-modal-{{ $asset_stock->id }}"
            data-asset-type="{{ $asset_stock->asset->asset_type }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Report Lost Asset</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="col-lg-12">
                        @php
                            $purchaseAssetId = App\Models\AssetPurchaseDetails::with('asset_purchase')
                                ->where('asset_id', $asset_stock->asset_id)
                                ->first();
                            // dd($purchaseAssetId);
                        @endphp
                        <div class="mb-3">
                            <label class="form-label">Asset: {{ $asset_stock->asset->name ?? '' }}
                                ({{ $asset_stock->asset->asset_code ?? '' }}, {{ $asset_stock->asset->asset_type ?? '' }})
                            </label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Asset Group: {{ $asset_stock->asset->asset_group->name ?? '' }}
                                ({{ $asset_stock->asset->asset_group->asset_category->name ?? '' }})</label>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Stock: <span
                                    style="color: red;">{{ $asset_stock->quantity }}</span></label>
                        </div>
                    </div>
                    <form id="lostAsset-{{ $asset_stock->id }}" class="lostAssetForm"
                        action="{{ route('save_report_lost_asset', $asset_stock->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="asset_id" class="asset_id_hidden"
                                    value="{{ $asset_stock->asset_id }}">
                                <input type="hidden" name="asset_group_id" value="{{ $asset_stock->asset_group_id }}">
                                {{-- @if ($asset_stock->asset->asset_type == 'Fixed')
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="employee_id">Assigned Employee<i class="text-danger">*</i></label>
                                            <select name="employee_id" class="employee_id form-control" required>
                                                <option value="" selected>Select Employee</option>
                                                @foreach ($employees as $employee)
                                                    @php
                                                        $assetAssign = App\Models\AssetAssign::where(
                                                            'employee_id',
                                                            $employee->id,
                                                        )
                                                            ->where('asset_id', $asset_stock->asset_id)
                                                            ->where('asset_group_id', $asset_stock->asset_group_id)
                                                            ->first();
                                                    @endphp
                                                    @if ($assetAssign)
                                                        <option value="{{ $employee->id }}">{{ $employee->name }}
                                                            ({{ $employee->department->name }},
                                                            {{ $employee->designation->name }})
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label for="assigned_quantity">After Damage or Lost (Remaining Quantity) /
                                                Assigned Quantity</label>
                                            <input type="text" class="form-control assigned-quantity" readonly>
                                        </div>
                                    </div>
                                @endif --}}
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Lost Quantity<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control quantity_input" name="quantity"
                                            placeholder="Quantity" required min="1"
                                            max="{{ $asset_stock->quantity }}">
                                        <div class="invalid-feedback" id="lost_quantity_feedback"></div>
                                    </div>
                                </div>
                                @if ($asset_stock->asset->asset_type == 'Fixed')
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Fine</label>
                                            <input type="number" class="form-control fine_input" name="fine"
                                                placeholder="Fine" min="0">
                                        </div>
                                    </div>
                                @endif
                                <input type="hidden" class="asset_id_hidden" value="{{ $asset_stock->asset_id }}">
                                <input type="hidden" class="unit_price"
                                    value="{{ $purchaseAssetId->unit_price ?? 0 }}">

                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea type="text" cols="4" rows="4" class="form-control" name="description"
                                            placeholder="Description"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Report Date</label>
                                        <input type="date" class="form-control" name="report_date"
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

    <script>
        $(document).ready(function() {
            $('.quantity_input').on('input', function() {
                var assetId = $(this).closest('.modal-content').find('.asset_id_hidden').val();
                var unitPrice = $(this).closest('.modal-content').find('.unit_price').val();
                var quantity = $(this).val();
                var fine = unitPrice * quantity;
                $(this).closest('.modal-content').find('.fine_input').val(fine);
            });

            $('.employee_id').change(function() {
                var employeeId = $(this).val();
                var assetId = $(this).closest('.modal-content').find('.asset_id_hidden').val();
                var assignedQuantityInput = $(this).closest('.modal-content').find('.assigned-quantity');

                if (assetId && employeeId) {
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
                                assignedQuantityInput.val(assignedQuantity);
                                console.log("Assigned Quantity:", assignedQuantity);
                            } else {
                                assignedQuantityInput.val('');
                                console.log("No assigned quantity found.");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error occurred:", error);
                        }
                    });
                } else {
                    assignedQuantityInput.val('');
                    console.log("Both assetId and employeeId are required.");
                }
            });


            $('.lostAssetForm').submit(function(event) {
                event.preventDefault();

                var form = $(this);
                var assetType = form.closest('.modal').data('asset-type');

                var assignedQuantityElement = form.find('.assigned-quantity');

                if (assignedQuantityElement.length > 0) {
                    var assignedQuantityValue = assignedQuantityElement.val();
                    if (assignedQuantityValue !== undefined) {
                        var remainingQuantity = parseInt(assignedQuantityValue.split('/')[0]);
                        var lostQuantity = parseInt(form.find('.quantity_input').val());

                        if (assetType === 'Fixed') {
                            if (lostQuantity > remainingQuantity) {
                                form.find('.quantity_input').addClass('is-invalid');
                                form.find('#lost_quantity_feedback').text(
                                    'Lost quantity cannot exceed remaining quantity');
                            } else {
                                form.find('.quantity_input').removeClass('is-invalid');
                                form.find('#lost_quantity_feedback').text('');
                                form.off('submit').submit();
                            }
                        } else {
                            form.off('submit').submit();
                        }
                    } else {
                        console.error("Assigned Quantity Value is undefined.");
                    }
                } else {
                    console.error("Element with class 'assigned-quantity' not found.");
                    form.off('submit').submit();
                }
            });




        });
    </script>

    <!-- Lost Modal End-->

    <!-- Liquidation Modal -->
    @foreach ($asset_stocks as $asset_stock)
        <div class="modal fade update liquidation-modal-{{ $asset_stock->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h4 class="modal-title">Liquidation Asset</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="col-lg-12">
                        @php
                            $purchaseAssetId = App\Models\AssetPurchaseDetails::with('asset_purchase')
                                ->where('asset_id', $asset_stock->asset_id)
                                ->first();
                            // dd($purchaseAssetId);
                        @endphp
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Asset: {{ $asset_stock->asset->name ?? '' }}
                                        ({{ $asset_stock->asset->asset_code ?? '' }})
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Asset Group:
                                        {{ $asset_stock->asset->asset_group->name ?? '' }}</label>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Asset Category:
                                        {{ $asset_stock->asset->asset_group->asset_category->name ?? '' }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Asset Type: {{ $asset_stock->asset->asset_type ?? '' }}
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Current Stock: <span
                                            style="color: red;">{{$asset_stock->quantity }}</span></label>
                                </div>
                                @php
                                    $assignedQuantity = App\Models\AssetAssign::where(
                                        'asset_id',
                                        $asset_stock->asset_id,
                                    )->sum('quantity');

                                    $damageQuantity = App\Models\AssetDamage::where('asset_id', $asset_stock->asset_id)->first();

                                    $totalDamageQuantity = 0;
                                    if ($damageQuantity) {
                                        $totalDamageQuantity = App\Models\DamageActivityLog::where(
                                            'damage_id',
                                            $damageQuantity->id,
                                        )->sum('quantity');
                                    }

                                    $lostQuantity = App\Models\AssetLost::where('asset_id', $asset_stock->asset_id)->first();

                                    $totalLostQuantity = 0;
                                    if ($lostQuantity) {
                                        $totalLostQuantity = App\Models\LostActivityLog::where(
                                            'lost_id',
                                            $lostQuantity->id,
                                        )->sum('quantity');
                                    }

                                    $remainingQuantity = $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;

                                @endphp
                                @if ($assignedQuantity)
                                    <div class="col-md-4">
                                        <label class="form-label"> Remaining Liquidatable Quantity (After Assign/ Damage/
                                            Lost): <span
                                                style="color: red;">{{ $asset_stock->quantity - $remainingQuantity }}</span></label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label">Quantity Purchased:</label>
                                    <span style="color: red;"><b>{{ $purchaseAssetId->quantity ?? 0 }}</b></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Unit Price While Purchased:</label>
                                    <span style="color: red;"><b>{{ $purchaseAssetId->unit_price ?? 0 }}</b></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total Price While Purchased:</label>
                                    <span
                                        style="color: red;"><b>{{ ($purchaseAssetId->quantity ?? 0) * ($purchaseAssetId->unit_price ?? 0) }}</b></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('save_liquidation_asset', $asset_stock->id) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="asset_id" value="{{ $asset_stock->asset_id }}">
                                <input type="hidden" name="asset_group_id" value="{{ $asset_stock->asset_group_id }}">
                                @php
                                    $assignedQuantity = App\Models\AssetAssign::where(
                                        'asset_id',
                                        $asset_stock->asset_id,
                                    )->sum('quantity');

                                    $damageQuantity = App\Models\AssetDamage::where('asset_id', $asset_stock->asset_id)->first();

                                    $totalDamageQuantity = 0;
                                    if ($damageQuantity) {
                                        $totalDamageQuantity = App\Models\DamageActivityLog::where(
                                            'damage_id',
                                            $damageQuantity->id,
                                        )->sum('quantity');
                                    }

                                    $lostQuantity = App\Models\AssetLost::where('asset_id', $asset_stock->asset_id)->first();

                                    $totalLostQuantity = 0;
                                    if ($lostQuantity) {
                                        $totalLostQuantity = App\Models\LostActivityLog::where(
                                            'lost_id',
                                            $lostQuantity->id,
                                        )->sum('quantity');
                                    }

                                    $remainingQuantity = $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;

                                @endphp
                                @if ($assignedQuantity)
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Quantity<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control"
                                                id="quantityl_{{ $asset_stock->id }}" name="quantity"
                                                placeholder="Quantity" required min="1"
                                                max="{{ $asset_stock->quantity - $remainingQuantity }}">
                                        </div>
                                    </div>
                                @else
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Quantity<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control"
                                                id="quantityl_{{ $asset_stock->id }}" name="quantity"
                                                placeholder="Quantity" required min="1"
                                                max="{{ $asset_stock->quantity }}">
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Liquidation Amount<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control"
                                            id="liquidation_amount_{{ $asset_stock->id }}" name="liquidation_amount"
                                            placeholder="Liquidation Amount" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fund">Fund<i class="text-danger">*</i></label>
                                    <select name="fund_id" id="fund" class="form-control" required
                                        onchange="showBankInfo()">
                                        <option value="">Select a Fund </option>
                                        @foreach ($fund_types as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="payment_type">Payment Type<i class="text-danger">*</i></label>
                                    <select class="form-control" name="payment_type" required>
                                        <option value="">Select One</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-md-6 bank">
                                    <label for="">Bank<i class="text-danger">*</i></label>
                                    <select name="bank_id" id="bank_id" class="form-control bank bank_info"
                                        onchange="filterAccount()">
                                        <option value="">Select a Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 bank">
                                    <label for="account">Account<i class="text-danger">*</i></label>
                                    <select name="account_id" id="account_id" class="form-control bank bank_info">
                                        <option value="">Select An Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6 bank">
                                    <label for="check">Check No.</label>
                                    <input type="number" name="check_no" class="form-control" />
                                </div>
                                <div class="col-lg-6 bank">
                                    <label for="check_issue_date">Check Issue Date</label>
                                    <input type="date" name="check_issue_date" class="form-control" />
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Description</label>
                                        <textarea type="text" cols="4" rows="4" class="form-control" id="description" name="description"
                                            placeholder="Description"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Liquidation Date</label>
                                        <input type="date" class="form-control" id="liquidation_date"
                                            name="liquidation_date" value="{{ date('Y-m-d') }}"
                                            data-date-format="yyyy-mm-dd">
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
        <script>
            var unitPrice_{{ $asset_stock->id }} = {{ $purchaseAssetId->unit_price ?? 0 }};
            // console.log(unitPrice_{{ $asset_stock->id }});

            function updateLiquidationAmount_{{ $asset_stock->id }}() {
                var quantityl = document.getElementById("quantityl_{{ $asset_stock->id }}").value;
                // console.log(quantityl);
                var liquidation_amount = unitPrice_{{ $asset_stock->id }} * quantityl;
                document.getElementById("liquidation_amount_{{ $asset_stock->id }}").value = liquidation_amount;
            }

            document.getElementById("quantityl_{{ $asset_stock->id }}").addEventListener("input",
                updateLiquidationAmount_{{ $asset_stock->id }});
        </script>
    @endforeach
    <!-- Liquidation Modal End-->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.bank').hide();
    });
    function showBankInfo() {
        var fund_id = document.getElementById('fund').value;
        console.log(fund_id);
        if (fund_id == 1) {
            $('.bank').show();
        } else {
            $('.bank').hide();
        }

    }

    function filterAccount() {
        var bank_id = document.getElementById('bank_id').value;
        var url = "{{ route('filter-bank-fund') }}";
        $.ajax({
            type: "GET",
            url: url,
            data: {
                bank_id
            },
            success: function(data) {
                $('#account_id').html('<option value="">Select One</option>');
                $.each(data, function(key, value) {
                    $('#account_id').append('<option value="' + value.id + '">' + value.account_no +
                        '</option>');
                });
            },
        });
    }
</script>