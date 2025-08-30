@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Liquidation Asset List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form action="{{ route('asset_liquidation_list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="asset_code">Asset Code</label>
                                    <input type="text" class="form-control" name="asset_code" placeholder="Asset Code">
                                </div>
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
                                <a href="{{ url('asset_liquidation_print?asset_code=' . request()->get('asset_code') . '&name=' . request()->get('name') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
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
                                    <th class="text-center">SL</th>
                                    <th class="text-center"> Liquidation Date </th>
                                    <th class="text-center"> Asset Group</th>
                                    <th class="text-center"> Asset </th>
                                    <th class="text-center"> Asset Category </th>
                                    <th class="text-center"> Asset Type </th>
                                    <th class="text-center"> Liquidation Quantity </th>
                                    <th class="text-center"> Liquidation Amount </th>
                                    {{-- <th> Description </th> --}}
                                    <th width="10%" class="text-center"> Action </th>
                                </tr>
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
                                        <td class="text-center">
                                            {{ $asset_liquidation->asset->asset_group->asset_category->name }}</td>
                                        <td class="text-center">{{ $asset_liquidation->asset->asset_type }}
                                        </td>
                                        <td class="text-center">{{ $asset_liquidation->quantity }}</td>
                                        <td class="text-center" style="color: red;">
                                            {{ $asset_liquidation->liquidation_amount }}</td>
                                        {{-- <td class="text-center"
                                            style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $asset_liquidation->description }}
                                        </td> --}}
                                        <td class="text-center">
                                            <a data-toggle="modal"
                                                data-target=".view-modal-{{ $asset_liquidation->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a data-toggle="modal" data-target=".update-modal-{{ $asset_liquidation->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
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
    @foreach ($assets_liquidation as $asset_liquidation)
        {{-- @dd($asset_d) --}}
        <div class="modal fade view-modal-{{ $asset_liquidation->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Liquidation Asset</h5>
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
                            <table class="table table-sm">
                                {{-- <tr>
                                <th width="20%">Liquidation Date: </th>
                                <td>{{ $asset_liquidation->liquidation_date ?? '' }}</td>

                                <th width="20%">Liquidation Amount: </th>
                                <td style="color: red;">{{ $asset_liquidation->liquidation_amount ?? '' }}</td>
                            </tr> --}}


                                <tr>
                                    {{-- <th width="20%">Liquidation Quantity: </th>
                                <td style="color: red;">{{ $asset_liquidation->quantity ?? '' }}</td> --}}

                                    <th width="20%">Asset Code: </th>
                                    <td>{{ $asset_liquidation->asset->asset_code ?? '' }}</td>
                                </tr>


                                <tr>
                                    <th width="20%">Asset: </th>
                                    <td>{{ $asset_liquidation->asset->name ?? '' }}</td>

                                    <th width="20%">Asset Group: </th>
                                    <td>{{ $asset_liquidation->asset->asset_group->name ?? '' }}</td>

                                </tr>

                                <tr>
                                    <th width="20%">Asset Category: </th>
                                    <td>{{ $asset_liquidation->asset->asset_group->asset_category->name ?? '' }}</td>

                                    <th width="20%">Asset Type: </th>
                                    <td>{{ $asset_liquidation->asset->asset_type ?? '' }}</td>
                                </tr>

                                {{-- <tr>
                                    <th width="20%">Fund: </th>
                                    <td>{{ $asset_liquidation->fund->name ?? '' }}</td>

                                    @if ($asset_liquidation->bank)
                                        <th width="20%">Bank: </th>
                                        <td>{{ $asset_liquidation->bank->name ?? '' }}</td>
                                    @endif

                                    @if ($asset_liquidation->account)
                                        <th width="20%">Account: </th>
                                        <td>{{ $asset_liquidation->account->account_no ?? '' }}</td>
                                    @endif
                                </tr> --}}

                                {{-- <tr>
                                    @if ($asset_liquidation->check_no)
                                        <th width="20%">Check No: </th>
                                        <td>{{ $asset_liquidation->check_no ?? '' }}</td>
                                    @endif

                                    @if ($asset_liquidation->check_issue_date)
                                        <th width="20%">Check Issue Date: </th>
                                        <td>{{ $asset_liquidation->check_issue_date ?? '' }}</td>
                                    @endif
                                </tr> --}}
                            </table>
                            {{-- <table class="table table-sm">
                            <tr>
                                <th width="20%">Description: </th>
                                <td>{{ $asset_liquidation->description ?? '' }}</td>
                            </tr>
                        </table> --}}

                            <table class="table table-sm">
                                <thead>
                                    <tr class="bg-info text-center">
                                        <th> SL</th>
                                        <th> Liquidation Date </th>
                                        <th> Fund </th>
                                        <th> Bank </th>
                                        <th> Account </th>
                                        <th> Check No </th>
                                        <th> Check Issue Date </th>
                                        <th> Liquidation Quantity </th>
                                        <th> Liquidation Amount </th>
                                        <th> Description </th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $serial = 0;
                                    @endphp
                                    {{-- @dd($asset_liquidation->activityLogs) --}}

                                    @foreach ($asset_liquidation->activityLogs as $activityLog)
                                        {{-- @dd($activityLog) --}}
                                        <tr>
                                            <td class="text-center">{{ ++$serial }}</td>
                                            <td class="text-center">{{ $activityLog->liquidation_date }}</td>
                                            <td class="text-center">{{ $activityLog->fund->name ?? ''}}</td>
                                            <td class="text-center">{{ $activityLog->bank->name ?? ''}}</td>
                                            <td class="text-center">{{ $activityLog->account->account_no ?? ''}}</td>
                                            <td class="text-center">{{ $activityLog->check_no }}</td>
                                            <td class="text-center">{{ $activityLog->check_issue_date }}</td>
                                            <td class="text-center">{{ $activityLog->quantity }}</td>
                                            <td class="text-center">{{ $activityLog->liquidation_amount }}</td>
                                            <td class="text-center"
                                                style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                                {{ $activityLog->description }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <!-- Edit Modal -->
    @foreach ($assets_liquidation as $asset_liquidation)
    {{-- @dd($asset_liquidation) --}}
        @foreach ($asset_liquidation->activityLogs as $activityLog)
            <div class="modal fade update update-modal-{{ $asset_liquidation->id }}" id="exampleModal" tabindex="-1"
                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-center">
                            <h5>Update Liquidation Asset</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="col-lg-12">
                            @php
                                $purchaseAssetId = App\Models\AssetPurchaseDetails::with('asset_purchase')
                                    ->where('asset_id', $asset_liquidation->asset_id)
                                    ->first();
                                // dd($purchaseAssetId);
                            @endphp
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Asset: {{ $asset_liquidation->asset->name }}
                                            ({{ $asset_liquidation->asset->asset_code }})
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Asset Group:
                                            {{ $asset_liquidation->asset->asset_group->name ?? '' }}</label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Asset Category:
                                            {{ $asset_liquidation->asset->asset_group->asset_category->name }}
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Asset Type: {{ $asset_liquidation->asset->asset_type }}
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $stockAssetId = App\Models\AssetStock::where(
                                                'asset_id',
                                                $asset_liquidation->asset_id,
                                            )->first();
                                            // dd($stockAssetId);
                                        @endphp
                                        <label class="form-label">Current Stock: <span
                                                style="color: red;">{{ $stockAssetId->quantity }}</span></label>
                                    </div>

                                    @php
                                        $assignedQuantity = App\Models\AssetAssign::where(
                                            'asset_id',
                                            $asset_liquidation->asset_id,
                                        )->sum('quantity');

                                        $damageQuantity = App\Models\AssetDamage::where(
                                            'asset_id',
                                            $asset_liquidation->asset_id,
                                        )->first();

                                        $totalDamageQuantity = 0;
                                        if ($damageQuantity) {
                                            $totalDamageQuantity = App\Models\DamageActivityLog::where(
                                                'damage_id',
                                                $damageQuantity->id,
                                            )->sum('quantity');
                                        }

                                        $lostQuantity = App\Models\AssetLost::where(
                                            'asset_id',
                                            $asset_liquidation->asset_id,
                                        )->first();

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
                                    @if ($assignedQuantity)
                                        <div class="col-md-4">
                                            <label class="form-label"> Remaining Liquidatable Quantity (After Assign/
                                                Damage/
                                                Lost):
                                                <span
                                                    style="color: red;">{{ $stockAssetId->quantity - $remainingQuantity }}</span></label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="form-label">Quantity Purchased:</label>
                                        <span style="color: red;"><b>{{ $purchaseAssetId->quantity }}</b></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Unit Price While Purchased:</label>
                                        <span style="color: red;"><b>{{ $purchaseAssetId->unit_price }}</b></span>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Total Price While Purchased:</label>
                                        <span
                                            style="color: red;"><b>{{ $purchaseAssetId->quantity * $purchaseAssetId->unit_price }}</b></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('update_liquidation_asset', $asset_liquidation->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="id" value="{{ $activityLog->id }}">
                                    <input type="hidden" name="asset_id" value="{{ $asset_liquidation->asset_id }}">
                                    <input type="hidden" name="asset_group_id"
                                        value="{{ $asset_liquidation->asset_group_id }}">
                                    @php
                                    $last_log_id = App\Models\LiquidationActivityLog::where('liquidation_id',$asset_liquidation->id)->orderByDesc('id')->limit(1)->first();
                                        $stockAssetId = App\Models\AssetStock::where(
                                            'asset_id',
                                            $asset_liquidation->asset_id,
                                        )->first();
                                        $assignedQuantity = App\Models\AssetAssign::where(
                                            'asset_id',
                                            $asset_liquidation->asset_id,
                                        )->sum('quantity');

                                        $damageQuantity = App\Models\AssetDamage::where(
                                            'asset_id',
                                            $asset_liquidation->asset_id,
                                        )->first();

                                        $totalDamageQuantity = 0;
                                        if ($damageQuantity) {
                                            $totalDamageQuantity = App\Models\DamageActivityLog::where(
                                                'damage_id',
                                                $damageQuantity->id,
                                            )->sum('quantity');
                                        }

                                        $lostQuantity = App\Models\AssetLost::where(
                                            'asset_id',
                                            $asset_liquidation->asset_id,
                                        )->first();

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
                                    @if ($assignedQuantity)
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Quantity<i class="text-danger">*</i></label>
                                                <input type="number" class="form-control"
                                                    id="quantityl_{{ $asset_liquidation->id }}" name="quantity"
                                                    placeholder="Quantity" value="{{ $last_log_id->quantity }}"
                                                    required min="0"
                                                    max="{{ $stockAssetId->quantity - $remainingQuantity }}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Quantity<i class="text-danger">*</i></label>
                                                <input type="number" class="form-control"
                                                    id="quantityl_{{ $asset_liquidation->id }}" name="quantity"
                                                    placeholder="Quantity" value="{{ $last_log_id->quantity }}"
                                                    required min="0" max="{{ $stockAssetId->quantity }}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Liquidation Amount</label>
                                            <input type="number" class="form-control"
                                                value="{{ $last_log_id->liquidation_amount }}"
                                                id="liquidation_amount_{{ $asset_liquidation->id }}"
                                                name="liquidation_amount" placeholder="Liquidation Amount"
                                                min="0">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="fund">Fund</label>
                                        <input type="text" class="form-control"
                                            value="{{ $asset_liquidation->fund->name ?? '' }}" readonly>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="payment_type">Payment Type</label>
                                        <input type="text" class="form-control"
                                            value="{{ $asset_liquidation->payment_type ?? '' }}" readonly>
                                    </div>
                                    @if ($asset_liquidation->bank)
                                        <div class="col-md-6">
                                            <label for="">Bank<i class="text-danger">*</i></label>
                                            <input type="text" class="form-control"
                                                value="{{ optional($asset_liquidation->bank)->name }}" readonly>
                                        </div>
                                    @endif

                                    @if ($asset_liquidation->account)
                                        <div class="col-md-6">
                                            <label for="account">Account<i class="text-danger">*</i></label>
                                            <input type="text" class="form-control"
                                                value="{{ $asset_liquidation->account->account_no ?? '' }}" readonly>
                                        </div>
                                    @endif
                                    @if ($asset_liquidation->check_no)
                                        <div class="col-lg-6 bank">
                                            <label for="check">Check No.</label>
                                            <input type="text" class="form-control"
                                                value="{{ $asset_liquidation->check_no ?? '' }}" readonly>
                                        </div>
                                    @endif
                                    @if ($asset_liquidation->check_issue_date)
                                        <div class="col-lg-6 bank">
                                            <label for="check_issue_date">Check Issue Date</label>
                                            <input type="date" class="form-control"
                                                value="{{ $asset_liquidation->check_issue_date ?? '' }}" readonly>
                                        </div>
                                    @endif
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea type="text" cols="4" rows="4" class="form-control" id="description" name="description"
                                                placeholder="Description">{{ $asset_liquidation->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Liquidation Date</label>
                                            <input type="date" class="form-control" id="liquidation_date"
                                                name="liquidation_date"
                                                value="{{ $asset_liquidation->liquidation_date }}"
                                                data-date-format="yyyy-mm-dd">
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
            <script>
                var unitPrice_{{ $asset_liquidation->id }} = {{ $purchaseAssetId->unit_price }};

                function updateLiquidationAmount_{{ $asset_liquidation->id }}() {
                    var quantityl = document.getElementById("quantityl_{{ $asset_liquidation->id }}").value;
                    var liquidation_amount = unitPrice_{{ $asset_liquidation->id }} * quantityl;
                    document.getElementById("liquidation_amount_{{ $asset_liquidation->id }}").value = liquidation_amount;
                }

                document.getElementById("quantityl_{{ $asset_liquidation->id }}").addEventListener("input",
                    updateLiquidationAmount_{{ $asset_liquidation->id }});
            </script>
        @endforeach
    @endforeach
    <!-- Edit Modal End-->
@endsection

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