@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')
<style>
    .td {}

    .text-red {
        color: rgb(145, 12, 12);
    }

    .text-green {
        color: rgb(19, 99, 8);
    }

    .text-blue {
        color: rgb(3, 47, 53);
    }

    .styled-hr {
        border: none;
        height: 5px;
        background-color: rgb(8, 71, 3);
        margin: 20px 0;
    }
</style>
<script>
    function printPage(itemId, companyName) {
        var printContents = document.getElementById('print_body_' + itemId).innerHTML;
        var today = new Date();
        var formattedDate = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        printWindow.document.write('.header { text-align: center; margin-bottom: 20px; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write('<div class="header">');
        printWindow.document.write('<h1>' + companyName + '</h1>');
        printWindow.document.write('<p>Date: ' + formattedDate + '</p>');
        printWindow.document.write('</div>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- @if ($landSaleCount > 0)
                    <h5 class="bg-warning p-3 text-center"> <strong>Need to pay {{ $landSaleCount }} installment in this
                            week!</strong> </h5>
                @endif --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Head Wise Incentive List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal"
                            data-target="#modal-withdraw">
                            <i class="fa fa-credit-card" aria-hidden="true"></i> Withdraw
                        </button>
                        <a href="{{ route('incentive_withdrawn_list') }}"><button
                                class="text-end col-sm-2 btn btn-success btn-sm">
                                <i class="fa fa-list" aria-hidden="true"></i> Withdrawn List
                            </button></a>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">

                        <form action="{{ route('incentive_stock_list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-5">
                                    <label for="head_id">Head<i class="text-danger">*</i></label>
                                    <select name="head_id" id="head_id_filter" class="form-control chosen-select" onchange="employeeFiltration(this);">
                                        <option value="">--Select One--</option>
                                        @foreach ($head as $item)
                                            <option value="{{ $item->head_id }}">
                                                {{ $item->head->head_name ?? '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-5">
                                    <label for="land_sale_employee_id">Director/Co-Ordinator/Shareholder/Outsider<i
                                            class="text-danger">*</i></label>
                                    <select name="land_sale_employee_id" id="land_sale_employee"
                                        class="form-control chosen-select">
                                        <option value="">--Select One--</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>




                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th width="10%" class="text-center">#</th>
                                    <th width="10%" class="text-center">Account Head</th>
                                    <th width="10%" class="text-center">Director/Co-Ordinator/Shareholder/Outsider</th>
                                    <th width="15%" class="text-center">Total Incentive Amount</th>
                                    <th width="10%" class="text-center">Left Amount</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($incentive_stock as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->employee->head->head_name ?? '' }}</td>
                                        <td class="text-center">
                                            {{ $item->employee->employee_name ?? '' }} ({{ $item->employee->userType->type ?? '' }})
                                        </td>
                                        <td class="text-center">{{ $item->incentive_amount ?? '' }} TK.</td>
                                        <td class="text-center">{{ $item->left_amount ?? '' }} TK.</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#viewModal{{ $item->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Head Wise Incentive Modal -->
                                    <div class="modal fade" id="viewModal{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title" id="viewModalLabel{{ $item->id }}">Incentive Details</h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Account Head:</strong> {{ $item->employee->head->head_name ?? 'N/A' }}</p>
                                                            <p><strong>Employee Name:</strong> {{ $item->employee->employee_name ?? 'N/A' }}</p>
                                                            <p><strong>User Type:</strong> {{ $item->employee->userType->type ?? 'N/A' }}</p>
                                                            <p><strong>Total Incentive Amount:</strong> {{ $item->incentive_amount ?? '0' }} TK</p>
                                                            <p><strong>Left Amount:</strong> {{ $item->left_amount ?? '0' }} TK</p>
                                                            <p><strong>Employee ID:</strong> {{ $item->employee->id ?? 'N/A' }}</p>
                                                            <p><strong>Incentive Stock ID:</strong> {{ $item->id }}</p>
                                                        </div>

                                                        @if($item->landSale)
                                                        <div class="col-md-6">
                                                            <h5>Sale Details</h5>
                                                            <p><strong>Project:</strong> {{ $item->landSale->customer->project->name ?? 'N/A' }}</p>
                                                            <p><strong>Type:</strong> {{ $item->landSale->type ?? 'N/A' }}</p>
                                                            <p><strong>Customer Name:</strong> {{ $item->landSale->customer->customer_name ?? 'N/A' }}</p>
                                                            <p><strong>Customer Code:</strong> {{ $item->landSale->customer->customer_code ?? 'N/A' }}</p>

                                                            @if($item->landSale->type == 'Flat')
                                                                <p><strong>Floor No:</strong> {{ $item->landSale->flat->flat_floor->floor_no ?? 'N/A' }}</p>
                                                                <p><strong>Flat No:</strong> {{ $item->landSale->flat->flat_no ?? 'N/A' }}</p>
                                                                <p><strong>Total Amount:</strong> {{ $item->landSale->flat_total_price ?? '0' }} TK</p>
                                                            @else
                                                                <p><strong>Plot No:</strong> {{ $item->landSale->plot->plot_no ?? 'N/A' }}</p>
                                                                <p><strong>Total Price:</strong> {{ $item->landSale->total_price ?? '0' }} TK</p>
                                                            @endif

                                                            @php
                                                                $totalPaid = $item->landSale->landPayments ? $item->landSale->landPayments->sum('amount') : 0;
                                                            @endphp
                                                            <p><strong>Total Paid:</strong> {{ $totalPaid }} TK</p>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    @if($item->landSale && $item->landSale->incentive && count($item->landSale->incentive) > 0)
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <table class="table table-sm table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th colspan="12" style="text-align: center; color: green;">Incentive Details</th>
                                                                    </tr>
                                                                </thead>
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center" colspan="3">Director</th>
                                                                        <th class="text-center" colspan="3">Co-Ordinator</th>
                                                                        <th class="text-center" colspan="3">Shareholder</th>
                                                                        <th class="text-center" colspan="3">Outsider</th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="text-center">Name</th>
                                                                        <th class="text-center">Incentive (%)</th>
                                                                        <th class="text-center">Amount</th>
                                                                        <th class="text-center">Name</th>
                                                                        <th class="text-center">Incentive (%)</th>
                                                                        <th class="text-center">Amount</th>
                                                                        <th class="text-center">Name</th>
                                                                        <th class="text-center">Incentive (%)</th>
                                                                        <th class="text-center">Amount</th>
                                                                        <th class="text-center">Name</th>
                                                                        <th class="text-center">Incentive (%)</th>
                                                                        <th class="text-center">Amount</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($item->landSale->incentive as $incentive)
                                                                        <tr>
                                                                            <td class="text-center">{{ $incentive->director->employee_name ?? 'N/A' }}</td>
                                                                            <td class="text-center">{{ $incentive->directors_incentive ?? '0' }}</td>
                                                                            <td class="text-center">{{ $incentive->directors_incentive_amount ?? ($incentive->director_per_sales_incentive ?? '0') }} TK</td>

                                                                            <td class="text-center">{{ $incentive->co_ordinator->employee_name ?? 'N/A' }}</td>
                                                                            <td class="text-center">{{ $incentive->coordinators_incentive ?? '0' }}</td>
                                                                            <td class="text-center">{{ $incentive->coordinators_incentive_amount ?? ($incentive->coordinator_per_sales_incentive ?? '0') }} TK</td>

                                                                            <td class="text-center">{{ $incentive->shareholder->employee_name ?? 'N/A' }}</td>
                                                                            <td class="text-center">{{ $incentive->shareholders_incentive ?? '0' }}</td>
                                                                            <td class="text-center">{{ $incentive->shareholders_incentive_amount ?? ($incentive->shareholder_per_sales_incentive ?? '0') }} TK</td>

                                                                            <td class="text-center">{{ $incentive->outsider->employee_name ?? 'N/A' }}</td>
                                                                            <td class="text-center">{{ $incentive->outsiders_incentive ?? '0' }}</td>
                                                                            <td class="text-center">{{ $incentive->outsiders_incentive_amount ?? ($incentive->outsider_per_sales_incentive ?? '0') }} TK</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    @else
                                                    <div class="row mt-3">
                                                        <div class="col-md-12">
                                                            <div class="alert alert-warning">No incentive details found for this sale.</div>
                                                        </div>
                                                    </div>
                                                    @endif

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                @endforeach
                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                @if ($incentive_stock instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $incentive_stock->links() }}
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Withdraw modal --}}
    <div class="modal fade modal-withdraw" id="modal-withdraw" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info text-center d-flex justify-content-between align-items-center">
                    <h5>Withdraw Incentive Amount</h5>
                    <div>
                        <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                    </div>
                </div>
                {{-- <div class="col-lg-12">
                    <div class="mb-3">
                        <div class="row">

                        </div>
                    </div>
                    <hr>
                </div> --}}
                <form action="{{ route('store_incentive_withdraw') }}" method="post" enctype="multipart/form-data"
                    id="payment-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            {{-- <input type="hidden" value="{{ $item->id }}" name="land_sale_id"> --}}
                            <div class="col-lg-12">
                                <label for="head_id">Head<i class="text-danger">*</i></label>
                                <select name="head_id" id="head_id" class="form-control chosen-select"
                                    onchange="filterEmployee(this);" required>
                                    <option value="">--Select One--</option>
                                    @foreach ($incentive_stock as $item)
                                        <option value="{{ $item->head_id }}">{{ $item->employee->head->head_name ?? '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 p-2">
                                <label for="land_sale_employee_id">Director/Co-Ordinator/Shareholder/Outsider<i
                                        class="text-danger">*</i></label>
                                <select name="land_sale_employee_id" id="land_sale_employee_id"
                                    class="form-control chosen-select" required>
                                    <option value="">--Select One--</option>
                                </select>
                            </div>


                            <div class="col-md-6 p-2">
                                <label for="incentive_amount">Incentive Amount (Tk.)</label>
                                <input type="text" id="incentive_amount" class="form-control" readonly>
                            </div>

                            <div class="col-md-6 p-2">
                                <label for="amount">Pay Amount (Tk.)</label>
                                <input type="number" id="amount" name="amount" class="form-control"
                                    placeholder="Amount.." required>
                                <small id="amount-error" class="text-danger" style="display: none;">Amount exceeds
                                    available
                                    amount.</small>
                            </div>

                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <label for="fund">Fund<i class="text-danger">*</i></label>
                                        <select name="fund_id" id="fund" class="form-control" required
                                            onchange="showBankInfo();checkPaymentType();checkType();">
                                            <option value="">Select a Fund </option>
                                            @foreach ($fund_types as $fund)
                                                <option value="{{ $fund->id }}">{{ $fund->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 bank" style="display: none;">
                                        <label for="">Bank <i class="text-danger">*</i></label>
                                        <select name="bank_id" id="bank_id" class="form-control"
                                            onchange="filterAccount()">
                                            <option value="">Select Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 bank" style="display: none;">
                                        <label for="account">Account <i class="text-danger">*</i></label>
                                        <select name="account_id" id="account_id" class="form-control"
                                            onchange="showAccountBranch()">
                                            <option value="">Select An Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">
                                                    {{ $account->account_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 bank" style="display: none;">
                                        <label for="branch">Branch <i class="text-danger">*</i></label>
                                        <input type="text" id="branch" class="form-control">
                                    </div>

                                    <div class="col-md-12">
                                        <label for="payment_type_id">Payment Method<i class="text-danger">*</i></label>
                                        <select name="payment_type_id" id="payment_type_id" required
                                            class="form-control">
                                            <option value="">Select a Method</option>
                                            @foreach ($payment_types as $payment_type)
                                                <option value="{{ $payment_type->id }}">
                                                    {{ $payment_type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="pay_date">Pay Date<i class="text-danger">*</i></label>
                                        <input type="date" name="pay_date" value="{{ date('Y-m-d') }}"
                                            class="form-control" placeholder="" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="remaining_amount_cheque_no">Cash/ Cheque/ Pay Order No.
                                            <i class="text-danger">*</i></label>
                                        <input type="text" name="remaining_amount_cheque_no" class="form-control"
                                            id="remaining_amount_cheque_no" placeholder="" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="note">Note
                                            <i class="text-danger">*</i></label>
                                        <input type="text" name="remarks" class="form-control" id="note"
                                            placeholder="">
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
@endsection
@push('script_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen({
                width: "100%"
            });
            $('#land_sale_employee_id').on('change', function() {
                const employeeId = $(this).val();
                if (employeeId) {
                    $.ajax({
                        url: `/get-incentive-amount/${employeeId}`,
                        type: 'GET',
                        success: function(response) {
                            $('#incentive_amount').val(response.incentive_amount);
                        },
                        error: function() {
                            $('#incentive_amount').val('Error retrieving incentive amount');
                        }
                    });
                } else {
                    $('#incentive_amount').val('');
                }
            });

            $('#amount').on('input', function() {
                const incentiveAmount = parseFloat($('#incentive_amount').val());
                const payAmount = parseFloat($(this).val());

                if (payAmount > incentiveAmount) {
                    $('#amount-error').show();
                } else {
                    $('#amount-error').hide();
                }
            });

            $('#payment-form').on('submit', function(event) {
                const incentiveAmount = parseFloat($('#incentive_amount').val());
                const payAmount = parseFloat($('#amount').val());

                if (payAmount > incentiveAmount) {
                    $('#amount-error').show();
                    event.preventDefault();
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.bank').hide();
        });

        function employeeFiltration(e) {
            const head_id_filter = e.value;
            console.log(head_id_filter);

            const employeeDropdown = $('#land_sale_employee');

            if (head_id_filter) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('filter-employee-wise-data') }}",
                    data: {
                        head_id_filter: head_id_filter
                    },
                    success: function(data) {
                        employeeDropdown.empty().append(
                            '<option value="">--Select One--</option>');
                        $.each(data, function(key, value) {
                            employeeDropdown.append('<option value="' + value.id + '">' + value
                                .employee_name + '</option>');
                        });
                        employeeDropdown.trigger("chosen:updated");
                    },
                    error: function() {
                        alert('Failed to fetch employee data. Please try again.');
                    }
                });
            } else {
                employeeDropdown.empty().append('<option value="">--Select One--</option>').trigger("chosen:updated");
            }
        }


        function filterEmployee(e) {
            const head_id = e.value;
            console.log(head_id);

            const employeeDropdown = $('#land_sale_employee_id');

            if (head_id) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('filter-employee-data') }}",
                    data: {
                        head_id: head_id
                    },
                    success: function(data) {
                        employeeDropdown.empty().append(
                            '<option value="">--Select One--</option>');
                        $.each(data, function(key, value) {
                            employeeDropdown.append('<option value="' + value.id + '">' + value
                                .employee_name + '</option>');
                        });
                        employeeDropdown.trigger("chosen:updated");
                    },
                    error: function() {
                        alert('Failed to fetch employee data. Please try again.');
                    }
                });
            } else {
                employeeDropdown.empty().append('<option value="">--Select One--</option>').trigger("chosen:updated");
            }
        }


        function checkType() {
            var fundSelect = document.getElementById("fund_id");
            var paymentTypeSelect = document.getElementById("payment_type");
            var selectedFundId = fundSelect.value;
            var paymentTypeOption = paymentTypeSelect.querySelector("option[value='Bank']");

            if (selectedFundId === "1") {
                paymentTypeOption.selected = true;
            }

            $(".chosen-select").chosen();
        }

        function checkPaymentType() {
            var fund_id = document.getElementById('fund_id').value;
            if (fund_id == 2) {
                $('.bank').hide().removeAttr('required', true);
                $('.cheque').hide();
                $('.mobile').hide();

            }
            if (fund_id == 1) {
                $('.bank').show().prop('required', true);
                $('.cheque').show();
                $('.mobile').hide();

            }
            if (fund_id == 3) {
                $('.bank').hide().removeAttr('required', true);
                $('.cheque').hide();
                $('.mobile').show();
            }
            if (fund_id == 4) {
                $('.bank').hide().removeAttr('required', true);
                $('.cheque').hide();
                $('.mobile').hide();
            }
        }

        function showBankInfo() {
            const fundId = $('#fund').val();
            console.log(fundId);
            if (fundId == 1) {
                $('.bank').show();
                $('#bank_id').prop('required', true);
                $('#account_id').prop('required', true);
            } else {
                $('.bank').hide();
                $('#bank_id').prop('required', false);
                $('#account_id').prop('required', false);
            }
        }

        function filterAccount() {
            var bank_id = document.getElementById('bank_id').value;
            var url = "{{ route('filter-account') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#account').find('option').remove();
                    $('#account').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account').append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                    $('#account').trigger("chosen:updated");
                },
            });
        }

        function showAccountBranch(id) {
            const accountId = $('#account_id-' + id).val();
            const url = "{{ route('getAccountBranch') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    account_id: accountId
                },
                dataType: "json",
                success: function(response) {
                    if (response) {
                        $('#branch-' + id).val(response.branch);
                    } else {
                        alert('No data found for the selected account.');
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the account data.');
                }
            });
        }
    </script>
@endpush
