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

    .modal-body-receipt {
        background-color: #ffffff !important;
    }

    .money-receipt {
        font-family: Arial, sans-serif;
        /* max-width: 800px; */
        margin: auto;
        padding: 20px;
        /* border: 1px solid #000; */
    }

    .header-section-receipt {
        display: flex;
        align-items: center;
        justify-content: left;
    }

    .logo img {
        width: 60px;
        margin-right: 10px;
    }

    .company-info-receipt h1 {
        font-family: 'Times New Roman', Times, serif;
        font-weight: bold;
    }

    .company-info-receipt p {
        font-size: 14px;
        color: #555;
        font-style: italic;
        text-align: right;
        margin: 0;
    }

    .text-center h4 {
        color: #928d8d;
        font-weight: bold;
        font-size: 20px;
    }

    .receipt-details {
        /* text-align: right; */
        margin-bottom: 10px;
    }

    /* .receipt-details p {
        margin: 0;
    } */

    .payment-type-section {
        border: 1px solid #000;
        padding: 5px;
        margin-bottom: 10px;
    }

    .payment-type-section label {
        display: inline-block;
        margin-right: 15px;
    }

    .payment-type-section p {
        margin: 5px 0;
    }

    .details-section-receipt p {
        display: flex;
        font-size: 14px;
        margin: 10px 0;
    }

    /* .details-section-receipt p::before {
        content: "";
        flex-grow: 1;
        border-bottom: 1px dotted #000;
        margin-right: 10px;
    } */

    .dotted-line {
        flex-grow: 2;
        border-bottom: 1px dotted #000;
        padding: 0 10px;
        margin-left: 10px;
    }

    .details-table-receipt {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .details-table-receipt th,
    .details-table-receipt td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
    }

    .details-table-receipt th {
        background-color: #f2f2f2;
    }

    .details-table-receipt tfoot td {
        font-weight: bold;
    }

    /* .footer-section-receipt {
        display: flex;
        justify-content: space-between;
        padding-top: 10px;
        margin-top: 60px;
    }

    .footer-box-receipt {
        text-align: center;
        flex: 1;
        border-top: 1px solid #000;
        padding: 5px;
        font-size: 12px;
    } */

    .footer-section-receipt {
        display: flex;
        justify-content: space-between;
        margin-top: 60px;
    }

    .footer-box-receipt {
        text-align: center;
        flex: 1;
        /* border-top: 1px solid #000; */
        padding: 5px;
        font-size: 12px;
    }

    .signature-box {
        position: relative;
        margin-bottom: -35px;
    }

    .signature-line {
        border-top: 1px solid #1b1b1b;
        position: absolute;
        top: 0;
        left: 50px;
        right: 50px;
    }

    .signature-box p {
        margin-top: 10px;
    }

    .company-info-footer {
        background-color: #4b4b4b45;
        text-align: center;
        font-size: 12px;
        margin-top: 20px;
    }
</style>
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            Payment List
                        </h3>
                        <div class="">
                            <a href="{{ route('development_payment_list') }}" class="btn btn-success" style="margin-left: 700px;">
                                <i class="fas fa-money-check-alt"></i> Development Payment Details
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        {{-- Search input --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-info">
                                    <tr>
                                        <th>#</th>
                                        <th width="10%" class="text-center">Voucher No</th>
                                        <th width="15%" class="text-center">Project</th>
                                        <th width="8%" class="text-center">Type</th>
                                        <th width="8%" class="text-center">TDC</th>
                                        <th width="12%" class="text-center">Customer Name</th>
                                        <th width="12%" class="text-center">Payment Type</th>
                                        <th width="12%" class="text-center">Pay Date</th>
                                        <th width="11%" class="text-center">Paid Amount</th>
                                        <th width="20%" class="text-center">remarks</th>
                                        <th width="10%" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="paymentTableBody">
                                    @foreach ($paid_installment_list as $item)
                                        <tr>
                                            @php
                                                $installment = \App\Models\Installment::where(
                                                    'land_sale_id',
                                                    $item->land_sale_id,
                                                )
                                                    ->where(['company_id' => Session::get('company_id')])
                                                    ->with('landSale')
                                                    ->first();

                                                $payment = \App\Models\LandPayment::where(
                                                    'land_sale_id',
                                                    $item->land_sale_id,
                                                )
                                                    ->where('payment_option', 'initial')
                                                    ->where(['company_id' => Session::get('company_id')])
                                                    ->first();
                                            @endphp
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $item->landSale->invoice_no ?? '' }}</td>
                                            <td class="text-center">{{ $item->landSale->customer->project->name ?? '' }}
                                            </td>
                                            <td class="text-center">{{ $item->landSale->type ?? '' }}</td>
                                            <td class="text-center">{{ $item->landSale->customer->customer_code ?? '' }}
                                            </td>
                                            <td class="text-center">{{ $item->landSale->customer->customer_name ?? '' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($item->PaymentType->name == 'Cash')
                                                    Cash
                                                @elseif($item->PaymentType->name == 'Bank')
                                                    Bank
                                                @else
                                                    {{ $item->PaymentType->name ?? '' }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $item->pay_date ?? '' }}</td>
                                            <td class="text-center">{{ $item->amount ?? '' }}</td>
                                            <td class="text-center">{{ $item->remarks ?? '' }}</td>
                                            <td>
                                                <a data-toggle="modal"
                                                    data-target="#view-payment-modal-{{ $item['id'] }}"><i
                                                        class="fas fa-file-alt pr-2 pl-2"
                                                        style="color: rgb(78, 151, 78)"></i></a>
                                                </a>
                                                <a href="{{ route('payment_credit_voucher', $item->id) }}"
                                                    target="_blank"><i class="fa fa-print"
                                                        style="color: rgb(28, 145, 199)"></i></a>
                                                <a data-toggle="modal"
                                                    data-target="#view-receipt-modal-{{ $item['id'] }}">
                                                    <i class="fas fa-receipt pr-2 pl-2" style="color: rgb(78, 151, 78)"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Credit Voucher --}}
    @foreach ($paid_installment_list as $model)
        @php
            // $detail_array = App\Models\IncomeDetails::where('income_id',$v_data->id)->get();
            $company_info = App\Models\Company::where('id', $model->company_id)->first();
        @endphp
        <div class="modal fade" id="view-payment-modal-{{ $model['id'] }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content custom-modal">
                    <div class="d-flex justify-content-end">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        {{-- <button type="button" class="btn btn-light" onclick="printVoucher('view-payment-modal-{{ $model['id'] }}')">
                            <i class="fa fa-print"></i> Print
                        </button> --}}
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
                                        {{ $company_info->phone }}
                                    </h6>
                                </div>
                                <div class="voucher-show-info">
                                    <h5>Voucher No.:</h5>
                                    <div
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center;">
                                        <h3 style="margin: 0; font-size: 16px;">
                                            <strong>{{ $model->landSale->invoice_no }}</strong>
                                        </h3>
                                    </div>
                                    <div class="mt-1"
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center; font-size: 15px;">
                                        <p style="margin: 0;"><strong>Credit Voucher</strong></p>
                                    </div>
                                </div>

                            </div>

                            <div class="voucher-info">
                                <div><strong><i class="fas fa-globe"
                                            style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
                                <div>
                                    <input type="checkbox" {{ $model->PaymentType->name == 'Cash' ? 'checked' : '' }}>
                                    <strong> Cash</strong>
                                </div>

                                <div><input type="checkbox"
                                        {{ $model->PaymentType->name == 'Bank' ? 'checked' : '' }}><strong>
                                        Bank</strong>
                                </div>
                                <div><strong>Date:
                                        {{ date('d/m/Y', strtotime($model->landSale->initial_payment_made_date)) }}</strong>
                                </div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container">
                                    <strong>Head of Accounts:</strong>
                                    <span class="dotted-line-voucher">
                                        {{ $model->landSale->type == 'Flat' ? 'Flat Sale' : ($model->landSale->type == 'Plot' ? 'Plot Sale' : 'Land Sale') }}
                                    </span>
                                </div>
                                <div class="dotted-container">
                                    <strong>Received From:</strong>
                                    <span class="dotted-line-voucher">{{ $model->landSale->customer->customer_name ?? '' }}</span>
                                </div>
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
                                        <td>
                                            Received From:<br>
                                            <strong>{{ $model->landSale->customer->customer_name ?? '' }}</strong>
                                        </td>
                                        <td>
                                            {{ number_format($model->amount, 2) }}
                                        </td>
                                    </tr>
                                    {{-- @endforeach --}}
                                </tbody>
                                <tfoot>
                                    <tr>

                                        @php
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $payment_amount = getBangladeshCurrency($model->amount);
                                        @endphp
                                        <td colspan="2" class="amount-words">
                                            <strong>In Words:</strong> {{ $payment_amount }} Only
                                        </td>
                                        <td>
                                            {{ number_format($model->amount, 2) }}
                                        </td>
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
                                        {{ $company_info->phone }}
                                    </h6>
                                </div>
                                <div class="voucher-show-info">
                                    <h5>Voucher No.:</h5>
                                    <div
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center;">
                                        <h3 style="margin: 0; font-size: 16px;">
                                            <strong>{{ $model->landSale->invoice_no }}</strong>
                                        </h3>
                                    </div>
                                    <div class="mt-1"
                                        style="border: 1px solid black; padding: 5px; border-radius: 8px; width: 135px; text-align: center; font-size: 15px;">
                                        <p style="margin: 0;"><strong>Credit Voucher</strong></p>
                                    </div>
                                </div>

                            </div>

                            <div class="voucher-info">
                                <div><strong><i class="fas fa-globe"
                                            style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
                                <div>
                                    <input type="checkbox" {{ $model->PaymentType->name == 'Cash' ? 'checked' : '' }}>
                                    <strong> Cash</strong>
                                </div>

                                <div><input type="checkbox"
                                        {{ $model->PaymentType->name == 'Bank' ? 'checked' : '' }}><strong>
                                        Bank</strong>
                                </div>
                                <div><strong>Date:
                                        {{ date('d/m/Y', strtotime($model->landSale->initial_payment_made_date)) }}</strong>
                                </div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container">
                                    <strong>Head of Accounts:</strong>
                                    <span
                                        class="dotted-line-voucher">{{ $model->landSale->type == 'Flat' ? 'Flat Sale' : 'Plot Sale' }}</span>
                                </div>
                                <div class="dotted-container">
                                    <strong>Received From:</strong>
                                    <span
                                        class="dotted-line-voucher">{{ $model->landSale->customer->customer_name ?? '' }}</span>
                                </div>
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
                                        <td>
                                            Received From:<br>
                                            <strong>{{ $model->landSale->customer->customer_name ?? '' }}</strong>
                                        </td>
                                        <td>
                                            {{ number_format($model->amount, 2) }}
                                        </td>
                                    </tr>
                                    {{-- @endforeach --}}
                                </tbody>
                                <tfoot>
                                    <tr>
                                        @php
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $payment_amount = getBangladeshCurrency($model->amount);
                                        @endphp
                                        <td colspan="2" class="amount-words">
                                            <strong>In Words:</strong> {{ $payment_amount }} Only
                                        </td>
                                        <td>
                                            {{ number_format($model->amount, 2) }}
                                        </td>
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

    {{-- Money Receipt --}}
    @foreach ($paid_installment_list as $model)
        @php
            // $detail_array = App\Models\IncomeDetails::where('income_id',$v_data->id)->get();
            $company_info = App\Models\Company::where('id', $model->company_id)->first();
        @endphp
        <div class="modal fade" id="view-receipt-modal-{{ $model['id'] }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content custom-modal">
                    <div class="d-flex justify-content-end">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <div>
                            <button type="button" class="btn btn-light">
                                <a href="{{ route('sale_payment_money_receipt', $model->id) }}" target="_blank"><i
                                        class="fa fa-print"></i> Print</a>

                            </button>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span class="fs-2 mr-3 mt-3" aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body-receipt">
                        <div class="money-receipt">
                            <!-- Header Section with Logo and Company Info -->
                            <div class="header-section-receipt">
                                <div class="logo">
                                    <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}"
                                        alt="Company Logo" />
                                </div>
                                <div class="company-info-receipt" style="margin-left: 20%;">
                                    <h1 style="font-size: 55px; font-weight: bold; margin: 0;">{{ $company_info->name }}
                                    </h1>
                                    <div style="display: flex; align-items: right; margin-left:50px">
                                        <hr style="flex: 1; border: 3px solid #4b4b4b73; margin: 5px 10px 5px 0;">
                                        <p style="font-style: italic; font-size: 14px; margin: 0;">Unity, Honesty &
                                            Prosperity</p>
                                    </div>

                                </div>
                            </div>

                            <div class="text-center">
                                <h4 style="font-style:initial; color: #7b7a7a; font-size: 20px; margin-top: 10px;"><b>Money
                                        Receipt</b></h4>
                            </div>


                            <!-- Date, MR No, and Total Amount Section -->
                            <div class="receipt-details" style="display: flex; justify-content: space-between;">
                                <!-- Payment Type Section -->
                                <div class="payment-type-section">
                                    <label>Cash <input type="checkbox"
                                            {{ $model->PaymentType->name == 'Cash' ? 'checked' : '' }} /></label>
                                    <hr style="margin: 10px 0; border: 1px solid #000000;">
                                    <p>Cheque/DD/PO No.:
                                        {{-- {{ $v_data->payment_type == 'Bank' ? $v_data->cheque_no ?? '' : '' }}</p> --}}
                                        {{ $model->remaining_amount_cheque_no ?? '' }}</p>
                                    <p>Drawn on: {{ $model->bank->name ?? '' }}
                                    </p>
                                    <p>Date: {{ $model->pay_date ?? '' }}</p>
                                </div>


                                <div style="text-align: left; font-size: 14px; margin-bottom: 10px;">
                                    <p style="margin-bottom: 25px;">Date:
                                        {{ date('d.m.Y', strtotime($model->pay_date)) }}</p>
                                    <p style="margin-bottom: 30px;">MR No.:
                                        <strong>{{ $model->landSale->invoice_no }}</strong>
                                    </p>
                                    <p>Total Amount:
                                        <span style="border: 1px solid black; padding: 5px 10px; display: inline-block;">
                                            <strong>{{ number_format($model->amount, 2) }} Tk</strong>
                                        </span>
                                    </p>

                                </div>

                            </div>

                            <!-- Received From and Account Details -->
                            <div class="details-section-receipt">
                                <p>Received with thanks from
                                    <span class="dotted-line"><b>{{ $model->landSale->customer->customer_name ?? '' }}
                                            &nbsp;(Code - {{ $model->landSale->customer->customer_code ?? '' }})</b></span>
                                </p>

                                <p>On account of
                                    <span
                                        class="dotted-line"><b> {{ $model->landSale->type == 'Flat' ? 'Flat Sale' : ($model->landSale->type == 'Plot' ? 'Plot Sale' : 'Land Sale') }}</b></span>
                                </p>

                                <p>A sum of amount:
                                    @php
                                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                        $amount = getBangladeshCurrency($model->amount);
                                        //echo $f->format($model->amount);
                                    @endphp
                                    <span class="dotted-line"><b>{{ $amount }} Only</b></span>
                                </p>
                            </div>



                            <!-- Footer Section with Signatures -->
                            <div class="footer-section-receipt">
                                <div class="footer-box-receipt">
                                    <div class="signature-box">
                                        <div class="signature-line"></div>
                                        <p>Payer/Ref. Signature</p>
                                    </div>
                                </div>
                                <div class="footer-box-receipt">
                                    <div class="signature-box">
                                        <div class="signature-line"></div>
                                        <p>Accounts Department</p>
                                    </div>
                                </div>
                                <div class="footer-box-receipt">
                                    <div class="signature-box">
                                        <div class="signature-line"></div>
                                        <p>Authorized Authority</p>
                                    </div>
                                </div>
                            </div>

                            <div class="company-info-footer">
                                <p><strong>Head Office: {{ $company_info->address }}</strong></p>
                                <p><strong>Email: {{ $company_info->email }}, Phone: {{ $company_info->phone }}</strong>
                                </p>
                                <p><strong>www.unitylandmark.com</strong></p>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    @endforeach


    {{-- Search --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('paymentTableBody');
            const rows = tableBody.getElementsByTagName('tr');

            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();

                for (let row of rows) {
                    const cells = row.getElementsByTagName('td');
                    let shouldDisplay = false;

                    // Skip the first cell (#) and last cell (Action)
                    for (let i = 1; i < cells.length - 1; i++) {
                        const cellText = cells[i].textContent.toLowerCase();
                        if (cellText.includes(searchTerm)) {
                            shouldDisplay = true;
                            break;
                        }
                    }

                    row.style.display = shouldDisplay ? '' : 'none';
                }
            });
        });
    </script>

    {{-- Money Receipt Print --}}
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
@endsection
