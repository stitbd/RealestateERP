<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankInfo;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\Session;

class BankInfoController extends Controller
{
    /**********  Bank  *********/
    public function index(){
        try{
            $data['main_menu']              = 'accounts';
            $data['child_menu']             = 'bank_list';
            $data['banks']                  = Bank::where('company_id',session()->get('company_id'))->get();
            return view('account.bank_info.index',$data); 
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    
    }
    public function create(){
        try{
            $data['main_menu']              = 'accounts';
            $data['child_menu']             = 'bank_info_create';
            return view('account.bank_info.create',$data); 
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function store(Request $request){
        try{
            $bank = new Bank;
             $bank->name                    = $request->bank_name;
            $bank->company_id                    = Session::get('company_id');
            $bank->save();
            $msg = 'Bank Name Added';
            $request->session()->flash('message',$msg);
            return redirect()->back()->with('status',$msg);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function update(Request $request,$id){
        try{
            $bank                  = Bank::find($id);
            $bank->name            = $request->bank_name;
            $bank->update();
            $msg                   = 'Bank Name Updated';
            $request->session()->flash('message',$msg);
            return redirect()->back()->with('status',$msg);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    /********** Bank Account *********/
    public function accountIndex(){
        try{
            $data['main_menu']              = 'accounts';
            $data['child_menu']             = 'account_list';
            $data['banks']                  = Bank::where('company_id',session()->get('company_id'))->get();
            $data['bank_accounts']          = BankAccount::where('company_id',session()->get('company_id'))->get();
            return view('account.bank_info.account_index',$data); 
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
    public function storeAccount(Request $request){
        try{
            $account = new BankAccount;
            $account->bank_id                       = $request->bank_id;
            $account->account_no                    = $request->account_no;
            $account->route_no                      = $request->route_no;
            $account->account_holder_name           = $request->account_holder_name;
            $account->branch                        = $request->branch;
            $account->bank_address                  = $request->bank_address;
            $account->current_balance               = $request->balance;
            $account->company_id                    = Session::get('company_id');
            $account->save();

            if($request->balance && $request->balance > 0){
                $fund = FundCurrentBalance::where(['fund_id'=>1,'bank_id'=>$request->bank_id,'status'=>1,'company_id'=>session()->get('comapny_id')])->first();

                if($fund != null){
                    $fund->amount = $fund->amount +  $request->balance ? $request->balance:0 ;
                    $fund->updated_by = auth()->user()->id; 
                    $fund->update();
                }
                else{
                    $fund_current_balance               = new FundCurrentBalance();
                    $fund_current_balance->fund_id      = 1;
                    $fund_current_balance->bank_id      = $request->bank_id;
                    $fund_current_balance->company_id   = session()->get('comapny_id');
                    $fund_current_balance->amount       = $request->balance ? $request->balance:0 ;
                    $fund_current_balance->status       = '1';
                    $fund_current_balance->created_by   = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }
            $msg                                    = 'Account Created Successfully';
            $request->session()->flash('message',$msg);
            return redirect()->back()->with('status',$msg);
        }catch (\Exception $e) {
            return $e->getMessage();
        }

    }
    public function updateAccount(Request $request,$id){
        try{
            $account = BankAccount::find($id);
            $account->bank_id                       = $request->bank_id;
            $account->account_no                    = $request->account_no;
            $account->route_no                      = $request->route_no;
            $account->account_holder_name           = $request->account_holder_name;
            $account->branch                        = $request->branch;
            $account->bank_address                  = $request->bank_address;
            $account->current_balance               = $request->balance;
            $account->update();

            if($request->balance && $request->balance >0){
                $fund = FundCurrentBalance::where(['fund_id'=>1,'bank_id'=>$request->bank_id,'status'=>1,'company_id'=>session()->get('comapny_id')])->first();

                if($fund != null){
                    if($account->current_balance >0){
                        $fund->amount = $fund->amount +  $request->balance ? $request->balance : 0 ;
                    }else{
                        $fund->amount = 0;
                    }
                    $fund->updated_by = auth()->user()->id; 
                    $fund->update();
                }
                else{
                    $fund_current_balance               = new FundCurrentBalance();
                    $fund_current_balance->fund_id      = 1;
                    $fund_current_balance->bank_id      = $request->bank_id;
                    $fund_current_balance->company_id   = session()->get('comapny_id');
                    $fund_current_balance->amount       = $request->balance ? $request->balance : 0 ;
                    $fund_current_balance->status       = '1';
                    $fund_current_balance->created_by   = auth()->user()->id;
                    $fund_current_balance->save();
                }
            }
            $msg                                    = 'Account Data Updated Successfully';
            $request->session()->flash('message',$msg);
            return redirect()->back()->with('status',$msg);
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

}
