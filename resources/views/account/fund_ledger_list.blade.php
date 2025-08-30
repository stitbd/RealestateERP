
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
        <button class="mt-2 col-sm-1 btn btn-warning" onClick="document.title = '{{$company_name}}-Daily Ledger-{{$date}}'; printDiv('printableArea'); " style="margin-right:100px"> <i class="fa fa-print"></i> Print </button>
    </div>
</div>

<div id="printableArea">

<div class="container" style="margin-top: 50px">
    <div class="row text-center">
        <div class="col-sm-12">
            <h2>{{$company_name}}</h2>
            <h5><strong> Fund Ledger Report</strong></h5>
            <h6>{{date('d/m/Y',strtotime($start_date))}} - {{date('d/m/Y',strtotime($end_date))}}</h6>
        </div>
    </div>
    @if(auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th>Date</th>
                        <th>Fund</th>
                        <th>Transaction Type</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; $total_expense = 0; $total_receive = 0;  $final_total = 0; $total = 0 @endphp
                    @if($fund_opening_balance)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($start_date))}}</td>
                        <td>Fund Opening Balance {{date('d/m/Y',strtotime($formattedPreviousDate))}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$fund_opening_balance}}</td>
                        @php 
                              $total +=  $fund_opening_balance;  
                        @endphp
                    </tr>
                    @endif
                    @foreach($history as $v_history)
                        <tr>
                            <td>{{date('d/m/Y',strtotime($v_history->transection_date))}}</td>
                            <td>{{$v_history->fund->name}}</td>
                            <td>{{$v_history->transection_type}}</td>
                            <td>{{$v_history->type == 2 ? $v_history->amount : ''}}</td>
                            <td>{{$v_history->type == 1 ? $v_history->amount : ''}}</td>
                            @php 
                               if($v_history->type == 1){
                                $total += $v_history->amount;
                                $total_receive += $v_history->amount;
                               }else{
                                $total -= $v_history->amount;
                                $total_expense -= $v_history->amount;
                               }
                            @endphp
                            <td>{{$total}}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                        <th class="text-right">{{$total}}</th>
                    </tr>
                </tfoot>
            </table>
            {{-- <table class="table table-bordered col-sm-6" style="margin-top: 50px; margin-bottom:10px;margin-left:50%;">
                <thead>
                    <tr>
                        <th colspan="2" class="text-right">Total Receive</th>
                        <th class="text-right">{{$total_receive}}</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Total Payment</th>
                        <th class="text-right">{{$total_expense}}</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Cash in Hand</th>
                        <th class="text-right">{{$total}}</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Payment For Advance</th>
                        <th class="text-right">@if($advanceexpense) @php $advanceTotal += $advanceexpense;  @endphp {{$advanceexpense}} @endif </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Cash in Hand Actual</th>
                        <th class="text-right">@php $final_total = $total - $advanceTotal  @endphp {{$final_total}}</th>
                    </tr>
                </thead>
            </table>  --}}

        </div>
    </div>
    @else
    {{-- <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead class="">
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
                    @php $total = 0; $sl = 0; $p = 0; $total_receive = 0;$total_expense = 0; $final_total = 0; $advanceTotal = 0; @endphp
                    @if($prev_cash)
                    <tr>
                        <td>--</td>
                        <td>Petty Cash From {{date('d/m/Y',strtotime($formattedPreviousDate))}} </td>
                        <td  class="text-right">{{$prev_cash}}</td>
                        <td></td>
                        <td></td>
                        <td  class="text-right">{{$prev_cash}}</td>
                        @php 
                            $total += $prev_cash;
                            $total_receive += $prev_cash;
                         @endphp
                    </tr>
                    @endif
                    @if($petty_cash)
                    <tr>
                        <td>{{date('d/m/Y')}}</td>
                        <td>Petty Cash From </td>
                        <td  class="text-right">{{$petty_cash}}</td>
                        <td></td>
                        <td></td>
                        @php 
                        $total += $petty_cash;
                        $total_receive += $petty_cash;
                         @endphp
                        <td  class="text-right">{{$total}}</td>
                    </tr>
                    @endif
                    @if($incomes)
                    @foreach($incomes as $v_income)
                    <tr>
                        <td>{{date('d/m/Y')}}</td>
                        <td>{{$v_income->remarks}}</td>
                        <td  class="text-right">{{$v_income->amount}}</td>
                        <td></td>
                        <td>{{$v_income->voucher_no}}</td>
                        @php 
                        $total += $v_income->amount;
                        $total_receive += $v_income->amount;
                         @endphp
                        <td  class="text-right">{{$total}}</td>
                    </tr>
                    @endforeach
                    @endif
                    @if($expense)
                    @foreach($expense as $v_data)
                    <tr>
                      <td>
                        {{date('d/m/Y',strtotime($v_data->payment_date))}}
                      </td>
                      <td>
                        {{$v_data->remarks}}
                      </td>
                      <td></td>
                      <td  class="text-right">
                       
                        {{$v_data->amount}}
                        @php
                        $total -= $v_data->amount;
                        $total_expense += $v_data->amount;
                      @endphp
                      </td>
                      <td  class="text-right">
                          {{$v_data->voucher_no}}
                      </td>
                      <td  class="text-right">
                        {{$total}}
                      </td>
                    </tr>
                    @endforeach
                    @endif
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>{{$total_receive}}</th>
                        <th>{{$total_expense }}</th>
                        <th></th>
                        <th class="text-right">{{$total}}</th>
                    </tr>
                </tfoot>
            </table> 
            <table class="table table-bordered col-sm-6" style="margin-top: 50px; margin-bottom:10px;margin-left:50%;">
                <thead>
                    <tr>
                        <th colspan="2" class="text-right">Total Receive</th>
                        <th class="text-right">{{$total_receive}}</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Total Payment</th>
                        <th class="text-right">{{$total_expense}}</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Cash in Hand</th>
                        <th class="text-right">{{$total}}</th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Payment For Advance</th>
                        <th class="text-right">@if($advanceexpenses) @php $advanceTotal += $advanceexpenses;  @endphp {{$advanceexpenses}} @endif </th>
                    </tr>
                    <tr>
                        <th colspan="2" class="text-right">Cash in Hand Actual</th>
                        <th class="text-right">@php $final_total = $total - $advanceTotal  @endphp {{$final_total}}</th>
                    </tr>
                    
                </thead>
            </table> 

        </div>
    </div> --}}
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
