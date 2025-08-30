<?php

namespace App\Http\Controllers;

use App\Models\AccountCategory;
use App\Models\AccountHead;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Company;
use App\Models\Fund;
use App\Models\FundCurrentBalance;
use App\Models\FundLog;
use App\Models\LoanCollection;
use App\Models\LoanStatus;
use App\Models\Project;
use App\Models\ReceivableLoan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PDF;

class LoanStatusController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']      = 'loans';
        $data['child_menu']     = 'loan-list';
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::all();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['companies']      = Company::where('status', 1)->get();
        $loan_collection        = LoanStatus::with('company', 'bank', 'account')->where('company_id', Session::get('company_id'))->where(['status' => 1]);
        $where = array();
        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $loan_collection = $loan_collection->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $loan_collection = $loan_collection->where('bank_id', '=', $request->bank_id);
        }
        if ($request->company_id) {
            $where['company_id'] = $request->company_id;
            $loan_collection = $loan_collection->where('company_id', '=', $request->company_id);
        }

        if ($request->loanee_name) {
            $where['loanee_name'] = $request->loanee_name;
            $loan_collection = $loan_collection->where('loanee_name', '=', $request->loanee_name);
        }

        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $loan_collection = $loan_collection->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $loan_collection = $loan_collection->whereDate('created_at', '<=', $request->end_date);
        }

        $loan_collection = $loan_collection->orderBy('id', 'desc')->paginate(20);
        $loan_collection->appends($where);
        $data['loans'] = $loan_collection;

        return view('account.loan_status.index', $data);
    }

    public function print(Request $request)
    {
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['banks']               = Bank::where('company_id', Session::get('company_id'))->get();
        $data['accounts']            = BankAccount::where('company_id', Session::get('company_id'))->get();
        $bank_garanties              = LoanStatus::with('company', 'bank', 'account')->where(['status' => 1]);
        $where = array();

        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $bank_garanties = $bank_garanties->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $bank_garanties = $bank_garanties->where('bank_id', '=', $request->bank_id);
        }
        if ($request->company_id) {
            $where['company_id'] = $request->company_id;
            $bank_garanties = $bank_garanties->where('company_id', '=', $request->company_id);
        }
        if ($request->loanee_name) {
            $where['loanee_name'] = $request->loanee_name;
            $bank_garanties = $bank_garanties->where('loanee_name', '=', $request->loanee_name);
        }

        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '<=', $request->end_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->get();
        $data['loans'] = $bank_garanties;

        return view('account.loan_status.print', $data);
    }

    public function pdf(Request $request)
    {
        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['banks']               = Bank::where('company_id', Session::get('company_id'))->get();
        $data['accounts']            = BankAccount::where('company_id', Session::get('company_id'))->get();
        $bank_garanties              = LoanStatus::with('company', 'bank', 'account')->where(['status' => 1]);
        $where = array();
        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $bank_garanties = $bank_garanties->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $bank_garanties = $bank_garanties->where('bank_id', '=', $request->bank_id);
        }
        if ($request->company_id) {
            $where['company_id'] = $request->company_id;
            $bank_garanties = $bank_garanties->where('company_id', '=', $request->company_id);
        }
        if ($request->loanee_name) {
            $where['loanee_name'] = $request->loanee_name;
            $bank_garanties = $bank_garanties->where('loanee_name', '=', $request->loanee_name);
        }

        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '<=', $request->end_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->get();
        $data['loans'] = $bank_garanties;

        $pdf = PDF::loadView('account.loan_status.print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('loan-status_' . $string . '.pdf');
    }

    public function create()
    {
        $data['main_menu'] = 'loans';
        $data['child_menu'] = 'create-loan';
        $data['categories'] = AccountCategory::get();
        // dd($data['categories']);
        $data['head'] = AccountHead::get();
        $data['fund_types'] = Fund::all();
        $data['companies'] = Company::where('status', 1)->get();
        $data['banks'] = Bank::get();
        $data['accounts'] = BankAccount::where('company_id', Session::get('company_id'))->get();

        $loan_code = LoanStatus::orderByDesc('id')->first();
        if ($loan_code) {
            $data['lastLoanId'] = $loan_code->id;
        }

        return view('account.loan_status.create', $data);
    }


    public function readyVoucher(Request $request)
    {
        // dd($request->all());
        $lastVoucher = LoanStatus::latest()->first();
        $lastNumber = ($lastVoucher) ? $lastVoucher->id : 0;
        $voucherNumber = "LOAN-" . $lastNumber + 1;

        $category = '';
        $head = '';


        $model                      = new LoanStatus();

        if ($request->category_name) {
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
        $model->company_id          = Session::get('company_id');
        $model->loan_date           = $request->loan_date;
        $model->employee_company_id = $request->employee_company_id;
        $model->loan_code           = $request->loan_code;
        $model->voucher_no          = $voucherNumber;
        $model->bank_id             = $request->bank_id;
        $model->fund_id             = $request->fund_id;
        $model->payment_type        = $request->payment_type;
        $model->account_id          = $request->account_id;
        $model->description         = $request->description;
        $model->loanee_name         = $request->loanee_name;
        $model->address             = $request->address;
        $model->department          = $request->department;
        $model->phone               = $request->phone;
        $model->email               = $request->email;
        $model->designation         = $request->designation;
        $model->amount              = $request->amount;
        $model->valid_date          = $request->valid_date;
        $model->remarks             = $request->remarks;
        $model->status              = '1';
        $model->created_by          = auth()->user()->id;

        if ($request->attachment != null) {
            $newImageName = time() . '_loan.' . $request->attachment->extension();
            $request->attachment->move(public_path('attachment'), $newImageName);
            $model->attachment = $newImageName;
        }
        if ($request->nid != null) {
            $newNidName = 'employee_nid.' . $request->nid->extension();
            $request->nid->move(public_path('nid'), $newNidName);
            $model->nid = $newNidName;
        }


        $receivable  =  new ReceivableLoan();
        $receivable->company_id           = Session::get('company_id');
        $receivable->fund_id              = $request->fund_id;
        $receivable->bank_id              = $request->bank_id;
        $receivable->fixed_amount         = $request->amount;
        $receivable->current_amount       = $request->amount;
        $receivable->created_by           = auth()->user()->id;


        $fund_log                       = new FundLog();
        $fund_log->company_id           = Session::get('company_id');
        $fund_log->fund_id              = $request->fund_id;
        $fund_log->type                 = '2';
        $fund_log->amount               = $request->amount;
        $fund_log->transection_type     = 'loan';
        $fund_log->transection_date     = $request->loan_date;
        $fund_log->status               = '1';
        $fund_log->created_by           = auth()->user()->id;


        session([
            'model'                     => $model,
            'fund_log'                  => $fund_log,
            'receivable'                => $receivable,
            'category'                  => $category,
            'head'                      => $head,
        ]);
        return view('account.loan_status.print_voucher', compact('model', 'fund_log', 'receivable', 'category', 'head'));

    }

    public function store(Request $request)
    {
        
        $model        = Session::get('model');
        $fund_log     = Session::get('fund_log');
        $receivable   = Session::get('receivable');
        
        $category     = Session::get('category');
        $head         = Session::get('head');

        if ($model->amount) {
            $lastVoucher       = LoanStatus::latest()->first();
            $lastNumber        = ($lastVoucher) ? $lastVoucher->id : 0;
           
            $voucherNumber      = "LOAN-" . $lastNumber + 1; //create voucher no
            $model->voucher_no  = $voucherNumber;

            if ($head  != "") {
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
            

            $receivable_loan                       =  new ReceivableLoan();
            $receivable_loan->company_id           = Session::get('company_id');
            $receivable_loan->loan_id              = $model->id;
            $receivable_loan->fund_id              = $receivable->fund_id;
            $receivable_loan->bank_id              = $receivable->bank_id;
            $receivable_loan->fixed_amount         = $receivable->fixed_amount;
            $receivable_loan->current_amount       = $receivable->current_amount;
            $receivable_loan->created_by           = auth()->user()->id;
            $receivable_loan->save();


            $log                       = new FundLog();
            $log->company_id           = Session::get('company_id');
            $log->fund_id              = $fund_log->fund_id;
            $log->type                 = '2';
            $log->amount               = $fund_log->amount;
            $log->transection_type     = 'loan';
            $log->transection_id       = $model->id;
            $log->transection_date     = $fund_log->loan_date;
            $log->status               = '1';
            $log->created_by           = auth()->user()->id;
            $log->save();

            $bankAccount = BankAccount::where('id',$model->account_id)->first();

            if($bankAccount){
                    $bankAccount->current_balance -= (float)$model->amount;
                    $bankAccount->update();
                    $bank_fund = FundCurrentBalance::where(['fund_id'=>$model->fund_id,'status'=>1])->where('bank_id',$model->bank_id)->first();
                    if($bank_fund){
                        $bank_fund->amount -=   $model->amount; 
                        $bank_fund->updated_by = auth()->user()->id;
                        $bank_fund->update();
                    }else{
                        $fund_current_balance = new FundCurrentBalance();
                        $fund_current_balance->fund_id = $model->fund_id;
                        $fund_current_balance->bank_id = $model->bank_id;
                        $fund_current_balance->company_id = Session::get('company_id');
                        $fund_current_balance->amount = $model->amount;
                        $fund_current_balance->status = '1';
                        $fund_current_balance->created_by = auth()->user()->id;
                        $fund_current_balance->save();
                    }
            }else{
                $fund = FundCurrentBalance::where(['fund_id'=>$model->fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
                if($fund != null){
                    $fund->amount += $model->amount;
                    $fund->updated_by = auth()->user()->id;
                    // dd($fund);
                    $fund->update();
                }
                else{
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $model->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $model->amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            $msg = "Loan Inserted.";
            $request->session()->flash('message', $msg);
        } else {
            $msg = "Loan Insert Failed.";
            $request->session()->flash('warning', $msg);
        }
        return redirect('loan-list')->with('status', $msg);
    }

    public function printLoanDebitVoucher($id)
    {
        $model          = LoanStatus::where('id', $id)->first();
        $receive_by     = $receive_by = auth()->user()->name;
        return view('account.loan_status.loan_debit_voucher_print', compact('model', 'receive_by'));
    }

    //Receivable Loan List
    public function receivable_index(Request $request)
    {
        $data['main_menu']      = 'loans';
        $data['child_menu']     = 'receivable-loan-list';
        $data['funds']          =  FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['loans']          =  LoanStatus::where(['status' => 1])->get();
        $data['companies']      =  Company::where(['status' => 1])->get();
        $bank_garanties         =  ReceivableLoan::with('fund', 'bank', 'loan','loancollection');


        $where = array();
        if ($request->loan_id) {
            $where['loan_id'] = $request->loan_id;
            $bank_garanties = $bank_garanties->where('loan_id', '=', $request->loan_id);
        }
        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->paginate(20);
        $bank_garanties->appends($where);
        $data['receivable_loans'] = $bank_garanties;

        return view('account.loan_status.receivable_loan', $data);
    }

    function receivable_loan_log($loan_id){
        $data['main_menu']              = 'loans';
        $data['child_menu']             = 'receivable-loan-list';
        $data['loan']                  = ReceivableLoan::with('company','fund','bank','loan')->where(['company_id'=>Session::get('company_id'),'loan_id'=>$loan_id])->first();
        // dd($data['loan']);
        return view('account.loan_status.receivable_loan_log',$data);
    }

    public function receivable_loan_print(Request $request)
    {
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['loans']          = LoanStatus::where(['status' => 1])->get();
        $data['companies']      = Company::where(['status' => 1])->get();
        $bank_garanties         = ReceivableLoan::with('fund', 'bank', 'loan');
        $where = array();

        if ($request->loan_id) {
            $where['loan_id'] = $request->loan_id;
            $bank_garanties = $bank_garanties->where('loan_id', '=', $request->loan_id);
        }

        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->get();
        $data['receivable_loans'] = $bank_garanties;

        return view('account.loan_status.receivable_loan_print', $data);
    }

    //Loan Collection
    public function collection_index(Request $request)
    {
        $data['main_menu']      = 'loans';
        $data['child_menu']     = 'loan-collection-list';
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::where('company_id', Session::get('company_id'))->get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['loans']          = LoanStatus::where(['status' => 1])->get();
        // dd($data['loans']);
        $bank_garanties         = LoanCollection::with('loan', 'bank', 'fund', 'receivable')->where(['status' => 1]);
        $where = array();
        if ($request->loan_id) {
            $where['loan_id'] = $request->loan_id;
            $bank_garanties = $bank_garanties->where('loan_id', '=', $request->loan_id);
        }
        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $bank_garanties = $bank_garanties->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $bank_garanties = $bank_garanties->where('bank_id', '=', $request->bank_id);
        }
        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '<=', $request->end_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->paginate(20);
        $bank_garanties->appends($where);
        $data['loan_collections'] = $bank_garanties;

        return view('account.loan_status.loan_collection', $data);
    }

    public function collection_create()
    {
        $data['main_menu']    = 'loans';
        $data['child_menu']   = 'loan-collection-list';
        $data['funds']        = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']   = Fund::all();
        $data['banks']        = Bank::get();
        $data['accounts']     = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['loans']        = LoanStatus::join('tbl_receivable', 'loan_statuses.id', '=', 'tbl_receivable.loan_id')
                                ->where('tbl_receivable.current_amount', '!=', 0)
                                ->where('loan_statuses.status', 1)
                                ->select('loan_statuses.*')
                                ->get();


        $collection_code = LoanCollection::orderByDesc('id')->first();


        if ($collection_code) {
            $data['lastCollectionId'] = $collection_code->id;
        }

        $currentDate = Carbon::now();
        $startOfWeek = $currentDate->copy()->startOfWeek(Carbon::SATURDAY);
        $endOfWeek = $currentDate->copy()->endOfWeek(Carbon::FRIDAY);

        $data['loanStatusCount'] = LoanStatus::join('tbl_receivable', 'loan_statuses.id', '=', 'tbl_receivable.loan_id')
            ->where('tbl_receivable.current_amount', '!=', 0)
            ->where('loan_statuses.status', 1)
            ->whereBetween('loan_statuses.valid_date', [$startOfWeek, $endOfWeek])
            ->count();

        $message = "Need to collect {$data['loanStatusCount']}  loan in this week";
        // dd($message);
        $data['message'] = $message;

        return view('account.loan_status.collection_create', $data);
    }

    public function readyCollectionVoucher(Request $request)
    {
        $lastVoucher    = LoanCollection::latest()->first();
        $lastNumber     = ($lastVoucher) ? $lastVoucher->id : 0;
        $voucherNumber  = "LCLT-" . $lastNumber + 1;

        $loan_id        = $request->loan_id;

        $receivable     = ReceivableLoan::where('loan_id', $loan_id)->first();
        $current_amount = ReceivableLoan::where('loan_id', $loan_id)->first();


        if (!$current_amount || $current_amount->current_amount < $request->collect_amount) {
            $msg = "Insufficient Current Amount.";
            $request->session()->flash('warning', $msg);
            return redirect()->back()->with('warning', $msg);
        }


        $model                      = new LoanCollection();
        $model->loan_id             = $request->loan_id;
        $model->company_id          = Session::get('company_id');
        $model->collection_code     = $request->collection_code;
        $model->receivable_id       = $receivable->id;
        $model->fund_id             = $request->fund_id;
        $model->bank_id             = $request->bank_id ?? null;
        $model->account_id          = $request->account_id ?? null;
        $model->cheque_no           = $request->cheque_no ?? null;
        $model->cheque_issue_date   = $request->cheque_issue_date ?? null;
        $model->voucher_no          = $voucherNumber;
        $model->date                = $request->date;
        $model->collect_amount      = $request->collect_amount;
        $model->note                = $request->note;
        $model->status              = '1';



        $log                       = new FundLog();
        $log->company_id           = Session::get('company_id');
        $log->fund_id              = $request->fund_id;
        $log->type                 = '1';
        $log->amount               = $request->collect_amount;
        $log->transection_type     = 'loan-collection';
        $log->transection_id       = $model->id;
        $log->transection_date     = $request->date;
        $log->status               = '1';
        $log->created_by           = auth()->user()->id;

        session([
            'model'                     => $model,
            'log'                       => $log,
            'current_amount'            => $current_amount,
            'receivable'                => $receivable,
            'loan_id'                   => $loan_id,
        ]);
        return view('account.loan_status.collection_credit_voucher', compact('model', 'current_amount', 'receivable', 'loan_id'));

    }



    public function store_loan_collection(Request $request)
    {
        $model              = Session::get('model');
        $log                = Session::get('log');
        $receivable         = Session::get('receivable');
        $loan_id            = Session::get('loan_id');
       
        if ($model->loan_id) {

            $lastVoucher       = LoanCollection::latest()->first();
            $lastNumber        = ($lastVoucher) ? $lastVoucher->id : 0;
            $voucherNumber     = "LCVHR-" . $lastNumber + 1;
            $model->voucher_no = $voucherNumber;



            $current_amount = ReceivableLoan::where('loan_id', $loan_id)->first();

            if (!$current_amount && $current_amount->current_amount < $request->collect_amount) {
                $msg = "Insufficient Current Amount.";
                $request->session()->flash('warning', $msg);
                return redirect()->back()->with('warning', $msg);
            }

            $bankAccount = BankAccount::where('id',$model->account_id)->first();
            if($bankAccount){
                    $bankAccount->current_balance -= (float)$model->collect_amount;
                    $bankAccount->update();
                    $bank_fund = FundCurrentBalance::where(['fund_id'=>$model->fund_id,'status'=>1])->where('bank_id',$model->bank_id)->first();
                    if($bank_fund){
                        $bank_fund->amount -=   $model->collect_amount; 
                        $bank_fund->updated_by = auth()->user()->id;
                        $bank_fund->update();
                    }else{
                        $fund_current_balance = new FundCurrentBalance();
                        $fund_current_balance->fund_id = $model->fund_id;
                        $fund_current_balance->bank_id = $model->bank_id;
                        $fund_current_balance->company_id = Session::get('company_id');
                        $fund_current_balance->amount = $model->collect_amount;
                        $fund_current_balance->status = '1';
                        $fund_current_balance->created_by = auth()->user()->id;
                        $fund_current_balance->save();
                    }
            }else{
                $fund = FundCurrentBalance::where(['fund_id'=>$model->fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
                if($fund != null){
                    $fund->amount -= $model->collect_amount;
                    $fund->updated_by = auth()->user()->id;
                    // dd($fund);
                    $fund->update();
                }
                else{
                    $fund_current_balance             = new FundCurrentBalance();
                    $fund_current_balance->fund_id    = $model->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount     = $model->collect_amount;
                    $fund_current_balance->status     = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            $log->transection_id       = $model->id;
            $log->save();


            $model->save();

            $msg = "Loan collected.";
            $request->session()->flash('message', $msg);
            $current_amount->decrement('current_amount', $model->collect_amount);
            return redirect('loan-collection-list')->with('status', $msg);
        } else {
            $msg = "Loan collection failed.";
            $request->session()->flash('warning', $msg);
            return redirect()->back()->with('warning', $msg);
        }
    }

    public function printCollectionVoucher($id){
        try{
            $model          = LoanCollection::where('id',$id)->first();
            $receive_by     = $receive_by = auth()->user()->name;
            return view('account.loan_status.print_collection_voucher',compact('model','receive_by'));
        }catch (\Exception $e) {
            return $e->getMessage();
        }
     }

    public function getCurrentAmount($loanId)
    {
        $loan_id = LoanStatus::find($loanId);
        // dd($loan_id);
        $loan = ReceivableLoan::where('loan_id', $loan_id->id)->first();
        // dd($loan);
        if ($loan) {
            return response()->json(['current_amount' => $loan->current_amount, 'valid_date' => $loan_id->valid_date]);
        } else {
            return response()->json(['error' => 'Loan not found'], 404);
        }
    }

    public function loan_collection_print(Request $request)
    {
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::where('company_id', Session::get('company_id'))->get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['loans']          = LoanStatus::where(['status' => 1])->get();
        $bank_garanties         = LoanCollection::with('loan', 'bank', 'fund', 'receivable')->where(['status' => 1]);
        $where = array();

        if ($request->loan_id) {
            $where['loan_id'] = $request->loan_id;
            $bank_garanties = $bank_garanties->where('loan_id', '=', $request->loan_id);
        }
        if ($request->fund_id) {
            $where['fund_id'] = $request->fund_id;
            $bank_garanties = $bank_garanties->where('fund_id', '=', $request->fund_id);
        }
        if ($request->bank_id) {
            $where['bank_id'] = $request->bank_id;
            $bank_garanties = $bank_garanties->where('bank_id', '=', $request->bank_id);
        }

        if ($request->start_date) {
            $where['start_date'] = $request->start_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $where['end_date'] = $request->end_date;
            $bank_garanties = $bank_garanties->whereDate('created_at', '<=', $request->end_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id', 'desc')->get();
        $data['loan_collections'] = $bank_garanties;

        return view('account.loan_status.loan_collection_print', $data);
    }

    //report

    public function loan_report_index()
    {

        $data['main_menu']          = 'loans';
        $data['child_menu']         = 'loan_report';
        $data['funds']              = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']         = Fund::all();
        $data['company_name']       = Session::get('company_name');
        $data['banks']              = Bank::where('company_id', Session::get('company_id'))->get();
        $data['accounts']           = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['loans']              = LoanStatus::with('loan_collection')->where(['status' => 1])->get();
        // dd($data['loans']);
        return view('account.loan_status.loan_report.loan_report', $data);
    }

    public function loanReportList(Request $request)
    {
        // dd($request->all());
        $start_date                 = $request->start_date;
        $end_date                   = $request->end_date;
        $company_name               = Session::get('company_name');

        $data['funds']              = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']         = Fund::all();
        $data['company_name']       = Session::get('company_name');
        $data['banks']              = Bank::where('company_id', Session::get('company_id'))->get();
        $data['accounts']           = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['loans']              = LoanStatus::with(['loan_collection' => function ($query) use ($start_date, $end_date) {
            $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);
        }])->where('status', 1)->get();


        return view('account.loan_status.loan_report.loan_report_list', compact('start_date', 'end_date', 'company_name', 'data'));
    }
}
