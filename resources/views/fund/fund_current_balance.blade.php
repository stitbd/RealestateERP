@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Fund Current Balance
                    </h3>
                </div> <!-- /.card-body -->
                <div class="card-body">
                  <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="bg-info text-center">
                            <th>ID</th>
                            <th>Fund Type</th>
                            <th>Fund Name</th>
                            <th>Account Number</th>
                            <th>Account Balance</th>
                            <th>Total Fund Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total_amount = 0;
                         //=============================== Cash Inflow ==============================//
                        $income = App\Models\Income::where('company_id', session()->get('company_id'))
                                        ->where('fund_id', 2)
                                        ->where('status', 1)
                                        ->sum('amount');

                        $income_detail = App\Models\IncomeDetails::with('income')
                                    ->whereHas('income', function ($query) {
                                        $query->where('status', 1);
                                        $query->where('company_id', Session::get('company_id'));
                                    })
                                    ->where('fund_id', 2)
                                    ->sum('amount');


                        $fund_transfer = App\Models\FundTransfer::where('company_id', session()->get('company_id'))
                                        ->where('to_fund_id', 2)
                                        ->where('status', 1)
                                        ->sum('transaction_amount');

                        $to_head_transfer = App\Models\HeadToHeadTransfer::where('company_id', session()->get('company_id'))
                                            ->where('to_fund_id', 2)
                                            ->where('status', 1)
                                            ->sum('transaction_amount');

                        $land_sale = App\Models\LandPayment::where('company_id', Session::get('company_id'))->where('fund_id', '2')->sum('amount');

                        //=============================== Cash Outflow ==============================//

                        $from_fund_transfer = App\Models\FundTransfer::where('company_id', session()->get('company_id'))
                                ->where('from_fund_id', 2)
                                ->where('status', 1)
                                ->sum('transaction_amount');


                        $from_head_transfer = App\Models\HeadToHeadTransfer::where('company_id', session()->get('company_id'))
                            ->where('from_fund_id', 2)
                            ->where('status', 1)
                            ->sum('transaction_amount');


                        $expense = App\Models\Expense::where('company_id', session()->get('company_id'))
                            ->where('fund_id', 2)
                            ->where('status', 1)
                            ->sum('amount');

                        $advance_expense = App\Models\AdvanceExpense::where('company_id', session()->get('company_id'))
                            ->where('status', 1)
                            ->sum('amount');

                        $sale_incentive_payment = App\Models\SalesIncentivePayment::where('fund_id', 2)->sum('amount');


                        $total_amount = ($income+$income_detail+$fund_transfer+$to_head_transfer+$land_sale) - ($expense + $advance_expense + $from_fund_transfer + $from_head_transfer +$sale_incentive_payment);
                        
                        $total_bank_amount = 0;
                    @endphp
                        @foreach ($fund_data as $item)

                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->fund->name }}</td>
                            <td>@if($item->bank_id){{ $item->bank->name }} @else {{ $item->fund->name }} @endif</td>
                            <td>@if($item->bank_id)
                                    @php
                                        $accounts = App\Models\BankAccount::where('bank_id',$item->bank_id)->where('company_id',session()->get('company_id'))->get();
                                    @endphp
                                    @foreach($accounts as $acc)
                                        {{$acc->account_no}}<br>
                                    @endforeach
                                @endif</td>
                                 <td>@if($item->bank_id)
                                    @php
                                        $accounts = App\Models\BankAccount::where('bank_id',$item->bank_id)->where('company_id',session()->get('company_id'))->get();
                                        $balance = App\Models\BankAccount::where('bank_id',$item->bank_id)->where('company_id',session()->get('company_id'))->sum('current_balance');
                                    @endphp
                                    @foreach($accounts as $acc)
                                        {{$acc->current_balance	? $acc->current_balance : 0}}<br>

                                    @endforeach
                                @endif</td>
                            <!--<td>{{ $item->amount }}</td>-->
                            <td>@if($item->fund_id == 1){{$balance}} @else {{$total_amount}} @endif</td>
                            {{-- <td>
                                <button data-toggle="modal" onclick="load_edit_body('{{$item->id}}','{{$item->name}}','{{$item->account_no}}','{{$item->branch}}','{{$item->details}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</button>
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>
</div>


<script>
    function load_edit_body(fund_id,name,account_no,branch,details){
        $('#fund_id').val(fund_id);
        $('#name').val(name);
        $('#account_no').val(account_no);
        $('#branch').val(branch);
        $('#details').val(details);
    }
</script>
@endsection

