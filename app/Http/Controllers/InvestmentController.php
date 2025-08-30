<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Fund;
use App\Models\Income;
use App\Models\Company;
use App\Models\FundLog;
use App\Models\Investment;
use App\Models\AccountHead;
use App\Models\BankAccount;
use App\Models\ReturnInvest;
use Illuminate\Http\Request;
use App\Models\AccountCategory;
use App\Models\ReceivableInvest;
use App\Models\FundCurrentBalance;
use App\Models\MissedMonthConsumer;
use Illuminate\Support\Facades\Session;
use App\Models\CollectMissedMonthConsumer;
use App\Models\ConsumerInvestorCollection;
use App\Models\IncomeDetails;
use PHPUnit\Framework\MockObject\Stub\ReturnStub;

class InvestmentController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        $data['main_menu']      = 'investment';
        $data['child_menu']     = 'investment_list';
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $investments            = Investment::with('bank', 'account')->where('company_id', Session::get('company_id'));
        $where = array();

        if ($request->consumer_name) {
            $where['consumer_name'] = $request->consumer_name;
            $investments = $investments->where('consumer_name', 'LIKE', '%' . $request->consumer_name . '%');
        }

        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $investments = $investments->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $investments = $investments->whereDate('created_at', '<=', $request->end_date);
        }

        $investments = $investments->orderBy('id', 'desc')->paginate(20);
        $investments->appends($where);
        $data['investments'] = $investments;

        return view('account.investment.index', $data);
    }

    public function create_investment()
    {
        $data['main_menu']           = 'investment';
        $data['child_menu']          = 'investment_list';
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['categories']          = AccountCategory::get();
        // dd($data['categories']);
        $data['head']                = AccountHead::get();
        $data['fund_types']          = Fund::all();
        $data['companies']           = Company::where('status', 1)->get();
        $data['banks']               = Bank::get();
        // dd($data['banks']);
        $data['accounts']            = BankAccount::where('company_id', Session::get('company_id'))->get();
        $invest_code                 = Investment::where('company_id', Session::get('company_id'))->orderByDesc('id')->first();
        if ($invest_code) {
            $data['lastInvestId'] = $invest_code->id;
        }
        return view('account.investment.create', $data);
    }

    public function store(Request $request)
    {
        $category = '';
        $head = '';

        $model                      = new Investment();
        $model->company_id          = session()->get('company_id');

        if ($request->category_name) {
            // dd($request->category_type);
            $category_type = json_encode($request->category_type);
            $category = new AccountCategory;
            $category->category_name = $request->category_name;
            $category->category_type = $category_type;
        } else {
            $model->category_id         = $request->category;
        }

        if ($request->head_name) {
            $head = new AccountHead;
            $head->head_name = $request->head_name;
        } else {
            $model->head_id             = $request->head;
        }
        $model->invest_date         = $request->invest_date;
        $model->invest_code         = $request->invest_code;
        $model->note                = $request->note;
        $model->consumer_name       = $request->consumer_name;
        $model->address             = $request->address;
        $model->phone               = $request->phone;
        $model->email               = $request->email;
        $model->invest_amount       = $request->invest_amount;
        $model->tentative_collection_date = $request->tentative_collection_date;
        $model->invest_length       = $request->invest_length;
        $model->created_by          = auth()->user()->id;
        if ($request->attachment != null) {
            $newImageName = time() . '_check.' . $request->attachment->extension();
            $request->attachment->move(public_path('attachment'), $newImageName);
            $model->attachment = $newImageName;
        }
        if ($request->nid != null) {
            $newNidName = time() . '_consumer_nid.' . $request->nid->extension();
            $request->nid->move(public_path('nid'), $newNidName);
            $model->nid = $newNidName;
        }

        if ($model->invest_amount) {
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
            $model->save();

            $msg = "Consumer Investor Details Inserted.";
        } else {
            $msg = "Details Insert Failed.";
        }
        return redirect('investment-list')->with('status', $msg);
    }

    public function edit($id)
    {
        $data['main_menu']           = 'investment';
        $data['child_menu']          = 'investment_list';
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['categories']          = AccountCategory::get();
        $data['head']                = AccountHead::get();
        $data['fund_types']          = Fund::all();
        $data['companies']           = Company::where('status', 1)->get();
        $data['banks']               = Bank::get();
        $data['accounts']            = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['consumer']            = Investment::find($id);
        // dd($data['consumer']);
        return view('account.investment.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $model = Investment::findOrFail($id);

        if ($request->hasFile('nid')) {
            $nid =  'nid.' . $request->nid->extension();
            $request->nid->move(public_path('nid'), $nid);
            $model->nid = $nid;
        } elseif ($request->has('remove_image')) {
            $model->nid = null;
        }

        if ($request->hasFile('attachment')) {
            $attachment = 'attachment.' . $request->attachment->extension();
            $request->attachment->move(public_path('attachment'), $attachment);
            $model->attachment = $attachment;
        } elseif ($request->has('remove_image')) {
            $model->attachment = null;
        }

        $model->category_id         = $request->category;
        $model->head_id             = $request->head;
        $model->company_id          = session()->get('company_id');
        $model->invest_date         = $request->invest_date;
        $model->note                = $request->note;
        $model->consumer_name       = $request->consumer_name;
        $model->address             = $request->address;
        $model->phone               = $request->phone;
        $model->email               = $request->email;
        $model->invest_amount       = $request->invest_amount;
        $model->tentative_collection_date = $request->tentative_collection_date;
        $model->invest_length       = $request->invest_length;
        $model->updated_by = auth()->user()->id;
        
        $model->update();

        $msg = "Consumer Investor Updated.";
        return redirect()->route('investment_list')->with('status', $msg);
    }

    public function printInvestDebitVoucher($id)
    {
        $model          = Investment::where('id', $id)->first();
        $receive_by     = $receive_by = auth()->user()->name;
        return view('account.investment.investment_debit_voucher_print', compact('model', 'receive_by'));
    }

    public function print(Request $request)
    {
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['banks']               = Bank::get();
        $data['accounts']            = BankAccount::where('company_id', Session::get('company_id'))->get();
        $investments                 = Investment::with('bank', 'account')->where('company_id', Session::get('company_id'))->where(['status' => 1]);
        $where = array();

        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $investments = $investments->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $investments = $investments->where('bank_id', '=', $request->bank_id);
        }
        if ($request->client_name) {
            $where['client_name'] = $request->client_name;
            $investments = $investments->where('client_name', '=', $request->client_name);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $investments = $investments->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $investments = $investments->whereDate('created_at', '<=', $request->end_date);
        }

        $investments = $investments->orderBy('id', 'desc')->get();
        $data['investments'] = $investments;

        return view('account.investment.print', $data);
    }

    public function pdf(Request $request)
    {
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['banks']               = Bank::where('company_id', Session::get('company_id'))->get();
        $data['accounts']            = BankAccount::where('company_id', Session::get('company_id'))->get();
        $investments                 = Investment::with('bank', 'account')->where('company_id', Session::get('company_id'))->where(['status' => 1]);
        $where = array();
        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $investments = $investments->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $investments = $investments->where('bank_id', '=', $request->bank_id);
        }
        if ($request->client_name) {
            $where['client_name'] = $request->client_name;
            $investments = $investments->where('client_name', '=', $request->client_name);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $investments = $investments->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $investments = $investments->whereDate('created_at', '<=', $request->end_date);
        }

        $investments = $investments->orderBy('id', 'desc')->get();
        $data['investments'] = $investments;

        $pdf = PDF::loadView('account.investment.print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('investment_' . $string . '.pdf');
    }

    //Receivable Investment List
    public function receivable_invest_index(Request $request)
    {
        $data['main_menu']      = 'investment';
        $data['child_menu']     = 'receivable-invest-list';
        $data['invests']        = Investment::where(['status' => 1])->get();
        $data['banks']          = Bank::get();
        $investments            = ReceivableInvest::with('fund', 'bank', 'invest')->where('company_id', Session::get('company_id'));
        $where = array();
        if ($request->invest_id) {
            $where['invest_id'] = $request->invest_id;
            $investments = $investments->where('invest_id', '=', $request->invest_id);
        }
        $investments = $investments->orderBy('id', 'desc')->paginate(20);
        $investments->appends($where);
        $data['receivable_invests'] = $investments;

        return view('account.investment.receivable_invest', $data);
    }

    function receivable_invest_log($invest_id)
    {
        $data['main_menu']              = 'investment';
        $data['child_menu']             = 'receivable-invest-list';
        $data['company_name']        = Session::get('company_name');
        $data['invest']                 = ReceivableInvest::with('company', 'fund', 'bank', 'invest')->where(['company_id' => Session::get('company_id'), 'invest_id' => $invest_id])->first();
        // dd($data['loan']);t
        return view('account.investment.receivable_invest_log', $data);
    }

    public function receivable_invest_print(Request $request)
    {
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['invests']        = Investment::where(['status' => 1])->get();
        $data['banks']          = Bank::get();
        $receivable_invest      = ReceivableInvest::with('fund', 'bank', 'invest')->where('company_id', Session::get('company_id'));
        $where = array();

        if ($request->invest_id) {
            $where['invest_id'] = $request->invest_id;
            $receivable_invest = $receivable_invest->where('invest_id', '=', $request->invest_id);
        }

        $receivable_invest = $receivable_invest->orderBy('id', 'desc')->get();
        $data['receivable_invests'] = $receivable_invest;

        return view('account.investment.receivable_invest_print', $data);
    }

    //Invest Collection
    public function collect_invest_index(Request $request)
    {
        $data['main_menu']      = 'investment';
        $data['child_menu']     = 'collect-invest-list';
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['invests']        = Investment::where('company_id', Session::get('company_id'))->where(['status' => 1])->get();

        $collect_invest = CollectMissedMonthConsumer::where('type', 'collect')->where('company_id', Session::get('company_id'))->with('collection');
        $where = array();
        if ($request->consumer_investor_id) {
            $where['consumer_investor_id'] = $request->consumer_investor_id;
            $collect_invest = $collect_invest->whereHas('collection', function ($query) use ($request) {
                $query->where('consumer_investor_id', '=', $request->consumer_investor_id);
            });
        }

        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $collect_invest = $collect_invest->whereHas('collection', function ($query) use ($request) {
                $query->where('fund_id', '=', $request->fund_id);
            });
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $collect_invest = $collect_invest->whereHas('collection', function ($query) use ($request) {
                $query->where('bank_id', '=', $request->bank_id);
            });
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $collect_invest = $collect_invest->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $collect_invest = $collect_invest->whereDate('created_at', '<=', $request->end_date);
        }

        $collect_invest = $collect_invest->where('type', 'collect')->orderBy('id', 'desc')->paginate(20);
        $collect_invest->appends($where);
        $data['collect_invest'] = $collect_invest;

        return view('account.investment.collect_invest_index', $data);
    }

    public function collect_invest_create()
    {
        $data['main_menu']           = 'investment';
        $data['child_menu']          = 'collect-invest-list';
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['companies']           = Company::where('status', 1)->get();
        $data['banks']               = Bank::get();
        $data['accounts']            = BankAccount::where('company_id', Session::get('company_id'))->get();

        $investments = Investment::where('company_id', Session::get('company_id'))
            ->where('status', 1)
            ->get();

        // $filteredInvestments = [];

        // foreach ($investments as $invest) {
        //     $hasMissingMonth = false;
        //     $currentMonth = Carbon::now()->startOfMonth();
        //     $startMonth = Carbon::parse($invest->created_at)->startOfMonth();
        //     $month = $startMonth;

        //     while ($month->lessThanOrEqualTo($currentMonth)) {
        //         $exists = ConsumerInvestorCollection::where('consumer_investor_id', $invest->id)
        //             ->whereYear('date', $month->year)
        //             ->whereMonth('date', $month->month)
        //             ->exists();

        //         if (!$exists) {
        //             $hasMissingMonth = true;
        //             break;
        //         }

        //         $month->addMonth();
        //     }

        //     if ($hasMissingMonth) {
        //         $filteredInvestments[] = $invest;
        //     }
        // }

        $data['invests'] = $investments;
        $collect_code = ConsumerInvestorCollection::where('company_id', Session::get('company_id'))->orderByDesc('id')->first();
        if ($collect_code) {
            $data['lastCollectId'] = $collect_code->id;
        }

        return view('account.investment.collect_invest_create', $data);
    }

    // public function getInvestAmount($investId)
    // {
    //     $invest_id = Investment::find($investId);
    //     // dd($invest_id);
    //     if ($invest_id) {
    //         return response()->json(['invest_amount' => $invest_id->invest_amount, 'due_amount' => $invest_id->due_amount]);
    //     } else {
    //         return response()->json(['error' => 'Invest not found'], 404);
    //     }
    // }

    public function getDueAmountMonthWise($investId)
    {
        $invest_id = Investment::find($investId);
        $collect_amount = ConsumerInvestorCollection::where('consumer_investor_id', $investId)->get();
        // dd($invest_id);
        if ($invest_id) {
            return response()->json(['invest_amount' => $invest_id->invest_amount, 'due_amount' => $invest_id->due_amount]);
        } else {
            return response()->json(['error' => 'Invest not found'], 404);
        }



        // $invest_id = Investment::find($investId);

        // $collect_amount = ConsumerInvestorCollection::where('consumer_investor_id', $investId)
        //     ->where('month', $collectionMonth)
        //     ->first();

        // if (is_null($collect_amount) && $invest_id->due_amount != null) {
        //     $collect_amount = min($invest_id->due_amount, $invest_id->invest_amount);

        //     $data = [
        //         'invest_amount' => $invest_id->invest_amount,
        //         'due_amount' => $invest_id->due_amount - $collect_amount
        //     ];

        //     return response()->json($data);
        // }

        // return response()->json([
        //     'invest_amount' => $invest_id->invest_amount,
        //     'due_amount' => $invest_id->due_amount
        // ]);
    }



    public function store_collect_invest(Request $request)
    {
        // dd($request->all());
        $lastVoucher = ConsumerInvestorCollection::where('company_id', Session::get('company_id'))->latest()->first();
        $lastNumber = ($lastVoucher) ? $lastVoucher->id : 0;
        $voucherNumber = "COLLECT-" . ($lastNumber + 1);

        $model = new ConsumerInvestorCollection();
        $model->consumer_investor_id = $request->consumer_investor_id;
        $model->company_id = Session::get('company_id');
        $model->collect_code = $request->collect_code;
        $model->fund_id = $request->fund_id;
        $model->bank_id = $request->bank_id;
        $model->account_id = $request->account_id;
        $model->cheque_no = $request->cheque_no;
        $model->check_issue_date = $request->check_issue_date;
        $model->payment_type = $request->payment_type;
        $model->voucher_no = $voucherNumber;
        $model->date = $request->date;
        $model->note = $request->note;
        $model->save();


        $investment = Investment::where('id', $model->consumer_investor_id)->first();

        $totalCollectedAmount = array_sum($request->collect_amount);

        $lastIncomeVoucher = Income::latest()->first();
        $lastIncomeNumber = ($lastIncomeVoucher) ? $lastIncomeVoucher->id : 0;
        $incomeVoucherNumber = "INC-" . date('Y') . $lastIncomeNumber + 1;
        $collectIncomeCode = "INC-" . $lastIncomeNumber + 1;

        $otherCompanyId = session()->get('company_id') != $request->company ? $request->company : 0;

        $income                       = new Income();
        $income->company_id           = Session::get('company_id');
        $income->other_company_id     = $otherCompanyId;
        $income->category_id          = $investment->category_id;
        $income->head_id              = $investment->head_id;
        $income->client_name          = $investment->consumer_name;
        $income->code_no              = $collectIncomeCode;
        $income->voucher_no           = $incomeVoucherNumber;
        $income->payment_date         = $request->date;
        // $income->income_type          = 'consumer_investment';
        $income->amount               = $totalCollectedAmount;
        $income->status = '1';
        $income->created_by = auth()->user()->id;
        $income->save();


        if ($investment) {

            $allCollection = CollectMissedMonthConsumer::where('consumer_investor_id', $model->consumer_investor_id)
                ->get();
            // dd($allCollection);

            if ($allCollection->isNotEmpty()) {
                $collectedMonths = $allCollection->pluck('collect_month')->toArray();
                $dueMonths = $allCollection->pluck('missed_month')->toArray();
                // dd($dueMonths);

                $allCollectedAndDueMonths = array_merge($collectedMonths, $dueMonths);
                $allCollectedAndDueMonths = collect($allCollectedAndDueMonths)->map(function ($month) {
                    return Carbon::parse($month)->startOfMonth();
                })->unique()->sort();

                $investStartMonth = Carbon::parse($investment->invest_date)->startOfMonth();
                $collectMonths = collect($request->collect_month)->map(function ($month) {
                    return Carbon::parse($month)->startOfMonth();
                })->sort();
                $investmentEndMonth = $collectMonths->last()->startOfMonth();

                $totalCollectedAmount = array_sum($request->collect_amount);

                $allMonthsInRange = collect();
                $month = $investStartMonth->copy();
                while ($month->lte($investmentEndMonth)) {
                    $allMonthsInRange->push($month->copy());
                    $month->addMonth();
                }

                $missedMonths = $allMonthsInRange->diff($allCollectedAndDueMonths)->diff($collectMonths)->map(function ($month) {
                    return $month->format('Y-m');
                });

                $investment->total_collected_amount += $totalCollectedAmount;
                $investment->due_amount += ($missedMonths->count() * $investment->invest_amount);


                foreach ($collectMonths as $index => $collectMonth) {
                    $formattedCollectMonth = $collectMonth->format('Y-m');

                    if (in_array($formattedCollectMonth, $dueMonths)) {
                        // dd('dfgdfrg');
                        $missedMonthConsumer = CollectMissedMonthConsumer::where('consumer_investor_id', $request->consumer_investor_id)
                            ->where('missed_month', $formattedCollectMonth)
                            ->first();
                        // dd($missedMonthConsumer);

                        if ($missedMonthConsumer) {
                            $collectAmount = $request->collect_amount[$index] ?? 0;

                            $missedMonthConsumer->type = 'collect';
                            $missedMonthConsumer->collect_amount = $collectAmount;
                            $missedMonthConsumer->collect_month = $missedMonthConsumer->missed_month;
                            $missedMonthConsumer->due_amount = Null;
                            $missedMonthConsumer->missed_month = Null;
                            $missedMonthConsumer->remarks = $request->remarks[$index];
                            $missedMonthConsumer->update();

                            $investment->due_amount -= $collectAmount;
                        }
                    } else {
                        // dd('fgyhfyt');
                        $collectAmount = $request->collect_amount[$index] ?? 0;

                        $collectedMonthConsumer = new CollectMissedMonthConsumer();
                        $collectedMonthConsumer->consumer_investor_id = $request->consumer_investor_id;
                        $collectedMonthConsumer->consumer_collection_id = $model->id;
                        $collectedMonthConsumer->collect_amount = $collectAmount;
                        $collectedMonthConsumer->collect_month = $collectMonth->format('Y-m');
                        $collectedMonthConsumer->remarks = $request->remarks[$index];
                        $collectedMonthConsumer->company_id = session()->get('company_id');
                        $collectedMonthConsumer->type = 'collect';
                        $collectedMonthConsumer->save();
                    }
                }
                $investment->save();

                foreach ($missedMonths as $missedMonth) {
                    $missedMonthConsumer = new CollectMissedMonthConsumer();
                    $missedMonthConsumer->consumer_investor_id = $request->consumer_investor_id;
                    $missedMonthConsumer->consumer_collection_id = $model->id;
                    $missedMonthConsumer->due_amount = $investment->invest_amount;
                    $missedMonthConsumer->missed_month = $missedMonth;
                    $missedMonthConsumer->company_id = session()->get('company_id');
                    $missedMonthConsumer->type = 'miss';
                    $missedMonthConsumer->save();
                }
            } else {
                $investStartMonth = Carbon::parse($investment->invest_date)->startOfMonth();

                $collectMonths = collect($request->collect_month)->map(function ($month) {
                    return Carbon::parse($month)->startOfMonth();
                })->sort();

                $investmentEndMonth = collect($collectMonths)->last()->startOfMonth();

                $totalCollectedAmount = array_sum($request->collect_amount);

                $allMonthsInRange = collect();
                $month = $investStartMonth->copy();
                while ($month->lte($investmentEndMonth)) {
                    $allMonthsInRange->push($month->copy());
                    $month->addMonth();
                }

                $missedMonths = $allMonthsInRange->diff($collectMonths)->map(function ($month) {
                    return $month->format('Y-m');
                });
                // dd($missedMonths);

                $investment->total_collected_amount += $totalCollectedAmount;
                $investment->due_amount += ($missedMonths->count() * $investment->invest_amount);
                $investment->save();

                foreach ($missedMonths as $missedMonth) {
                    $missedMonthConsumer = new CollectMissedMonthConsumer();
                    $missedMonthConsumer->consumer_investor_id = $request->consumer_investor_id;
                    $missedMonthConsumer->consumer_collection_id = $model->id;
                    $missedMonthConsumer->due_amount = $investment->invest_amount;
                    $missedMonthConsumer->missed_month = $missedMonth;
                    $missedMonthConsumer->company_id = session()->get('company_id');
                    $missedMonthConsumer->type = 'miss';
                    $missedMonthConsumer->save();
                }

                foreach ($collectMonths as $index => $collectMonth) {
                    $collectAmount = $request->collect_amount[$index] ?? 0;

                    $collectedMonthConsumer = new CollectMissedMonthConsumer();
                    $collectedMonthConsumer->consumer_investor_id = $request->consumer_investor_id;
                    $collectedMonthConsumer->consumer_collection_id = $model->id;
                    $collectedMonthConsumer->collect_amount = $collectAmount;
                    $collectedMonthConsumer->collect_month = $collectMonth->format('Y-m');
                    $collectedMonthConsumer->remarks = $request->remarks[$index];
                    $collectedMonthConsumer->company_id = session()->get('company_id');
                    $collectedMonthConsumer->type = 'collect';
                    $collectedMonthConsumer->save();
                }
            }


            foreach ($collectMonths as $index => $collectMonth) {
                $collectAmount = $request->collect_amount[$index] ?? 0;

                $incomeDetails = new IncomeDetails();
                $incomeDetails->fund_id = $request->fund_id;
                $incomeDetails->bank_id = $request->bank_id;
                $incomeDetails->cheque_no = $request->cheque_no;
                $incomeDetails->cheque_issue_date = $request->cheque_issue_date;
                $incomeDetails->account_id = $request->account_id;
                $incomeDetails->amount = $collectAmount;
                $incomeDetails->payment_type = $request->payment_type;
                $incomeDetails->remarks = $request->remarks[$index];
                $incomeDetails->income_id = $income->id;
                $incomeDetails->status = '1';
                $incomeDetails->created_by = auth()->user()->id;
                $incomeDetails->save();
            }


            if ($request->collect_amount) {
                $fund_log = new FundLog();
                $fund_log->company_id = Session::get('company_id');
                $fund_log->fund_id = $request->fund_id;
                $fund_log->type = '1';
                $fund_log->amount =  array_sum($request->collect_amount);
                $fund_log->transection_type = 'collect_consumer_invest';
                $fund_log->transection_date = $request->date;
                $fund_log->status = '1';
                $fund_log->created_by = auth()->user()->id;
                $fund_log->save();

                $bankAccount = BankAccount::where('id', $model->account_id)->first();
                if ($bankAccount) {
                    $bankAccount->current_balance += (float)array_sum($request->collect_amount);
                    $bankAccount->update();

                    $bank_fund = FundCurrentBalance::where(['fund_id' => $model->fund_id, 'status' => 1])
                        ->where('bank_id', $model->bank_id)
                        ->first();

                    if ($bank_fund) {
                        $bank_fund->amount +=  array_sum($request->collect_amount);
                        $bank_fund->updated_by = auth()->user()->id;
                        $bank_fund->update();
                    } else {
                        $fund_current_balance = new FundCurrentBalance();
                        $fund_current_balance->fund_id = $model->fund_id;
                        $fund_current_balance->bank_id = $model->bank_id;
                        $fund_current_balance->company_id = Session::get('company_id');
                        $fund_current_balance->amount =  array_sum($request->collect_amount);
                        $fund_current_balance->status = '1';
                        $fund_current_balance->created_by = auth()->user()->id;
                        $fund_current_balance->save();
                    }
                } else {
                    $fund = FundCurrentBalance::where([
                        'fund_id' => $model->fund_id,
                        'company_id' => Session::get('company_id'),
                        'status' => 1,
                    ])->first();

                    if ($fund) {
                        $fund->amount +=  array_sum($request->collect_amount);
                        $fund->updated_by = auth()->user()->id;
                        $fund->update();
                    } else {
                        $fund_current_balance = new FundCurrentBalance();
                        $fund_current_balance->fund_id = $model->fund_id;
                        $fund_current_balance->company_id = Session::get('company_id');
                        $fund_current_balance->amount =  array_sum($request->collect_amount);
                        $fund_current_balance->status = '1';
                        $fund_current_balance->created_by = auth()->user()->id;
                        $fund_current_balance->save();
                    }
                }

                $msg = "Invest Collected.";
                return redirect('collect-invest-list')->with('status', $msg);
            } else {
                $msg = "Invest collection failed.";
                return redirect()->back()->with('warning', $msg);
            }
        }
    }

    public function printCollectVoucher($id)
    {
        try {
            $model          = CollectMissedMonthConsumer::where('company_id', Session::get('company_id'))->with('collection')->where('id', $id)->first();
            $company_info   = Company::where('id',$model->company_id)->first();
            return view('account.investment.collect_credit_voucher', compact('model', 'company_info'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function investor_money_receipt($id)
    {
        $invest_Amount = CollectMissedMonthConsumer::where('company_id', Session::get('company_id'))->with('collection')->where('id', $id)->first();
        // dd($invest_Amount);
        return view('account.investment.invest_money_receipt', compact('invest_Amount'));
    }

    public function collect_invest_print(Request $request)
    {
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['invests']        = Investment::where('company_id', Session::get('company_id'))->where(['status' => 1])->get();
        $collect_invest         = ConsumerInvestorCollection::where('company_id', Session::get('company_id'))->with('consumer', 'bank', 'fund', 'account');
        $where = array();
        if ($request->consumer_investor_id) {
            $where['consumer_investor_id'] = $request->consumer_investor_id;
            $collect_invest = $collect_invest->where('consumer_investor_id', '=', $request->consumer_investor_id);
        }
        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $collect_invest = $collect_invest->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $collect_invest = $collect_invest->where('bank_id', '=', $request->bank_id);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $collect_invest = $collect_invest->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $collect_invest = $collect_invest->whereDate('created_at', '<=', $request->end_date);
        }
        $collect_invest = $collect_invest->orderBy('id', 'desc')->get();
        $data['collect_invest'] = $collect_invest;

        return view('account.investment.collect_invest_print', $data);
    }

    //report

    public function investment_report_index()
    {

        $data['main_menu']           = 'investment';
        $data['child_menu']          = 'investment_report';
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['company_name']        = Session::get('company_name');
        $data['banks']               = Bank::where('company_id', Session::get('company_id'))->get();
        $data['invests']             = Investment::with('collect_invest')->where('company_id', Session::get('company_id'))->where(['status' => 1])->get();
        // dd($data['invests']);
        return view('account.investment.investment_report.investment_report', $data);
    }

    public function investmentReportList(Request $request)
    {
        $consumer_id = $request->consumer_name;
        $company_name = Session::get('company_name');

        $data['funds'] = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types'] = Fund::all();
        $data['company_name'] = $company_name;
        $data['consumer_name'] = Investment::where('id', $consumer_id)->select('consumer_name')->first();
        // dd($data['consumer_name']);
        $data['banks'] = Bank::where('company_id', Session::get('company_id'))->get();
        $data['invests'] = Investment::with('collect_invest')
            ->where('id', $consumer_id)
            ->where('status', 1)
            ->get();

        return view('account.investment.investment_report.investment_report_list', $data);
    }


    public function updateConsumerStatus($id)
    {
        $model   = Investment::find($id);

        if ($model->status == 1) {
            $model->status = 2;
            $model->update();
        } else {
            $model->status = 1;
            $model->update();
        }
        $msg = "Status Updated...";

        return redirect()->route('investment_list')->with('status', $msg);
    }
}
