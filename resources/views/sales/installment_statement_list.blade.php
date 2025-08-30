@php

use Carbon\Carbon;

@endphp


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
        /* font-size:18px; */
    }
</style>
<div class="row">
    @php $date = date('d/m/Y'); @endphp
    <div class="col-md-12 text-right">
        <button class="mt-2 col-sm-1 btn btn-warning" onClick="document.title = '{{$company->name}}-Project Ledger'; printDiv('printableArea'); " style="margin-right:100px"> <i class="fa fa-print"></i> Print </button>
    </div>
</div>

<div id="printableArea">

    <div class="container" style="margin-top: 50px">
        <div class="row text-center">
            <div class="col-sm-12">
                <h2>{{ $company->name }}</h2>
                <h5><strong> Installment Statement Report</strong></h5>
                <h6><strong> @if ($customer != null) {{$customer->project->name}} @endif</strong></h6>
                 <h6><strong> @if ($customer != null) {{$customer->customer_name}}-{{$customer->customer_code}} @endif</strong></h6>
            </div>
        </div>
        @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
            <div class="row" style="margin-top: 20px">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        <thead class="">
                            <tr>
                                <th>Installment Month</th>
                                <th>Installment Amount</th>
                                <th>Status</th>
                                {{-- <th>Transaction Type</th> --}}
                                <th>Paid</th>
                                <th>Unpaid</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $total_price = 0;
                                $total_paid = 0;
                                $total_unpaid = 0;
                            @endphp

                            @for ($i = 1; $i <= $installment_number; $i++)
                                @php

                                    $currentInstallmentMonth = $installment_date
                                        ->copy()
                                        ->addMonths($i - 1)
                                        ->format('Y-m');

                                    $status = 'Unpaid';
                                    $amountPaid = 0;

                                    foreach ($payments as $payment) {
                                        $paymentDateMonth = Carbon::parse($payment->payment_month)->format('Y-m');
                                        if ($currentInstallmentMonth === $paymentDateMonth) {
                                            $status = 'Paid';
                                            $amountPaid = $payment->amount;
                                            break;
                                        }else{

                                        }
                                    }

                                    $total_price += $installment->monthly_installment;
                                    // if($status = 'Unpaid'){
                                    //     $total_unpaid += $installment->monthly_installment;
                                    // }else{
                                    //     $total_paid += $amountPaid;
                                    // }


                                    $color = $status === 'Paid' ? 'green' : 'red';
                                @endphp
                                <tr>
                                    <td>{{ $installment_date->copy()->addMonths($i - 1)->format('F Y') }}
                                    </td>
                                    <td>{{ $installment->monthly_installment }}</td>
                                    <td style="color: {{ $color }}">{{ $status }}
                                    </td>
                                    <td>{{ $status === 'Paid' ? $amountPaid : 0 }}
                                    </td>
                                    <td>{{ $status === 'Unpaid' ? $installment->monthly_installment : 0 }}</td>
                                    @php
                                    if($status = 'Paid'){
                                            $total_paid += $amountPaid;
                                        }
                                        if($status = 'Unpaid'){
                                            $total_unpaid = $total_price -  $total_paid;
                                        }
                                        @endphp
                                </tr>
                            @endfor
                                
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>{{$total_price}}</th>
                                <th></th>
                                <th>{{$total_paid}}</th>
                                <th>{{$total_unpaid}}</th>
                            </tr>
                        </tfoot>
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
