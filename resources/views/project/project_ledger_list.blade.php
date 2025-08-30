
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
        <button class="mt-2 col-sm-1 btn btn-warning" onClick="document.title = '{{$company_name}}-Project Ledger'; printDiv('printableArea'); " style="margin-right:100px"> <i class="fa fa-print"></i> Print </button>
    </div>
</div>

<div id="printableArea">

<div class="container" style="margin-top: 50px">
    <div class="row text-center">
        <div class="col-sm-12">
            <h2>{{$company_name}}</h2>
            <h5><strong> Project Ledger Report</strong></h5>
            <h6><strong> @if($project_id != null) {{$project->name ?? ''}} @endif</strong></h6>
            <h6>@if($start_date || $end_date){{date('d/m/Y',strtotime($start_date))}}- {{date('d/m/Y',strtotime($end_date))}} @endif</h6>
        </div>
    </div> 
    @if(auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
    <div class="row" style="margin-top: 20px">
        <div class="col-sm-12">
            <table class="table table-bordered">
                <thead class="">
                    <tr>
                        <th>Date</th>
                        <th>Project Name</th>
                        <th>Particulars</th>
                        {{-- <th>Transaction Type</th> --}}
                        <th>Credit</th>
                        <th>Debit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; $total_expense = 0; $total_receive = 0; @endphp
    
                    {{-- @foreach($subprojects as $project) --}}
                    @php

                        $prev_balance = 0;

                        $expense = App\Models\Expense::where('project_id',$project_id)->where('status',1);
                        $payment = App\Models\Payment::where('project_id',$project_id)->where('status',1);
                        $income  = App\Models\Income::where('project_id',$project_id)->where('status',1);


                        if($category_id != null){
                            $expense->where('category_id',$category_id);
                            $income->whereHas('income_details', function($query) use ($category_id) {
                                    $query->where('category_id', $category_id);
                                });

                        }

                        if($head_id != null){
                            $expense->where('head_id',$head_id);
                            $income->whereHas('income_details', function($query) use ($head_id) {
                                    $query->where('head_id', $head_id);
                                });
                        }

                        if($start_date || $end_date){
                            $expense->whereBetween('payment_date',[$start_date,$end_date]);
                            $payment->whereBetween('payment_date',[$start_date,$end_date]);
                            $income->whereBetween('payment_date',[$start_date,$end_date]);

                            $prev_income = App\Models\Income::where('payment_date','<',$start_date)->sum('amount');
                            $prev_expense = App\Models\Expense::where('payment_date','<',$start_date)->sum('amount');
                            $prev_payment = App\Models\Payment::where('payment_date','<',$start_date)->sum('amount');

                            $prev_balance = $prev_income - ($prev_expense + $prev_payment);
                        }
                        
                        $expenses = $expense->get();
                        $payments = $payment->get();
                        $incomes  = $income->get();

                        $total_expense = 0;
                        $total_income  = 0;
                    @endphp

                    @if($prev_balance > 0)
                       <tr>
                        <td>{{date('d/m/Y',strtotime($start_date))}}</td>
                        <td>{{$project->name ?? ''}}</td>
                        <td>Prev Balance</td>
                        <td>{{$prev_balance}}</td>
                        <td></td>
                        @php 
                                $total += $prev_balance;
                                $total_income += $prev_balance;
                        @endphp
                        <td>{{$total}}</td>
                    </tr> 
                    @endif
                    @if($incomes)
                    @foreach($incomes as $v_income)
                    <tr>
                        <td>{{date('d/m/Y',strtotime($v_income->payment_date))}}</td>
                        <td>{{$project->name ?? ''}}</td>
                        <td>{{$v_income->remarks}}</td>
                        <td>{{$v_income->amount}}</td>
                        <td></td>
                        @php 
                           $total += $v_income->amount;
                           $total_income += $v_income->amount;
                      @endphp
                        <td>{{$total}}</td>
                    </tr>
                   
                    @endforeach
                    @endif
                        @if($expenses)
                        @foreach($expenses as $v_expense)
                        <tr>
                            <td>{{date('d/m/Y',strtotime($v_expense->payment_date))}}</td>
                            <td>{{$project->name ?? ''}}</td>
                            <td>{{$v_expense->remarks}}</td>
                            <td></td>
                            @php 
                                $total -= $v_expense->amount;
                                $total_expense += $v_expense->amount;
                            @endphp
                            <td>{{$v_expense->amount}}</td>
                            <td>{{$total}}</td>
                        </tr>
                    
                        @endforeach
                        @endif

                        @if($payments)
                        @foreach($payments as $v_payment)
                        <tr>
                            <td>{{date('d/m/Y',strtotime($v_payment->payment_date))}}</td>
                            <td>{{$project->name ?? ''}}</td>
                            <td>{{$v_payment->remarks}}</td>
                            <td></td>
                            @php 
                                $total -= $v_payment->amount;
                                $total_expense += $v_payment->amount;
                            @endphp
                            <td>{{$v_payment->amount}}</td>
                            <td>{{$total}}</td>
                        </tr>
                    
                        @endforeach
                        @endif
                        
                    {{-- @endforeach --}}
                </tbody>
                <tfoot>
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="text-right">Total</th>
                        <th class="text-right">{{$total_income}}</th>
                        <th class="text-right">{{$total_expense}}</th>
                        <th class="text-right">{{$total}}</th>
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
