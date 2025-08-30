@php
    use App\Models\Expense;
    use App\Models\SiteOpeningBalance;
    use App\Models\SiteExpense;

@endphp

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="nav-link">Home</a>
        </li>
    </ul>

    <marquee class="text-center text-success pl-4 text-bold d-sm-inline-block">Bismillahir Rahmanir Raheem</marquee>

    <!-- Right navbar links -->

    <ul class="navbar-nav ml-auto">

        @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
            @php
                $total_amount = 0;
                $user = auth()->user()->id;
                $date = date('Y-m-d');

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


                // dd($expense);
                $total_amount = ($income+$income_detail+$fund_transfer+$to_head_transfer+$land_sale) - ($expense + $advance_expense + $from_fund_transfer + $from_head_transfer +$sale_incentive_payment);
            @endphp

            <li>
                <h4 class="nav-item d-inline-block pr-4 pt-2 text-bold">Cash</h4>
            </li>
            <li>
                <h4 class="nav-item d-inline-block pr-4 pt-2">: &nbsp; </h4>
            </li>
            <li>
                <h4 class="nav-item d-inline-block pr-4 pt-2 text-bold">{{ $total_amount }}</h4>
            </li>

        @endif

        <li class="nav-item d-none d-sm-inline-block">
            <form action="{{ route('logout') }}" method="post">
                @csrf
                <button type="submit" class="nav-link">Logout</button>
            </form>
        </li>
    </ul>


</nav>
