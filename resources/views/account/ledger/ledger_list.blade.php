<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 5px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 0px solid #ddd;
        text-align: left !important;
        font-size: 18px;
    }
</style>
<div class="row">
    @php $date = date('d/m/Y'); @endphp
    <div class="col-md-12 text-right">
        <button class="mt-2 col-sm-1 btn btn-warning"
            onClick="document.title = '{{ $company_name }}-Daily Ledger-{{ $date }}'; printDiv('printableArea'); "
            style="margin-right:100px"> <i class="fa fa-print"></i> Print </button>
    </div>
</div>

<div id="printableArea">

    @if ($fund_id != null )
        <div class="container" style="margin-top: 50px">
            <div class="row text-center">
                <div class="col-sm-12">
                    <h2>{{ $company_name }}</h2>

                    <!-- Fund name and Ledger Report -->
                    <h5>
                        <strong>
                            @if ($fund_id != null)
                                {{ $fund_name }}
                            @endif
                            Ledger Report
                        </strong>
                    </h5>

                    <!-- Head name -->
                    {{-- <h5>
                        <strong>
                            @if ($head_id != null)
                                {{ $head_name }}
                            @endif
                        </strong>
                    </h5> --}}

                    <!-- Date range -->
                    <h6>{{ date('d/m/Y', strtotime($start_date)) }} - {{ date('d/m/Y', strtotime($end_date)) }}</h6>
                </div>
            </div>

            <!-- Check user role -->
            @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12">
                        <!-- Ledger table -->
                        <table class="table table-bordered">
                            <thead>
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

                                <!-- Previous balance row -->

                                @if ($fund_prev_balance)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($start_date)) }}</td>
                                        <td>{{ $fund_name }} Balance From
                                            {{ date('d/m/Y', strtotime($formattedPreviousDate)) }}</td>
                                        <td class="text-right">{{ $fund_prev_balance }}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">
                                            @php
                                            $total += $fund_prev_balance;
                                            $total_receive += $fund_prev_balance;
                                        @endphp {{ $total }}
                                        </td>
                                    </tr>
                                @endif
                                @if($incomes)
                                    @foreach($incomes as $income)
                                        <tr>
                                            <td>{{date('d/m/Y',strtotime($start_date))}}</td>
                                            <td>Opening Balance</td>
                                            <td class="text-right">{{$income->amount}}</td>
                                            <td class="text-right"></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">@php $total += $income->amount;  $total_receive += $income->amount @endphp {{$total}}</td>
                                        </tr>
                                    @endforeach
                                 @endif
                                @if ($income_details)
                                    @foreach ($income_details as $v_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_income->income->payment_date)) }}</td>
                                            <td>{{ $v_income->remarks }}</td>
                                            <td class="text-right">{{ $v_income->amount }}</td>
                                            <td class="text-right">{{ $v_income->income->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                @php
                                                $total += $v_income->amount;
                                                $total_receive += $v_income->amount;
                                            @endphp {{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($adjustment_incomes)
                                    @foreach ($adjustment_incomes as $adj_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($adj_income->income->payment_date)) }}</td>
                                            <td>{{ $adj_income->remarks }}</td>
                                            <td class="text-right">{{ $adj_income->amount }}</td>
                                            <td class="text-right">{{ $adj_income->income->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                @php
                                                $total += $adj_income->amount;
                                                $total_receive += $adj_income->amount;
                                            @endphp {{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($credit_fund_transfer)
                                    @foreach ($credit_fund_transfer as $transfer)
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
                                @if ($credit_head_fund_transfer)
                                    @foreach ($credit_head_fund_transfer as $v_transfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_transfer->transaction_date)) }}</td>
                                            <td>{{ $v_transfer->particulars }}</td>
                                            <td class="text-right">{{ $v_transfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $v_transfer->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $total += $v_transfer->transaction_amount;
                                                $total_receive += $v_transfer->transaction_amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($land_sale)
                                    @foreach ($land_sale as $v_sale)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_sale->pay_date)) }}</td>
                                            <td>
                                            Sales of
                                                @if ($v_sale->project)
                                                    {{ $v_sale->project->name }}
                                                    @if ($v_sale->landSale && $v_sale->landSale->plot)
                                                        ({{ $v_sale->landSale->plot->plot_no }})
                                                    @elseif ($v_sale->landSale && $v_sale->landSale->flat)
                                                        ({{ $v_sale->landSale->flat->flat_floor->floor_no}})
                                                    @endif
                                                @else
                                                    Not Available
                                                @endif
                                            </td>
                                                                                        <td class="text-right">{{ $v_sale->amount }}</td>
                                            <td class="text-right">{{ $v_sale->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $total += $v_sale->amount;
                                                $total_receive += $v_sale->amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($debit_fund_transfer)
                                    @foreach ($debit_fund_transfer as $debit_transfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($debit_transfer->transaction_date)) }}</td>
                                            <td>{{ $debit_transfer->particulars }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $debit_transfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $debit_transfer->voucher_no }}</td>
                                            @php
                                                $total -= $debit_transfer->transaction_amount;
                                                $total_expense += $debit_transfer->transaction_amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($debit_head_fund_transfer)
                                    @foreach ($debit_head_fund_transfer as $debit_transfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($debit_transfer->transaction_date)) }}</td>
                                            <td>{{ $debit_transfer->particulars }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $debit_transfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $debit_transfer->voucher_no }}</td>
                                            @php
                                                $total -= $debit_transfer->transaction_amount;
                                                $total_expense += $debit_transfer->transaction_amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
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
                                    @foreach ($adjustment_expenses as $adj_expense)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($adj_expense->payment_date)) }}
                                            </td>
                                            <td>
                                                {{ $adj_expense->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ $adj_expense->amount }}
                                                @php
                                                    $total -= $adj_expense->amount;
                                                    $total_expense += $adj_expense->amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $adj_expense->voucher_no }}
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
                                                    $total      -= $v_payment->amount;
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

                                <!-- Loop through each transaction type -->
                                {{-- @foreach (['incomes', 'credit_head_fund_transfer', 'debit_head_fund_transfer', 'credit_fund_transfer', 'debit_fund_transfer', 'expenses'] as $transactionType)
                                    @foreach ($transactionType as $transaction)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($transaction->transaction_date ?? $start_date)) }}
                                            </td>
                                            <td>{{ $transaction->remarks ?? $transaction->particulars }}</td>
                                            <td class="text-right">
                                                @if (in_array($transactionType, ['incomes', 'credit_head_fund_transfer', 'credit_fund_transfer']))
                                                    {{ $transaction->amount ?? $transaction->transaction_amount }}
                                                @endif
                                            </td>
                                            <td class="text-right">{{ $transaction->voucher_no ?? '' }}</td>
                                            <td class="text-right">
                                                @if (in_array($transactionType, ['debit_head_fund_transfer', 'debit_fund_transfer', 'expenses']))
                                                    {{ $transaction->amount ?? $transaction->transaction_amount }}
                                                @endif
                                            </td>
                                            <td class="text-right">{{ $transaction->voucher_no ?? '' }}</td>
                                            <td class="text-right">
                                                @php
                                                    if (
                                                        in_array($transactionType, [
                                                            'incomes',
                                                            'credit_head_fund_transfer',
                                                            'credit_fund_transfer',
                                                        ])
                                                    ) {
                                                        $total +=
                                                            $transaction->amount ?? $transaction->transaction_amount;
                                                        $total_receive +=
                                                            $transaction->amount ?? $transaction->transaction_amount;
                                                    } else {
                                                        $total -=
                                                            $transaction->amount ?? $transaction->transaction_amount;
                                                        $total_expense +=
                                                            $transaction->amount ?? $transaction->transaction_amount;
                                                    }
                                                @endphp
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach --}}


                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-right">Total Receive</th>
                                    <th class="text-right">{{ $total_receive }}</th>
                                    <th></th>
                                    <th class="text-right">{{ $total_expense }}</th>
                                    <th></th>
                                    <th class="text-right">{{ $total }}</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!-- Summary table for totals -->
                        <table class="table table-bordered col-sm-6"
                            style="margin-top: 50px; margin-bottom:10px; margin-left:50%;">
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
                                    <th colspan="2" class="text-right">Total Balance</th>
                                    <th class="text-right">{{ $total }}</th>
                                </tr>
                                @if ($advanceexpense)
                                    <tr>
                                        <th colspan="2" class="text-right">Payment For Advance</th>
                                        <th class="text-right">
                                            @php $advanceTotal += $advanceexpense; @endphp
                                            {{ $advanceexpense }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-right">Cash in Hand Actual</th>
                                        <th class="text-right">
                                            @php $final_total = $total - $advanceTotal; @endphp
                                            {{ $final_total }}
                                        </th>
                                    </tr>
                                @endif
                            </thead>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Approval section -->
            <div class="row mt-5 mb-5">
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
    @else
        <div class="container" style="margin-top: 50px">
            <div class="row text-center">
                <div class="col-sm-12">
                    <h2>{{ $company_name }}</h2>
                    <h5><strong> Daily Ledger Report</strong></h5>
                    <h6>{{ date('d/m/Y', strtotime($start_date)) }} - {{ date('d/m/Y', strtotime($end_date)) }}</h6>
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
                                    $bank_payment = 0;
                                    $bank_receive = 0;
                                    $bank_total = 0;
                                @endphp
                                @if ($prev_balance)
                                    <tr>
                                        <td>{{ date('d/m/Y', strtotime($start_date)) }}</td>
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
                                    @foreach($incomes as $v_income)
                                    <tr>
                                        <td>{{date('d/m/Y', strtotime($v_income->payment_date))}}</td>
                                        <td>Opening Balance</td>
                                        <td  class="text-right">{{$v_income->amount}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        @php
                                        $total += $v_income->amount;
                                        $total_receive += $v_income->amount;
                                        @endphp
                                        <td  class="text-right">{{$total}}</td>
                                    </tr>
                                    @endforeach
                                @endif
                                @if ($income_details)
                                    @foreach ($income_details as $v_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_income->income->payment_date)) }}</td>
                                            <td>{{ $v_income->remarks }}</td>
                                            <td class="text-right">{{ $v_income->amount }}</td>
                                            <td class="text-right">{{ $v_income->income->voucher_no }}</td>
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
                                            <td>{{ date('d/m/Y', strtotime($adj_income->income->payment_date)) }}</td>
                                            <td>{{ $adj_income->remarks }}</td>
                                            <td class="text-right">{{ $adj_income->amount }}</td>
                                            <td class="text-right">{{ $adj_income->income->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                @php
                                                $total += $adj_income->amount;
                                                $total_receive += $adj_income->amount;
                                            @endphp {{ $total }}</td>
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
                                            <td class="text-right">
                                                @php
                                                    $total += $transfer->transaction_amount;
                                                    $total_receive += $transfer->transaction_amount;
                                                @endphp
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($head_transfer)
                                    @foreach ($head_transfer as $h_transfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($h_transfer->transaction_date)) }}</td>
                                            <td>{{ $h_transfer->particulars }}</td>
                                            <td class="text-right">{{ $h_transfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $h_transfer->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $total += $h_transfer->transaction_amount;
                                                $total_receive += $h_transfer->transaction_amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                 @if ($land_sale)
                                    @foreach ($land_sale as $v_sale)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_sale->pay_date)) }}</td>
                                            <td>
                                                Sales of
                                                @if ($v_sale->project)
                                                    {{ $v_sale->project->name }}
                                                    @if ($v_sale->landSale && $v_sale->landSale->plot)
                                                        ({{ $v_sale->landSale->plot->plot_no }})
                                                    @elseif ($v_sale->landSale && $v_sale->landSale->flat)
                                                        ({{ $v_sale->landSale->flat->flat_floor->floor_no }})
                                                    @endif
                                                @else
                                                    Not Available
                                                @endif
                                            </td>

                                            <td class="text-right">{{ $v_sale->amount }}</td>
                                            <td class="text-right">{{ $v_sale->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $total += $v_sale->amount;
                                                $total_receive += $v_sale->amount;
                                            @endphp
                                            <td class="text-right">{{ $total }}</td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if ($cash_head_debit_transfer)
                                    @foreach ($cash_head_debit_transfer as $head_debit_transfer)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($head_debit_transfer->transaction_date)) }}
                                            </td>
                                            <td>
                                                {{ $head_debit_transfer->particulars }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ $head_debit_transfer->transaction_amount }}
                                                @php
                                                    $total -= $head_debit_transfer->transaction_amount;
                                                    $total_expense += $head_debit_transfer->transaction_amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $head_debit_transfer->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($cash_debit_transfer)
                                    @foreach ($cash_debit_transfer as $debit_transfer)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($debit_transfer->transaction_date)) }}
                                            </td>
                                            <td>
                                                {{ $debit_transfer->particulars }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ $debit_transfer->transaction_amount }}
                                                @php
                                                    $total -= $debit_transfer->transaction_amount;
                                                    $total_expense += $debit_transfer->transaction_amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $debit_transfer->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($expenses)
                                    @foreach ($expenses as $t_expense)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($t_expense->payment_date)) }}
                                            </td>
                                            <td>
                                                {{ $t_expense->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ $t_expense->amount }}
                                                @php
                                                    $total -= $t_expense->amount;
                                                    $total_expense += $t_expense->amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $t_expense->voucher_no }}
                                            </td>
                                            <td class="text-right">
                                                {{ $total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                 @if ($adjustment_expenses)
                                    @foreach ($adjustment_expenses as $adj_expense)
                                        <tr>
                                            <td>
                                                {{ date('d/m/Y', strtotime($adj_expense->payment_date)) }}
                                            </td>
                                            <td>
                                                {{ $adj_expense->remarks }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">
                                                {{ $adj_expense->amount }}
                                                @php
                                                    $total -= $adj_expense->amount;
                                                    $total_expense += $adj_expense->amount;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                {{ $adj_expense->voucher_no }}
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
                                                    $total      -= $v_payment->amount;
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

                                @if ($bank_income)
                                    @foreach ($bank_income as $v_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_income->payment_date)) }}</td>
                                            <td>Opening Balance At-({{$v_income->account->account_no}})</td>
                                            <td class="text-right">{{ $v_income->amount }}</td>
                                            <td>
                                                @if ($v_income->voucher_no)
                                                    {{ $v_income->voucher_no }}
                                                @endif
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $bank_total += $v_income->amount;
                                                $bank_receive += $v_income->amount;
                                            @endphp
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_income_details)
                                    @foreach ($bank_income_details as $v_income)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_income->income->payment_date)) }}</td>
                                            <td>{{ $v_income->remarks }}</td>
                                            <td class="text-right">{{ $v_income->amount }}</td>
                                            <td>
                                                @if ($v_income->income->voucher_no)
                                                    {{ $v_income->income->voucher_no }}
                                                @endif
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $bank_total += $v_income->amount;
                                                $bank_receive += $v_income->amount;
                                            @endphp
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
                                            <td></td>
                                            @php
                                                $bank_total -= $v_expense->amount;
                                                $bank_payment += $v_expense->amount;
                                            @endphp
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_credit_head_fund_transfer)
                                    @foreach ($bank_credit_head_fund_transfer as $head_cridit_trnasfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($head_cridit_trnasfer->transaction_date)) }}
                                            </td>
                                            <td>{{ $head_cridit_trnasfer->particulars }}</td>
                                            <td class="text-right">{{ $head_cridit_trnasfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $head_cridit_trnasfer->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $bank_receive += $head_cridit_trnasfer->transaction_amount;
                                            @endphp
                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_credit_fund_transfer)
                                    @foreach ($bank_credit_fund_transfer as $v_cridit_trnasfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_cridit_trnasfer->transaction_date)) }}
                                            </td>
                                            <td>{{ $v_cridit_trnasfer->particulars }}</td>
                                            <td class="text-right">{{ $v_cridit_trnasfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $v_cridit_trnasfer->voucher_no }}</td>
                                            <td></td>
                                            <td></td>
                                            @php
                                                $bank_receive += $v_cridit_trnasfer->transaction_amount;
                                            @endphp
                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_head_fund_transfer)
                                    @foreach ($bank_head_fund_transfer as $head_bank_trnasfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($head_bank_trnasfer->transaction_date)) }}
                                            </td>
                                            <td>{{ $head_bank_trnasfer->particulars }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $head_bank_trnasfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $head_bank_trnasfer->voucher_no }}</td>
                                            @php
                                                $bank_payment += $head_bank_trnasfer->transaction_amount;
                                            @endphp
                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if ($bank_fund_transfer)
                                    @foreach ($bank_fund_transfer as $v_bank_trnasfer)
                                        <tr>
                                            <td>{{ date('d/m/Y', strtotime($v_bank_trnasfer->transaction_date)) }}
                                            </td>
                                            <td>{{ $v_bank_trnasfer->particulars }}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{ $v_bank_trnasfer->transaction_amount }}</td>
                                            <td class="text-right">{{ $v_bank_trnasfer->voucher_no }}</td>
                                            @php
                                                $bank_payment += $v_bank_trnasfer->transaction_amount;
                                            @endphp
                                            <td class="text-right"></td>
                                        </tr>
                                    @endforeach
                                @endif

                                 @if ($bank_incentive_payment)
                                    @foreach ($bank_incentive_payment as $v_payment)
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
                                                    $total      -= $v_payment->amount;
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
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="">Cash - Amount</th>
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

                                <tr>
                                    <th colspan="2" class="text-right">Total Payment from Bank</th>
                                    <th class="text-right"> {{ $bank_payment }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Total Receive to Bank</th>
                                    <th class="text-right"> {{ $bank_receive }}</th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right">Bank Current Balance</th>
                                    <th class="text-right"> {{ $bank_amount }}</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>

            @endif
            <div class="row mt-5 mb-5">
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
    @endif

</div>

<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
