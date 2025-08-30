<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Income;
use App\Models\Company;
use App\Models\Expense;
use App\Models\AccountHead;
use App\Models\BankAccount;
use App\Models\LandPayment;
use App\Models\FundTransfer;
use Illuminate\Http\Request;
use App\Models\IncomeDetails;
use App\Models\AdvanceExpense;
use App\Models\HeadOpeningBalance;
use App\Models\HeadToHeadTransfer;
use App\Models\SalesIncentivePayment;
use Illuminate\Support\Facades\Session;
use App\Models\CollectMissedMonthConsumer;

class ReportController extends Controller
{
    public function incomeStatement(Request $request)
    {
        $main_menu = 'income';
        $child_menu = 'income-statement-list';   
        $ac_heads = AccountHead::all();
        $company = Company::where('id', Session::get('company_id'))->first();

        return view('account.income.statement.income_statement', compact('ac_heads', 'company', 'main_menu', 'child_menu'));
    }


    public function incomeStatementView(Request $request)
    {
        $data['head_id'] = $head_id = $request->head_id;
        $data['start_date'] = $start_date = $request->start_date;
        $data['end_date'] = $end_date = $request->end_date;
        $data['company_name'] = $company_name = Session::get('company_name');
        $data['head'] = AccountHead::find($head_id);
        $data['head_name'] = $data['head']->head_name;

        $prev_balance = 0;

        if ($head_id != null && $start_date != null && $end_date != null) {

            //===========================Income Related Previous Balance========================//
            $data['prev_incomes'] = Income::where('payment_date', '<', $start_date)
                ->where('head_id', $head_id)->where('company_id', Session::get('company_id'))
                ->where('status', 1)
                ->where('income_type', 'general')
                ->sum('amount');

            // $data['prev_income_details'] = IncomeDetails::with('income')
            //     ->whereHas('income', function ($query) use ($start_date) {
            //         $query->where('payment_date', '<', $start_date);
            //         $query->where('company_id', Session::get('company_id'));
            //         $query->where('status', 1);
            //     })
            //     // ->where('adjust_head_id', $head_id)
            //     ->sum('amount');

            //=======================Head To Head Transfer Related Previous Balance=======================//
            $data['prev_credit_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', $start_date)->where('to_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');
            $data['prev_debit_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', $start_date)->where('from_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');

            //=======================Expense Related Previous Balance=======================//

            $data['prev_expenses'] = Expense::where('payment_date', '<', $start_date)->where('status', 1)
                ->where('head_id', $head_id)->where('company_id', Session::get('company_id'))
                ->where('expense_type', 'current')
                ->sum('amount');

            //=======================Investment Related Previous Balance=======================//

            $data['prev_investment'] = CollectMissedMonthConsumer::with(['consumer', 'collection'])
                ->whereHas('consumer', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->whereHas('collection', function ($query) use ($start_date, $end_date) {
                    $query->where('date', '<', $start_date);
                })
                ->where('type', 'collect')
                ->where('company_id', Session::get('company_id'))
                ->sum('collect_amount');

            //=======================Sales Incentive Related Previous Balance=======================//

            $data['prev_sale_incentive_payment'] = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
                ->whereHas('sales_incentive', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->where('pay_date', '<', $start_date)
                ->sum('amount');

            //=========================Previous Balance Calculation==========================//
            /** +Balance */
            if ($data['prev_sale_incentive_payment']) {
                $prev_balance += $data['prev_sales'];
            }

            if ($data['prev_incomes']) {
                $prev_balance += $data['prev_incomes'];
            }
            // if ($data['prev_income_details']) {
            //     $prev_balance += $data['prev_income_details'];
            // }

            if ($data['prev_credit_head_fund_transfer']) {
                $prev_balance += $data['prev_credit_head_fund_transfer'];
            }

            /** -Balance */

            if ($data['prev_expenses']) {
                $prev_balance -= $data['prev_expenses'];
            }

            if ($data['prev_debit_head_fund_transfer']) {
                $prev_balance -= $data['prev_debit_head_fund_transfer'];
            }

            if ($data['prev_investment']) {
                $prev_balance -= $data['prev_investment'];
            }

            $data['prev_balance'] = $prev_balance;

            // dd($prev_balance);

            /** Date */
            $carbonDate = Carbon::parse($start_date);
            $previousDate = $carbonDate->subDay();
            $data['formattedPreviousDate'] = $previousDate->toDateString();

            //============================ Get Data From Income and Income Details as Headwise ===================//

            $data['incomes'] = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($head_id, $start_date, $end_date) {
                    $query->where('payment_date', '>=', $start_date);
                    $query->where('payment_date', '<=', $end_date);
                    $query->where('head_id', $head_id);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->get();

            // dd($data['incomes']);

            // $data['income_details'] = IncomeDetails::with('income')
            //     ->whereHas('income', function ($query) use ($start_date, $end_date) {
            //         $query->where('payment_date', '>=', $start_date);
            //         $query->where('payment_date', '<=', $end_date);
            //         $query->where('company_id', Session::get('company_id'));
            //         $query->where('status', 1);
            //     })
            //     ->where('adjust_head_id', $head_id)
            //     ->get();

            //============================ Get Headwise Data From Expense ===================//

            $data['expenses'] = Expense::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)
                ->where('status', 1)->where('head_id', $head_id)->where('company_id', Session::get('company_id'))
                ->where('expense_type', 'adjustment')
                ->get();
            // dd($data['expenses']);
            //============================ Get Headwise Data From Head To Head Transfer ===================//

            $data['credit_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('to_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->get();
            $data['debit_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('from_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->get();

            //============================ Get Headwise Data From Consumer Investor ===================//

            $data['investment'] = CollectMissedMonthConsumer::with(['consumer', 'collection'])
                ->whereHas('consumer', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->whereHas('collection', function ($query) use ($start_date, $end_date) {
                    $query->where('date', '>=', $start_date);
                    $query->where('date', '<=', $end_date);
                })
                ->where('type', 'collect')
                ->where('company_id', Session::get('company_id'))
                ->get();

            //============================ Get Headwise Data From Sales Incentive Payment ===================//

            $data['sales'] = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
                ->whereHas('sales_incentive', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->where('pay_date', '>=', $start_date)->where('pay_date', '<=', $end_date)
                ->get();

            $data['head_opening_balance'] = HeadOpeningBalance::with(['head', 'category'])
                ->where('head_id', $head_id)
                ->where('date', '>=', $start_date)->where('date', '<=', $end_date)
                ->get();

            // dd($data['head_opening_balance']);

            return view('account.income.statement.income_statement_view', $data);
        } else {
            echo "Please Select Head and Date.";
        }
    }

    //Receipt and Payment Statement

    public function receiptAndPaymentStatement(Request $request)
    {
        $main_menu = 'accounts';
        $child_menu = 'receipt_and_payment_statement';
        $opening_balance_cash = Income::where('income_type', 'opening_balance')
            ->where('fund_id', 2)
            ->where('company_id', session()->get('company_id'))
            ->where('status', 1)
            ->sum('amount');

        $opening_balance_bank = Income::where('income_type', 'opening_balance')
            ->where('fund_id', 1)
            ->where('company_id', session()->get('company_id'))
            ->where('status', 1)
            ->sum('amount');
        // dd($opening_balance_cash);



        //closing balance
        $closing_balance_cash = 0;
        //=============================== Cash Inflow ==============================//
        $income = Income::where('company_id', session()->get('company_id'))
            ->where('fund_id', 2)
            ->where('status', 1)
            ->sum('amount');

        $income_detail = IncomeDetails::with('income')
            ->whereHas('income', function ($query) {
                $query->where('status', 1);
                $query->where('company_id', Session::get('company_id'));
            })
            ->where('fund_id', 2)
            ->sum('amount');

        $fund_transfer = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $fund_transfer_bank = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');

        $to_head_transfer = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $to_head_transfer_bank = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');

        $land_sale = LandPayment::where('company_id', Session::get('company_id'))->where('fund_id', '2')->sum('amount');
        $land_sale_bank_cash = LandPayment::where('company_id', Session::get('company_id'))->sum('amount');

        //=============================== Cash Outflow ==============================//

        $from_fund_transfer = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $from_fund_transfer_bank = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');


        $from_head_transfer = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $from_head_transfer_bank = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');


        $expense = Expense::where('company_id', session()->get('company_id'))
            ->where('fund_id', 2)
            ->where('status', 1)
            ->sum('amount');

        $advance_expense = AdvanceExpense::where('company_id', session()->get('company_id'))
            ->where('status', 1)
            ->sum('amount');

        $sale_incentive_payment = SalesIncentivePayment::where('fund_id', 2)->sum('amount');

        $sale_incentive_payment_bank = SalesIncentivePayment::where('fund_id', 1)->sum('amount');

        $closing_balance_cash = ($income + $income_detail + $fund_transfer + $to_head_transfer + $land_sale) - ($expense + $advance_expense + $from_fund_transfer + $from_head_transfer + $sale_incentive_payment);

        $closing_balance_bank = BankAccount::where('company_id',session()->get('company_id'))->sum('current_balance');
        $company = Company::where('id', Session::get('company_id'))->first();
        // dd($company);

        return view('account.ledger.receipt_and_payment_statement', compact('main_menu', 'child_menu', 'company', 'opening_balance_cash', 'opening_balance_bank', 'closing_balance_cash', 'closing_balance_bank', 'land_sale', 'land_sale_bank_cash', 'advance_expense', 'fund_transfer', 'to_head_transfer', 'from_fund_transfer', 'from_head_transfer', 'fund_transfer_bank', 'to_head_transfer_bank', 'from_fund_transfer_bank', 'from_head_transfer_bank', 'sale_incentive_payment_bank', 'sale_incentive_payment'));
    }

    public function balance_sheet(Request $request)
    {
        $main_menu = 'accounts';
        $child_menu = 'balance_sheet';
        $opening_balance_cash = Income::where('income_type', 'opening_balance')
            ->where('fund_id', 2)
            ->where('company_id', session()->get('company_id'))
            ->where('status', 1)
            ->sum('amount');

        $opening_balance_bank = Income::where('income_type', 'opening_balance')
            ->where('fund_id', 1)
            ->where('company_id', session()->get('company_id'))
            ->where('status', 1)
            ->sum('amount');
        // dd($opening_balance_cash);



        //closing balance
        $closing_balance_cash = 0;
        //=============================== Cash Inflow ==============================//
        $income = Income::where('company_id', session()->get('company_id'))
            ->where('fund_id', 2)
            ->where('status', 1)
            ->sum('amount');

        $income_detail = IncomeDetails::with('income')
            ->whereHas('income', function ($query) {
                $query->where('status', 1);
                $query->where('company_id', Session::get('company_id'));
            })
            ->where('fund_id', 2)
            ->sum('amount');

        $fund_transfer = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $fund_transfer_bank = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');

        $to_head_transfer = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $to_head_transfer_bank = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('to_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');

        $land_sale = LandPayment::where('company_id', Session::get('company_id'))->where('fund_id', '2')->sum('amount');
        $land_sale_bank_cash = LandPayment::where('company_id', Session::get('company_id'))->sum('amount');

        //=============================== Cash Outflow ==============================//

        $from_fund_transfer = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $from_fund_transfer_bank = FundTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');


        $from_head_transfer = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 2)
            ->where('status', 1)
            ->sum('transaction_amount');

        $from_head_transfer_bank = HeadToHeadTransfer::where('company_id', session()->get('company_id'))
            ->where('from_fund_id', 1)
            ->where('status', 1)
            ->sum('transaction_amount');


        $expense = Expense::where('company_id', session()->get('company_id'))
            ->where('fund_id', 2)
            ->where('status', 1)
            ->sum('amount');

        $advance_expense = AdvanceExpense::where('company_id', session()->get('company_id'))
            ->where('status', 1)
            ->sum('amount');

        $sale_incentive_payment = SalesIncentivePayment::where('fund_id', 2)->sum('amount');

        $sale_incentive_payment_bank = SalesIncentivePayment::where('fund_id', 1)->sum('amount');

        $closing_balance_cash = ($income + $income_detail + $fund_transfer + $to_head_transfer + $land_sale) - ($expense + $advance_expense + $from_fund_transfer + $from_head_transfer + $sale_incentive_payment);

        $closing_balance_bank = BankAccount::where('company_id',session()->get('company_id'))->sum('current_balance');
        $company = Company::where('id', Session::get('company_id'))->first();
        // dd($company);

        return view('account.ledger.balance_sheet', compact('main_menu', 'child_menu', 'company', 'opening_balance_cash', 'opening_balance_bank', 'closing_balance_cash', 'closing_balance_bank', 'land_sale', 'land_sale_bank_cash', 'advance_expense', 'fund_transfer', 'to_head_transfer', 'from_fund_transfer', 'from_head_transfer', 'fund_transfer_bank', 'to_head_transfer_bank', 'from_fund_transfer_bank', 'from_head_transfer_bank', 'sale_incentive_payment_bank', 'sale_incentive_payment'));
    }
}
