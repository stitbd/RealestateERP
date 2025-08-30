@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Asset Purchase List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Asset Purchase
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <form action="{{ route('asset_purchase_list') }}" method="get">
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
                                            <option value="{{ $asset->id }}">{{ $asset->name }}
                                                ({{ $asset->asset_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label class="form-label">Purchase Code</label>
                                    <input type="text" class="form-control" name="p_code" placeholder="Purchase Code">
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
                                <a href="{{ url('asset_purchase_print?p_code=' . request()->get('p_code') . '&asset_group_id=' . request()->get('asset_group_id') . '&asset_id=' . request()->get('asset_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
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
                                    <th> Asset Group </th>
                                    <th> Asset </th>
                                    <th> Purchase Code </th>
                                    <th> Purchase Date </th>
                                    <th> Purchase Quantity </th>
                                    <th> Total Amount </th>
                                    <th> Action </th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($asset_purchased as $asset_purchase)
                                    {{-- @dd($asset_purchase->asset_details) --}}
                                    @foreach ($asset_purchase->asset_details as $purchase_details)
                                        <tr>
                                            <td class="text-center">{{ ++$serial }}</td>
                                            <td class="text-center">{{ $purchase_details->asset->asset_group->name ?? '' }}
                                            </td>
                                            <td class="text-center">{{ $purchase_details->asset->name ?? '' }}
                                                ({{ $purchase_details->asset->asset_group->asset_category->name ?? '' }},
                                                {{ $purchase_details->asset->asset_type ?? '' }})
                                            </td>
                                            <td class="text-center">{{ $purchase_details->asset_purchase->p_code ?? '' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $purchase_details->asset_purchase->purchase_date ?? '' }}
                                            </td>
                                            <td class="text-center">{{ $purchase_details->quantity ?? '' }}</td>
                                            <td class="text-center">
                                                {{ $purchase_details->asset_purchase->total_amount ?? '' }}
                                            </td>
                                            <td width="15%" class="text-center">
                                                <a onclick="view_invoice('{{ $purchase_details->id }}')"
                                                    class="pl-2 text-success" data-toggle="modal" data-target="#modal-view">
                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                                <a data-toggle="modal"
                                                    data-target=".update-modal-{{ $asset_purchase->id }}"
                                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                {{-- <a href="{{ route('asset_purchase_delete', $asset_purchase->id) }}"
                                                    onclick="return confirm('Are you sure you want to delete?');"
                                                    style="padding: 2px;" class="delete btn btn-xs btn-danger  mr-1">
                                                    <i class="fas fa-trash"></i>
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $asset_purchased->links() }}
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>


    <!-- View Modal -->
    <div class="modal fade" id="modal-view">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Invoice Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" id="purchase_details">

                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    {{-- @foreach ($asset_purchased as $asset_p)
        @foreach ($asset_p->asset_details as $asset_d)
            
            <div class="modal fade view-modal-{{ $asset_p->id }}" id="exampleModal" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-center">
                            <h5>View Purchased Asset</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="1%"></th>
                                        @if (!empty($asset_d->asset->image))
                                            <td class="text-center">
                                                <img src="{{ asset('upload_images/asset/' . $asset_d->asset->image) }}" height="80px"
                                                    width="100px" />
                                            </td>
                                        @else
                                            <td class="text-center">
                                                <img src="{{ asset('upload_images/asset/default_image.jpg') }}"
                                                    alt="Asset Image" style="height: 80px; width: 100px;">
                                            </td>
                                        @endif
                                    </tr>
                                </table>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="20%">Purchase Date: </th>
                                        <td>{{ $asset_d->asset_purchase->purchase_date }}</td>

                                        <th width="20%">Purchase Code: </th>
                                        <td>{{ $asset_d->asset_purchase->p_code }}</td>
                                    </tr>


                                    <tr>
                                        <th width="20%">Unit Price: </th>
                                        <td>{{ $asset_d->unit_price }}</td>

                                        <th width="20%">Purchased Quantity: </th>
                                        <td style="color: red;">{{ $asset_d->quantity }}</td>
                                    </tr>


                                    <tr>
                                        <th width="20%">Total Amount: </th>
                                        <td>{{ $asset_d->asset_purchase->total_amount }}</td>

                                        <th width="20%">Asset Code: </th>
                                        <td>{{ $asset_d->asset->asset_code ?? '' }}</td>
                                    </tr>


                                    <tr>
                                        <th width="20%">Asset Group: </th>
                                        <td>{{ $asset_d->asset->asset_group->name ?? '' }}</td>

                                        <th width="20%">Asset: </th>
                                        <td>{{ $asset_d->asset->name ?? '' }}</td>
                                    </tr>
                
                                    <tr>
                                        <th width="20%">Asset Category: </th>
                                        <td>{{ $asset_d->asset->asset_group->asset_category->name ?? '' }}</td>

                                        <th width="20%">Asset Type: </th>
                                        <td>{{ $asset_d->asset->asset_type ?? '' }}</td>
                                    </tr>
                                </table>
                                <table class="table table-sm">
                                    <tr>
                                        <th width="20%">Purchase Note: </th>
                                        <td>{{ $asset_d->asset_purchase->purchase_note ?? '' }}</td>
                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach
    @endforeach --}}
    <!-- /.modal -->

    <!-- Add Modal -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Asset Purchase</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_asset_purchase') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <label for="asset_group_id">Asset Group<i class="text-danger">*</i></label>
                                <select name="asset_group_id" id="asset_group_id" class="form-control form-select"
                                    required>
                                    <option value="" selected>Select Group</option>
                                    @foreach ($asset_groups as $asset_group)
                                        <option value="{{ $asset_group->id }}">{{ $asset_group->name }}
                                            ({{ $asset_group->asset_category->name }})
                                        </option>
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
                                                ({{ $asset->asset_type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Asset Quantity<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="quantity" name="quantity"
                                        placeholder="Asset Quantity" required min="1">
                                    {{-- <small style="color:red">Asset Code No. Should Be Unique</small> --}}
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Unit Price<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="unit_price" name="unit_price"
                                        placeholder="Unit Price" required>
                                    {{-- <small style="color:red">Asset Code No. Should Be Unique</small> --}}
                                </div>
                            </div>
                            {{-- <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Amount<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount" readonly>
                                    <small style="color:red">Asset Code No. Should Be Unique</small>
                                </div>
                            </div> --}}
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Total Amount</label>
                                    <input type="number" class="form-control" id="total_amount" name="total_amount"
                                        placeholder="Total Amount" readonly>
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
                                    <label class="form-label">Purchase Date</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date"
                                        placeholder="Total Amount" value="{{ date('Y-m-d') }}"
                                        data-date-format="yyyy-mm-dd">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Purchase Note</label>
                                    <input type="text" class="form-control" id="purchase_note" name="purchase_note"
                                        placeholder="Purchase Note">
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
    @foreach ($asset_purchased as $asset_p)
        {{-- @dd($asset_p) --}}
        @foreach ($asset_p->asset_details as $asset_d)
            <div class="modal fade update update-modal-{{ $asset_p->id }}" id="exampleModal" tabindex="-1"
                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-info text-center">
                            <h5>Update Asset Purchase</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form action="{{ route('update_asset_purchase', $asset_p->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <input type="hidden" name="asset_id" value="{{ $asset_d->asset_id }}">
                                    <input type="hidden" name="asset_group_id" value="{{ $asset_d->asset_group_id }}">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Asset: {{ $asset_d->asset->name ?? '' }}
                                                        ({{ $asset_d->asset->asset_code ?? '' }})
                                                    </label>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Asset Group:
                                                        {{ $asset_d->asset->asset_group->name ?? '' }}</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Asset Category:
                                                        {{ $asset_d->asset->asset_group->asset_category->name ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Asset Quantity<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control" id="quantity-{{ $asset_p->id }}"
                                                name="quantity" value="{{ $asset_d->quantity }}"
                                                placeholder="Asset Quantity" required min="0">
                                            {{-- <small style="color:red">Asset Code No. Should Be Unique</small> --}}
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Unit Price<i class="text-danger">*</i></label>
                                            <input type="number" class="form-control"
                                                id="unit_price-{{ $asset_p->id }}" name="unit_price"
                                                value="{{ $asset_d->unit_price }}" placeholder="Unit Price" required>
                                            {{-- <small style="color:red">Asset Code No. Should Be Unique</small> --}}
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Amount<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Amount" readonly>
                                        <small style="color:red">Asset Code No. Should Be Unique</small>
                                    </div>
                                </div> --}}
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Total Amount</label>
                                            <input type="number" class="form-control"
                                                id="total_amount-{{ $asset_p->id }}" name="total_amount"
                                                value="{{ $asset_p->total_amount }}" placeholder="Total Amount" readonly>
                                        </div>
                                    </div>
                                    {{-- @dd($asset_p) --}}
                                    <div class="col-md-6">
                                        <label for="fund">Fund</label>
                                        <input type="text" class="form-control"
                                            value="{{ $asset_p->fund->name ?? '' }}" readonly>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="payment_type">Payment Type</label>
                                        <input type="text" class="form-control"
                                            value="{{ $asset_p->payment_type ?? '' }}" readonly>
                                    </div>
                                    @if ($asset_p->bank)
                                        <div class="col-md-6">
                                            <label for="">Bank<i class="text-danger">*</i></label>
                                            <input type="text" class="form-control"
                                                value="{{ optional($asset_p->bank)->name }}" readonly>
                                        </div>
                                    @endif

                                    @if ($asset_p->account)
                                        <div class="col-md-6">
                                            <label for="account">Account<i class="text-danger">*</i></label>
                                            <input type="text" class="form-control"
                                                value="{{ $asset_p->account->account_no ?? '' }}" readonly>
                                        </div>
                                    @endif
                                    @if ($asset_p->check_no)
                                        <div class="col-lg-6 bank">
                                            <label for="check">Check No.</label>
                                            <input type="text" class="form-control"
                                                value="{{ $asset_p->check_no ?? '' }}" readonly>
                                        </div>
                                    @endif
                                    @if ($asset_p->check_issue_date)
                                        <div class="col-lg-6 bank">
                                            <label for="check_issue_date">Check Issue Date</label>
                                            <input type="date" class="form-control"
                                                value="{{ $asset_p->check_issue_date ?? '' }}" readonly>
                                        </div>
                                    @endif
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Purchase Date</label>
                                            <input type="date" class="form-control" id="purchase_date"
                                                name="purchase_date" value="{{ $asset_p->purchase_date }}"
                                                data-date-format="yyyy-mm-dd">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Purchase Note</label>
                                            <input type="text" class="form-control" id="purchase_note"
                                                name="purchase_note" value="{{ $asset_p->purchase_note }}"
                                                placeholder="Purchase Note">
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
    @endforeach
    <!-- /.modal -->
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('[id^=asset_group_id]').change(function() {
            var groupId = $(this).val();
            var id = $(this).attr('id').split('-').pop();

            $.ajax({
                url: '/get-groupWise-asset',
                type: 'GET',
                data: {
                    asset_group_id: groupId
                },
                success: function(response) {
                    // console.log(response);
                    var assets = response;
                    var options = '<option value="" selected>Select Asset</option>';

                    $.each(assets, function(index, asset) {
                        options += '<option value="' + asset.id + '">' + asset
                            .name + ' (' + asset.asset_type +
                            ')</option>';
                    });


                    $('#asset_id-' + id).html(options);
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
        function calculateAmount() {
            var quantity = parseFloat($('#quantity').val());
            var unitPrice = parseFloat($('#unit_price').val());
            // console.log(quantity);
            var total_amount = quantity * unitPrice;
            $('#total_amount').val(total_amount);
        }

        $('#quantity, #unit_price').on('input', function() {
            calculateAmount();
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.bank').hide();

        function calculateAmount(assetId) {
            var quantity = parseFloat($('#quantity-' + assetId).val());
            var unitPrice = parseFloat($('#unit_price-' + assetId).val());
            var totalAmountField = $('#total_amount-' + assetId);
            if (!isNaN(quantity) && !isNaN(unitPrice)) {
                var totalAmount = quantity * unitPrice;
                totalAmountField.val(totalAmount);
            } else {
                totalAmountField.val('');
            }
        }

        $('[id^=quantity]').on('input', function() {
            var assetId = $(this).attr('id').split('-')[1];
            calculateAmount(assetId);
        });

        $('[id^=unit_price]').on('input', function() {
            var assetId = $(this).attr('id').split('-')[1];
            calculateAmount(assetId);
        });

        $('#asset_group_id').change(function() {
            var groupId = $(this).val();
            console.log(groupId);
            if (groupId) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('getGroupWiseAsset') }}",
                    data: {
                        asset_group_id: groupId
                    },
                    dataType: "json",
                    success: function(response) {
                        $('#asset_id').empty();
                        $('#asset_id').append('<option value="">Select Asset</option>');
                        $.each(response, function(index,
                            asset) {
                            // console.log(categoryName);
                            var options = '<option value="' + asset.id + '">' +
                                asset.name + ' (' + asset
                                .asset_type + ')</option>';
                            $('#asset_id').append(options);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#asset_id').empty();
                $('#asset_id').append('<option value="">Select Asset</option>');
            }
        });

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
<script>
    function view_invoice(asset_id) {
        url = '{{ route('asset-purchase-invoice', ':asset_id') }}';
        url = url.replace(':asset_id', asset_id);
        //alert(url);
        $.ajax({
            cache: false,
            type: "GET",
            error: function(xhr) {
                alert("An error occurred: " + xhr.status + " " + xhr.statusText);
            },
            url: url,
            success: function(response) {
                $('#purchase_details').html(response);
            }
        })
    }
</script>
