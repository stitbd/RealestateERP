<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\Fund;
use App\Models\Capital;
use App\Models\FundLog;
use App\Models\FundTransfer;
use Illuminate\Http\Request;
use App\Models\FundCurrentBalance;
use App\Models\Income;
use Illuminate\Support\Facades\Session;

class FundTransferController extends Controller
{
    public function index(){
        $data['main_menu']      = "Fund Transfer";
        $data['child_menu']     = "transfer-log";

        $data['fund_transfer']  = FundTransfer::where(['company_id'=>Session::get('company_id'),'status'=>1])->get();
        
        return view('fund_transfer.transfer_log',$data);
    }

    public function create(){
        $data['main_menu']           = "Fund Transfer";
        $data['child_menu']          = "transfer_entry";
        $data['funds']               = FundCurrentBalance::where('fund_id',1)->orderByDesc('id')->get();
        $data['fund_types']          = Fund::all();
        $data['banks']               = Bank::get();
        $data['accounts']            = BankAccount::where('company_id',Session::get('company_id'))->get();
        return view('fund_transfer.create_transfer',$data);
    }

    public function filterAccount(Request $request){
        $bank_id = $request->bank_id;
        $accounts = BankAccount::where('bank_id',$bank_id)->get();
        if(count($accounts)>0){
            return response()->json($accounts);
        }
    }
    
   
     public function store(Request $request){
        
        $from_fund                         = FundCurrentBalance::where(['fund_id'=>$request->from_fund_id,'status' => 1,'company_id'=>session()->get('company_id')])->first();

        if($request->from_fund_id){
            $voucher  = FundTransfer::latest()->first();
             
            if( $voucher ){
                $voucherNumber = 'FHR-'. date('Y').$voucher->id + 1;
            }else{
                $voucherNumber = 'FHR-'. date('Y').'1';
            }

            $transfer                          = new FundTransfer;
            $transfer->voucher_no              = $voucherNumber;
            $transfer->from_fund_id            = $request->from_fund_id;
            $transfer->from_bank_id            = $request->from_bank_id;
            $transfer->from_acc_no             = $request->from_acc_no;
            $transfer->to_fund_id              = $request->to_fund_id;
            $transfer->to_bank_id              = $request->to_bank_id;
            $transfer->to_acc_no               = $request->to_acc_no;
            $transfer->transaction_date        = $request->date;
            $transfer->transaction_amount      = $request->amount;
            $transfer->particulars             = $request->particulars;
            $transfer->created_by              = auth()->user()->id;
            $transfer->company_id              = Session::get('company_id');
            $transfer->save();


            $from_bank_id = FundCurrentBalance::where(['fund_id'=>$request->from_fund_id,'bank_id'=>$request->from_bank_id,'status' => 1,'company_id'=>session()->get('company_id')])->first();
            if( $from_bank_id && $from_bank_id->bank_id != null){
                    $from_bank_id->amount -= $request->amount;
                    $from_bank_id->update();
                    $bankAccount = BankAccount::where(['id'=>$request->from_acc_no,'bank_id'=>$request->from_bank_id])->first();
                    $bankAccount->current_balance -= $request->amount;
                    $bankAccount->update();

                    $fund_log                       = new FundLog();
                    $fund_log->company_id           = Session::get('company_id');
                    $fund_log->fund_id              = $request->from_fund_id;
                    $fund_log->type                 = '2';
                    $fund_log->amount               = $request->amount;
                    $fund_log->transection_type     = 'transfer_out';
                    $fund_log->transection_date     = $request->date;
                    $fund_log->transection_id       = $transfer->id;
                    $fund_log->payment_type         = "Bank";
                    $fund_log->status               = '1';
                    $fund_log->created_by           = auth()->user()->id; 
                    $fund_log->save();          

            }else if($from_fund->bank_id == null && $from_fund->fund_id == $request->from_fund_id){
                // dd('sds');
                $from_fund->amount -= $request->amount;
                $from_fund->update();

                $fund_log                       = new FundLog();
                $fund_log->company_id           = Session::get('company_id');
                $fund_log->fund_id              = $request->from_fund_id;
                $fund_log->type                 = '2';
                $fund_log->amount               = $request->amount;
                $fund_log->transection_type     = 'transfer_out';
                $fund_log->transection_date     = $request->date;
                $fund_log->transection_id       = $transfer->id;
                $fund_log->payment_type         = "Cash";
                $fund_log->status               = '1';
                $fund_log->created_by           = auth()->user()->id;  
                $fund_log->save();

            }else{
                $fund = new FundCurrentBalance();
                $fund->fund_id = $request->fund_id;
                $fund->company_id = Session::get('company_id');
                $fund->amount = $request->amount;
                $fund->status = '1';
                $fund->created_by = auth()->user()->id;
                $fund->save();

            }

            $to_fund      = FundCurrentBalance::where(['fund_id'=>$request->to_fund_id,'status' => 1])->orWhere('bank_id',$request->to_bank_id)->first() ;
            if( $to_fund && $to_fund->bank_id != null){
                $to_fund->amount += $request->amount;
                 $to_fund->update();

                 $bankacc = BankAccount::where(['id'=>$request->to_acc_no,'bank_id'=>$request->to_bank_id])->first();
                 $bankacc->current_balance += $request->amount;
                 $bankacc->update();

                $fund_log                       = new FundLog();
                $fund_log->company_id           = Session::get('company_id');
                $fund_log->fund_id              = $request->to_fund_id;
                $fund_log->type                 = '1';
                $fund_log->amount               = $request->amount;
                $fund_log->transection_type     = 'transfer_in';
                $fund_log->transection_id       = $transfer->id;
                $fund_log->transection_date     = $request->date;
                $fund_log->payment_type         = "Bank";
                $fund_log->status               = '1';
                $fund_log->created_by           = auth()->user()->id;  
                $fund_log->save();

             }else if($to_fund  && $to_fund->fund_id == $request->to_fund_id){
                // if($to_fund->updated_at->toDateString() < $request->date){
                //     $to_fund->prev_day_amount = $to_fund->amount;
                //     $to_fund->update();
                // }
                $to_fund->amount += $request->amount;
                 $to_fund->update();

                $fund_log                       = new FundLog();
                $fund_log->company_id           = Session::get('company_id');
                $fund_log->fund_id              = $request->from_fund_id;
                $fund_log->type                 = '1';
                $fund_log->amount               = $request->amount;
                $fund_log->transection_type     = 'transfer_in';
                $fund_log->transection_date     = $request->date;
                $fund_log->payment_type         = "Cash";
                $fund_log->transection_id       = $transfer->id;
                $fund_log->status               = '1';
                $fund_log->created_by           = auth()->user()->id;  
                $fund_log->save();

             }else{
                 $fund = new FundCurrentBalance();
                 $fund->fund_id = $request->to_fund_id;
                 $fund->amount  = $request->amount;
                 $fund->status  = '1';
                 $fund->created_by = auth()->user()->id;
                 $fund->save();
             }

           
             if($request->attachment != null){
                $newImageName =  time().'_transfer.'.$request->attachment->extension();
                $request->attachment->move(public_path('attachment'),$newImageName);
                $transfer->attachment = $newImageName;
            }
        

            $msg="Transfered & Fund Amount Updated.";
            $request->session()->flash('warning',$msg);
       
        }else{
            $msg="Transfer Declined.Don't Have Sufficient Amount To Fund";
            $request->session()->flash('warning',$msg);
        }
        return redirect()->back()->with('status',$msg);
    }

    public function logStatusUpdate($id){
        $transfer = FundTransfer::find($id);
        // dd($transfer);
        if($transfer->from_bank_id != null && $transfer->from_acc_no != null){
            $bankAccount = BankAccount::where('id',$transfer->from_acc_no)->first();
            $bankFund    = FundCurrentBalance::where(['fund_id' => $transfer->from_fund_id,'bank_id' =>$transfer->from_bank_id])->first();
            $fundLog     = FundLog::where(['transection_id'=>$transfer->id,'type'=>2,'transection_type'=>'transfer_out'])->first();
            // dd($fundLog);
            if($bankAccount && $bankFund){
                $bankAccount->current_balance += $transfer->transaction_amount;
                $bankAccount->update();
                $bankFund->amount += $transfer->transaction_amount;
                $bankFund->update();
            }
            if($fundLog){
                $fundLog->status = 2;
                $fundLog->update();

            }
        }else{
            $fund    = FundCurrentBalance::where(['fund_id' => $transfer->from_fund_id])->first();
            $fundLog     = FundLog::where(['transection_id'=>$transfer->id,'type'=>2,'transection_type'=>'transfer_out'])->first();

            if($fund){
                $fund->amount += $transfer->transaction_amount;
                $fund->update();
            }

            if($fundLog){
                $fundLog->status = 2;
                $fundLog->update();
            }
 
        }

        if($transfer->to_bank_id != null && $transfer->to_acc_no != null){
            $bankAccount = BankAccount::where('id',$transfer->to_acc_no)->first();
            $bankFund    = FundCurrentBalance::where(['fund_id' => $transfer->to_fund_id,'bank_id' =>$transfer->to_bank_id])->first();
            $fundLog = FundLog::where(['transection_id'=>$transfer->id,'type'=>1,'transection_type'=>'transfer_in'])->first();
            // dd($fundLog);
            if($bankAccount && $bankFund){
                $bankAccount->current_balance -= $transfer->transaction_amount;
                $bankAccount->update();
                $bankFund->amount -= $transfer->transaction_amount;
                $bankFund->update();
            } 
            if($fundLog){
                $fundLog->status = 2;
                $fundLog->update();

            }
        }else{
            $fund    = FundCurrentBalance::where(['fund_id' => $transfer->to_fund_id])->first();
            $fundLog     = FundLog::where(['transection_id'=>$transfer->id,'type'=>1,'transection_type'=>'transfer_in'])->first();

            if($fund){
                $fund->amount += $transfer->transaction_amount;
                $fund->update();
            }

            if($fundLog){
                $fundLog->status = 2;
                $fundLog->update();
            }
        }

        $transfer->status = 2;
        $transfer->update();
        $msg="Transfered Log Removed..";
        return redirect()->back()->with('status',$msg);
    }
}
