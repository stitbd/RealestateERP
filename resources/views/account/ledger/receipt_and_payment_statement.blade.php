@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<style>
    .table-bordered {
        border-color: #000000 !important;
    }

    .table-bordered td,
    .table-bordered th {
        border: 1px solid #000000 !important;
    }

    .table thead th {
        vertical-align: bottom;
        border-bottom: 2px solid #000000;
    }
</style>
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        {{-- <h3 class="card-title">
                            Receipt and Payment Statement
                        </h3> --}}

                        {{-- <div class="row"> --}}
                            @php $date = date('d/m/Y'); @endphp
                            <div class="col-md-12 text-right">
                                <button class="mt-2 col-sm-1 btn btn-warning"
                                    onClick="document.title = '{{ $company->name }}-Receipt and Payment Statement-{{ $date }}'; printDiv('printableArea'); "
                                    style="margin-right:100px"> <i class="fa fa-print"></i> Print </button>
                            </div>
                        {{-- </div> --}}
                    </div> <!-- /.card-body -->
                    <div id="printableArea">
                        <div class="card-body p-3">
                            <div class="row text-center">
                                <div class="col-sm-12">
                                    <h2>{{ $company->name }}</h2>
                
                                    <!-- Statement head name -->
                                    <h5>
                                        <strong>
                                            Receipt and Payment Statement
                                        </strong>
                                    </h5>
                
                                    <!-- Date -->
                                    <h6>{{ date('d/m/Y') }}</h6>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 20px">
                                <div class="col-sm-12">
                                    <!-- Ledger table -->
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr style="background-color: rgb(233, 230, 230)">
                                                <th>Receipts</th>
                                                <th>Amount</th>
                                                <th>Payments</th>
                                                <th>Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Opening Balance (Cash in Hand)</td>
                                                <td>{{ $opening_balance_cash }} Tk.</td>
                                                <td>Sales Incentive Payment (Cash)</td>
                                                <td>{{ $sale_incentive_payment }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td>Opening Balance (Bank Balance)</td>
                                                <td>{{ $opening_balance_bank }} Tk.</td>
                                                <td>Sales Incentive Payment (Bank)</td>
                                                <td>{{ $sale_incentive_payment_bank }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td>Sale of Property (Plots/Flats)</td>
                                                <td>{{ $land_sale_bank_cash }} Tk.</td>
                                                <td>Advance Expense</td>
                                                <td>{{ $advance_expense }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td>To Fund Transfer (Cash)</td>
                                                <td>{{ $fund_transfer }} Tk.</td>
                                                <td>From Fund Transfer (Cash)</td>
                                                <td>{{ $from_fund_transfer }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td>To Fund Transfer (Bank)</td>
                                                <td>{{ $fund_transfer_bank }} Tk.</td>
                                                <td>From Fund Transfer (Bank)</td>
                                                <td>{{ $from_fund_transfer_bank }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td>To Head Transfer (Cash)</td>
                                                <td>{{ $to_head_transfer }} Tk.</td>
                                                <td>From Head Transfer (Cash)</td>
                                                <td>{{ $from_head_transfer }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td>To Head Transfer (Bank)</td>
                                                <td>{{ $to_head_transfer_bank }} Tk.</td>
                                                <td>From Head Transfer (Bank)</td>
                                                <td>{{ $from_head_transfer_bank }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Closing Balance (Cash)</td>
                                                <td>{{ $closing_balance_cash }} Tk.</td>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td>Closing Balance (Bank)</td>
                                                <td>{{ $closing_balance_bank }} Tk.</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-left">Total </th>
                                                <th class="text-left">
                                                    {{ $opening_balance_cash + $opening_balance_bank + $land_sale_bank_cash + $fund_transfer + $to_head_transfer + $to_head_transfer_bank }}
                                                    Tk.</th>
                                                <th class="text-left">Total </th>
                                                <th class="text-left">
                                                    {{ $sale_incentive_payment + $sale_incentive_payment_bank + $advance_expense + $from_fund_transfer + $from_head_transfer + $from_head_transfer_bank + $closing_balance_cash + $closing_balance_bank }}
                                                    Tk.</th>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    <!-- Summary table for totals -->
                                    <table class="table table-bordered col-sm-6"
                                        style="margin-top: 50px; margin-bottom:10px; margin-left:50%;">
                                        <thead>
                                            {{-- <tr>
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
                                        </tr> --}}

                                        </thead>
                                    </table>
                                </div>
                            </div>
                            {{-- <div class="row mt-5 mb-5">
                            <div class="col-sm-4">
                                <p>Prepared By</p>
                            </div>
                            <div class="col-sm-4">
                                <p>Checked By</p>
                            </div>
                            <div class="col-sm-4">
                                <p>Approved By</p>
                            </div>
                        </div> --}}
                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
@endsection

<script>
    function printDiv(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
