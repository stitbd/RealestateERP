@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
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
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Income List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('income-list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="category">Income Category</label>
                                    <select name="category" class="form-control chosen-select" id="category"
                                        onchange="filterHead(this);">
                                        <option value="">Select One</option>
                                        @foreach ($categories as $v_category)
                                            @php $incomes = json_decode($v_category->category_type)  @endphp
                                            @if ($incomes && in_array('Income', $incomes))
                                                <option value="{{ $v_category->id }}">{{ $v_category->category_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="head">Income Head</label>
                                    <select name="head" class="form-control chosen-select" id="head">
                                        <option value="">Select One</option>
                                        @foreach ($head as $v_head)
                                            <option value="{{ $v_head->id }}">{{ $v_head->head_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="search">Search For</label>
                                    <input type="text" name="search" class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <label for="Project">Fund</label>
                                    <select name="fund_id" class="form-control">
                                        <option value="">Select One</option>
                                        @foreach ($fund_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="start_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('income-print?category=' . request()->get('category') . '&head=' . request()->get('head') . '&search=' . request()->get('search') . '&fund_id=' . request()->get('fund_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                            </div>
                        </div>
                        {{-- <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('income-print?fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print
                            </a>
                        </div>
                    </div> --}}
                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>SL NO.</th>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Fund</th>
                                    <th>Adjustment</th>
                                    <th>Amount</th>
                                    <th>Attachment</th>
                                    <th>View & Print</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $i = 0;
                                @endphp
                                @foreach ($income_data as $item)
                                    <tr>
                                        <td> @php
                                            $i =
                                                $income_data instanceof \Illuminate\Pagination\LengthAwarePaginator
                                                    ? $loop->iteration +
                                                        $income_data->perPage() * ($income_data->currentPage() - 1)
                                                    : ++$i;
                                        @endphp {{ $i }}
                                        </td>
                                        <td>{{ date('d/m/Y', strtotime($item->payment_date)) }}</td>
                                        <td>
                                            @foreach ($item->income_details as $index => $v_detail)
                                                {{ $v_detail->remarks ? $v_detail->remarks : '' }} <br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($item->income_details as $index => $v_detail)
                                                {{ $v_detail->fund->name ?? '' }} <br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">
                                            @if ($item->adjustment_amount)
                                                Tk. {{ $item->adjustment_amount ?? '' }}
                                            @endif
                                        </td>
                                        <td class="text-right">Tk. {{ $item->amount }}</td>
                                        <td>
                                            @if ($item->attachment != null)
                                                <a href="{{ asset('attachment/' . $item->attachment) }}" target="_blank"
                                                    class="btn btn-info">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <a data-toggle="modal" data-target="#view-modal-{{ $item['id'] }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a href="{{ route('print-voucher', $item->id) }}" target="_blank"><i
                                                    class="fa fa-print" style="color: rgb(28, 145, 199)"></i></a>
                                            <a data-toggle="modal" data-target="#view-receipt-modal-{{ $item['id'] }}">
                                                <i class="fas fa-file-invoice pr-2 pl-2"
                                                    style="color: rgb(78, 151, 78)"></i>
                                            </a>

                                        </td>
                                        <td class="text-center">
                                            {{-- @if ($item->created_at->format('Y-m-d') == date('Y-m-d')) --}}
                                            @if ($item->income_type != 'collection')
                                            <a href="{{ route('edit-income', $item->id) }}"><i class="fa fa-edit"
                                                    style="color: rgb(28, 145,199)"></i></a>
                                            @endif
                                            <a href="{{ route('statusUpdate', $item->id) }}"
                                                onclick="alert('Are You Sure Want to delete this?')"><i
                                                    class="fa fa-trash text-danger" style=""></i></a>
                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            @if ($item->client_name != null)
                                                {!! $item->client_name != null ? 'Client Name:' . $item->client_name . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->client_id != null)
                                                {!! $item->client_id != null ? 'Client ID:' . $item->client_id . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->cheque_no)
                                                {!! $item->cheque_no != null ? 'Check Number:' . $item->cheque_no . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->cheque_issue_date)
                                                {!! $item->cheque_issue_date != null ? 'Check Issue Date:' . $item->cheque_issue_date . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->bank)
                                                {!! $item->bank->name != null ? 'Bank Name:' . $item->bank->name . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->account)
                                                {!! $item->account_id != null ? 'Bank Account Name:' . $item->account->account_no . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->account)
                                                {!! $item->account_id != null ? 'Bank Holder Name:' . $item->account->account_holder_name . '<br/>' : '' !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $total += $item->amount;
                                    @endphp
                                @endforeach
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th class="text-right">Tk. {{ $total }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                @if ($income_data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $income_data->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($income_data as $v_data)
        @php
            $detail_array = App\Models\IncomeDetails::where('income_id', $v_data->id)->get();
            $company_info = App\Models\Company::where(
                'id',
                $v_data->other_company_id ? $v_data->other_company_id : $v_data->company_id,
            )->first();
        @endphp
        <div class="modal fade" id="view-modal-{{ $v_data['id'] }}" tabindex="-1" role="dialog"
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
                                        <p style="margin: 0;"><strong>Credit Voucher</strong></p>
                                    </div>
                                </div>

                            </div>

                            <div class="voucher-info">
                                <div><strong><i class="fas fa-globe"
                                            style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
                                <div>
                                    <input type="checkbox"
                                        {{ $detail_array->where('fund_id', 2)->isNotEmpty() ? 'checked' : '' }}>
                                    <strong>Cash</strong>
                                </div>
                                <div>
                                    <input type="checkbox"
                                        {{ $detail_array->where('fund_id', 1)->isNotEmpty() ? 'checked' : '' }}>
                                    <strong>Bank</strong>
                                </div>
                                <div>
                                    <input type="checkbox" {{ $v_data->adjustment_amount != null ? 'checked' : '' }}>
                                    <strong>Adjustment</strong>
                                </div>

                                <div><strong>Date: {{ date('d/m/Y', strtotime($v_data->payment_date)) }}</strong></div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container"><strong>Head of Accounts:</strong>
                                    <span
                                        class="dotted-line-voucher">{{ $v_data->head->head_name  ?? ''}}</span>
                                </div>
                                <div class="dotted-container"><strong>Received From:</strong><span
                                        class="dotted-line-voucher">{{ $v_data->client_name ?? '' }}</span></div>
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
                                    @foreach ($detail_array as $index => $v_detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $v_detail->remarks ?? '' }}</strong></td>
                                            <td>{{ number_format($v_detail->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
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
                                        <p style="margin: 0;"><strong>Credit Voucher</strong></p>
                                    </div>
                                </div>

                            </div>

                            <div class="voucher-info">
                                <div><strong><i class="fas fa-globe"
                                            style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
                                <div>
                                    <input type="checkbox"
                                        {{ $detail_array->where('fund_id', 2)->isNotEmpty() ? 'checked' : '' }}>
                                    <strong>Cash</strong>
                                </div>
                                <div>
                                    <input type="checkbox"
                                        {{ $detail_array->where('fund_id', 1)->isNotEmpty() ? 'checked' : '' }}>
                                    <strong>Bank</strong>
                                </div>
                                <div>
                                    <input type="checkbox" {{ $v_data->adjustment_amount != null ? 'checked' : '' }}>
                                    <strong>Adjustment</strong>
                                </div>

                                <div><strong>Date: {{ date('d/m/Y', strtotime($v_data->payment_date)) }}</strong></div>
                            </div>

                            <!-- Head of Accounts and Received From Section -->
                            <div class="details-section">
                                <div class="dotted-container"><strong>Head of Accounts:</strong>
                                    <span
                                        class="dotted-line-voucher">{{ $v_data->head->head_name  ?? ''}}</span>
                                </div>
                                <div class="dotted-container"><strong>Received From:</strong><span
                                        class="dotted-line-voucher">{{ $v_data->client_name ?? '' }}</span></div>
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
                                    @foreach ($detail_array as $index => $v_detail)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td><strong>{{ $v_detail->remarks ?? '' }}</strong></td>
                                            <td>{{ number_format($v_detail->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
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

    {{-- money receipt --}}
    @foreach ($income_data as $v_data)
        @php
            $detail_array = App\Models\IncomeDetails::where('income_id', $v_data->id)->get();
            $company_info = App\Models\Company::where(
                'id',
                $v_data->other_company_id ? $v_data->other_company_id : $v_data->company_id,
            )->first();
        @endphp
        <div class="modal fade" id="view-receipt-modal-{{ $v_data['id'] }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content custom-modal">
                    <div class="d-flex justify-content-end">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <div>
                            <button type="button" class="btn btn-light">
                                <a href="{{ route('payment_money_receipt', $v_data->id) }}" target="_blank"><i
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
                                    <label> Cash <input type="checkbox"
                                            {{ $detail_array->where('fund_id', 2)->isNotEmpty() ? 'checked' : '' }}>
                                    </label>
                                    <hr style="margin: 10px 0; border: 1px solid #000000;">
                                    <p>Cheque/DD/PO No.:
                                        {{-- {{ $v_data->payment_type == 'Bank' ? $v_data->cheque_no ?? '' : '' }}</p> --}}
                                        {{ $v_data->cheque_no ?? '' }}</p>
                                    <p>Drawn on: {{ $v_data->bank->name ?? '' }}
                                    </p>
                                    <p>Date: {{ $v_data->payment_date ?? '' }}</p>
                                </div>


                                <div style="text-align: left; font-size: 14px; margin-bottom: 10px;">
                                    <p style="margin-bottom: 25px;">Date:
                                        {{ date('d.m.Y', strtotime($v_data->payment_date)) }}</p>
                                    <p style="margin-bottom: 30px;">MR No.: <strong>{{ $v_data->voucher_no }}</strong></p>
                                    <p>Total Amount:
                                        <span style="border: 1px solid black; padding: 5px 10px; display: inline-block;">
                                            <strong>{{ number_format($v_data->amount, 2) }} Tk</strong>
                                        </span>
                                    </p>

                                </div>

                            </div>

                            <!-- Received From and Account Details -->
                            <div class="details-section-receipt">
                                <p>Received with thanks from
                                    <span class="dotted-line"><b>{{ $v_data->client_name ?? '' }} ({{ $v_data->client_id ?? '' }})</b></span>
                                </p>

                                <p>On account of
                                    <span
                                        class="dotted-line"><b>{{ $v_data->head->head_name  ?? ''}}</b></span>
                                </p>

                                <p>A sum of amount:
                                    @php
                                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                        $amount = getBangladeshCurrency($v_data->amount);
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
@endsection
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        function filterHead() {
            var category_id = document.getElementById('category').value;
            var url = "{{ route('filter-head') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    category_id
                },
                success: function(data) {
                    $('#head').find('option').remove();
                    $('#head').html('');
                    $('#head').append(`<option value="" disabled selected>Select One</option>`);
                    $.each(data, function(key, value) {
                        $('#head').append(`
                            <option value="` + value.id + `">` + value.head_name +
                            `</option>`);
                    });
                    $('#head').trigger("chosen:updated");
                },
            });
            $(".chosen-select").chosen();


        }
    </script>
    <script>
        $(".chosen-select").chosen();

        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
