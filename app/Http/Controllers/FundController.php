<?php

/**
 * Author: Tushar Das
 * Sr. Software Engineer
 * tushar2499@gmail.com
 * 01815920898
 * STITBD
 * 06/10/2021
 */

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Fund;
use App\Models\Income;
use App\Models\FundLog;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class FundController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']      = 'fund';
        $data['child_menu']     = 'fund-list';
        $data['fund_data']      = Fund::all();

        return view('fund.fund_list', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required'
        ]);


        $model = new Fund();
        $model->name                = $request->post('name');
        $model->account_no          = $request->post('account_no');
        $model->branch              = $request->post('branch');
        $model->details             = $request->post('details');
        $model->save();

        $msg = "Fund Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('fund-list')->with('status', $msg);
    }

    function status_update(Request $request, $status = 1, $id = null)
    {

        $model                  = Fund::find($id);
        $model->status          = $status;
        $model->save();

        $msg = "Fund Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('fund-list')->with('status', $msg);
    }



    function update(Request $request)
    {
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());

        $model = Fund::find($request->post('id'));
        $model->name                = $request->post('name');
        $model->account_no          = $request->post('account_no');
        $model->branch              = $request->post('branch');
        $model->details             = $request->post('details');

        $model->save();

        $msg = "Fund Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('fund-list')->with('status', $msg);
    }

    //====================== Fund Opening  Balance =====================//

    public function fundOpeningBalance()
    {
        try {
            $data['main_menu']      = 'fund';
            $data['child_menu']     = 'fund-opening-balance';
            $data['fund']           = Fund::all();
            $data['bank']           = Bank::all();
            $data['account']        = BankAccount::all();
            $data['income']         = Income::where('income_type', 'opening_balance')->where('company_id', session()->get('company_id'))->where('status', 1)->paginate(10);
            return view('fund.fund_opening_balance', $data);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function fundOpeningBalanceStore(Request $request)
    {
        try {
            $fund = FundCurrentBalance::where('fund_id', $request->fund_id)->where('bank_id', $request->bank_id)->where('status', 1)->where('company_id', session()->get('company_id'))->first();
            if ($fund) {
                $fund->amount += $request->amount;
                $fund->update();
            } else {
                $fund = new FundCurrentBalance;
                $fund->fund_id      =  $request->fund_id;
                $fund->bank_id      =  $request->bank_id;
                $fund->company_id   =  session()->get('company_id');
                $fund->amount       =  $request->amount;
                $fund->status       =  1;
                $fund->save();
            }
            $payment_type = '';

            if ($request->fund_id == 1) {
                $payment_type = 'Bank';
            } else if ($request->fund_id == 2) {
                $payment_type = 'Cash';
            } else if ($request->fund_id == 3) {
                $payment_type = 'Bkash';
            } else {
                $payment_type = 'Card';
            }


            $income = new Income;
            $income->fund_id        = $request->fund_id;
            $income->bank_id        = $request->bank_id;
            $income->company_id     = Session::get('company_id');
            $income->account_id     = $request->account_id;
            // $income->remarks        = "Opening Balance";
            $income->payment_date   = $request->date;
            $income->payment_type   = $payment_type;
            $income->income_type    = "opening_balance";
            $income->amount         = $request->amount;
            $income->created_by     = auth()->user()->id;
            $income->status         = 1;
            $income->save();
            // dd($income);


            $fund_log                       = new FundLog();
            $fund_log->company_id           = Session::get('company_id');
            $fund_log->fund_id              = $request->fund_id;
            $fund_log->type                 = '1';
            $fund_log->amount               = $request->amount;
            $fund_log->transection_type     = 'fund_transfer';
            $fund_log->transection_id       = $income->id;
            $fund_log->transection_date     = $request->date;
            $fund_log->payment_type         = $payment_type;
            $fund_log->status               = '1';
            $fund_log->created_by           = auth()->user()->id;
            $fund_log->save();

            $bankAccount = BankAccount::where('id', $request->account_id)->where('bank_id', $request->bank_id)->first();

            if ($bankAccount) {
                $bankAccount->current_balance += $request->amount;
                $bankAccount->update();
            }
            $msg = "Opening Balance Updated.";
            $request->session()->flash('warning', $msg);

            return redirect()->back()->with('status', $msg);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    //====================== Fund Transaction History =====================//
    public function fundLedger()
    {
        $data['main_menu']      = 'fund';
        $data['child_menu']     = 'fund-ledger';
        $data['funds']          = Fund::all();
        $data['logs']           = FundLog::where('company_id', Session::get('company_id'))->where('transection_date', date('Y-m-d'))->orderByDesc('id')->get();
        return view('account.fund_daily_ledger', $data);
    }

    public function fundLedgerList(Request $request)
    {
        try {
            $start_date                 = $request->start_date;
            $end_date                   = $request->end_date;
            $fund_id                    = $request->fund_id;
            $company_name               = Session::get('company_name');

            $fundlog       = FundLog::orderBy('id', 'asc')->where('status', 1);

            if ($fund_id) {
                $fundlog->where('fund_id', $fund_id);
            }
            if ($start_date) {
                $fundlog->where('transection_date', '>=', $start_date);
            }
            if ($end_date) {
                $fundlog->where('transection_date', '<=', $end_date);
            }

            $fund_opening_balance = Fundlog::where('transection_date', '<', $start_date)->where('fund_id', $fund_id)->where('status', 1)->sum('amount');
            // dd($fund_opening_balance);
            $carbonDate                    = Carbon::parse($end_date);
            $previousDate                  = $carbonDate->subDay();
            $formattedPreviousDate = $previousDate->toDateString();

            $history = $fundlog->where('status', 1)->get();

            return view('account.fund_ledger_list', compact('start_date', 'end_date', 'fund_id', 'history', 'company_name', 'formattedPreviousDate', 'fund_opening_balance'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
