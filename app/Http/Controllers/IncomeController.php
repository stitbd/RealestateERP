<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Fund;
use App\Models\Item;
use App\Models\Income;
use App\Models\Company;
use App\Models\Expense;
use App\Models\FundLog;
use App\Models\Project;
use App\Models\BankInfo;
use App\Models\Employee;
use App\Models\Subproject;
use App\Models\AccountHead;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\IncomeDetails;
use App\Models\AccountCategory;
use App\Models\FundCurrentBalance;
use App\Models\HeadOpeningBalance;
use App\Models\HeadToHeadTransfer;
use Illuminate\Support\Facades\DB;
use App\Models\SalesIncentivePayment;
use Illuminate\Support\Facades\Session;
use App\Models\CollectMissedMonthConsumer;


class IncomeController extends Controller
{
    public function index(Request $request)
    {
        try {

            $data['main_menu']              = 'income';
            $data['child_menu']             = 'income-list';
            $data['fund_data']              = Fund::where(['status' => 1])->get();
            $data['categories']             = AccountCategory::all();
            $data['head']                   = AccountHead::all();

            if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin') {
                $income                   = Income::with('company', 'fund', 'income_details')->where(['status' => 1])->where('income_type', 'general')->where('company_id', Session::get('company_id'))->orderBy('id', 'DESC');
            } else {
                $income                   = Income::with('company', 'fund')->where(['status' => 1, 'created_by' => auth()->user()->id])->where('company_id', Session::get('company_id'))->where('income_type', 'general')->orderBy('id', 'DESC');
            }

            if ($request->category || $request->head || $request->fund_id || $request->start_date || $request->end_date || $request->search) {
                if ($request->category) {
                    $income->whereHas('income_details', function ($query) use ($request) {
                        $query->where('category_id', $request->category);
                    });
                }

                if ($request->head) {
                    $income->whereHas('income_details', function ($query) use ($request) {
                        $query->where('head_id', $request->head);
                    });
                }

                if ($request->search) {
                    $searchQuery = trim($request->query('search'));
                    $income->where('remarks', 'like', "%$searchQuery%");
                }
                if ($request->fund_id) {
                    $where['fund_id'] = $request->fund_id;
                    $income->where('fund_id', '=', $request->fund_id);
                }
                if ($request->start_date) {
                    $where['start_date'] = $request->start_date;
                    $income->whereDate('payment_date', '>=', $request->start_date);
                }
                if ($request->end_date) {
                    $where['end_date'] = $request->end_date;
                    $income->whereDate('payment_date', '<=', $request->end_date);
                }

                $income                          = $income->get();
                // dd($income);
                $data['income_data']             = $income;
            } else {
                $income                        = $income->paginate(20);
                $data['income_data']           = $income;
            }


            return view('account.income.index', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function create()
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'create-income';
        $data['fund_data']              = Fund::where(['status' => 1])->get();
        $data['categories']             = AccountCategory::get();
        $data['head']                   = AccountHead::get();
        $data['company']                = Company::get();
        $data['project']                = Project::where('company_id', session()->get('company_id'))->get();
        $data['sub_project']            = Subproject::where('company_id', session()->get('company_id'))->get();
        $data['items']                  = Item::get();
        $data['banks']                  = Bank::get();
        $data['accounts']               = BankAccount::where('company_id', session()->get('company_id'))->get();
        $data['employee']               = Employee::get();
        $income                         = Income::where('income_type', 'general')->latest()->first();
        if ($income) {
            $data['income_code'] = $income->id;
        }

        return view('account.income.create2', $data);
    }

    public function incomeVoucher(Request $request)
    {
        // dd($request->all());
        \DB::beginTransaction();
        try {

            $totalFunds = count($request->fund_id);
            $totalPaymentAmount = array_sum($request->total_amount);

            $voucher = Income::where('income_type', 'general')->latest()->first();
            $voucherNumber = 'INC-' . date('Y') . (($voucher ? $voucher->id : 0) + 1);

            $otherCompanyId = session()->get('company_id') != $request->company ? $request->company : 0;

            $category = '';
            $head = '';

            $income = new Income();
            $income->company_id = session()->get('company_id');
            $income->other_company_id = $otherCompanyId;

            if ($request->category_name) {
                $category_type = json_encode($request->category_type);
                $category = new AccountCategory;
                $category->category_name = $request->category_name;
                $category->category_type = $category_type;
                $category->save();
                $income->category_id = $category->id;
            } else {
                $income->category_id         = $request->category;
            }

            if ($request->head_name) {
                $head = new AccountHead;
                $head->category_id = $category->id;
                $head->head_name = $request->head_name;
                $head->save();
                // dd($head);
                $income->head_id = $head->id;
            } else {
                $income->head_id             = $request->head;
            }


            $income->project_id = $request->project;
            $income->code_no = $request->code_no;
            $income->voucher_no = $voucherNumber;
            $income->client_name = $request->client_name;
            $income->client_id = $request->client_id;
            $income->payment_date = $request->payment_date;
            $income->adjustment_amount = $request->adjustment_amount ? array_sum($request->adjustment_amount) : null;
            // dd($income->adjustment_amount);
            // $income->adjustment_remarks = $request->adjustment_remarks ?? '';
            $income->amount = $totalPaymentAmount + ($request->adjustment_amount ? array_sum($request->adjustment_amount) : 0);
            $income->status = '1';
            $income->created_by = auth()->user()->id;

            if ($request->hasFile('attachment')) {
                $attachmentName = time() . '_income.' . $request->attachment->getClientOriginalExtension();
                $request->attachment->move(public_path('attachment'), $attachmentName);
                $income->attachment = $attachmentName;
            }

            // dd($income);
            $income->save();

            $bankIndex = 0;
            //dd($request->account_id);
            foreach ($request->fund_id as $i => $fundId) {
                $bankId = $request->bank_id[$i] ?? null;
                $details = new IncomeDetails();
                $details->fund_id = $fundId;
                $details->amount = $request->total_amount[$i];
                $details->payment_type = $request->payment_type[$i];
                if ($request->fund_id[$i] === "1") {
                    $details->bank_id = $request->bank_id[$bankIndex] ?? null;
                    $details->cheque_no = $request->cheque_no[$bankIndex] ?? null;
                    $details->cheque_issue_date = $request->cheque_issue_date[$bankIndex] ?? null;
                    $details->account_id = $request->account_id[$bankIndex] ?? null;
                    $bankIndex++;
                } else {
                    $details->bank_id = null;
                    $details->cheque_no = null;
                    $details->cheque_issue_date = null;
                    $details->account_id = null;
                }

                $details->remarks = $request->remarks[$i];
                $details->income_id = $income->id;
                $details->status = '1';
                $details->created_by = auth()->user()->id;
                $details->save();

                $fundLog = new FundLog();
                $fundLog->company_id = session()->get('company_id');
                $fundLog->fund_id = $fundId;
                $fundLog->type = '1';
                $fundLog->amount = $request->total_amount[$i];
                $fundLog->transection_type = 'income';
                $fundLog->transection_id = $income->id;
                $fundLog->transection_date = $request->payment_date;
                $fundLog->payment_type = $request->payment_type[$i];
                $fundLog->status = '1';
                $fundLog->created_by = auth()->user()->id;
                $fundLog->save();

                if (!empty($request->bank_id[$i])) {
                    $bankAccount = BankAccount::where('bank_id', $request->bank_id[$i])->where('id', $request->account_id[$i])->first();
                    if ($bankAccount) {
                        $bankAccount->current_balance += $request->total_amount[$i];
                        $bankAccount->save();
                    }
                }

                $bankId = ($fundId === "1") ? ($request->bank_id[$bankIndex - 1] ?? null) : null;

                $fundBalance = FundCurrentBalance::where([
                    'fund_id' => $fundId,
                    'bank_id' => $request->bank_id[$i] ?? null,
                    'company_id' => session()->get('company_id'),
                    'status' => 1,
                ])->first();

                if ($fundBalance) {
                    $fundBalance->amount += $request->total_amount[$i];
                    $fundBalance->updated_by = auth()->user()->id;
                    $fundBalance->save();
                } else {
                    $newFundBalance = new FundCurrentBalance();
                    $newFundBalance->fund_id = $fundId;
                    $newFundBalance->bank_id = $bankId;
                    $newFundBalance->company_id = session()->get('company_id');
                    $newFundBalance->amount = $request->total_amount[$i];
                    $newFundBalance->status = '1';
                    $newFundBalance->created_by = auth()->user()->id;
                    $newFundBalance->save();
                }
            }

            $lastAdjust = IncomeDetails::latest()->first();
            $lastAdNumber = ($lastAdjust ? $lastAdjust->adjust_code : 0) + 1;
            $adjustNumber = "ADJUST-" . $lastAdNumber;

            if ($income->adjustment_amount != null) {

                foreach ($request->category_id as $key => $categoryId) {
                    $adjust = new IncomeDetails();
                    $adjust->income_id = $income->id;
                    $adjust->amount = $request->adjustment_amount[$key];
                    $adjust->remarks = $request->adjustment_remarks[$key];
                    $adjust->adjust_code = $adjustNumber;
                    $adjust->status = '1';
                    $adjust->created_by = auth()->user()->id;
                    $adjust->adjust_head_id = $request->head_id[$key];
                    $adjust->adjust_category_id = $categoryId;
                    $adjust->save();
                }
            }

            // if ($income->adjustment_amount != null) {
            $lastExVoucher = Expense::latest()->first();
            $lastExNumber = ($lastExVoucher ? $lastExVoucher->id : 0) + 1;
            $exVoucherNumber = "VHR-" . now()->format('Y') . $lastExNumber;

            if (is_array($request->category_id) && !empty($request->category_id)) {
                foreach ($request->category_id as $key => $categoryId) {
                    $expense = Expense::where('adjust_code', $income->adjust_code)
                        ->where('category_id', $categoryId)
                        ->where('head_id', $request->head_id[$key])
                        ->first();

                    if ($expense) {
                        $expense->project_id = $income->project_id;
                        $expense->expenser_name = $income->client_name;
                        $expense->payment_date = $income->payment_date;
                        $expense->amount = $request->adjustment_amount[$key];
                        $expense->remarks = $request->adjustment_remarks[$key];
                        $expense->status = '1';
                        $expense->updated_by = auth()->user()->id;
                        $expense->head_id = $request->head_id[$key];
                        $expense->category_id = $categoryId;

                        if ($request->hasFile("attachment.$key")) {
                            $attachmentName = time() . "_expense_$key." . $request->attachment[$key]->getClientOriginalExtension();
                            $request->attachment[$key]->move(public_path('attachment'), $attachmentName);
                            $expense->attachment = $attachmentName;
                        }

                        $expense->update();
                    } else {
                        $expense = new Expense();
                        $expense->company_id = session()->get('company_id');
                        $expense->other_company_id = $income->other_company_id;
                        $expense->project_id = $income->project_id;
                        $expense->code_no = $income->code_no;
                        $expense->voucher_no = $exVoucherNumber;
                        $expense->expenser_name = $income->client_name;
                        $expense->payment_date = $income->payment_date;
                        $expense->amount = $request->adjustment_amount[$key];
                        $expense->remarks = $request->adjustment_remarks[$key];
                        $expense->adjust_code = $adjustNumber;
                        $expense->status = '1';
                        $expense->expense_type = 'adjustment';
                        $expense->created_by = auth()->user()->id;
                        $expense->head_id = $request->head_id[$key];
                        $expense->category_id = $categoryId;

                        if ($request->hasFile("attachment.$key")) {
                            $attachmentName = time() . "_expense_$key." . $request->attachment[$key]->getClientOriginalExtension();
                            $request->attachment[$key]->move(public_path('attachment'), $attachmentName);
                            $expense->attachment = $attachmentName;
                        }

                        $expense->save();
                    }
                }
            }

            // }

            \DB::commit();
            return redirect()->route('create-income')->with('status', 'Income Stored Successfully.');
        } catch (\Exception $e) {
            // dd($e);
            \DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getCurrentBalance(Request $request)
    {
        // $query->where('payment_date', '<', Carbon::now());
        $head_id = $request->head_id;

        $prev_balance = 0;

        if ($head_id) {
            $prev_incomes = Income::where('payment_date', '<', now())
                ->where('head_id', $head_id)
                ->where('company_id', Session::get('company_id'))
                ->where('status', 1)
                ->where('income_type', 'general')
                ->sum('amount');

            // $prev_income_details = IncomeDetails::with('income')
            //     ->whereHas('income', function ($query) use ($head_id) {
            //         $query->where('payment_date', '<', now())
            //             ->where('company_id', Session::get('company_id'))
            //             ->where('status', 1);
            //     })
            //     ->where('adjust_head_id', $head_id)
            //     ->sum('amount');


            $prev_credit_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '<', now())->where('to_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');
            $prev_debit_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '<', now())->where('from_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');

            //=======================Expense Related Previous Balance=======================//

            $prev_expenses = Expense::where('payment_date', '<', now())->where('status', 1)
                ->where('head_id', $head_id)->where('company_id', Session::get('company_id'))
                ->where('expense_type', 'adjustment')
                ->sum('amount');

            //=======================Investment Related Previous Balance=======================//

            $prev_investment = CollectMissedMonthConsumer::with(['consumer', 'collection'])
                ->whereHas('consumer', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->whereHas('collection', function ($query) {
                    $query->where('date', '<', now());
                })
                ->where('type', 'collect')
                ->where('company_id', Session::get('company_id'))
                ->sum('collect_amount');

            //=======================Sales Incentive Related Previous Balance=======================//

            $prev_sale_incentive_payment = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
                ->whereHas('sales_incentive', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->where('pay_date', '<', now())
                ->sum('amount');
            // dd($prev_sale_incentive_payment);

            $prev_head_opening_balance = HeadOpeningBalance::with(['head', 'category'])
                ->where('head_id', $head_id)
                ->where('date', '<', now())
                ->sum('amount');
            //=========================Previous Balance Calculation==========================//
            /** +Balance */
            if ($prev_head_opening_balance) {
                $prev_balance += $prev_head_opening_balance;
            }

            if ($prev_sale_incentive_payment) {
                $prev_balance += $prev_sale_incentive_payment;
            }

            if ($prev_incomes) {
                $prev_balance += $prev_incomes;
            }

            // if ($prev_income_details) {
            //     $prev_balance += $prev_income_details;
            // }

            if ($prev_credit_head_fund_transfer) {
                $prev_balance += $prev_credit_head_fund_transfer;
            }

            /** -Balance */

            if ($prev_expenses) {
                $prev_balance -= $prev_expenses;
            }

            if ($prev_debit_head_fund_transfer) {
                $prev_balance -= $prev_debit_head_fund_transfer;
            }

            if ($prev_investment) {
                $prev_balance -= $prev_investment;
            }

            $prev_balance = $prev_balance;
            // dd($prev_balance);
        }

        return response()->json(['prev_balance' => $prev_balance]);
    }

    public function getCurrentBalanceEdit(Request $request)
    {
        // dd($request->all());
        $head_id = $request->head_id;
        $category_id = $request->categoryId;
        // $adjustment_amount = $request->adjustmentAmount;
        $income_details_id = $request->IncomeDetailsId;

        $adjustment_amount = IncomeDetails::where('id', $income_details_id)->where('adjust_head_id', $head_id)->where('adjust_category_id', $category_id)->first();

        $prev_balance = 0;

        if ($head_id) {
            $prev_incomes = Income::where('payment_date', '<', now())
                ->where('head_id', $head_id)
                ->where('company_id', Session::get('company_id'))
                ->where('status', 1)
                ->where('income_type', 'general')
                ->sum('amount');

            $prev_credit_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '<', now())->where('to_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');
            $prev_debit_head_fund_transfer = HeadToHeadTransfer::where('transaction_date', '<', now())->where('from_head_id', $head_id)->where('company_id', Session::get('company_id'))->where('status', 1)->sum('transaction_amount');

            //=======================Expense Related Previous Balance=======================//

            $prev_expenses = Expense::where('payment_date', '<', now())->where('status', 1)
                ->where('head_id', $head_id)->where('company_id', Session::get('company_id'))
                ->where('expense_type', 'adjustment')
                ->sum('amount');

            //=======================Investment Related Previous Balance=======================//

            $prev_investment = CollectMissedMonthConsumer::with(['consumer', 'collection'])
                ->whereHas('consumer', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->whereHas('collection', function ($query) {
                    $query->where('date', '<', now());
                })
                ->where('type', 'collect')
                ->where('company_id', Session::get('company_id'))
                ->sum('collect_amount');

            //=======================Sales Incentive Related Previous Balance=======================//

            $prev_sale_incentive_payment = SalesIncentivePayment::with(['sales_incentive', 'sales_incentive.head'])
                ->whereHas('sales_incentive', function ($query) use ($head_id) {
                    $query->where('head_id', $head_id);
                })
                ->where('pay_date', '<', now())
                ->sum('amount');
            // dd($prev_sale_incentive_payment);

            $prev_head_opening_balance = HeadOpeningBalance::with(['head', 'category'])
                ->where('head_id', $head_id)
                ->where('date', '<', now())
                ->sum('amount');
            //=========================Previous Balance Calculation==========================//
            /** +Balance */
            if ($prev_head_opening_balance) {
                $prev_balance += $prev_head_opening_balance;
            }

            if ($prev_sale_incentive_payment) {
                $prev_balance += $prev_sale_incentive_payment;
            }

            if ($prev_incomes) {
                $prev_balance += $prev_incomes;
            }
            // if ($prev_income_details) {
            //     $prev_balance += $prev_income_details;
            // }

            if ($prev_credit_head_fund_transfer) {
                $prev_balance += $prev_credit_head_fund_transfer;
            }

            /** -Balance */

            if ($prev_expenses) {
                $prev_balance -= $prev_expenses;
            }

            if ($prev_debit_head_fund_transfer) {
                $prev_balance -= $prev_debit_head_fund_transfer;
            }

            if ($prev_investment) {
                $prev_balance -= $prev_investment;
            }

            $prev_balance = $prev_balance;
            // dd($prev_balance);
        }

        return response()->json(['prev_balance' => $prev_balance, 'adjustment_amount' => $adjustment_amount->amount]);
    }

    public function store(Request $request)
    {
        // try{
        $receive_by = auth()->user()->name;
        $detail_array = [];

        $model              = Session::get('model');
        $detail_array       = Session::get('detail_array');
        $fund_log           = Session::get('fund_log');

        if ($model->fund_id && $model->amount) {
            $model->save();
            $income_id = $model->id;
            // $category = $request->category;
            // $head = $request->head;
            // $amount = $request->amount;
            // $totalCategory = count($request->category);
            foreach ($detail_array as $array) {
                $array->income_id = $income_id;
                $array->save();
            }


            $fund_log->transection_id       = $income_id;
            $fund_log->save();

            $bankAccount = BankAccount::where('id', $model->account_id)->first();
            if ($bankAccount) {
                $bankAccount->current_balance += (float)$model->amount;
                $bankAccount->update();
                $bank_fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1])->where('bank_id', $model->bank_id)->first();
                if ($bank_fund) {
                    $bank_fund->amount +=   $model->amount;
                    $bank_fund->updated_by = auth()->user()->id;
                    $bank_fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $model->fund_id;
                    $fund_current_balance->bank_id = $model->bank_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $model->amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            } else {
                $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();
                if ($fund != null) {
                    $fund->amount += $model->amount;
                    $fund->updated_by = auth()->user()->id;
                    // dd($fund);
                    $fund->update();
                } else {
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $model->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $model->amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            $msg = "Income Stored.";
            $request->session()->flash('message', $msg);
        } else {
            $msg = "Income Not Stored.";
            $request->session()->flash('warning', $msg);
        }


        return redirect('create-income')->with('status', $msg);        // }catch (\Exception $e) {
        // }catch (\Exception $e) {
        //     return $e->getMessage();
        // }
    }

    public function printVoucher($id)
    {
        try {
            $model          = Income::where('id', $id)->first();
            $detail_array   = IncomeDetails::where('income_id', $id)->get();
            $receive_by     = $receive_by = auth()->user()->name;
            $company_info   = Company::where('id', $model->other_company_id != 0 ? $model->other_company_id : $model->company_id)->first();

            return view('account.income.print_voucher', compact('detail_array', 'model', 'receive_by', 'company_info'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function filterHeadData(Request $request)
    {
        $categoryId = $request->category_id;
        $category = AccountHead::where('category_id', $categoryId)->get();
        if (count($category) > 0) {
            return response()->json($category);
        }
    }

    public function edit($id)
    {
        $data['income']                 = Income::where('id', $id)->first();
        $data['expense']                = Expense::where('code_no', $data['income']->code_no)->first();
        // dd($data['expense']);
        $data['main_menu']              = 'income';
        $data['fund_data']              = Fund::where(['status' => 1])->get();
        $data['categories']             = AccountCategory::get();
        $data['head']                   = AccountHead::get();
        $data['company']                = Company::get();
        $data['project']                = Project::where('company_id', session()->get('company_id'))->get();
        $data['sub_project']            = Subproject::where('company_id', session()->get('company_id'))->get();
        $data['items']                  = Item::get();
        $data['banks']                  = Bank::get();
        $data['accounts']               = BankAccount::where('company_id', session()->get('company_id'))->get();
        $data['employee']               = Employee::get();
        return view('account.income.edit', $data);
    }
    public function update(Request $request, $id)
    {
        //   dd($request->all());
        \DB::beginTransaction();
        try {

            $totalFunds = count($request->fund_id);
            $totalPaymentAmount = array_sum($request->total_amount);

            $category = '';
            $head = '';

            $income = Income::findOrFail($id);
            IncomeDetails::where('income_id', $id)->delete();

            if ($request->category_name) {
                $category_type = json_encode($request->category_type);
                $category = new AccountCategory;
                $category->category_name = $request->category_name;
                $category->category_type = $category_type;
            } else {
                $income->category_id         = $request->category;
            }

            if ($request->head_name) {
                $head = new AccountHead;
                $head->head_name = $request->head_name;
            } else {
                $income->head_id             = $request->head;
            }

            $income->project_id = $request->project;
            $income->client_name = $request->client_name;
            $income->client_id = $request->client_id;
            $income->payment_date = $request->payment_date;
            $income->adjustment_amount = $request->adjustment_amount ? array_sum($request->adjustment_amount) : null;
            // $income->adjustment_remarks = $request->adjustment_remarks ?? '';
            $income->amount = $totalPaymentAmount + ($request->adjustment_amount ? array_sum($request->adjustment_amount) : 0);
            $income->status = '1';
            $income->created_by = auth()->user()->id;

            if ($request->hasFile('attachment')) {
                $attachmentName = time() . '_income.' . $request->attachment->getClientOriginalExtension();
                $request->attachment->move(public_path('attachment'), $attachmentName);
                $income->attachment = $attachmentName;
            }

            $income->update();
            $bankIndex = 0;

            foreach ($request->fund_id as $i => $fundId) {
                $details = new IncomeDetails();
                $details->fund_id = $fundId;
                $details->amount = $request->total_amount[$i];
                $details->payment_type = $request->payment_type[$i];
                if ($request->fund_id[$i] === "1") {
                    // dd('ujfdg');
                    $details->bank_id = $request->bank_id[$bankIndex] ?? null;
                    $details->cheque_no = $request->cheque_no[$bankIndex] ?? null;
                    $details->cheque_issue_date = $request->cheque_issue_date[$bankIndex] ?? null;
                    $details->account_id = $request->account_id[$bankIndex] ?? null;
                    $bankIndex++;
                } else {
                    $details->bank_id = null;
                    $details->cheque_no = null;
                    $details->cheque_issue_date = null;
                    $details->account_id = null;
                }

                $details->remarks = $request->remarks[$i];
                $details->income_id = $id;
                $details->status = '1';
                $details->created_by = auth()->user()->id;
                // dd($details);
                $details->save();

                $fundLog = new FundLog();
                $fundLog->company_id = session()->get('company_id');
                $fundLog->fund_id = $fundId;
                $fundLog->type = '1';
                $fundLog->amount = $request->total_amount[$i];
                $fundLog->transection_type = 'income';
                $fundLog->transection_id = $income->id;
                $fundLog->transection_date = $request->payment_date;
                $fundLog->payment_type = $request->payment_type[$i];
                $fundLog->status = '1';
                $fundLog->created_by = auth()->user()->id;
                $fundLog->save();

                if (!empty($request->bank_id[$i])) {
                    $bankAccount = BankAccount::find($request->bank_id[$i]);
                    if ($bankAccount) {
                        $bankAccount->current_balance += $request->total_amount[$i];
                        $bankAccount->save();
                    }
                }
                $fundBalance = FundCurrentBalance::where([
                    'fund_id' => $fundId,
                    'bank_id' => $request->bank_id[$i] ?? null,
                    'company_id' => session()->get('company_id'),
                    'status' => 1,
                ])->first();

                if ($fundBalance) {
                    $previousAmount = $fundBalance->amount;
                    $giveAmount = $request->total_amount[$i];
                    $difference = $previousAmount - $giveAmount;
                    if ($difference >= 0) {
                        $fundBalance->amount = $fundBalance->amount + $difference;
                    } else {
                        $fundBalance->amount = abs($difference);
                    }
                    $fundBalance->updated_by = auth()->user()->id;
                    $fundBalance->update();
                } else {
                    // dd("nai");
                    $newFundBalance = new FundCurrentBalance();
                    $newFundBalance->fund_id = $fundId;
                    $newFundBalance->bank_id = $request->bank_id[$i] ?? null;
                    $newFundBalance->company_id = session()->get('company_id');
                    $newFundBalance->amount = $request->total_amount[$i];
                    $newFundBalance->status = '1';
                    $newFundBalance->created_by = auth()->user()->id;
                    $newFundBalance->save();
                }
            }

            if (!empty($income->adjustment_amount) && !empty($request->category_id)) {
                foreach ($request->category_id as $key => $categoryId) {
                    // Retrieve the existing income detail by head and category
                    $incomeDetails = IncomeDetails::where('adjust_head_id', $request->head_id[$key])
                        ->where('adjust_category_id', $categoryId)
                        ->where('income_id', $income->id)
                        ->first();

                    $adjustmentAmount = $request->adjustment_amount[$key] ?? null;
                    $adjustHeadId = $request->head_id[$key] ?? null;

                    if ($adjustmentAmount !== null && $adjustHeadId !== null) {
                        if ($incomeDetails) {
                            // Update existing record
                            $incomeDetails->amount = $adjustmentAmount;
                            $incomeDetails->remarks = $request->adjustment_remarks[$key] ?? $incomeDetails->remarks;
                            $incomeDetails->updated_by = auth()->user()->id;
                            $incomeDetails->save();
                        } else {
                            // Create a new record
                            $adjust = new IncomeDetails();
                            $adjust->income_id = $income->id;
                            $adjust->amount = $adjustmentAmount;
                            $adjust->remarks = $request->adjustment_remarks[$key] ?? '';
                            $adjust->status = '1';
                            $adjust->created_by = auth()->user()->id;
                            $adjust->adjust_head_id = $adjustHeadId;
                            $adjust->adjust_category_id = $categoryId;
                            $adjust->save();
                        }
                    }
                }
            }



            $lastExVoucher = Expense::latest()->first();
            $lastExNumber = ($lastExVoucher ? $lastExVoucher->id : 0) + 1;
            $exVoucherNumber = "VHR-" . now()->format('Y') . $lastExNumber;

            if (is_array($request->category_id) && !empty($request->category_id)) {
                // dd('jkhdjk');
                foreach ($request->category_id as $key => $categoryId) {
                    // Ensure necessary data exists before proceeding
                    if (!isset($request->head_id[$key], $request->adjustment_amount[$key], $request->adjustment_remarks[$key])) {
                        continue; // Skip this iteration if any required data is missing
                    }

                    $expense = Expense::where('code_no', $income->code_no)
                        ->where('category_id', $categoryId)
                        ->where('expense_type', 'adjustment')
                        ->where('head_id', $request->head_id[$key])
                        ->first();
                    // dd($expense);

                    if ($expense) {
                        // Update existing expense
                        $expense->project_id = $income->project_id;
                        $expense->expenser_name = $income->client_name;
                        $expense->payment_date = $income->payment_date;
                        $expense->amount = $request->adjustment_amount[$key];
                        $expense->remarks = $request->adjustment_remarks[$key];
                        $expense->status = '1';
                        $expense->updated_by = auth()->user()->id;
                        $expense->head_id = $request->head_id[$key];
                        $expense->category_id = $categoryId;

                        // Handle file attachment
                        if ($request->hasFile("attachment.$key")) {
                            $attachmentName = time() . "_expense_$key." . $request->attachment[$key]->getClientOriginalExtension();
                            $request->attachment[$key]->move(public_path('attachment'), $attachmentName);
                            $expense->attachment = $attachmentName;
                        }

                        $expense->update();
                    } else {
                        // Create a new expense
                        $expense = new Expense();
                        $expense->company_id = session()->get('company_id');
                        $expense->other_company_id = $income->other_company_id;
                        $expense->project_id = $income->project_id;
                        $expense->code_no = $income->code_no;
                        $expense->voucher_no = $exVoucherNumber;
                        $expense->expenser_name = $income->client_name;
                        $expense->payment_date = $income->payment_date;
                        $expense->amount = $request->adjustment_amount[$key];
                        $expense->remarks = $request->adjustment_remarks[$key];
                        $expense->status = '1';
                        $expense->expense_type = 'adjustment';
                        $expense->created_by = auth()->user()->id;
                        $expense->head_id = $request->head_id[$key];
                        $expense->category_id = $categoryId;

                        // Handle file attachment
                        if ($request->hasFile("attachment.$key")) {
                            $attachmentName = time() . "_expense_$key." . $request->attachment[$key]->getClientOriginalExtension();
                            $request->attachment[$key]->move(public_path('attachment'), $attachmentName);
                            $expense->attachment = $attachmentName;
                        }

                        $expense->save();
                    }
                }
            } else {
                $expense = Expense::where('code_no', $income->code_no)
                    ->where('expense_type', 'adjustment')
                    ->first();
                // dd($expense);
                if ($expense) {
                    $expense->delete();
                }
            }


            \DB::commit();
            return redirect()->route('income-list')->with('status', 'Income Updated Successfully.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function printList(Request $request)
    {
        try {
            $data['title']                  = 'Income List || ' . Session::get('company_name');
            $income_data                   = Income::where(['company_id' => Session::get('company_id')])->with('company');

            if ($request->category) {
                $income_data->whereHas('income_details', function ($query) use ($request) {
                    $query->where('category_id', $request->category);
                });
            }
            if ($request->head) {
                $income_data->whereHas('income_details', function ($query) use ($request) {
                    $query->where('head_id', $request->head);
                });
            }

            if ($request->search) {
                $searchQuery = trim($request->query('search'));
                $income_data->where('remarks', 'like', "%$searchQuery%");
            }
            if ($request->fund_id) {
                $where['fund_id'] = $request->fund_id;
                $income_data->where('fund_id', '=', $request->fund_id);
            }
            if ($request->start_date) {
                $where['start_date'] = $request->start_date;
                $income_data->whereDate('payment_date', '>=', $request->start_date);
            }
            if ($request->end_date) {
                $where['end_date'] = $request->end_date;
                $income_data->whereDate('payment_date', '<=', $request->end_date);
            }

            $income_data = $income_data->where('income_type', 'general')->where('status', 1)->get();
            $data['income_data']             = $income_data;

            return view('account.income.print_all_income', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function payment_money_receipt($id)
    {
        $income = Income::with('company', 'fund', 'income_details')->where(['status' => 1])->where('income_type', 'general')->where('company_id', Session::get('company_id'))->where('id', $id)->first();
        // dd($income);
        return view('sales.payment_money_receipt', compact('income'));
    }

    public function statusUpdate($id)
    {
        $income = Income::find($id);
        $income->status = 2;
        $income->save();
        $msg = "Data Removed Successfully ....";
        return redirect()->back()->with('status', $msg);
    }
}
