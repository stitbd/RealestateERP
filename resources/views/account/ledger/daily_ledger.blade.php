@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Daily Ledger List
                        </h3>
                    </div> <!-- /.card-body -->
                    <!--<div class="card-body p-3">-->
                    <!--    {{-- <form action="{{ route('ledger-list') }}" method="get"> --}}-->
                    <!--    <div class="row pb-3">-->
                    <!--        <div class="col-lg-3">-->
                    <!--            <label for="start_date">Start Date</label>-->
                    <!--            <input type="date" class="form-control" name="start_date" id="start_date" value="@php echo date('Y-m-d') @endphp"/>-->
                    <!--        </div>-->
                    <!--        <div class="col-lg-3">-->
                    <!--            <label for="end_date">End Date</label>-->
                    <!--            <input type="date" class="form-control" name="end_date" id="end_date" value="@php echo date('Y-m-d') @endphp"/>-->
                    <!--        </div>-->

                    <!--        <div class="col-lg-3">-->
                    <!--            <label for="action">Action</label> <br/>-->
                    <!--            <button class="btn btn-success btn-block" onclick="viewLedger();">-->
                    <!--                <i class="fa fa-search"></i> Search-->
                    <!--            </button>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--    {{-- </form> --}}-->
                    <!--</div>-->

                    <div class="card-body p-3">
                        {{-- <form action="{{ route('ledger-list') }}" method="get"> --}}
                        <div class="row pb-3">
                            {{-- <div class="col-lg-3">
                                <label for="head">Head</label>
                                <select name="head_id" id="head_id" class="form-control chosen-select">
                                    <option value="">Select Head</option>
                                    @foreach ($heads as $data)
                                        <option value="{{ $data->id }}">{{ $data->head_name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}
                            <div class="col-lg-3">
                                <label for="start_date">Fund</label>
                                <select name="fund_id" id="fund_id" class="form-control">
                                    <option value="">Select Fund</option>
                                    @foreach ($fund_data as $data)
                                        <option value="{{ $data->id }}">{{ $data->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="start_date"
                                    value="@php echo date('Y-m-d') @endphp" />
                            </div>
                            <div class="col-lg-3">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="end_date"
                                    value="@php echo date('Y-m-d') @endphp" />
                            </div>

                            <div class="col-lg-3">
                                <label for="action">Action</label> <br />
                                <button class="btn btn-success btn-block" onclick="viewLedger();">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="wrapper">

    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            @php $date = date('d/m/Y'); @endphp
            <button
                class="mt-2 col-sm-1 btn btn-warning "onClick="document.title = '{{ $company_name }}-Daily Ledger-{{ $date }}'; printArea('printableArea');"
                style="margin-right:100px"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        </div>
    </div>
    <div id="printableArea">
        <div class="container" style="margin-top: 80px" id="main-table">
            <div class="row text-center">
                <div class="col-sm-12">
                    <h2>{{ $company_name }}</h2>
                    <h5><strong> Daily Ledger Report</strong></h5>
                    <h6>{{ $date }}</h6>
                </div>
            </div>
            @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <thead class="">
                                <tr>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Receive</th>
                                    <th>V.NO.</th>
                                    <th>Payment</th>
                                    <th>V.NO.</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $total_expense = 0;
                                    $total_receive = 0;
                                    $final_total = 0;
                                    $advanceTotal = 0;
                                @endphp
                                @if ($prev_balance)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
                                        <td>Cash From {{ date('d/m/Y', strtotime($formattedPreviousDate)) }}</td>
                                        <td class="text-right">{{ $prev_balance }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">@php
                                            $total += $prev_balance;
                                            $total_receive += $prev_balance;
                                        @endphp {{ $total }}</td>
                                    </tr>
                                @endif
                                   @if($incomes)
                                    @foreach($incomes as $income)
                                        <tr>
                                            <td>{{date('d/m/Y',strtotime(date('Y-m-d')))}}</td>
                                            <td>Opening Balance</td>
                                            <td class="text-right">{{$income->amount}}</td>
                                            <td>@if($income->voucher_no){{$income->voucher_no}}@endif</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">@php  $total += $income->amount; $total_receive += $income->amount;  @endphp {{$total}}</td>
                                        </tr>
                                    @endforeach
                                    @endif
                                @if ($income_details)
                                    @foreach ($income_details as $v_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
                                            <td>{{ $v_income->remarks }}</td>
                                            <td class="text-right">{{ $v_income->amount }}</td>
                                            <td>
                                                @if ($v_income->income->voucher_no)
                                                    {{ $v_income->income->voucher_no }}
                                                @endif
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">@php
                                                $total += $v_income->amount;
                                                $total_receive += $v_income->amount;
                                            @endphp {{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($adjustment_incomes)
                                    @foreach ($adjustment_incomes as $adj_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
                                            <td>{{ $adj_income->remarks }}</td>
                                            <td class="text-right">{{ $adj_income->amount }}</td>
                                            <td>
                                                @if ($adj_income->income->voucher_no)
                                                    {{ $adj_income->income->voucher_no }}
                                                @endif
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">@php
                                                $total += $adj_income->amount;
                                                $total_receive += $adj_income->amount;
                                            @endphp {{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($land_sale)
                                    @foreach ($land_sale as $v_sale)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
                                            <td>
                                                Sales of
                                                @if ($v_sale->landSale->plot)
                                                    {{ $v_sale->project->name }} ({{ $v_sale->landSale->plot->plot_no }})
                                                @elseif ($v_sale->landSale->flat)
                                                    {{ $v_sale->project->name }} ({{ $v_sale->landSale->flat->flat_floor->floor_no }})
                                                @else
                                                Not Available
                                                @endif
                                            </td>
                                            <td class="text-right">{{ $v_sale->amount }}</td>
                                            <td>
                                                @if ($v_sale->voucher_no)
                                                    {{ $v_sale->voucher_no }}
                                                @endif
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">@php
                                                $total += $v_sale->amount;
                                                $total_receive += $v_sale->amount;
                                            @endphp {{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($head_transfer)
                                    @foreach ($head_transfer as $head)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($head->transaction_date)) }}</td>
                                            <td>{{ $head->particulars }}</td>
                                            <td class="text-right">{{ $head->transaction_amount }}</td>
                                            <td class="text-right">{{ $head->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $total += $head->transaction_amount;
                                                $total_receive += $head->transaction_amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($debit_head_transfer)
                                    @foreach ($debit_head_transfer as $debit_head)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($debit_head->transaction_date)) }}
                                            </td>
                                            <td>
                                                {{ $debit_head->particulars }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">

                                                {{ $debit_head->transaction_amount }}
                                                @php
                                                    $total -= $debit_head->transaction_amount;
                                                    $total_expense += $debit_head->transaction_amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $debit_head->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($fund_transfer)
                                    @foreach ($fund_transfer as $transfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($transfer->transaction_date)) }}</td>
                                            <td>{{ $transfer->particulars }}</td>
                                            <td class="text-right">{{ $transfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $transfer->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                            $total += $transfer->transaction_amount;
                                            $total_receive += $transfer->transaction_amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($debit_fund_transfer)
                                    @foreach ($debit_fund_transfer as $debit)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($debit->transaction_date)) }}
                                            </td>
                                            <td>
                                                {{ $debit->particulars }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">

                                                {{ $debit->transaction_amount }}
                                                @php
                                                    $total -= $debit->transaction_amount;
                                                    $total_expense += $debit->transaction_amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $debit->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($expenses)
                                    @foreach ($expenses as $expense)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($expense->payment_date)) }}
                                            </td>
                                            <td>
                                                {{ $expense->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">

                                                {{ $expense->amount }}
                                                @php
                                                    $total -= $expense->amount;
                                                    $total_expense += $expense->amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $expense->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($adjustment_expenses)
                                    @foreach ($adjustment_expenses as $v_expense)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($v_expense->payment_date)) }}
                                            </td>
                                            <td>
                                                {{ $v_expense->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">

                                                {{ $v_expense->amount }}
                                                @php
                                                    $total -= $v_expense->amount;
                                                    $total_v_expense += $v_expense->amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $v_expense->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($payment)
                                    @foreach ($payment as $v_payment)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($v_payment->pay_date)) }}
                                            </td>
                                            <td>
                                                {{ $v_payment->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">

                                                {{ $v_payment->amount }}
                                                @php
                                                    $total -= $v_payment->amount;
                                                    $total_expense += $v_payment->amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $v_payment->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                  @if($bank_income)
                                    @foreach($bank_income as $v_income)
                                        <tr>
                                            <td>{{date('d/m/Y',strtotime(date('Y-m-d')))}}</td>
                                            <td>Opening Balance at Account-({{$v_income->account->account_no}})</td>
                                            <td class="text-right">{{$v_income->amount}}</td>
                                            <td>@if($v_income->voucher_no){{$v_income->voucher_no}}@endif</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                    @endif
                                @if ($bank_income_details)
                                    @foreach ($bank_income_details as $v_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
                                            <td>{{ $v_income->remarks }}</td>
                                            <td class="text-right">{{ $v_income->amount }}</td>
                                            <td>
                                                @if ($v_income->voucher_no)
                                                    {{ $v_income->voucher_no }}
                                                @endif
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_head_transfer)
                                    @foreach ($bank_head_transfer as $head_bank_trnasfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($head_bank_trnasfer->transaction_date)) }}</td>
                                            <td>{{ $head_bank_trnasfer->particulars }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $head_bank_trnasfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $head_bank_trnasfer->voucher_no }}</td>

                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_fund_transfer)
                                    @foreach ($bank_fund_transfer as $v_bank_trnasfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_bank_trnasfer->transaction_date)) }}</td>
                                            <td>{{ $v_bank_trnasfer->particulars }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $transfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $transfer->voucher_no }}</td>

                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_expense)
                                    @foreach ($bank_expense as $v_expense)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($v_expense->payment_date)) }}
                                            </td>
                                            <td>
                                                {{ $v_expense->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ $v_expense->amount }}

                                            </td>
                                            <td class="text-right">
                                                {{ $v_expense->voucher_no }}
                                            </td>
                                            <td class="text-right">

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_payment)
                                    @foreach ($bank_payment as $v_payment)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($v_payment->payment_date)) }}
                                            </td>
                                            <td>
                                                {{ $v_payment->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ $v_payment->amount }}

                                            </td>
                                            <td class="text-right">
                                                {{ $v_payment->voucher_no }}
                                            </td>
                                            <td class="text-right">

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th class="text-right">{{ $total_receive }}</th>
                                    <th></th>
                                    <th class="text-right">{{ $total_expense }}</th>
                                    <th></th>
                                    <th class="text-right">{{ $total }}</th>
                                </tr>
                            </tfoot>
                        </table>
                        <table class="table table-bordered col-sm-6"
                            style="margin-top: 50px; margin-bottom:10px;margin-left:50%;">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-right">Total Receive</th>
                                    <th class="text-right">{{ $total_receive }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Total Payment</th>
                                    <th class="text-right">{{ $total_expense }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Cash in Hand</th>
                                    <th class="text-right">{{ $total }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Payment For Advance</th>
                                    <th class="text-right">
                                        @if ($advanceexpense)
                                            @php $advanceTotal += $advanceexpense;  @endphp {{ $advanceexpense }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Cash in Hand Actual</th>
                                    <th class="text-right">@php $final_total = $total - $advanceTotal  @endphp {{ $final_total }}</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            @else
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Particulars</th>
                                    <th>Receive</th>
                                    <th>Payment</th>
                                    <th>V.NO.</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = $total_receive = $total_expense = $advanceTotal = 0;
                                @endphp

                                @if ($prev_cash)
                                    <tr>
                                        <td>--</td>
                                        <td>Cash From {{ date('d/m/Y', strtotime($formattedPreviousDate)) }}</td>
                                        <td class="text-right">{{ $prev_cash }}</td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">{{ $prev_cash }}</td>
                                        @php
                                            $total += $prev_cash;
                                            $total_receive += $prev_cash;
                                        @endphp
                                    </tr>
                                @endif

                                @if ($incomes)
                                    @foreach ($incomes as $v_income)
                                        <tr>
                                            <td>{{ date('d/m/Y') }}</td>
                                            <td>{{ $v_income->remarks }}</td>
                                            <td class="text-right">{{ $v_income->amount }}</td>
                                            <td></td>
                                            <td>{{ $v_income->voucher_no }}</td>
                                            @php
                                                $total += $v_income->amount;
                                                $total_receive += $v_income->amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($expenses)
                                    @foreach ($expenses as $v_data)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_data->payment_date)) }}</td>
                                            <td>{{ $v_data->remarks }}</td>
                                            <td></td>
                                            <td class="text-right">{{ $v_data->amount }}</td>
                                            <td>{{ $v_data->voucher_no }}</td>
                                            <td class="text-right">
                                                @php
                                                    $total -= $v_data->amount;
                                                    $total_expense += $v_data->amount;
                                                @endphp
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>

                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>{{ $total_receive }}</th>
                                    <th>{{ $total_expense }}</th>
                                    <th></th>
                                    <th class="text-right">{{ $total }}</th>
                                </tr>
                            </tfoot>
                        </table>

                        <table class="table table-bordered col-sm-6"
                            style="margin-top: 50px; margin-bottom: 10px; margin-left: 50%;">
                            <thead>
                                <tr>
                                    <th colspan="2" class="text-right">Total Receive</th>
                                    <th class="text-right">{{ $total_receive }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Total Payment</th>
                                    <th class="text-right">{{ $total_expense }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Cash in Hand</th>
                                    <th class="text-right">{{ $total }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Payment For Advance</th>
                                    <th class="text-right">
                                        @if ($advanceexpense)
                                            @php $advanceTotal += $advanceexpense; @endphp
                                            {{ $advanceexpense }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Cash in Hand Actual</th>
                                    <th class="text-right">
                                        @php $final_total = $total - $advanceTotal; @endphp
                                        {{ $final_total }}
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            @endif
            <div class="row mt-5">
                <div class="col-sm-4">
                    <p>Prepared By</p>
                </div>
                <div class="col-sm-4">
                    <p>Checked By</p>
                </div>
                <div class="col-sm-4">
                    <p>Approved By</p>
                </div>
            </div>
        </div>
    </div>


@endsection
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(".chosen-select").chosen();
    </script>
    <script>
        function viewLedger() {
            $('#main-table').hide();
            $('.btn-warning').hide();
            //var head_id = document.getElementById('head_id').value;
            var fund_id = document.getElementById('fund_id').value;
            var start_date = document.getElementById('start_date').value;
            var end_date = document.getElementById('end_date').value;
            var url = "{{ route('ledger-list') }}"

            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    start_date,
                    end_date,
                    fund_id,
                    //head_id
                },
                success: function(data) {
                    $('#wrapper').html(data);
                }
            });
        }
    </script>

    <script>
        function printArea(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
