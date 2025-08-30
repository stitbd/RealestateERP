<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\AdvanceCheque;
use Illuminate\Support\Facades\Session;

class AdvanceChequeController extends Controller
{
   public function index(Request $request){
        $data['main_menu']              = 'expense';
        $data['child_menu']             = 'advance-cheque';
        $data['banks']                          = Bank::get();
        $data['accounts']                       = BankAccount::where('company_id',Session::get('company_id'))->get();
        $cheque                                 = AdvanceCheque::where('company_id',Session::get('company_id'))->whereNotIn('status',[4]);
        

        if($request->start_date || $request->end_date || $request->status){
            if($request->start_date){
                $where['start_date'] = $request->start_date;
                $cheque->whereDate('date','>=',$request->start_date);
            }
            if($request->end_date){
                $where['end_date'] = $request->end_date;
                $cheque->whereDate('date','<=',$request->end_date);
            }
            if($request->status){
                $cheque->where('status',$request->status);            
            }
            $cheque->orderBy('date','ASC');
            $cheque   =  $cheque->get();
            $data['advance_cheques'] = $cheque;
        }
         else{
            $cheque = $cheque->paginate(20);
            $data['advance_cheques'] = $cheque;
        }
           
        
            // dd( $data['advance_cheques']);

        return view('account.advancecheque.advancecheque',$data);
   }

   public function chequeStore(Request $request){
            // return $request->all();
            try{
            $cheque = new AdvanceCheque();
            $cheque->date                       =  $request->date;
            $cheque->bank_id                    =  $request->bank_id;
            $cheque->company_id                 =  session()->get('company_id');
            $cheque->account_id                 =  $request->account_id;
            $cheque->account_holder             =  $request->account_holder;
            $cheque->cheque_no                  =  $request->cheque_no;
            $cheque->cheque_issue_date          =  $request->cheque_issue_date;
            $cheque->benificiary                =  $request->benificiary;
            $cheque->amount                     =  $request->amount;
            $cheque->remarks                    =  $request->remarks;
            $cheque->status                     =  $request->status;
            $cheque->created_by                 =  auth()->user()->id;
            $cheque->save();
            $msg    ="Advance Cheque Added.";
            return redirect()->back()->with('status', $msg);

        }catch (\Exception $e) {
            $e->getMessage();
        }
   }

   public function statusUpdate($id){
        $cheque = AdvanceCheque::find($id);
        if($cheque){
            $cheque->status = 4;
        }
        $cheque->update();
        $msg    ="Cheque Rejected.";
        return redirect()->back()->with('status', $msg);
   }
}
