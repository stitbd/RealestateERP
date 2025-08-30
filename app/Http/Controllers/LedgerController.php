<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use App\Models\AccountHead;
use App\Models\AdvanceExpense;
use App\Models\BankAccount;
use App\Models\CollectMissedMonthConsumer;
use App\Models\Expense;
use App\Models\Fund;
use App\Models\FundTransfer;
use App\Models\HeadOpeningBalance;
use App\Models\HeadToHeadTransfer;
use App\Models\Income;
use App\Models\IncomeDetails;
use App\Models\LandPayment;
use App\Models\SalesIncentivePayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LedgerController extends Controller
{
   public function index()
    {

        $data['main_menu'] = 'accounts';
        $data['child_menu'] = 'ledger';
        $data['fund_data'] = Fund::where(['status' => 1])->get();
        $data['heads'] = AccountHead::all();
        $data['date'] = $date = date('Y-m-d');
        $data['company_name'] = Session::get('company_name');

        // if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin') {

        //========================== Prev Balance Calculation Start ===========================//

        $prev_balance = 0;

        $data['prev_fund_transfer'] = FundTransfer::where('transaction_date', '<', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('to_fund_id', '2')->sum('transaction_amount');
        $data['prev_debit_fund_transfer'] = FundTransfer::where('transaction_date', '<', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('from_fund_id', '2')->sum('transaction_amount');

        $data['prev_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('to_fund_id', '2')->sum('transaction_amount');
        $data['prev_head_debit_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('from_fund_id', '2')->sum('transaction_amount');
        $data['prev_incomes']    = Income::where('payment_date', '<', date('Y-m-d'))->where('status', 1)->where('company_id', Session::get('company_id'))->where('fund_id', '2')->sum('amount');

        $data['prev_income_details'] = IncomeDetails::with('income')
            ->whereHas('income', function ($query) {
                $query->where('payment_date', '<', date('Y-m-d'));
                $query->where('status', 1);
                $query->where('company_id', Session::get('company_id'));
            })
            ->where('fund_id', 2)
            ->sum('amount');

        $data['prev_adjustment'] = IncomeDetails::with('income')
            ->whereHas('income', function ($query) {
                $query->where('payment_date', '<', date('Y-m-d'));
                $query->where('company_id', Session::get('company_id'));
                $query->where('status', 1);
            })
            ->whereNull('fund_id')
            ->sum('amount');

        $data['prev_land_sale'] = LandPayment::with('landSale')->where('pay_date', '<', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('fund_id', '2')->sum('amount');

        $data['prev_expenses'] = Expense::where('payment_date', '<', date('Y-m-d'))->where('status', 1)->where('company_id', Session::get('company_id'))->where('fund_id', '2')->sum('amount');

        $data['prev_adjustment_expenses'] = Expense::where('payment_date', '<', date('Y-m-d'))->where('status', 1)->where('company_id', Session::get('company_id'))->where('expense_type', 'adjustment')->sum('amount');

        $data['prev_sale_incentive_payment'] = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
            ->where('pay_date', '<', date('Y-m-d'))
            ->where('fund_id', 2)
            ->sum('amount');

        // dd($data['prev_sale_incentive_payment']);

        //Balance Plus(+)

        if ($data['prev_fund_transfer']) {
            $prev_balance += $data['prev_fund_transfer'];
        }

        if ($data['prev_head_fund_transfer']) {
            $prev_balance += $data['prev_head_fund_transfer'];
        }

        if ($data['prev_incomes']) {
            $prev_balance += $data['prev_incomes'];
        }

        if ($data['prev_income_details']) {
            $prev_balance += $data['prev_income_details'];
        }

        if ($data['prev_adjustment']) {
            $prev_balance += $data['prev_adjustment'];
        }
        if ($data['prev_land_sale']) {
            $prev_balance += $data['prev_land_sale'];
        }

        //Balance Minus(-)

        if ($data['prev_expenses']) {
            $prev_balance -= $data['prev_expenses'];
        }
        if ($data['prev_adjustment_expenses']) {
            $prev_balance -= $data['prev_adjustment_expenses'];
        }
        if ($data['prev_debit_fund_transfer']) {
            $prev_balance -= $data['prev_debit_fund_transfer'];
        }
        if ($data['prev_head_debit_fund_transfer']) {
            $prev_balance -= $data['prev_head_debit_fund_transfer'];
        }

        if ($data['prev_sale_incentive_payment']) {
            $prev_balance -= $data['prev_sale_incentive_payment'];
        }

        //  dd($data['prev_incomes']);
        $data['prev_balance'] = $prev_balance;

        //========================== Prev Balance Calculation End ===========================//

        //========================== Fund Transfer Data  ===========================//
        $data['fund_transfer'] = FundTransfer::where('transaction_date', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('to_fund_id', '2')->where('status', 1)->get();
        $data['debit_fund_transfer'] = FundTransfer::where('transaction_date', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('from_fund_id', '2')->where('status', 1)->get();

        //========================== Head To Head Transfer Data  ===========================//
        $data['head_transfer'] = HeadToHeadTransfer::where('transaction_date', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('to_fund_id', '2')->where('status', 1)->get();
        $data['debit_head_transfer'] = HeadToHeadTransfer::where('transaction_date', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('from_fund_id', '2')->where('status', 1)->get();

        //========================== Income & Expense Data  ===========================//

        $data['incomes'] = Income::where(['payment_date' => date('Y-m-d'), 'status' => 1])->where('fund_id', 2)->where('company_id', Session::get('company_id'))->get();

        $data['income_details'] = IncomeDetails::with('income')
            ->whereHas('income', function ($query) {
                $query->where('payment_date', date('Y-m-d'));
                $query->where('status', 1);
                $query->where('company_id', Session::get('company_id'));
            })
            ->where('fund_id', 2)
            ->get();

        $data['adjustment_incomes'] = IncomeDetails::with('income')
            ->whereHas('income', function ($query) {
                $query->where('payment_date', date('Y-m-d'));
                $query->where('status', 1);
                $query->where('company_id', Session::get('company_id'));
            })
            ->whereNull('fund_id')
            ->get();

        $data['expenses'] = Expense::where(['payment_date' => date('Y-m-d'), 'status' => 1])->where('fund_id', 2)->where('company_id', Session::get('company_id'))->get();
        $data['adjustment_expenses'] = Expense::where(['payment_date' => date('Y-m-d'), 'status' => 1])->where('expense_type', 'adjustment')->where('company_id', Session::get('company_id'))->get();
        $data['advanceexpense'] = AdvanceExpense::where('status', 1)->where('company_id', Session::get('company_id'))->sum('amount');

        //========================== Payment Related Data  ===========================//
        $data['payment'] = SalesIncentivePayment::with('sales_incentive')
            ->where('pay_date', date('Y-m-d'))
            ->where('fund_id', 2)
            ->get();

        //========================== Sales Related Data  ===========================//

        $data['land_sale'] = LandPayment::with('landSale')->where('pay_date', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('fund_id', '2')->get();

        //========================== Bank Fund Data  ===========================//
        $data['bank_income'] = Income::where(['payment_date' => date('Y-m-d'), 'status' => 1])->where('fund_id', 1)->where('company_id', Session::get('company_id'))->get();


        $data['bank_income_details'] = IncomeDetails::where('fund_id', 1)
            ->whereHas('income', function ($query) {
                $query->where('payment_date', date('Y-m-d'));
                $query->where('status', 1);
                $query->where('company_id', Session::get('company_id'));
            })
            ->get();

        $data['bank_expense'] = Expense::where(['payment_date' => date('Y-m-d'), 'status' => 1])->where('fund_id', 1)->where('company_id', Session::get('company_id'))->get();
        $data['bank_fund_transfer'] = FundTransfer::where('transaction_date', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('from_fund_id', '1')->get();
        $data['bank_head_transfer'] = HeadToHeadTransfer::where('transaction_date', date('Y-m-d'))->where('company_id', Session::get('company_id'))->where('from_fund_id', '1')->get();
        $data['bank_payment'] = SalesIncentivePayment::with('sales_incentive')
            ->where('pay_date', date('Y-m-d'))
            ->where('fund_id', 1)
            ->get();

        /** Previous Date Format  ===========================//*/

        $carbonDate = Carbon::parse($date);
        $previousDate = $carbonDate->subDay();
        $data['formattedPreviousDate'] = $previousDate->toDateString();

        // }

        // dd($data['date']);

        return view('account.ledger.daily_ledger', $data);
    }

    public function ledgerListView(Request $request)
    {
        $head_id = $request->head_id;
        $fund_id = $request->fund_id;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $company_name = Session::get('company_name');
        $incomes = '';
        $fund_transfers = '';
        $head_transfers = '';
        $credit_fund_transfer = '';
        $credit_head_fund_transfer = '';
        $debit_fund_transfer = '';
        $debit_head_fund_transfer = '';
        $prev_credit_fund_transfer = '';
        $prev_credit_head_fund_transfer = '';
        $prev_debit_fund_transfer = '';
        $prev_debit_head_fund_transfer = '';
        $prev_fund_transfer = '';
        $prev_head_fund_transfer = '';
        $fund_transfer = '';
        $head_transfer = '';
        $fund_name = '';
        $head_name = '';
        $bank_amount = '';
        $bank_credit_fund_transfer = '';
        $bank_credit_head_fund_transfer = '';
        $cash_debit_transfer = '';
        $cash_head_debit_transfer = '';
        $prev_balance = '';
        $fund_prev_balance = '';
        $land_sale_fund_bank = '';
        $bank_incentive_payment = '';
        $income_details = '';
        $bank_income_details = '';
        // =====================For Admin ======================== //

        if ($fund_id != null) {
            $fund = Fund::where('id', $fund_id)->first();
            $fund_name = $fund->name;

            // $head = AccountHead::where('id', $head_id)->first();
            // $head_name = $head->head_name;

            $fund_prev_balance = 0;
            // $head_fund_prev_balance = 0;

            $data['prev_credit_fund_transfer'] = FundTransfer::where('transaction_date', '<', $start_date)->where('to_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');
            $data['prev_credit_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', $start_date)->where('to_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');

            $data['prev_debit_fund_transfer'] = FundTransfer::where('transaction_date', '<', $start_date)->where('from_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');
            $data['prev_debit_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', $start_date)->where('from_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');

            $data['prev_incomes'] = Income::where('payment_date', '<', $start_date)->where('status', 1)->where('fund_id', $fund_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('amount');

            $data['prev_income_details'] = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($start_date) {
                    $query->where('payment_date', '<', $start_date);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->where('fund_id', $fund_id)
                ->sum('amount');

            // $data['prev_adjustment'] = IncomeDetails::with('income')
            //     ->whereHas('income', function ($query) use( $start_date) {
            //         $query->where('payment_date', '<', $start_date);
            //         $query->where('company_id', Session::get('company_id'));
            //         $query->where('status', 1);

            //     })
            //     ->whereNull('fund_id')
            //     ->sum('amount');

            $data['prev_expenses'] = Expense::where('payment_date', '<', $start_date)->where('status', 1)->where('fund_id', $fund_id)->where('company_id', Session::get('company_id'))->sum('amount');

            $data['prev_sale_incentive_payment'] = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
                ->where('pay_date', '<', $start_date)
                ->where('fund_id', $fund_id)
                ->sum('amount');

            $data['prev_land_sale'] = LandPayment::with('landSale')->where('pay_date', '<', $start_date)->where('company_id', Session::get('company_id'))->where('fund_id', $fund_id)->sum('amount');

            if ($data['prev_incomes']) {
                $fund_prev_balance += $data['prev_incomes'];
            }
            if ($data['prev_income_details']) {
                $fund_prev_balance += $data['prev_income_details'];
            }
            if ($data['prev_credit_fund_transfer']) {
                $fund_prev_balance += $data['prev_credit_fund_transfer'];
            }
            if ($data['prev_credit_head_fund_transfer']) {
                $fund_prev_balance += $data['prev_credit_head_fund_transfer'];
            }
            if ($data['prev_land_sale']) {
                $fund_prev_balance += $data['prev_land_sale'];
            }
            if ($data['prev_expenses']) {
                $fund_prev_balance -= $data['prev_expenses'];
            }
            if ($data['prev_debit_fund_transfer']) {
                $fund_prev_balance -= $data['prev_debit_fund_transfer'];
            }
            if ($data['prev_debit_head_fund_transfer']) {
                $fund_prev_balance -= $data['prev_debit_head_fund_transfer'];
            }
            if ($data['prev_sale_incentive_payment']) {
                $fund_prev_balance -= $data['prev_sale_incentive_payment'];
            }

            $fund_prev_balance = $fund_prev_balance;
            // dd($data['prev_incomes']);

            //======================= Previous Date Format =======================//

            $carbonDate = Carbon::parse($start_date);
            $previousDate = $carbonDate->subDay();
            $formattedPreviousDate = $previousDate->toDateString();

            //======================= Expense & Income Data =======================//

            $expenses = Expense::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('fund_id', $fund_id)->where('status', 1)->where('company_id', Session::get('company_id'))->get();
            $adjustment_expenses = Expense::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('status', 1)->where('expense_type', 'adjustment')->where('company_id', Session::get('company_id'))->get();
            $incomes = Income::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('fund_id', $fund_id)->where('status', 1)->where('company_id', Session::get('company_id'))->get();
            $income_details = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($start_date, $end_date) {
                    $query->where('payment_date', '>=', $start_date);
                    $query->where('payment_date', '<=', $end_date);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->where('fund_id', $fund_id)
                ->get();

            $adjustment_incomes = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($start_date, $end_date) {
                    $query->where('payment_date', '>=', $start_date);
                    $query->where('payment_date', '<=', $end_date);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->whereNull('fund_id')
                ->get();

            //========================== Payment Related Data  ===========================//
            $payment = SalesIncentivePayment::with('sales_incentive')
                ->where('pay_date', '>=', $start_date)
                ->where('pay_date', '<=', $end_date)
                ->where('fund_id', $fund_id)
                ->get();

            //========================== Land Sales Related Data ======================//

            $land_sale = LandPayment::with('landSale')->where('pay_date', '>=', $start_date)->where('pay_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('fund_id', $fund_id)->get();

            // $purchase             = Purchase::where('purchase_date','>=',$start_date)->where('purchase_date','<=',$end_date)->where('company_id',Session::get('company_id'))->orderBy('id','desc')->get();

            //======================= Fund Transfer & Head to Head Transfer Data =======================//

            $credit_fund_transfer = FundTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('to_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->get();
            $debit_fund_transfer = FundTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('from_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->get();
            $credit_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('to_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->get();
            $debit_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('from_fund_id', $fund_id)->where('company_id', Session::get('company_id'))->get();

            // dd($debit_fund_transfer);

            if ($fund_id == 2) {
                $advanceexpense = AdvanceExpense::where('status', 1)->where('date', '<=', $start_date)->where('company_id', Session::get('company_id'))->sum('amount');
            } else {
                $advanceexpense = 0;
            }

            $bank_income = '';
            $bank_expense = '';
            $bank_fund_transfer = '';
            $bank_head_fund_transfer = '';
        } else {
            $prev_balance = 0;
            //======================= Previous Balance Calculaton =======================//

            $data['prev_fund_transfer'] = FundTransfer::where('transaction_date', '<', $start_date)->where('to_fund_id', '2')->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');
            $data['prev_debit_fund_transfer'] = FundTransfer::where('transaction_date', '<', $start_date)->where('from_fund_id', '2')->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');
            $data['prev_head_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', $start_date)->where('company_id', Session::get('company_id'))->where('to_fund_id', '2')->sum('transaction_amount');
            $data['prev_head_debit_fund_transfer'] = HeadToHeadTransfer::where('transaction_date', '<', $start_date)->where('company_id', Session::get('company_id'))->where('from_fund_id', '2')->sum('transaction_amount');
            $data['prev_land_sale'] = LandPayment::with('landSale')->where('pay_date', '<', $start_date)->where('company_id', Session::get('company_id'))->where('fund_id', 2)->sum('amount');

            // $data['prev_incomes'] = Income::where('payment_date', '<', $start_date)->where('status', 1)->where('company_id', Session::get('company_id'))
            //     ->whereHas('income_details', function ($query) {
            //         $query->where('fund_id', 2);
            //     })
            //     ->withSum('income_details as total_income_amount', 'amount')
            //     ->value('total_income_amount') ?? 0;

            // $data['prev_expenses'] = Expense::where('payment_date', '<', $end_date)->where('status', 1)->where('fund_id', '2')->where('company_id', Session::get('company_id'))->sum('amount');
            $data['prev_incomes'] = Income::where('payment_date', '<', $start_date)->where('status', 1)->where('fund_id', '2')->where('company_id', Session::get('company_id'))->sum('amount');

            $data['prev_income_details'] = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($start_date) {
                    $query->where('payment_date', '<', $start_date);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->where('fund_id', 2)
                ->sum('amount');

            $data['prev_adjustment'] = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($start_date) {
                    $query->where('payment_date', '<', $start_date);
                    $query->where('company_id', Session::get('company_id'));
                    $query->where('status', 1);
                })
                ->whereNull('fund_id')
                ->sum('amount');

            $data['prev_expenses'] = Expense::where('payment_date', '<', $start_date)->where('status', 1)->where('company_id', Session::get('company_id'))->where('fund_id', '2')->sum('amount');
            $data['prev_adjustment_expenses'] = Expense::where('payment_date', '<', $start_date)->where('status', 1)->where('company_id', Session::get('company_id'))->where('expense_type', 'adjustment')->sum('amount');

            $data['prev_sale_incentive_payment'] = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
                ->where('pay_date', '<', $start_date)
                ->where('fund_id', 2)
                ->sum('amount');

            if ($data['prev_fund_transfer']) {
                $prev_balance += $data['prev_fund_transfer'];
            }
            if ($data['prev_head_fund_transfer']) {
                $prev_balance += $data['prev_head_fund_transfer'];
            }
            if ($data['prev_incomes']) {
                $prev_balance += $data['prev_incomes'];
            }
            if ($data['prev_income_details']) {
                $prev_balance += $data['prev_income_details'];
            }
            if ($data['prev_land_sale']) {
                $prev_balance += $data['prev_land_sale'];
            }

            if ($data['prev_expenses']) {
                $prev_balance -= $data['prev_expenses'];
            }

            if ($data['prev_debit_fund_transfer']) {
                $prev_balance -= $data['prev_debit_fund_transfer'];
            }

            if ($data['prev_head_debit_fund_transfer']) {
                $prev_balance -= $data['prev_head_debit_fund_transfer'];
            }
            if ($data['prev_sale_incentive_payment']) {
                $prev_balance -= $data['prev_sale_incentive_payment'];
            }

            $prev_balance = $prev_balance;

            // dd($data['prev_expenses']);

            //==================== Previous Calculation End =========================//

            //====================  Previous Date Format =======================//

            $carbonDate = Carbon::parse($start_date);
            $previousDate = $carbonDate->subDay();
            $formattedPreviousDate = $previousDate->toDateString();

            //====================  Get Income & Expense Data =======================//

            $expenses = Expense::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('status', 1)->where('company_id', Session::get('company_id'))->where('fund_id', 2)->get();
            $adjustment_expenses = Expense::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('status', 1)->where('expense_type', 'adjustment')->where('company_id', Session::get('company_id'))->get();
            $incomes = Income::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('status', 1)->where('company_id', Session::get('company_id'))->where('fund_id', 2)->get();
            $income_details = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($start_date, $end_date) {
                    $query->where('payment_date', '>=', $start_date);
                    $query->where('payment_date', '<=', $end_date);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->where('fund_id', 2)
                ->get();

            $adjustment_incomes = IncomeDetails::with('income')
                ->whereHas('income', function ($query) use ($start_date, $end_date) {
                    $query->where('payment_date', '>=', $start_date);
                    $query->where('payment_date', '<=', $end_date);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->whereNull('fund_id')
                ->get();

            // $incomes = Income::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('status', 1)->where('company_id', Session::get('company_id'))->where('fund_id', 2)->get();
            // $purchase             = Purchase::where('purchase_date','>=',$start_date)->where('purchase_date','<=',$end_date)->where('company_id',Session::get('company_id'))->orderBy('id','desc')->get();
            $advanceexpense = AdvanceExpense::where('status', 1)->where('date', '<=', $start_date)->where('company_id', Session::get('company_id'))->sum('amount');

            //====================  Fund Transfer & Head To Head Transfer Data =======================//
            $fund_transfer = FundTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('to_fund_id', '2')->get();
            $cash_debit_transfer = FundTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('from_fund_id', '2')->get();
            $head_transfer = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('to_fund_id', '2')->where('status', 1)->get();
            $cash_head_debit_transfer = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('from_fund_id', '2')->where('status', 1)->get();

            // dd($cash_debit_head_transfer);
            //========================== Payment Related Data  ===========================//
            $payment = SalesIncentivePayment::with(['sales_incentive'])
                ->where('pay_date', '>=', $start_date)
                ->where('pay_date', '<=', $end_date)
                ->where('fund_id', 2)
                ->get();

            //========================== Land Sales Related Data ===========================//
            $land_sale = LandPayment::with('landSale')->where('pay_date', '>=', $start_date)->where('pay_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('fund_id', 2)->get();

            //====================  Bank Fund Related Data =======================//

            $bank_amount = BankAccount::where('company_id', Session::get('company_id'))->sum('current_balance');
            $bank_income = Income::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('fund_id', 1)->where('status', 1)->where('company_id', Session::get('company_id'))->get();

            $bank_income_details = IncomeDetails::where('fund_id', 1)
                ->whereHas('income', function ($query) use ($start_date, $end_date) {
                    $query->where('payment_date', '>=', $start_date);
                    $query->where('payment_date', '<=', $end_date);
                    $query->where('status', 1);
                    $query->where('company_id', Session::get('company_id'));
                })
                ->get();

            $bank_expense = Expense::where('payment_date', '>=', $start_date)->where('payment_date', '<=', $end_date)->where('fund_id', 1)->where('status', 1)->where('company_id', Session::get('company_id'))->get();

            $bank_fund_transfer = FundTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('from_fund_id', '1')->get();
            $bank_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('from_fund_id', '1')->get();
            $bank_credit_fund_transfer = FundTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('to_fund_id', '1')->get();
            $bank_credit_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '>=', $start_date)->where('transaction_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('to_fund_id', '1')->get();
            //========================== Payment Related Data  ===========================//
            $bank_incentive_payment = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
                ->where('pay_date', '>=', $start_date)
                ->where('pay_date', '<=', $end_date)
                ->where('fund_id', 1)
                ->get();

            //========================== Land Sales Related Data ===========================//
            $land_sale_fund_bank = LandPayment::with('landSale')->where('pay_date', '>=', $start_date)->where('pay_date', '<=', $end_date)->where('company_id', Session::get('company_id'))->where('fund_id', 1)->get();
        }

        // dd($prev_balance);

        $bank_amount = BankAccount::where('company_id', Session::get('company_id'))->sum('current_balance');
        // =====================For General User ======================== //

        return view('account.ledger.ledger_list', compact(
            'expenses',
            'adjustment_expenses',
            'adjustment_incomes',
            'fund_prev_balance',
            'incomes',
            'payment',
            'bank_incentive_payment',
            'prev_balance',
            'start_date',
            'end_date',
            'formattedPreviousDate',
            'company_name',
            'fund_transfer',
            'fund_transfer',
            'head_transfers',
            'head_transfer',
            'advanceexpense',
            'bank_income',
            'bank_expense',
            'bank_fund_transfer',
            'bank_head_fund_transfer',
            'fund_id',
            'head_id',
            'fund_name',
            'head_name',
            'credit_fund_transfer',
            'credit_head_fund_transfer',
            'debit_fund_transfer',
            'debit_head_fund_transfer',
            'bank_amount',
            'bank_credit_fund_transfer',
            'bank_credit_head_fund_transfer',
            'cash_debit_transfer',
            'cash_head_debit_transfer',
            'land_sale_fund_bank',
            'land_sale',
            'bank_income_details',
            'income_details',
        ));
    }

    public function haedwiseLedger()
    {
        $data['main_menu'] = 'accounts';
        $data['child_menu'] = 'head-ledger';
        $data['fund_data'] = Fund::where(['status' => 1])->get();
        $data['heads'] = AccountCategory::all();
        $data['sub_heads'] = AccountHead::all();
        $data['date'] = $date = date('Y-m-d');
        $data['company_name'] = Session::get('company_name');

        return view('account.ledger.head_wise_ledger', $data);
    }

    public function haedwiseLedgerList(Request $request)
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

            return view('account.ledger.head_wise_ledger_after_search', $data);
        } else {
            echo "Please Select Head and Date.";
        }
    }
}
