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


    body {
        font-family: Arial, sans-serif;
        background-color: #e6f7ff;
        margin: 0;
        padding: 0;
    }

    .container-voucher {
        /* width: 800px;
        margin: 50px auto; */
        padding: 20px;
        background-color: #89d8e6;
        /* border: 2px solid #000; */
        /* border-radius: 10px; */
    }

    .with-margin {
        margin-bottom: -8px !important;
    }

    .with-margin-address {
        margin-bottom: -1px !important;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-section .logo img {
        height: 100px;
        width: auto;
    }

    .mr-6 {
        margin-right: 16.4rem !important;
    }

    .company-info {
        text-align: center;
    }

    .company-info h1 {
        font-size: 3rem !important;
        margin: 0;
    }

    .company-info h5 {
        font-size: 16px;
        margin: 0;
    }

    .company-info h6 {
        font-size: 14px;
        margin: 0;
    }

    .voucher-show-info {
        text-align: center;
    }

    .voucher-show-info h3 {
        font-size: 18px;
        margin: 0;
    }

    .voucher-show-info h5 {
        font-size: 14px;
        margin: 0;
    }

    .voucher-info {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .details-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dotted-container {
        display: flex;
        align-items: center;
        font-size: 14px;
        margin: 5px 0;
    }

    .dotted-line-voucher {
        flex-grow: 1;
        border-bottom: 1px dotted #000;
        margin-left: 10px;
        padding: 0 5px;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .details-table th,
    .details-table td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }

    .details-table th {
        background-color: #161616;
        color: #fff;
    }

    .amount-words {
        margin-top: 10px;
        font-size: 14px;
        font-weight: bold;
    }

    .footer-section {
        display: flex;
        justify-content: space-between;
        margin-top: 21px;
    }

    .footer-box {
        text-align: center;
        font-size: 14px;
        padding-top: 20px;
        /* border-top: 1px solid #000; */
        width: 18%;
    }

    .modal-body {
        background-color: #89d8e6 !important;
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
                            Withdrawn Incentive List
                        </h3>
                        <a href="{{ route('incentive_stock_list') }}"><button
                            class="text-end col-sm-2 btn btn-success btn-sm">
                            <i class="fa fa-list" aria-hidden="true"></i> Head Wise Incentive List
                        </button></a>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">

                        {{-- search / Filter options --}}

                        <form action="{{ route('incentive_withdrawn_list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="voucher_no">Voucher No.<i class="text-danger">*</i></label>
                                    <input type="text" placeholder="Voucher No." name="voucher_no" class="form-control">
                                </div>
                                <div class="col-lg-3">
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
                                <div class="col-lg-4">
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
                                    <th width="10%" class="text-center">Voucher No</th>
                                    <th width="10%" class="text-center">Account Head</th>
                                    <th width="10%" class="text-center">Director/Co-Ordinator/Shareholder/Outsider</th>
                                    <th width="10%" class="text-center">Withdrawn Amount</th>
                                    <th width="25%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($incentive_payment) --}}
                                @foreach ($incentive_payment as $item)
                                    {{-- @dd($item) --}}
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->voucher_no ?? '' }}</td>
                                        <td class="text-center">
                                            {{ $item->sales_incentive->employee->head->head_name ?? '' }}</td>
                                        <td class="text-center">{{ $item->sales_incentive->employee->employee_name ?? '' }} ({{ $item->sales_incentive->employee->userType->type ?? '' }})
                                        </td>
                                        <td class="text-center">{{ $item->amount ?? '' }} TK.</td>

                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-withdrawn-modal-{{ $item->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-success  mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- <a href="{{ route('land_sale_bill_generate', $item->id) }}" target="_blank"><i
                                                    class="fa fa-print" style="color: rgb(28, 145, 199)"></i></a> --}}

                                            {{-- @if ($item->remaining_amount == 0)
                                            <span class="label label-success text-green"><b>Paid</b></span>
                                            @else --}}
                                            {{-- <a data-toggle="modal"
                                                data-target=".incentive-payment-modal-{{ $item->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-primary  mr-1">
                                                <i class="fa fa-credit-card"></i>
                                            </a> --}}
                                            {{-- @endif --}}


                                            {{-- </td>
                                    </tr>
                                    {{-- </tr> --}}
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">Total:</th>
                                    <th class="text-center">{{ $incentive_payment->sum('amount') }} TK.</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                @if ($incentive_payment instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $incentive_payment->links() }}
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    @foreach ($incentive_payment as $v_data)
    {{-- @dd($v_data) --}}
        @php
            // $detail_array = App\Models\IncomeDetails::where('income_id', $v_data->id)->get();
            $company_info = App\Models\Company::where(
                'id',
                $v_data->other_company_id ? $v_data->other_company_id : $v_data->company_id,
            )->first();
        @endphp
        <div class="modal fade view-withdrawn-modal-{{ $v_data->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content custom-modal">
                    <div class="d-flex justify-content-end">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="fs-2 mr-3 mt-3" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-voucher">
                            <!-- Header Section -->
                            <div class="header-section">
                                <div class="logo ml-5">
                                    <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}"
                                        alt="Logo">
                                </div>
                                <div class="company-info">
                                    <h5 class="mr-6 with-margin"><strong>A sister concern of Unity Group</strong></h5>
                                    <h1>{{ $company_info->name }}</h1>
                                    <h5><strong>Unity, Honesty & Prosperity</strong></h5>
                                    <h6 class="with-margin-address">{{ $company_info->address }}</h6>
                                    <h6 class="with-margin-address">E-Mail: {{ $company_info->email }}, Phone:
                                        {{ $company_info->phone }} </h6>
                                </div>
                                <div class="voucher-show-info">
                                    <h5>Voucher No.:</h5>
                                    <div
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center;">
                                        <h3 style="margin: 0; font-size: 16px;"><strong>{{ $v_data->voucher_no }}</strong>
                                        </h3>
                                    </div>
                                    <div class="mt-1"
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center; font-size: 15px;">
                                        <p style="margin: 0;"><strong>Debit Voucher</strong></p>
                                    </div>
                                </div>

                            </div>

                            <div class="voucher-info">
                                <div><strong><i class="fas fa-globe"
                                            style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
                                <div>
                                    <input type="checkbox"
                                        {{ $v_data->fund_id == 2 ? 'checked' : '' }}>
                                    <strong>Cash</strong>
                                </div>
                                <div>
                                    <input type="checkbox"
                                        {{ $v_data->fund_id == 1 ? 'checked' : '' }}>
                                    <strong>Bank</strong>
                                </div>
                                {{-- <div>
                                    <input type="checkbox" {{ $v_data->adjustment_amount != null ? 'checked' : '' }}>
                                    <strong>Adjustment</strong>
                                </div> --}}

                                <div><strong>Date: {{ date('d/m/Y', strtotime($v_data->pay_date)) }}</strong></div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container"><strong>Head of Accounts:</strong>
                                    <span class="dotted-line-voucher">{{ $v_data->sales_incentive->employee->head->head_name ?? '' }}</span>
                                </div>
                                <div class="dotted-container"><strong>Paid To:</strong><span
                                        class="dotted-line-voucher">{{ $v_data->sales_incentive->employee->employee_name ?? '' }}</span></div>
                            </div>

                            <!-- Table for Particulars and Amounts -->
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Description</th>
                                        <th>Amount (BDT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($detail_array as $index => $v_detail) --}}
                                        <tr>
                                            <td>1</td>
                                            <td><strong>{{ $v_data->remarks ?? '' }}</strong></td>
                                            <td>{{ number_format($v_data->amount, 2) }}</td>
                                        </tr>
                                    {{-- @endforeach --}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        @php
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $amount = getBangladeshCurrency($v_data->amount);
                                            //echo $f->format($v_data->amount);
                                        @endphp
                                        <td colspan="2" class="amount-words">
                                            <strong>In Words:</strong> {{ $amount }} Only
                                        </td>
                                        <td>{{ number_format($v_data->amount, 2) }}</td>
                                    </tr>

                                </tfoot>
                            </table>



                            <!-- Footer Section -->
                            <div class="footer-section">
                                <div class="footer-box">Received By</div>
                                <div class="footer-box">Accountant</div>
                                <div class="footer-box">Director Admin</div>
                                <div class="footer-box">Director Finance</div>
                                <div class="footer-box">Managing Director / Chairman</div>
                            </div>
                        </div>

                        <div class="container-voucher mt-5" style="margin-top: 120px;">
                            <!-- Header Section -->
                            <div class="header-section">
                                <div class="logo ml-5">
                                    <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}"
                                        alt="Logo">
                                </div>
                                <div class="company-info">
                                    <h5 class="mr-6 with-margin"><strong>A sister concern of Unity Group</strong></h5>
                                    <h1>{{ $company_info->name }}</h1>
                                    <h5><strong>Unity, Honesty & Prosperity</strong></h5>
                                    <h6 class="with-margin-address">{{ $company_info->address }}</h6>
                                    <h6 class="with-margin-address">E-Mail: {{ $company_info->email }}, Phone:
                                        {{ $company_info->phone }} </h6>
                                </div>
                                <div class="voucher-show-info">
                                    <h5>Voucher No.:</h5>
                                    <div
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center;">
                                        <h3 style="margin: 0; font-size: 16px;"><strong>{{ $v_data->voucher_no }}</strong>
                                        </h3>
                                    </div>
                                    <div class="mt-1"
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center; font-size: 15px;">
                                        <p style="margin: 0;"><strong>Debit Voucher</strong></p>
                                    </div>
                                </div>

                            </div>

                            <div class="voucher-info">
                                <div><strong><i class="fas fa-globe"
                                            style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
                                            <div>
                                                <input type="checkbox"
                                                    {{ $v_data->fund_id == 2 ? 'checked' : '' }}>
                                                <strong>Cash</strong>
                                            </div>
                                            <div>
                                                <input type="checkbox"
                                                    {{ $v_data->fund_id == 1 ? 'checked' : '' }}>
                                                <strong>Bank</strong>
                                            </div>
                                {{-- <div>
                                    <input type="checkbox" {{ $v_data->adjustment_amount != null ? 'checked' : '' }}>
                                    <strong>Adjustment</strong>
                                </div> --}}

                                <div><strong>Date: {{ date('d/m/Y', strtotime($v_data->pay_date)) }}</strong></div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container"><strong>Head of Accounts:</strong>
                                    <span class="dotted-line-voucher">{{ $v_data->sales_incentive->employee->head->head_name ?? '' }}</span>
                                </div>
                                <div class="dotted-container"><strong>Paid To:</strong><span
                                        class="dotted-line-voucher">{{ $v_data->sales_incentive->employee->employee_name ?? '' }}</span></div>
                            </div>


                            <!-- Table for Particulars and Amounts -->
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Description</th>
                                        <th>Amount (BDT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($detail_array as $index => $v_detail) --}}
                                        <tr>
                                            <td>1</td>
                                            <td><strong>{{ $v_data->remarks ?? '' }}</strong></td>
                                            <td>{{ number_format($v_data->amount, 2) }}</td>
                                        </tr>
                                    {{-- @endforeach --}}
                                </tbody>


                                <tfoot>
                                    <tr>
                                        @php
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $amount = getBangladeshCurrency($v_data->amount);
                                            //echo $f->format($v_data->amount);
                                        @endphp
                                        <td colspan="2" class="amount-words">
                                            <strong>In Words:</strong> {{ $amount }} Only
                                        </td>
                                        <td>{{ number_format($v_data->amount, 2) }}</td>
                                    </tr>

                                </tfoot>
                            </table>



                            <!-- Footer Section -->
                            <div class="footer-section">
                                <div class="footer-box">Received By</div>
                                <div class="footer-box">Accountant</div>
                                <div class="footer-box">Director Admin</div>
                                <div class="footer-box">Director Finance</div>
                                <div class="footer-box">Managing Director / Chairman</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@push('script_js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
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
    </script>