<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Fund;
use App\Models\Item;
use App\Models\Company;
use App\Models\Expense;
use App\Models\FundLog;
use App\Models\Project;
use App\Models\BankInfo;
use App\Models\Employee;
use App\Models\PettyCash;
use App\Models\Subproject;
use App\Models\AccountHead;
use App\Models\BankAccount;
use App\Models\SiteExpense;
use Illuminate\Http\Request;
use App\Models\AdvanceCheque;
use App\Models\AdvanceExpense;
use App\Models\ExpenseDetails;
use App\Models\AccountCategory;
use App\Models\ExpenseBankInfo;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;


class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data['main_menu']              = 'expense';
            $data['child_menu']             = 'expense-list';
            $data['fund_data']              = Fund::where(['status' => 1])->get();
            $data['categories']             = AccountCategory::all();
            $data['head']                   = AccountHead::all();
            $data['projects']                = Project::where('company_id', Session::get('company_id'))->get();
            $data['serachcategoryId']       = '';
            $data['serachHeadId']           = '';
            $data['serachText']             = '';

            if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin') {
                $expense_data   = Expense::with('company', 'fund', 'project')->where('status', 1)->where('company_id', Session::get('company_id'));
                if ($request->category || $request->head || $request->project || $request->search || $request->fund_id || $request->start_date || $request->end_date) {
                    if ($request->category) {
                        $where['category_id'] = $request->category;
                        $expense_data->where('category_id', '=', $request->category);
                        $data['serachcategoryId'] = $request->category;

                        // dd($expense_data);
                    }
                    if ($request->head) {
                        $where['head_id'] = $request->head;
                        $expense_data->where('head_id', '=', $request->head);
                        $data['serachHeadId'] = $request->head;
                    }
                    if ($request->search) {
                        $searchQuery = trim($request->query('search'));

                        $expense_data->where('remarks', 'like', "%$searchQuery%")->orWhere('expenser_name', 'like', "%$searchQuery%")
                            ->orWhereHas('category', function ($query) use ($searchQuery) {
                                $query->where('category_name', 'like', "%$searchQuery%");
                            })
                            ->orWhereHas('head', function ($query) use ($searchQuery) {
                                $query->where('head_name', 'like', "%$searchQuery%");
                            });

                        $data['serachText'] = $searchQuery;
                    }

                    if ($request->fund_id) {
                        $where['fund_id'] = $request->fund_id;
                        $expense_data->where('fund_id', '=', $request->fund_id);
                    }

                    if ($request->project) {
                        $where['project_id'] = $request->project;
                        $expense_data->where('project_id', '=', $request->project);
                    }

                    if ($request->start_date || $request->end_date) {
                        $where['start_date'] = $request->start_date;
                        $expense_data->whereDate('payment_date', '>=', $request->start_date);
                        $where['end_date'] = $request->end_date;
                        $expense_data->whereDate('payment_date', '<=', $request->end_date);
                        $expense_data->orderBy('payment_date', 'ASC');
                    }
                    $expense_data                   = $expense_data->get();
                    $data['expense_data']           = $expense_data;
                } else {
                    $expense_data                   = $expense_data->orderByDesc('id');
                    $expense_data                   = $expense_data->paginate(20);
                    $data['expense_data']           = $expense_data;
                }
            } else {
                $expense_data                   = Expense::with('company', 'fund')->where(['status' => 1, 'created_by' => auth()->user()->id])->orderBy('id', 'DESC');
                if ($request->category) {
                    $where['category_id'] = $request->category;
                    $expense_data->where('category_id', '=', $request->category);
                    // dd($data);
                }
                if ($request->head) {
                    $where['head_id'] = $request->head;
                    $expense_data->where('head_id', '=', $request->head);
                }
                if ($request->search) {
                    $searchQuery = trim($request->query('search'));

                    $expense_data->where('remarks', 'like', "%$searchQuery%")
                        ->orWhereHas('category', function ($query) use ($searchQuery) {
                            $query->where('category_name', 'like', "%$searchQuery%");
                        })
                        ->orWhereHas('head', function ($query) use ($searchQuery) {
                            $query->where('head_name', 'like', "%$searchQuery%");
                        });

                    // $expense_data->select('*')
                    //     ->from('expenses')
                    //     ->where('remarks', 'like', "%$searchQuery%")
                    //     ->union(DB::table('account_categories as c')
                    //         ->select('c.*')
                    //         ->where('c.category_name', 'like', "%$searchQuery%"))
                    //     ->union(DB::table('account_heads as h')
                    //         ->select('h.*')
                    //         ->where('h.head_name', 'like', "%$searchQuery%"));


                }

                if ($request->fund_id) {
                    $where['fund_id'] = $request->fund_id;
                    $expense_data->where('fund_id', '=', $request->fund_id);
                }
                if ($request->start_date) {
                    $where['start_date'] = $request->start_date;
                    $expense_data->whereDate('payment_date', '>=', $request->start_date);
                }
                if ($request->end_date) {
                    $where['end_date'] = $request->end_date;
                    $expense_data->whereDate('payment_date', '<=', $request->end_date);
                }
                $expense_data                   = $expense_data->paginate(10);
                // dd($expense_data);
                $data['expense_data']           = $expense_data;
            }


            return view('account.expense.index', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function requisitionList()
    {
        $data['main_menu']              = 'expense';
        $data['child_menu']             = 'requisition-list';
        $data['fund_data']              = Fund::where(['status' => 1])->get();
        $data['categories']             = AccountCategory::all();
        $data['head']                   = AccountHead::all();
        if (auth()->user()->role == 'User') {
            $data['expense']                = Expense::with('company', 'fund')->where('status', 0)->where('company_id', Session::get('company_id'))->where('created_by', auth()->user()->id)->get();
        } else {
            $data['expense']                = Expense::with('company', 'fund')->where('status', 0)->where('company_id', Session::get('company_id'))->get();
        }
        // dd($data['expense']);

        return view('account.expense.requisition_list', $data);
    }

    public function rejectExpenseList()
    {
        $data['main_menu']              = 'expense';
        $data['child_menu']             = 'reject-list';
        $data['fund_data']              = Fund::where(['status' => 1])->get();
        $data['categories']             = AccountCategory::all();
        $data['head']                   = AccountHead::all();
        if (auth()->user()->role == 'User') {
            $data['expense']                = Expense::with('company', 'fund')->where('status', 2)->where('company_id', Session::get('company_id'))->where('created_by', auth()->user()->id)->get();
        } else {
            $data['expense']                = Expense::with('company', 'fund')->where('status', 2)->where('company_id', Session::get('company_id'))->get();
        }

        return view('account.expense.reject_list', $data);
    }

    public function advanceExpense()
    {
        try {
            $data['main_menu']              = 'expense';
            $data['child_menu']             = 'advance-expense';
            $data['petty_cash']             = PettyCash::where('user_id', auth()->user()->id)->where('given_date', date('Y-m-d'))->orderByDesc('id')->first();
            if (auth()->user()->role == "SuperAdmin" || auth()->user()->role == "Admin") {
                $data['advance_expenses']       = AdvanceExpense::where('company_id', Session::get('company_id'))->where('status', 1)->paginate(10);
                $data['total_advance']          = AdvanceExpense::where('company_id', Session::get('company_id'))->where('status', 1)->sum('amount');
            } else {
                $data['advance_expenses']       = AdvanceExpense::where('company_id', auth()->user()->id)->where('status', 1)->paginate(10);
                $data['total_advance']          = AdvanceExpense::where('company_id', auth()->user()->id)->where('status', 1)->sum('amount');
            }
            return view('account.expense.manage_advance_expense', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function advanceExpenseAmount(Request $request)
    {
        try {
            $advance_id = AdvanceExpense::where('id', $request->advance_id)->first();
            $amount = $advance_id->amount;
            return response()->json($amount);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function advanceExpenseStore(Request $request)
    {
        try {
            $petty_cash = PettyCash::where('user_id', auth()->user()->id)->where('given_date', '<=', date('Y-m-d'))->orderByDesc('id')->first();
            $advance = new AdvanceExpense;
            $advance->details       = $request->details;
            $advance->amount        = $request->amount;
            $advance->status        = 1; // Advance Entry
            $advance->created_by    = auth()->user()->id;
            $advance->date          = $request->date;
            $advance->company_id    = Session::get('company_id');
            $advance->save();
            $amount = 0;
            $fund = FundCurrentBalance::where('fund_id', 2)->where('status', 1)->where('company_id', session()->get('company_id'))->first();

            if ($fund) {
                $fund->amount -= $request->amount;
                $fund->update();
            } else {
                $fund = new FundCurrentBalance;
                $fund->fund_id      =  2;
                $fund->company_id   =  session()->get('company_id');
                $fund->amount       =  $request->amount;
                $fund->status       =  1;
                $fund->save();
            }

            $log                        = new FundLog();
            $log->transection_id       = $advance->id;
            $log->company_id           = Session::get('company_id');
            $log->fund_id              = $fund->fund_id;
            $log->type                 = '2';
            $log->amount               = $request->amount;
            $log->transection_type     = 'Advance-expense';
            $log->transection_date     = $request->date;
            $log->payment_type         = "Cash";
            $log->status               = '1';
            $log->created_by           = auth()->user()->id;
            $log->save();
            $msg    = "Advance Expense Added.";
            $request->session()->flash('message', $msg);


            return redirect()->back()->with('status', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function advanceExpenseUpdate(Request $request, $id)
    {
        try {
            $petty_cash = PettyCash::where('user_id', auth()->user()->id)->where('given_date', '<=', date('Y-m-d'))->orderByDesc('id')->first();
            // if($petty_cash->remain_amount > $request->amount){
            $advance = AdvanceExpense::find($id);
            $fund = FundCurrentBalance::where('fund_id', 2)->where('status', 1)->where('company_id', session()->get('company_id'))->first();
            if ($fund) {
                // dd($request->amount);
                if ($request->amount != null && $advance->amount > (int) $request->amount) {
                    $fund->amount += ($advance->amount - $request->amount);
                } else if ($request->amount == null) {
                    $fund->amount -= $advance->amount;
                } else {
                    $fund->amount -= ($request->amount - $advance->amount);
                }
                $fund->update();
            } else {
                $fund = new FundCurrentBalance;
                $fund->fund_id      =  2;
                $fund->company_id   =  session()->get('company_id');
                $fund->amount       =  $request->amount;
                $fund->status       =  1;
                $fund->save();
            }

            $log = FundLog::where('transection_id', $advance->id)->where('transection_type', 'Advance-expense')->first();
            // dd($log);
            if ($log) {
                $log->amount               = $request->amount  ? $request->amount : 0;
                $log->update();
            }



            $advance->details = $request->details;
            $advance->amount = $request->amount != null ? $request->amount : 0;
            $advance->status = 1; // Advance Entry
            $advance->date          = $request->date;
            $advance->created_by = auth()->user()->id;

            $advance->update();
            $msg = "Data Updated.";
            $request->session()->flash('message', $msg);

            return redirect()->back()->with('status', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function advanceChequeAmount(Request $request)
    {
        try {
            $advance_id = AdvanceCheque::where('id', $request->advance_cheque_id)->first();
            $amount = $advance_id->amount;
            return response()->json($advance_id);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function advanceChequeBankInfo(Request $request)
    {
        try {
            $advance = AdvanceCheque::where('id', $request->advance_cheque_id)->first();
            return response()->json($advance);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function filterHead(Request $request)
    {
        $categoryId = $request->category_id;
        $category = AccountHead::where('category_id', $categoryId)->get();
        if (count($category) > 0) {
            return response()->json($category);
        }
    }
    public function filterAccount(Request $request)
    {
        $bank_id = $request->bank_id;
        $accounts = BankAccount::where('bank_id', $bank_id)->where('company_id', session()->get('company_id'))->get();
        if (count($accounts) > 0) {
            return response()->json($accounts);
        }
    }
    public function accountHolder(Request $request)
    {
        $account_id = $request->account_id;
        $account = BankAccount::where('id', $account_id)->first();
        $holder_name =  $account->account_holder_name;
        return response()->json($holder_name);
    }
    public function filterProject(Request $request)
    {
        $company_id = $request->company_id;
        $company = Project::where('company_id', $company_id)->get();
        if (count($company) > 0) {
            return response()->json($company);
        }
    }

    public function filterSubProject(Request $request)
    {
        $company_id = $request->company_id;
        $company = Subproject::where('company_id', $company_id)->get();
        if (count($company) > 0) {
            return response()->json($company);
        }
    }

    public function create()
    {
        try {
            $data['main_menu']                  = 'expense';
            $data['child_menu']                 = 'create-expense';
            $data['fund_data']                  = Fund::where(['status' => 1])->get();
            $data['categories']                 = AccountCategory::get();
            $data['head']                       = AccountHead::get();
            $data['company']                    = Company::get();
            $data['project']                    = Project::get();
            $data['items']                      = Item::get();
            $data['employee']                   = Employee::get();
            $data['user_type']                  = auth()->user()->role;
            $data['company_id'] = $company_id   = Session::get('company_id');
            $data['banks']                      = Bank::get();
            $data['bank_accounts']              = BankAccount::where('company_id', session()->get('company_id'))->get();
            $petty_cash = PettyCash::where('user_id', auth()->user()->id)->orderByDesc('id')->first();
            $expense_code = Expense::orderByDesc('id')->first();
            if ($expense_code) {
                $data['lastExpenseId'] = $expense_code->id;
            }
            $remain_amount  = 0;
            if ($petty_cash) {
                $remain_amount     =  $petty_cash->remain_amount;
            }
            $data['remain_amount'] =  $remain_amount;
            $data['company_project']       = Project::where('company_id', $company_id)->get();
            $data['sub_project']           = Subproject::where('company_id', $company_id)->get();


            $startDate = Carbon::now()->endOfMonth()->subDays(6)->startOfDay();
            $endDate = Carbon::now()->endOfMonth()->endOfDay();

            $pendingCheques = AdvanceCheque::where('status', 1)
                ->whereBetween('cheque_issue_date', [$startDate, $endDate])
                ->count();

            $null_cheque = AdvanceCheque::where('status', 1)
                ->where('cheque_issue_date', null)
                ->count();

            $data['pendingCheques'] = $pendingCheques + $null_cheque;

            if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin') {
                $data['advance_expenses']      = AdvanceExpense::where('status', 1)->where('company_id', Session::get('company_id'))->get();
                $data['advance_cheques']       = AdvanceCheque::whereIn('status', [1, 3])->where('company_id', Session::get('company_id'))->get();
            } else {
                $data['advance_expenses']      = AdvanceExpense::where('status', 1)->where('created_by', auth()->user()->id)->get();
                $data['advance_cheques']       = AdvanceCheque::whereIn('status', [1, 3])->where('created_by', auth()->user()->id)->get();
            }
            return view('account.expense.create', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function readyVoucher(Request $request)
    {
        try {
            // if($petty_cash){
            if ($request->amount) {
                $remain_amount = 0;
                $lastVoucher = Expense::latest()->first();
                $lastNumber = ($lastVoucher) ? $lastVoucher->id : 0;
                $date = now()->format('Y');
                $voucherNumber = "VHR-" . $date . ($lastNumber + 1);
                $other_company_id = 0;
                if (session()->get('company_id') != $request->company_id) {
                    $other_company_id = $request->company_id;
                }

                $category = '';
                $head = '';

                $model                      = new Expense();
                $model->company_id          = session()->get('company_id');
                $model->other_company_id    = $other_company_id;

                if ($request->category_name) {
                    $category_type = json_encode($request->category_type);
                    $category = new AccountCategory;
                    $category->category_name = $request->category_name;
                    $category->category_type = $category_type;
                    $category->save();
                    $model->category_id = $category->id;
                } else {
                    $model->category_id         = $request->category;
                }

                if ($request->head_name) {
                    $head = new AccountHead;
                    $head->category_id = $category->id;
                    $head->head_name = $request->head_name;
                    $head->save();
                    // dd($head);
                    $model->head_id = $head->id;
                } else {
                    $model->head_id             = $request->head;
                }

                $model->project_id          = $request->project;
                $model->code_no             = $request->code_no;
                $model->voucher_no          = $voucherNumber;
                $model->fund_id             = $request->fund_id;
                $model->expense_by          = $request->employee;
                $model->expenser_name       = $request->expenser_name;
                $model->department          = $request->department;
                $model->nid                 = $request->nid;
                $model->designation         = $request->designation;
                $model->expenser_mobile_no  = $request->expenser_mobile_no;
                $model->payment_date        = $request->payment_date;
                $model->payment_type        = $request->payment_type;
                $model->amount              = $request->amount;
                $model->mobile_no           = $request->mobile_no;
                $model->remarks             = $request->remarks;
                $model->advance_expense_id  = $request->advance;
                $model->advance_cheque_id   = $request->advance_cheque;
                if ($request->expense_type == 'requisition') {
                    $model->status = 0;
                } else {
                    $model->status = 1;
                }
                $model->created_by          = auth()->user()->id;
                $model->expense_type        = $request->expense_type;

                if ($request->attachment != null) {
                    $newImageName =  time() . '_expense.' . $request->attachment->extension();
                    $request->attachment->move(public_path('attachment'), $newImageName);
                    $model->attachment = $newImageName;
                }
                $model->save();

                $expense_id = $model->id;
                $bankArray = [];
                $bank_info  = $request->bank_id;
                if ($bank_info && $model->fund_id == 1) {
                    $totalBank = count($bank_info);
                    for ($i = 0; $i < $totalBank; $i++) {
                        $bank = new BankInfo();
                        $bank->bank_id             = $bank_info[$i];
                        $bank->account_id          = $request->account_id[$i];
                        $bank->account_holder      = $request->account_holder_name[$i];
                        $bank->note                = $request->payment_note[$i];
                        $bank->cheque_no           = $request->cheque_no[$i];
                        $bank->cheque_issue_date   = $request->cheque_issue_date[$i];
                        $bank->amount              = $request->bank_amount[$i];
                        $bankArray[] =  $bank;
                    }
                }
                if ($model->expense_type == 'requisition') {
                    if ($bankArray) {
                        foreach ($bankArray as $array) {
                            $bank = new ExpenseBankInfo();
                            $bank->master_id           = $expense_id;
                            $bank->bank_id             = $array->bank_id;
                            $bank->account_id          = $array->account_id;
                            $bank->account_holder      = $array->account_holder;
                            $bank->note                = $array->note;
                            $bank->cheque_no           = $array->cheque_no;
                            $bank->cheque_issue_date   = $array->cheque_issue_date;
                            $bank->amount              = $array->amount;
                            $bank->save();
                            $amount = 0;
                        }
                    }
                } else {
                    if ($bankArray) {
                        foreach ($bankArray as $array) {
                            $bank = new ExpenseBankInfo();
                            $bank->master_id           = $expense_id;
                            $bank->bank_id             = $array->bank_id;
                            $bank->account_id          = $array->account_id;
                            $bank->account_holder      = $array->account_holder;
                            $bank->note                = $array->note;
                            $bank->cheque_no           = $array->cheque_no;
                            $bank->cheque_issue_date   = $array->cheque_issue_date;
                            $bank->amount              = $array->amount;
                            $bank->save();
                            $amount = 0;

                            $bankAccount = BankAccount::where('id', $array->account_id)->where('company_id', session()->get('company_id'))->first();
                            if ($bankAccount) {
                                $amount = $bankAccount->current_balance - $array->amount;
                                $bankAccount->current_balance = $amount;
                                $bankAccount->update();
                                $bank_fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->where('bank_id', $array->bank_id)->first();
                                if ($bank_fund) {
                                    $bank_fund->amount -=  $array->amount;
                                    $bank_fund->update();
                                }
                            }
                        }
                        $log                        = new FundLog();
                        $log->transection_id       = $expense_id;
                        $log->company_id           = Session::get('company_id');
                        $log->fund_id              = $request->fund_id;
                        $log->type                 = '2';
                        $log->amount               = $request->amount;
                        $log->transection_type     = 'expense';
                        $log->transection_date     = $request->payment_date;
                        $log->payment_type         = $request->payment_type;
                        $log->status               = '1';
                        $log->created_by           = auth()->user()->id;
                        $log->save();
                    } else {
                        if ($model->expense_type == 'current') {
                            $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                            if ($fund != null) {
                                $fund->amount -=  $model->amount;
                                $fund->updated_by = auth()->user()->id;
                                $fund->update();
                            } else {
                                $fund_current_balance               = new FundCurrentBalance();
                                $fund_current_balance->fund_id      = $model->fund_id;
                                $fund_current_balance->company_id   = session()->get('company_id');
                                $fund_current_balance->amount       = -$model->amount;
                                $fund_current_balance->status       = '1';
                                $fund_current_balance->created_by   = auth()->user()->id;
                                $fund_current_balance->save();
                            }

                            $log                        = new FundLog();
                            $log->transection_id       = $expense_id;
                            $log->company_id           = Session::get('company_id');
                            $log->fund_id              = $request->fund_id;
                            $log->type                 = '2';
                            $log->amount               = $request->amount;
                            $log->transection_type     = 'expense';
                            $log->transection_date     = $request->payment_date;
                            $log->payment_type         = $request->payment_type;
                            $log->status               = '1';
                            $log->created_by           = auth()->user()->id;
                            $log->save();
                        }
                    }
                }


                $advance_id = $request->advance;
                $advance_cheque_id  = $request->advance_cheque;

                if ($advance_cheque_id != null) {
                    $cheque                 = AdvanceCheque::where('id', $advance_cheque_id)->first();
                    $cheque->status         = 2;
                    $cheque->expense_id     = $model->id;
                    $cheque->updated_by     = auth()->user()->id;
                    $cheque->update();
                }

                if ($advance_id != null) {
                    $advance = AdvanceExpense::where('id', $advance_id)->first();
                    if ($advance->amount > $model->amount) {
                        $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                        $fund->amount += ($advance->amount - $model->amount);
                        $fund->update();
                        $advance->amount = $model->amount;
                        $advance->expense_id = $model->id;
                        $advance->status = 2;
                        $advance->update();
                    } elseif ($advance->amount < $model->amount) {
                        $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                        $fund->amount += ($model->amount - $advance->amount);
                        $fund->update();
                        $advance->amount = $model->amount;
                        $advance->status = 2;
                        $advance->expense_id = $model->id;
                        $advance->update();
                    } else {
                        $advance->status = 2;
                        $advance->expense_id = $model->id;
                        $advance->update();
                    }
                }

                $msg = "Expense Inserted.";
                // $request->session()->flash('message', $msg);
            } else {
                $msg = "Expense Not Inserted.";
                // $request->session()->flash('warning', $msg);
            }

            return redirect()->route('create-expense')->with('status', $msg);
        } catch (\Exception $e) {
            dd($e);
            return $e->getMessage();
        }
    }
    public function store(Request $request)
    {
        try {
            $model              = Session::get('model');
            $bankArray          = Session::get('bankArray');
            $fund_log           = Session::get('fund_log');
            $advance_id         = Session::get('advance_id');
            $advance_cheque_id  = Session::get('advance_cheque_id');
            $category           = Session::get('category');
            $head               = Session::get('head');

            if ($model->amount) {
                $remain_amount = 0;
                $lastVoucher = Expense::latest()->first();
                $lastNumber = ($lastVoucher) ? $lastVoucher->id : 0;
                $date = now()->format('Y');
                $voucherNumber = "VHR-" . $date . $lastNumber + 1;
                $model->voucher_no = $voucherNumber;

                if ($head != "") {
                    if ($category != "") {
                        $category->save();
                        $model->category_id =  $category->id;
                        $head->category_id =  $category->id;
                    } else {
                        $head->category_id =  $model->category_id;
                    }
                    $head->save();
                    $model->head_id =  $head->id;
                }

                if ($model->expense_type == 'requisition') {
                    $model->status = 0; //Expense Requisition

                } else {
                    $model->status = 1;
                }

                $model->save();
                $expense_id = $model->id;
                if ($model->expense_type == 'requisition') {
                    if ($bankArray) {
                        foreach ($bankArray as $array) {
                            $bank = new ExpenseBankInfo();
                            $bank->master_id           = $expense_id;
                            $bank->bank_id             = $array->bank_id;
                            $bank->account_id          = $array->account_id;
                            $bank->account_holder      = $array->account_holder;
                            $bank->note                = $array->note;
                            $bank->cheque_no           = $array->cheque_no;
                            $bank->cheque_issue_date   = $array->cheque_issue_date;
                            $bank->amount              = $array->amount;
                            $bank->save();
                            $amount = 0;
                        }
                    }
                } else {
                    if ($bankArray) {
                        foreach ($bankArray as $array) {
                            $bank = new ExpenseBankInfo();
                            $bank->master_id           = $expense_id;
                            $bank->bank_id             = $array->bank_id;
                            $bank->account_id          = $array->account_id;
                            $bank->account_holder      = $array->account_holder;
                            $bank->note                = $array->note;
                            $bank->cheque_no           = $array->cheque_no;
                            $bank->cheque_issue_date   = $array->cheque_issue_date;
                            $bank->amount              = $array->amount;
                            $bank->save();
                            $amount = 0;

                            $bankAccount = BankAccount::where('id', $array->account_id)->where('company_id', session()->get('company_id'))->first();
                            if ($bankAccount) {
                                $amount = $bankAccount->current_balance - $array->amount;
                                $bankAccount->current_balance = $amount;
                                $bankAccount->update();
                                $bank_fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->where('bank_id', $array->bank_id)->first();
                                if ($bank_fund) {
                                    $bank_fund->amount -=  $array->amount;
                                    $bank_fund->update();
                                }
                            }
                        }
                        $log                        = new FundLog();
                        $log->transection_id       = $expense_id;
                        $log->company_id           = Session::get('company_id');
                        $log->fund_id              = $fund_log->fund_id;
                        $log->type                 = '2';
                        $log->amount               = $fund_log->amount;
                        $log->transection_type     = 'expense';
                        $log->transection_date     = $fund_log->transection_date;
                        $log->payment_type         = $fund_log->payment_type;
                        $log->status               = '1';
                        $log->created_by           = auth()->user()->id;
                        $log->save();
                    } else {
                        if ($model->expense_type == 'current') {
                            $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                            if ($fund != null) {
                                $fund->amount -=  $model->amount;
                                $fund->updated_by = auth()->user()->id;
                                $fund->update();
                            } else {
                                $fund_current_balance               = new FundCurrentBalance();
                                $fund_current_balance->fund_id      = $model->fund_id;
                                $fund_current_balance->company_id   = session()->get('company_id');
                                $fund_current_balance->amount       = -$model->amount;
                                $fund_current_balance->status       = '1';
                                $fund_current_balance->created_by   = auth()->user()->id;
                                $fund_current_balance->save();
                            }

                            $log                       = new FundLog();
                            $log->transection_id       = $expense_id;
                            $log->company_id           = Session::get('company_id');
                            $log->fund_id              = $fund_log->fund_id;
                            $log->type                 = '2';
                            $log->amount               = $fund_log->amount;
                            $log->transection_type     = 'expense';
                            $log->transection_date     = $fund_log->transection_date;
                            $log->payment_type         = $fund_log->payment_type;
                            $log->status               = '1';
                            $log->created_by           = auth()->user()->id;
                            $log->save();
                        }
                    }
                }

                if ($advance_cheque_id != null) {
                    $cheque                 = AdvanceCheque::where('id', $advance_cheque_id)->first();
                    $cheque->status         = 2;
                    $cheque->expense_id     = $model->id;
                    $cheque->updated_by     = auth()->user()->id;
                    $cheque->update();
                }

                if ($advance_id != null) {
                    $advance = AdvanceExpense::where('id', $advance_id)->first();
                    if ($advance->amount > $model->amount) {
                        $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                        $fund->amount += ($advance->amount - $model->amount);
                        $fund->update();
                        $advance->amount = $model->amount;
                        $advance->expense_id = $model->id;
                        $advance->status = 2;
                        $advance->update();
                    } elseif ($advance->amount < $model->amount) {
                        $fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                        $fund->amount += ($model->amount - $advance->amount);
                        $fund->update();
                        $advance->amount = $model->amount;
                        $advance->status = 2;
                        $advance->expense_id = $model->id;
                        $advance->update();
                    } else {
                        $advance->status = 2;
                        $advance->expense_id = $model->id;
                        $advance->update();
                    }
                }

                $msg = "Expense Inserted.";
                $request->session()->flash('message', $msg);
            } else {
                $msg = "Expense Not Inserted.";
                $request->session()->flash('warning', $msg);
            }

            return redirect('create-expense')->with('status', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function addAttachment(Request $request, $id)
    {
        try {
            $model = Expense::find($id);
            if ($request->attachment != null) {
                $newImageName =  time() . '_expense.' . $request->attachment->extension();
                $request->attachment->move(public_path('attachment'), $newImageName);
                $model->attachment = $newImageName;
            }
            $model->update();
            $msg = "Attachment Added Successfully.";
            return redirect('expense-list')->with('status', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function printDebitVoucher($id)
    {
        try {
            $model          = Expense::where('id', $id)->first();
            $receive_by     = $receive_by = auth()->user()->name;
            $company_info   = Company::where('id', $model->other_company_id != 0 ? $model->other_company_id : $model->company_id)->first();

            return view('account.expense.debit_voucher_print', compact('model', 'receive_by', 'company_info'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    function print(Request $request)
    {
        try {
            $data['title']                  = 'Expense List || ' . Session::get('company_name');
            $expense_data                   = Expense::where(['company_id' => Session::get('company_id')])->with('company');

            $where = array();
            if ($request->category) {
                $where['category_id'] = $request->category;
                $expense_data->where('category_id', '=', $request->category);
                // dd($data);
            }
            if ($request->head) {
                $where['head_id'] = $request->head;
                $expense_data->where('head_id', '=', $request->head);
            }
            if ($request->search) {
                $searchQuery = trim($request->query('search'));

                $expense_data->where('remarks', 'like', "%$searchQuery%")
                    ->orWhereHas('category', function ($query) use ($searchQuery) {
                        $query->where('category_name', 'like', "%$searchQuery%");
                    })
                    ->orWhereHas('head', function ($query) use ($searchQuery) {
                        $query->where('head_name', 'like', "%$searchQuery%");
                    });
            }
            if ($request->fund_id) {
                $where['fund_id'] = $request->fund_id;
                $expense_data->where('fund_id', '=', $request->fund_id);
            }
            if ($request->start_date) {
                $where['start_date'] = $request->start_date;
                $expense_data->whereDate('payment_date', '>=', $request->start_date);
            }
            if ($request->end_date) {
                $where['end_date'] = $request->end_date;
                $expense_data->whereDate('payment_date', '<=', $request->end_date);
            }

            $expense_data = $expense_data->where('status', 1)->get();
            $data['expense_data']             = $expense_data;
            // $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
            return view('account.expense.print_all_expense', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function editExpense($id)
    {
        $data['expense']                 = Expense::where('id', $id)->first();
        $data['main_menu']               = 'expense';
        $data['fund_data']               = Fund::where(['status' => 1])->get();
        $data['categories']              = AccountCategory::get();
        $data['head']                    = AccountHead::get();
        $data['company']                 = Company::get();
        $data['project']                 = Project::where('company_id', session()->get('company_id'))->get();
        $data['sub_project']             = Subproject::where('company_id', session()->get('company_id'))->get();
        $data['items']                   = Item::get();
        $data['banks']                   = Bank::get();
        $data['bank_accounts']           = BankAccount::where('company_id', session()->get('company_id'))->get();
        $data['employee']                = Employee::get();

        return view('account.expense.edit', $data);
    }
    public function updateExpense(Request $request, $id)
    {
        //   return $request->all();
        try {
            $model = Expense::findOrFail($id);

            if ($request->amount > 0) {

                if ($model->advance_expense_id != null) {
                    $advance_id = $model->advance_expense_id;
                    $advance = AdvanceExpense::where('id', $advance_id)->first();
                    if ($advance->amount > $request->amount) {
                        $advance->amount += ($advance->amount - $request->amount);
                        $advance->update();
                    } elseif ($advance->amount < $request->amount) {
                        $advance->amount -= ($request->amount - $advance->amount);
                        $advance->update();
                    } else {
                        $advance->status = 2;
                        $advance->expense_id = $model->id;
                        $advance->update();
                    }

                    $fund_log                       = FundLog::where(['transection_id' => $advance_id, 'transection_type' => 'Advance-expense'])->first();
                    $fund_log->amount               = $request->amount;
                    $fund_log->updated_by           = auth()->user()->id;
                    $fund_log->update();
                }
                $bankArray = $request->bank_id;
                $account  = $request->account_id;
                //   dd($bankArray);

                if ($model->expense_type == 'requisition') {
                    if($model->status == 0 && $model->forward_status == 3){
                        $model->status = 1;
                    }
                    if ($bankArray) {
                        for ($i = 0; $i < count($bankArray); $i++) {
                            $bank = ExpenseBankInfo::where('master_id', $model->id)->where('bank_id', $bankArray[$i])->where('account_id', $account[$i])->first();
                            // dd($bank);
                            if ($bank) {
                                $bank->amount              = $request->bank_amount[$i];
                                $bank->note                = $request->payment_note[$i];
                                $bank->cheque_no           = $request->cheque_no[$i];
                                $bank->cheque_issue_date   = $request->cheque_issue_date[$i];
                                $bank->update();
                            } else {
                                $bank = new ExpenseBankInfo();
                                $bank->master_id           = $model->id;
                                $bank->bank_id             = $bankArray[$i];
                                $bank->account_id          = $account[$i];
                                $bank->account_holder      = $request->account_holder_name[$i];
                                $bank->note                = $request->payment_note[$i];
                                $bank->cheque_no           = $request->cheque_no[$i];
                                $bank->cheque_issue_date   = $request->cheque_issue_date[$i];
                                $bank->amount              = $request->bank_amount[$i];
                                $bank->save();
                            }
                        }
                    }
                } else {
                    if ($bankArray) {
                        for ($i = 0; $i < count($bankArray); $i++) {
                            $bank = ExpenseBankInfo::where('master_id', $model->id)->where('bank_id', $bankArray[$i])->where('account_id', $account[$i])->first();
                            // dd($bank);
                            if ($bank) {
                                $bank->amount              = $request->bank_amount[$i];
                                $bank->note                = $request->payment_note[$i];
                                $bank->cheque_no           = $request->cheque_no[$i];
                                $bank->cheque_issue_date   = $request->cheque_issue_date[$i];
                                $bank->update();
                            } else {
                                $bank = new ExpenseBankInfo();
                                $bank->master_id           = $model->id;
                                $bank->bank_id             = $bankArray[$i];
                                $bank->account_id          = $account[$i];
                                $bank->account_holder      = $request->account_holder_name[$i];
                                $bank->note                = $request->payment_note[$i];
                                $bank->cheque_no           = $request->cheque_no[$i];
                                $bank->cheque_issue_date   = $request->cheque_issue_date[$i];
                                $bank->amount              = $request->bank_amount[$i];
                                $bank->save();
                            }

                            $bankAccount = BankAccount::where('id', $account[$i])->where('company_id', session()->get('company_id'))->first();

                            if ($bankAccount) {
                                if ($model->amount < $request->amount) {
                                    $bankAccount->current_balance -= ($request->amount - $model->amount);
                                } else {
                                    $bankAccount->current_balance += ($model->amount - $request->amount);
                                }
                                $bankAccount->update();
                                $bank_fund = FundCurrentBalance::where(['fund_id' => $request->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->where('bank_id', $bankArray[$i])->first();
                                if ($bank_fund) {
                                    if ($model->amount < $request->amount) {
                                        $bank_fund->amount -= ($request->amount - $model->amount);
                                    } else {
                                        $bank_fund->amount += ($model->amount - $request->amount);
                                    }
                                    $bank_fund->updated_by = auth()->user()->id;
                                    $bank_fund->update();
                                }
                            }
                        }
                        $log                        = FundLog::where(['transection_id' => $model->id, 'transection_type' => 'expense'])->first();
                        $log->amount               = $request->amount;
                        $log->update();
                    } else {
                        if ($model->expense_type == 'current') {
                            $fund = FundCurrentBalance::where(['fund_id' => $request->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                            if ($fund != null) {
                                if ($model->amount < $request->amount) {
                                    $fund->amount -= ($request->amount - $model->amount);
                                } else {
                                    $fund->amount += ($model->amount - $request->amount);
                                }
                                $fund->update();
                            } else {
                                $fund_current_balance               = new FundCurrentBalance();
                                $fund_current_balance->fund_id      = $request->fund_id;
                                $fund_current_balance->company_id   = session()->get('company_id');
                                $fund_current_balance->amount       = -$request->amount;
                                $fund_current_balance->status       = '1';
                                $fund_current_balance->created_by   = auth()->user()->id;
                                $fund_current_balance->save();
                            }
                            $log                        = FundLog::where(['transection_id' => $model->id, 'transection_type' => 'expense'])->first();
                            $log->amount               = $request->amount;
                            $log->update();

                            $model->status = 1;

                        }
                    }

                }

                $model->company_id          = session()->get('company_id');
                $model->other_company_id    = $request->company_id;
                $model->project_id          = $request->project;
                $model->code_no             = $request->code_no;
                $model->fund_id             = $request->fund_id;
                $model->expenser_name       = $request->expenser_name;
                $model->designation         = $request->designation;
                $model->payment_date        = $request->payment_date;
                $model->payment_type        = $request->payment_type;
                $model->amount              = $request->amount;
                $model->category_id         = $request->category;
                $model->head_id             = $request->head;
                $model->remarks             = $request->remarks;
                $model->updated_by          = auth()->user()->id;

                //   dd($model);
                $model->update();

                $msg = "Expense Updated.";
                $request->session()->flash('message', $msg);
            } else {
                $msg = "Expense Not Updated.";
                $request->session()->flash('message', $msg);
            }

            return redirect()->back()->with('status', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function expenseForwardStatus(Request $request, $id = '', $fwstatus = '')
    {
        $expense       = Expense::where('id', $id)->first();
        if ($fwstatus == 1) {
            $expense->forward_status = 1;
            $expense->update();
        } else if ($fwstatus == 2) {
            $expense->forward_status = 2;
            $expense->update();
        } else if ($fwstatus == 3) {
            $expense->forward_status = 3;
            $expense->update();
        } else {
            $expense->forward_status = 4;
            $expense->update();
        }

        $msg = "Approved Successfully.";
        return redirect()->back()->with('status', $msg);
    }

    public function expenseStatusUpdate(Request $request, $id = '', $status = '')
    {
        $expense       = Expense::where('id', $id)->first();
        $expense_banks = ExpenseBankInfo::where('master_id', $id)->get();

        if ($status == 1) {
            $expense->status = 1;
            $expense->expense_type == 'current';
            $expense->update();
            if ($expense_banks) {
                foreach ($expense_banks as $array) {
                    $bankAccount = BankAccount::where('id', $array->account_id)->where('company_id', session()->get('company_id'))->first();
                    if ($bankAccount) {
                        $amount = $bankAccount->current_balance - $array->amount;
                        $bankAccount->current_balance = $amount;
                        $bankAccount->update();
                        $bank_fund = FundCurrentBalance::where(['fund_id' => $expense->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->where('bank_id', $array->bank_id)->first();
                        if ($bank_fund) {
                            $bank_fund->amount -=  $array->amount;
                            $bank_fund->update();
                        }
                    }
                }
                $log                       = new FundLog();
                $log->transection_id       = $id;
                $log->company_id           = Session::get('company_id');
                $log->fund_id              = $expense->fund_id;
                $log->type                 = '2';
                $log->amount               = $expense->amount;
                $log->transection_type     = 'expense';
                $log->transection_date     = $expense->transection_date;
                $log->payment_type         = $expense->payment_type;
                $log->status               = '1';
                $log->created_by           = auth()->user()->id;
                $log->save();
            } else {
                $fund = FundCurrentBalance::where(['fund_id' => $expense->fund_id, 'status' => 1, 'company_id' => session()->get('company_id')])->first();
                if ($fund != null) {
                    $fund->amount -=  $expense->amount;
                    $fund->updated_by = auth()->user()->id;
                    $fund->update();
                } else {
                    $fund_current_balance               = new FundCurrentBalance();
                    $fund_current_balance->fund_id      = $expense->fund_id;
                    $fund_current_balance->company_id   = session()->get('company_id');
                    $fund_current_balance->amount       = -$expense->amount;
                    $fund_current_balance->status       = '1';
                    $fund_current_balance->created_by   = auth()->user()->id;
                    $fund_current_balance->save();
                }

                $log                       = new FundLog();
                $log->transection_id       = $expense->id;
                $log->company_id           = Session::get('company_id');
                $log->fund_id              = $expense->fund_id;
                $log->type                 = '2';
                $log->amount               = $expense->amount;
                $log->transection_type     = 'expense';
                $log->transection_date     = $expense->transection_date;
                $log->payment_type         = $expense->payment_type;
                $log->status               = '1';
                $log->created_by           = auth()->user()->id;
                $log->save();
            }

            $msg = "Confirmed Successfully.";
            return redirect('requisition-list')->with('status', $msg);
        }

        if ($status == 2) {
            $expense->status = 2;
            $expense->rejected_by = auth()->user()->id;
            $expense->rejected_date = date('Y-m-d');
            $expense->update();
            $msg = "Rejected.";

            return redirect('requisition-list')->with('status', $msg);
        }
    }

    public function statusUpdate($id)
    {
        $expense = Expense::find($id);
        if ($expense) {
            $log                        = FundLog::where(['transection_id' => $id, 'transection_type' => 'expense'])->first();
            if ($log) {
                $log->status = 2;
                $log->update();
            }
            $banks = ExpenseBankInfo::where('master_id', $id)->get();
            // dd($banks);

            if (count($banks) > 0) {
                // dd('dfgdfgdfg');
                foreach ($banks as $v_bank) {
                    //Find the Expense Bank Info
                    $bankExpense = ExpenseBankInfo::where('master_id', $expense->id)->where('bank_id', $v_bank->bank_id)->where('account_id', $v_bank->account_id)->first();
                    //Find the Expense Bank Account
                    $bankAccount = BankAccount::where('id', $v_bank->account_id)->where('company_id', session()->get('company_id'))->first();
                    //Find the Fund
                    $bankFund    = FundCurrentBalance::where(['bank_id' => $v_bank->bank_id, 'fund_id' => $expense->fund_id])->where('company_id', session()->get('company_id'))->first();

                    if ($bankAccount &&  $bankFund) {
                        $bankAccount->current_balance += $v_bank->amount;
                        $bankAccount->update(); //Update Account Amount

                        $bankFund->amount +=  $v_bank->amount;
                        $bankFund->update(); //Update Fund Amount

                    }
                    $bankExpense->amount = 0;
                    $bankExpense->update(); //Update Bank Expense Info
                }
            } else if ($expense->expense_type == 'adjustment') {
                $expense->status = 2;
                $expense->save();
            } else {
                // dd('dsf');
                $fund_balance    = FundCurrentBalance::where(['fund_id' => $expense->fund_id])->where('company_id', session()->get('company_id'))->first();
                $fund_balance->amount += $expense->amount;
                $fund_balance->update();
            }
            $expense->status = 2;
            $expense->save();
        }
        $msg = "Data Removed Successfully ....";
        return redirect()->back()->with('status', $msg);
    }


    public function allSiteExpenseList(Request $request)
    {
        $data['main_menu']                   = 'expense';
        $data['child_menu']                  = 'site-expense-list';
        $expense_data                        = SiteExpense::where('company_id', Session::get('company_id'))->where('status', 1)->orderBy('payment_date', 'desc');

        if ($request->category || $request->head || $request->start_date || $request->end_date) {

            if ($request->category) {
                $where['category_id'] = $request->category;
                $expense_data->where('main_head_id', '=', $request->category);
                $data['serachcategoryId'] = $request->category;

                // dd($expense_data);
            }
            if ($request->head) {
                $where['head_id'] = $request->head;
                $expense_data->where('sub_head_id', '=', $request->head);
                $data['serachHeadId'] = $request->head;
            }

            if ($request->start_date || $request->end_date) {
                $where['start_date'] = $request->start_date;
                $expense_data->whereDate('payment_date', '>=', $request->start_date);
                $where['end_date'] = $request->end_date;
                $expense_data->whereDate('payment_date', '<=', $request->end_date);
                $expense_data->orderBy('payment_date', 'ASC');
            }
        }
        $expense_data                        = $expense_data->get();
        $data['expense']                     = $expense_data;
        $data['projects']                    = Project::where('company_id', Session::get('company_id'))->get();
        $data['categories']                  = AccountCategory::where('only_head_office', 0)->get();
        $data['head']                        = AccountHead::where('only_head_office', 0)->get();
        // dd( $data['expenses']);
        return view('account.expense.all_site_expense', $data);
    }
}
