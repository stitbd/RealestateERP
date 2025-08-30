<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Bank;
use App\Models\Fund;
use App\Models\Company;
use App\Models\FundLog;
use App\Models\BankAccount;
use App\Models\PaymentType;
use Illuminate\Http\Request;
use App\Models\ConsumerInvestor;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\Session;
use App\Models\ConsumerInvestorCollection;
use App\Models\Income;

class ConsumerInvestorsController extends Controller
{
    //Consumer Investor
    public function consumer_investors()
    {
        $data['main_menu']              = 'consumer_investors';
        $data['child_menu']             = 'consumer_investors';
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['payment_types']          = PaymentType::get();
        $data['consumer_investors']     = ConsumerInvestor::where('company_id', Session::get('company_id'))->orderByDesc('id')->get();
        return view('consumer_investor.consumer_investors', $data);
    }


    public function save_consumer_investors(Request $request)
    {
        $lastConsumer = ConsumerInvestor::latest()->first();
        $lastCode = ($lastConsumer) ? $lastConsumer->id : 0;
        $prefix = "CONSUMER";
        $nextConsumerCode = $lastCode + 1;
        $ConsumerNumber = $prefix . '-' . $nextConsumerCode;

        $model = new ConsumerInvestor();
        $model->name                     = $request->post('name');
        $model->email                    = $request->post('email');
        $model->phone                    = $request->post('phone');
        $model->address                  = $request->post('address');
        $model->invest_amount            = $request->post('invest_amount');
        $model->invest_validity          = $request->post('invest_validity');
        $model->code                     = $ConsumerNumber;
        $model->company_id               = Session::get('company_id');
        $model->created_by               = auth()->user()->id;
        $model->save();

        $msg = "Consumer Investment Details Inserted.";

        return redirect()->route('consumer_investors')->with('status', $msg);
    }

    public function update_consumer_investors(Request $request, $id)
    {
        // return $request->all();

        $model = ConsumerInvestor::findOrFail($id);
        $model->name                  = $request->post('name');
        $model->email                  = $request->post('email');
        $model->phone                  = $request->post('phone');
        $model->address                = $request->post('address');
        $model->invest_amount          = $request->post('invest_amount');
        $model->invest_validity        = $request->post('invest_validity');
        $model->company_id             = Session::get('company_id');
        $model->updated_by             = auth()->user()->id;
        $model->update();

        $msg = "Consumer Investment Details Updated.";
        return redirect()->route('consumer_investors')->with('status', $msg);
    }


    public function updateConsumerStatus($id)
    {
        $model   = ConsumerInvestor::find($id);

        if ($model->status == 1) {
            $model->status = 2;
            $model->update();
        } else {
            $model->status = 1;
            $model->update();
        }
        $msg = "Status Updated...";

        return redirect()->back()->with('status', $msg);
    }



    //Collect Invest
    public function store_collect_invest(Request $request)
    {
       
            $lastVoucher = ConsumerInvestorCollection::where('company_id', Session::get('company_id'))->latest()->first();
            $lastNumber = ($lastVoucher) ? $lastVoucher->id : 0;
            $voucherNumber = "CIV-" . $lastNumber + 1;
            $collectCode = "CIC-" . $lastNumber + 1;

            $model                       = new ConsumerInvestorCollection();
            $model->company_id           = Session::get('company_id');
            $model->consumer_investor_id = $request->consumer_investor_id;
            $model->collect_code         = $collectCode;
            $model->voucher_no           = $voucherNumber;
            $model->date                 = $request->date;
            $model->fund_id              = $request->fund_id;
            $model->bank_id              = $request->bank_id;
            $model->account_id           = $request->account_id;
            $model->check_no             = $request->check_no;
            $model->payment_type_id      = $request->payment_type_id;
            $model->collect_amount       = $request->collect_amount;
            $model->note                 = $request->note;
            $model->created_by           = auth()->user()->id;
            $model->save();


            $lastIncomeVoucher = Income::latest()->first();
            $lastIncomeNumber = ($lastIncomeVoucher) ? $lastIncomeVoucher->id : 0;
            $incomeVoucherNumber = "INC-" . date('Y') . $lastIncomeNumber + 1;
            $collectIncomeCode = "INC-" . $lastIncomeNumber + 1;

            $otherCompanyId = session()->get('company_id') != $request->company ? $request->company : 0;

            $income                       = new Income();
            $income->company_id           = Session::get('company_id');
            $income->other_company_id     = $otherCompanyId;
            $income->consumer_investor_id = $request->consumer_investor_id;
            $income->code_no              = $collectIncomeCode;
            $income->voucher_no           = $incomeVoucherNumber;
            $income->date                 = $request->date;
            $income->fund_id              = $request->fund_id;
            $income->bank_id              = $request->bank_id;
            $income->account_id           = $request->account_id;
            $income->check_no             = $request->check_no;
            $income->payment_type_id      = $request->payment_type_id;
            $income->collect_amount       = $request->collect_amount;
            $income->income_type          = 'collection';
            $income->note                 = $request->note;
            $income->created_by           = auth()->user()->id;
            $income->save();
            // dd($income);

            $log                       = new FundLog();
            $log->company_id           = Session::get('company_id');
            $log->fund_id              = $request->fund_id;
            $log->type                 = '1';
            $log->amount               = $request->collect_amount;
            $log->transection_type     = 'collect_invest_amount';
            $log->transection_id       = $model->id;
            $log->transection_date     = $request->date;
            $log->status               = '1';
            $log->created_by           = auth()->user()->id;
            $log->save();

            $bankAccount = BankAccount::where('id',$model->account_id)->first();
            if($bankAccount){
                    $bankAccount->current_balance += (float)$model->collect_amount;
                    $bankAccount->update();
                    $bank_fund = FundCurrentBalance::where(['fund_id'=>$model->fund_id,'status'=>1])->where('bank_id',$model->bank_id)->first();
                    if($bank_fund){
                        $bank_fund->amount +=   $model->collect_amount; 
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
                    $fund->amount += $model->collect_amount;
                    $fund->updated_by = auth()->user()->id;
                    $fund->update();
                }
                else{
                    $fund_current_balance = new FundCurrentBalance();
                    $fund_current_balance->fund_id = $model->fund_id;
                    $fund_current_balance->company_id = Session::get('company_id');
                    $fund_current_balance->amount = $model->collect_amount;
                    $fund_current_balance->status = '1';
                    $fund_current_balance->created_by = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }

            
            $fund = FundCurrentBalance::where(['fund_id' => $request->fund_id, 'company_id' => Session::get('company_id'), 'status' => 1])->first();
            if ($fund != null) {
                $fund->amount = $fund->amount + $request->collect_amount;
                $fund->updated_by = auth()->user()->id;
                // $fund->update();
            } else {
                $fund = new FundCurrentBalance();
                $fund->fund_id = $request->fund_id;
                $fund->company_id = Session::get('company_id');
                $fund->amount = $request->collect_amount;
                $fund->status = '1';
                $fund->created_by = auth()->user()->id;
                // $fund->save();
            }

            $msg = "Invest Amount Collected.";
            return redirect()->route('consumer_investors')->with('status', $msg);
    }

    public function collect_invest_index()
    {
        $data['main_menu']      = 'consumer_investors';
        $data['child_menu']     = 'collect_invest_index';
        $data['funds']          = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']     = Fund::all();
        $data['banks']          = Bank::get();
        $data['accounts']       = BankAccount::where('company_id', Session::get('company_id'))->get();
        $data['investors']      = ConsumerInvestor::with('investor_collection')->where('company_id', Session::get('company_id'))->where(['status' => 1])->get();
        $data['collections']    = ConsumerInvestorCollection::with('consumer')->where('company_id', Session::get('company_id'))->paginate(20);

        return view('consumer_investor.amount_collection_list', $data);
    }

    public function printCollectInvestVoucher($id){
        try{
            $model          = ConsumerInvestorCollection::with('consumer')->where('company_id', Session::get('company_id'))->where('id', $id)->first();
            $company_info   = Company::where('id', $model->company_id)->first();
            return view('consumer_investor.voucher_print',compact('model','company_info'));
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
   

    public function investor_money_receipt($id)
    {
            $invest_Amount = ConsumerInvestorCollection::with('consumer')->where('company_id', Session::get('company_id'))->where('id', $id)->first();
        // dd($income);
        return view('consumer_investor.invest_money_receipt', compact('invest_Amount'));
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
        $data['invests']             = Investment::with('return_invest')->where('company_id', Session::get('company_id'))->where(['status' => 1])->get();
        // dd($data['invests']);
        return view('account.investment.investment_report.investment_report', $data);
    }

    public function investmentReportList(Request $request)
    {
        // dd($request->all());
        $start_date                 = $request->start_date;
        $end_date                   = $request->end_date;
        $company_name               = Session::get('company_name');

        $data['funds']               = FundCurrentBalance::where('fund_id', 1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['company_name']        = Session::get('company_name');
        $data['banks']               = Bank::where('company_id', Session::get('company_id'))->get();
        $data['invests']             = Investment::with('return_invest')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->where('status', 1)->get();
        return view('account.investment.investment_report.investment_report_list', compact('start_date', 'end_date', 'company_name', 'data'));
    }
}
