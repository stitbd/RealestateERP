@extends('layouts.app')
<style>
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
                {{-- @dd($investsCount) --}}
                {{-- @if ($investsCount > 0) 
                    <h5 class="bg-warning p-3 text-center"> <strong>Need to return {{$investsCount}}  invest in this week!</strong> </h5>
                 @endif --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Collected Invest Amount List
                        </h3>
                        <a href="{{ route('collect-invest-create') }}" class="text-end col-sm-2 btn btn-success btn-sm">
                            <i class="fa fa-plus" aria-hidden="true"></i> Collect
                        </a>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('collect-invest-list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="fund">Fund Type</label>
                                    <select name="fund_id" class="form-control">
                                        <option value="">Select a Fund </option>
                                        @foreach ($fund_types as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="bank">Bank</label>
                                    <select name="bank_id" class="form-control">
                                        <option value="">Select a Bank </option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="consumer_investor_id">Consumer Investor</label>
                                    <select name="consumer_investor_id" class="form-control">
                                        <option value="">Select Investor</option>
                                        @foreach ($invests as $invest)
                                            <option value="{{ $invest->id }}">{{ $invest->consumer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">From</label>
                                    <input type="date" class="form-control" name="start_date">
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_date">To</label>
                                    <input type="date" class="form-control" name="end_date">
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
                                {{-- <a href="{{ url('collect-invest-print?fund_id=' . request()->get('fund_id') . '&consumer_investor_id=' . request()->get('consumer_investor_id') . '&start_date=' . request()->get('start_date') . '&bank_id=' . request()->get('bank_id') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a> --}}
                                {{-- <a href="{{ url('loan-pdf?project_id=' . request()->get('project_id') . '&loan_date=' . request()->get('loan_date') . '&bank_id=' . request()->get('bank_id') . '&valid_date=' . request()->get('valid_date')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a> --}}
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>Voucher No</th>
                                    <th>Date</th>
                                    <th>Collection Month</th>
                                    <th>Consumer Investor</th>
                                    <th>Fund</th>
                                    <th>Bank</th>
                                    <th>Account</th>
                                    <th>Collected Amount</th>
                                    <th>Due Amount (Missed Month's)</th>
                                    <th>Remarks</th>
                                    <th>View & Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = $collect_invest->perPage() * ($collect_invest->currentPage() - 1) + 1;
                                    $displayedConsumers = [];
                                @endphp
                            
                                @foreach ($collect_invest as $item)
                                    @if ($item->type === 'collect')
                                        <tr>
                                            <td>
                                                {{-- @php
                                                    $i =
                                                        $item instanceof \Illuminate\Pagination\LengthAwarePaginator
                                                            ? $loop->iteration + $item->perPage() * ($item->currentPage() - 1)
                                                            : ++$i;
                                                @endphp
                                                {{ $i }} --}}
                                                 {{$i++}}
                                            </td>
                                            <td>{{ $item->collection->voucher_no }}</td>
                                            <td>{{ date('d F Y', strtotime($item->collection->date)) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->collect_month)->format('F Y') }}</td>
                                            <td>{{ $item->collection->consumer->consumer_name ?? '' }}</td>
                                            <td>{{ $item->collection->fund->name ?? '' }}</td>
                                            <td>{{ $item->collection->bank->name ?? '' }}</td>
                                            <td>{{ $item->collection->account->account_no ?? '' }}</td>
                                            <td>{{ $item->collect_amount }} Tk.</td>
                            
                                            @if (!in_array($item->consumer_investor_id, $displayedConsumers))
                                                @php
                                                    $rowspan = $collect_invest
                                                        ->filter(function ($c) use ($item) {
                                                            return $c->consumer_investor_id === $item->consumer_investor_id;
                                                        })
                                                        ->count();
                            
                                                    $dueAmount = $item->collection->consumer->due_amount ?? '';
                                                @endphp
                            
                                                <td rowspan="{{ $rowspan }}">
                                                    {{ $dueAmount }} Tk.
                                                </td>
                            
                                                @php
                                                    $displayedConsumers[] = $item->consumer_investor_id;
                                                @endphp
                                            @endif
                            
                                            <td>{{ $item->remarks }}</td>
                                            <td>
                                                <a data-toggle="modal" data-target="#view-modal-{{ $item['id'] }}">
                                                    <i class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i>
                                                </a>
                                                <a href="{{ route('print-collect-voucher', $item->id) }}" target="_blank">
                                                    <i class="fa fa-print" style="color: rgb(28, 145, 199)"></i>
                                                </a>
                                                <a data-toggle="modal" data-target="#view-receipt-modal-{{ $item['id'] }}">
                                                    <i class="fas fa-file-invoice pr-2 pl-2" style="color: rgb(78, 151, 78)"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            

                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $collect_invest->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @dd($collect_invest) --}}
    @foreach ($collect_invest as $item)
        @php
            $company_info = App\Models\Company::where('id', $item->company_id)->first();
        @endphp
        <div class="modal fade" id="view-modal-{{ $item['id'] }}" tabindex="-1" role="dialog"
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
                                        <h3 style="margin: 0; font-size: 16px;">
                                            <strong>{{ $item->collection->voucher_no }}</strong>
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
                                <div><input type="checkbox"
                                        {{ $item->collection->payment_type == 'Cash' ? 'checked' : '' }}><strong>
                                        Cash</strong>
                                </div>
                                <div><input type="checkbox"
                                        {{ $item->collection->payment_type == 'Bank' ? 'checked' : '' }}><strong>
                                        Bank</strong>
                                </div>
                                <div><strong>Date: {{ date('d/m/Y', strtotime($item->collection->date)) }}</strong></div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container"><strong>Head of Accounts:</strong>
                                    <span
                                        class="dotted-line-voucher">{{ $item->collection->consumer->head->head_name ?? 'No Head Name' }}
                                    </span>
                                </div>
                                <div class="dotted-container"><strong>Received From:</strong><span
                                        class="dotted-line-voucher">{{ $item->collection->consumer->consumer_name ?? '' }}</span>
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
                                    <tr>
                                        {{-- @dd($item) --}}
                                        <td>1</td>
                                        <td>
                                            <strong>{{ $item->remarks ?? '' }}</strong>
                                        </td>
                                        <td>{{ number_format($item->collect_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        @php
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $amount = getBangladeshCurrency($item->collect_amount);
                                        @endphp
                                        <td colspan="2" class="amount-words">
                                            <strong>In Words:</strong> {{ $amount }} Only
                                        </td>
                                        <td>{{ number_format($item->collect_amount, 2) }}</td>
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
                                        <h3 style="margin: 0; font-size: 16px;">
                                            <strong>{{ $item->collection->voucher_no }}</strong>
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
                                <div><input type="checkbox"
                                        {{ $item->collection->payment_type == 'Cash' ? 'checked' : '' }}><strong>
                                        Cash</strong>
                                </div>
                                <div><input type="checkbox"
                                        {{ $item->collection->payment_type == 'Bank' ? 'checked' : '' }}><strong>
                                        Bank</strong>
                                </div>
                                <div><strong>Date: {{ date('d/m/Y', strtotime($item->collection->date)) }}</strong></div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container"><strong>Head of Accounts:</strong>
                                    <span
                                        class="dotted-line-voucher">{{ $item->collection->consumer->head->head_name ?? 'No Head Name' }}</span>
                                </div>
                                <div class="dotted-container"><strong>Received From:</strong><span
                                        class="dotted-line-voucher">{{ $item->collection->consumer->consumer_name ?? '' }}</span>
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
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <strong>{{ $item->remarks ?? '' }}</strong>
                                        </td>
                                        <td>{{ number_format($item->collect_amount, 2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        @php
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $amount = getBangladeshCurrency($item->collect_amount);
                                        @endphp
                                        <td colspan="2" class="amount-words">
                                            <strong>In Words:</strong> {{ $amount }} Only
                                        </td>
                                        <td>{{ number_format($item->collect_amount, 2) }}</td>
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



    {{-- money receipt --}}
    @foreach ($collect_invest as $item)
        @php
            $company_info = App\Models\Company::where('id', $item->company_id)->first();
        @endphp
        <div class="modal fade" id="view-receipt-modal-{{ $item['id'] }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content custom-modal">
                    <div class="d-flex justify-content-end">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <div>
                            <button type="button" class="btn btn-light">
                                <a href="{{ route('investor_money_receipt', $item->id) }}" target="_blank"><i
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
                                    <h1 style="font-size: 55px; font-weight: bold; margin: 0;">
                                        {{ $company_info->name }}
                                    </h1>
                                    <div style="display: flex; align-items: right; margin-left:50px">
                                        <hr style="flex: 1; border: 3px solid #4b4b4b73; margin: 5px 10px 5px 0;">
                                        <p style="font-style: italic; font-size: 14px; margin: 0;">Unity, Honesty &
                                            Prosperity</p>
                                    </div>

                                </div>
                            </div>

                            <div class="text-center">
                                <h4 style="font-style:initial; color: #7b7a7a; font-size: 20px; margin-top: 10px;">
                                    <b>Money
                                        Receipt</b>
                                </h4>
                            </div>


                            <!-- Date, MR No, and Total Amount Section -->
                            <div class="receipt-details" style="display: flex; justify-content: space-between;">
                                <!-- Payment Type Section -->
                                <div class="payment-type-section">
                                    <label>Cash <input type="checkbox"
                                            {{ $item->collection->payment_type == 'Cash' ? 'checked' : '' }} /></label>
                                    <hr style="margin: 10px 0; border: 1px solid #000000;">
                                    <p>Cheque/DD/PO No.:
                                        {{-- {{ $v_data->payment_type == 'Bank' ? $v_data->cheque_no ?? '' : '' }}</p> --}}
                                        {{ $item->collection->cheque_no ?? '' }}</p>
                                    <p>Drawn on: {{ $item->collection->bank->name ?? '' }}
                                    </p>
                                    <p>Date: {{ $item->collection->cheque_issue_date ?? '' }}</p>
                                </div>


                                <div style="text-align: left; font-size: 14px; margin-bottom: 10px;">
                                    <p style="margin-bottom: 25px;">Date:
                                        {{ date('d.m.Y', strtotime($item->collection->date)) }}</p>
                                    <p style="margin-bottom: 30px;">MR No.:
                                        <strong>{{ $item->collection->voucher_no }}</strong>
                                    </p>
                                    <p>Total Amount:
                                        <span style="border: 1px solid black; padding: 5px 10px; display: inline-block;">
                                            <strong>{{ number_format($item->collect_amount, 2) }} Tk</strong>
                                        </span>
                                    </p>

                                </div>

                            </div>

                            <!-- Received From and Account Details -->
                            <div class="details-section-receipt">
                                <p>Received with thanks from
                                    <span
                                        class="dotted-line"><b>{{ $item->collection->consumer->consumer_name ?? '' }}</b></span>
                                </p>

                                <p>On account of
                                    <span
                                        class="dotted-line"><b>{{ $item->collection->consumer->head->head_name ?? 'No Head Name' }}</b></span>
                                </p>

                                <p>A sum of amount:
                                    @php
                                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                        $amount = getBangladeshCurrency($item->collect_amount);
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
                                <p><strong>Email: {{ $company_info->email }}, Phone:
                                        {{ $company_info->phone }}</strong>
                                </p>
                                <p><strong>www.unitylandmark.com</strong></p>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
