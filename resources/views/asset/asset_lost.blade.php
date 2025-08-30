@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Lost Asset List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form action="{{ route('asset_lost_list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="report_code">Report Code</label>
                                    <input type="text" class="form-control" name="report_code" placeholder="Asset Code">
                                </div>
                                <div class="col-lg-3">
                                    <label for="employee_id">Employee</label>
                                    <select name="employee_id" class="form-control">
                                        <option value="" selected>Select Employee</option>
                                        {{-- @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}
                                                ({{ $employee->department->name }}, {{ $employee->designation->name }})
                                            </option>
                                        @endforeach --}}
                                    </select>
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
                                <div class="col-lg-3">
                                    <label for="asset_code">Asset Code</label>
                                    <input type="text" class="form-control" name="asset_code" placeholder="Asset Code">
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
                                <a href="{{ url('asset_lost_print?report_code=' . request()->get('report_code') . '&asset_code=' . request()->get('asset_code') . '&asset_group_id=' . request()->get('asset_group_id') . '&employee_id=' . request()->get('employee_id') . '&asset_id=' . request()->get('asset_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
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
                                    {{-- <th> Description </th> --}}
                                    <th> Action </th>
                                </tr>
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
                                        <td class="text-center">{{ $asset_lost->report_date ?? '' }}</td>
                                        <td class="text-center">{{ $asset_lost->report_code ?? '' }}</td>
                                        {{-- @if ($asset_lost->employee_id)
                                            <td class="text-center">{{ $asset_lost->employee->name ?? '' }}
                                                ({{ $asset_lost->employee->department->name ?? '' }},
                                                {{ $asset_lost->employee->designation->name ?? '' }})
                                            </td>
                                        @else --}}
                                            <td class="text-center"></td>
                                        {{-- @endif --}}
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
                                        {{-- <td class="text-center"
                                            style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $asset_lost->description }}
                                        </td> --}}
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $asset_lost->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            @if ($asset_lost->status == 'revoke')
                                            @else
                                                <a data-toggle="modal" data-target=".update-modal-{{ $asset_lost->id }}"
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
    @foreach ($assets_lost as $asset_lost)
        {{-- @dd($asset_d) --}}
        <div class="modal fade view-modal-{{ $asset_lost->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Lost Asset</h5>
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

                                <tr>
                                    @if ($asset_lost->employee_id)
                                        <th width="20%">Assigned Employee: </th>
                                        <td>{{ $asset_lost->employee->name ?? '' }}
                                            ({{ $asset_lost->employee->department->name ?? '' }},
                                            {{ $asset_lost->employee->designation->name ?? '' }})
                                        </td>
                                    @endif

                                    <th width="20%">Asset Code: </th>
                                    <td>{{ $asset_lost->asset->asset_code }}</td>
                                </tr>
                                <tr>
                                    <th width="20%">Asset: </th>
                                    <td>{{ $asset_lost->asset->name ?? '' }}</td>

                                    <th width="20%">Asset Group: </th>
                                    <td>{{ $asset_lost->asset->asset_group->name ?? '' }}</td>
                                </tr>


                                <tr>
                                    <th width="20%">Asset Category: </th>
                                    <td>{{ $asset_lost->asset->asset_group->asset_category->name ?? '' }}</td>

                                    <th width="20%">Asset Type: </th>
                                    <td>{{ $asset_lost->asset->asset_type ?? '' }}</td>
                                </tr>

                            </table>
                            <table class="table table-sm">
                                <thead>
                                    <tr class="bg-info text-center">
                                        <th> SL</th>
                                        <th> Report Date </th>
                                        <th> Lost Quantity </th>
                                        <th> Fine </th>
                                        <th> Description </th>
                                    </tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $serial = 0;
                                    @endphp
                                    {{-- @dd($asset_lost) --}}
                                    {{-- @if ($asset_lost->created_at)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $asset_lost->report_date }}</td>
                                        <td class="text-center">{{ $asset_lost->quantity }}</td>
                                        <td class="text-center">{{ $asset_lost->fine }}</td>
                                        <td class="text-center"
                                            style="max-width: 200px; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $asset_lost->description }}</td>
                                    </tr>
                                    @endif --}}
                                    {{-- @dd($asset_lost) --}}
                                    @foreach ($asset_lost->lostActivityLogs as $activityLog)
                                        <tr>
                                            <td class="text-center">{{ ++$serial }}</td>
                                            <td class="text-center">{{ $activityLog->report_date }}</td>
                                            <td class="text-center">{{ $activityLog->quantity }}</td>
                                            <td class="text-center">{{ $activityLog->fine }}</td>
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
    @foreach ($assets_lost as $asset_lost)
        {{-- @dd($asset_lost) --}}
        @foreach ($asset_lost->lostActivityLogs as $activityLog)
            <div class="modal fade update update-modal-{{ $asset_lost->id }}" id="exampleModal" tabindex="-1"
                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-center">
                            <h5>Update Lost Asset</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="col-lg-12">
                            @php
                                $purchaseAssetId = App\Models\AssetPurchaseDetails::with('asset_purchase')
                                    ->where('asset_id', $asset_lost->asset_id)
                                    ->first();
                                // dd($purchaseAssetId);
                            @endphp
                            <div class="mb-3">
                                <label class="form-label">Asset: {{ $asset_lost->asset->name }}
                                    ({{ $asset_lost->asset->asset_code }}, {{ $asset_lost->asset->asset_type }})
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Asset Group: {{ $asset_lost->asset->asset_group->name ?? '' }}
                                    ({{ $asset_lost->asset->asset_group->asset_category->name ?? '' }})</label>
                            </div>
                            @if ($asset_lost->employee_id)
                                <div class="mb-3">
                                    <label class="form-label">Assigned Employee:
                                        {{ $asset_lost->employee->name ?? '' }}
                                        ({{ $asset_lost->employee->department->name ?? '' }},
                                        {{ $asset_lost->employee->designation->name ?? '' }})
                                    </label>
                                </div>
                            @endif
                            {{-- <div class="mb-3">
                            <label class="form-label">Asset Category:
                                {{ $asset_lost->asset->asset_group->asset_category->name }}</label>
                        </div> --}}
                            <div class="mb-3">
                                @php
                                    $stockAssetId = App\Models\AssetStock::where(
                                        'asset_id',
                                        $asset_lost->asset_id,
                                    )->first();
                                    // dd($stockAssetId);
                                @endphp
                                <label class="form-label">Current Stock: <span
                                        style="color: red;">{{ $stockAssetId->quantity }}</span></label>
                            </div>
                        </div>
                        <form action="{{ route('update_lost_asset', $asset_lost->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="id" value="{{ $activityLog->id }}">
                                    <input type="hidden" name="asset_id" value="{{ $asset_lost->asset_id }}">
                                    <input type="hidden" name="asset_group_id"
                                        value="{{ $asset_lost->asset_group_id }}">
                                    <input type="hidden" name="employee_id" value="{{ $asset_lost->employee_id }}">
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
                                            $totalLostQuantity = App\Models\LostActivityLog::where(
                                                'lost_id',
                                                $lostQuantity->id,
                                            )->sum('quantity');
                                        }

                                        $remainingQuantity =
                                            $assignedQuantity - $totalDamageQuantity - $totalLostQuantity;
                                    @endphp
                                    @if ($asset_lost->employee_id)
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label for="assigned_quantity">After Damage or Lost (Remaining Quantity) /
                                                    Assigned Quantity</label>
                                                <input type="text"
                                                    value="{{ $remainingQuantity }} / {{ $assignedQuantity }}"
                                                    class="form-control" readonly>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($assignedQuantity)
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Lost Quantity<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" class="form-control"
                                                    id="quantity_{{ $asset_lost->id }}" name="quantity"
                                                    placeholder="Quantity" value="{{ $asset_lost->quantity }}" required
                                                    min="0" max="{{ $remainingQuantity }}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Lost Quantity<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" class="form-control"
                                                    id="quantity_{{ $asset_lost->id }}" name="quantity"
                                                    placeholder="Quantity" value="{{ $asset_lost->quantity }}" required
                                                    min="0">
                                            </div>
                                        </div>
                                    @endif
                                    @if ($asset_lost->employee_id)
                                        <div class="col-lg-12">
                                            <div class="mb-3">
                                                <label class="form-label">Fine</label>
                                                <input type="number" class="form-control"
                                                    value="{{ $asset_lost->fine }}" id="fine_{{ $asset_lost->id }}"
                                                    name="fine" placeholder="Fine" min="0">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea type="text" cols="4" rows="4" class="form-control" id="description" name="description"
                                                placeholder="Description">{{ $asset_lost->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Report Date</label>
                                            <input type="date" class="form-control" id="report_date"
                                                name="report_date" value="{{ $asset_lost->report_date }}"
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
                var unitPrice_{{ $asset_lost->id }} = {{ $purchaseAssetId->unit_price }};

                function updateFine_{{ $asset_lost->id }}() {
                    var quantity = document.getElementById("quantity_{{ $asset_lost->id }}").value;
                    var fine = unitPrice_{{ $asset_lost->id }} * quantity;
                    document.getElementById("fine_{{ $asset_lost->id }}").value = fine;
                }

                document.getElementById("quantity_{{ $asset_lost->id }}").addEventListener("input",
                    updateFine_{{ $asset_lost->id }});
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
