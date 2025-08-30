<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Fund;
use App\Models\Income;
use App\Models\Capital;
use App\Models\Expense;
use App\Models\FundLog;
use App\Models\BankInfo;
use App\Models\AccountHead;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use App\Models\IncomeDetails;
use App\Models\AccountCategory;
use App\Models\CapitalCategory;
use App\Models\CapitalTransfer;
use App\Models\ExpenseBankInfo;
use App\Models\FundCurrentBalance;
use Illuminate\Support\Facades\Session;

class CapitalController extends Controller
{
    public function categoryIndex(){
        $data['main_menu'] = 'Capital';
        $data['child_menu'] = 'capital_category';
        $data['categories'] = CapitalCategory::all();
        $data['heads']      = AccountHead::whereIn('category_id',[])->get();
        return view('capital.category.manage_category',$data);
    }

    public function storeCategory(Request $request) {
        $category = new CapitalCategory;
        $category->category_name = $request->category_name;
        $category->created_by  = auth()->user()->id;
        $category->save();
        $msg = "Category Created Successfully";
        return redirect()->back()->with('status',$msg);
    }

    public function updateCategory(Request $request,$id) {
        $category = CapitalCategory::find($id);
        $category->category_name = $request->edit_category_name;
        $category->update();
        $msg = "Category Updated Successfully";
        return redirect()->back()->with('status',$msg);
    }

    public function index(){
        $data['main_menu']  = 'Capital';
        $data['child_menu'] = 'capital-list';
        $data['categories'] = CapitalCategory::all();
        $data['capitals']   = Capital::all();
        $data['funds']      = Fund::all();
        $data['banks']      = Bank::all();
        $data['accounts']   = BankAccount::all();

        // শুধুমাত্র Capital টাইপের ক্যাটাগরি ফিল্টার করুন
        $data['categories'] = AccountCategory::whereJsonContains('category_type', 'Capital')->get();

        $data['heads']      = AccountHead::all();

        // প্রথমে সব হেড লোড করবেন না, শুধু রিলেশন সেট করে রাখবেন
        // $data['heads'] = AccountHead::query();
        // $data['heads'] = AccountHead::whereHas('category', function($query) {
        //     $query->where('category_name', 'Investment Partner Money');
        //  })->get();

        return view('capital.manage_capital',$data);
    }

    public function getHeadsByCategory(Request $request)
    {
        $categoryId = $request->input('category_id');

        $heads = AccountHead::where('category_id', $categoryId)->get();

        return response()->json([
            'heads' => $heads
        ]);
    }

    public function getAccountsByBank(Request $request)
    {
        $bankId = $request->input('bank_id');

        $accounts = BankAccount::where('bank_id', $bankId)->get();

        return response()->json([
            'accounts' => $accounts
        ]);
    }



    public function filterAccount(Request $request){
        $bank_id = $request->bank_id;
        $accounts = BankAccount::where('bank_id',$bank_id)->get();
        if(count($accounts)>0){
            return response()->json($accounts);
        }
    }


    public function storeCapital(Request $request){
        $request->validate([
            'head_id' => 'required|unique:capitals,head_id',
            'capital_amount' => 'required',
            'date' => 'required|date', // যদি ফর্ম থেকে date আসে
        ]);

        $capital = new Capital;
        $capital->head_id = $request->head_id;
        $capital->initial_capital_entry_date = $request->date; // এখানে পরিবর্তন
        $capital->created_by = auth()->user()->id;
        $capital->initial_capital_amount = $request->capital_amount;
        $capital->current_capital_amount = $request->capital_amount;
        $capital->save();

        $msg = "Initial Capital Added Successfully";
        return redirect()->back()->with('status', $msg);
    }

    public function updateCapital(Request $request,$id){
        $capital = Capital::findOrFail($id);
        $capital->category_id = $request->category;
        $capital->initial_capital_amount = $request->edit_capital_amount;
        $capital->current_capital_amount = $request->edit_capital_amount;
        $capital->date = $request->date;
        $capital->update();
        $msg = "Capital Updated Successfully";
        return redirect()->back()->with('status',$msg);
    }

    public function sharedContribution(Request $request){
        $request->validate([
            'amount' => 'required',
            'head_id' => 'required',
            'fund_id' => 'required',
            'payment_type' => 'required',
            'date' => 'required',
        ]);

        $capital = Capital::where('head_id',$request->head_id)->first();
        //Capital Data Update
        $capital->total_contribution     += $request->amount;
        $capital->current_capital_amount += $request->amount;
        $capital->update();

        //Income Data Insert
        $income   = new Income;
        $voucher  = Income::where('income_type', 'general')->latest()->first();
        $voucherNumber = 'INC-' . date('Y') . (($voucher ? $voucher->id : 0) + 1);
        $codeNumber   = 'INC-' . (($voucher ? $voucher->id : 0) + 1);
        $income->company_id     = Session::get('company_id');
        $income->category_id    = $request->category_id;
        $income->head_id        = $request->head_id;
        $income->voucher_no     = $voucherNumber;
        $income->code_no        = $codeNumber;
        $income->amount         = $request->amount;
        $income->income_type    = 'contribution';
        $income->payment_date   = $request->date;
        $income->attachment     = $request->attachment;
        $income->created_by     = auth()->user()->id;

        if ($request->attachment != null) {
            $newImageName = time() . '_expense.' . $request->attachment->extension();
            $request->attachment->move(public_path('attachment'), $newImageName);
            $income->attachment = $newImageName;
        }
        $income->save();
        $income_id = $income->id;

        //Income Details Insert
        $details = new IncomeDetails;
        $details->income_id      = $income_id;
        $details->fund_id        = $request->fund_id;
        $details->bank_id        = $request->bank_id;
        $details->account_id     = $request->account_id;
        $details->cheque_no      = $request->cheque_no;
        $details->payment_type   = $request->payment_type;
        $details->cheque_issue_date      = $request->cheque_issue_date;
        $details->remarks        = $request->remarks;
        $details->amount         = $request->amount;
        $details->created_by     = auth()->user()->id;
        $details->save();

        //Fund Log Insert
        $fundLog = new  FundLog;
        $fundLog->company_id = session()->get('company_id');
        $fundLog->fund_id = $request->fund_id;
        $fundLog->type = '1';
        $fundLog->amount = $request->amount;
        $fundLog->transection_type = 'income';
        $fundLog->transection_id = $income->id;
        $fundLog->transection_date = $request->date;
        $fundLog->payment_type = $request->payment_type;
        $fundLog->status = '1';
        $fundLog->created_by = auth()->user()->id;
        $fundLog->save();

        $bankAccount = BankAccount::find($request->account_id);

        if($bankAccount){
            $bankAccount = BankAccount::find($request->account_id);
            if($bankAccount){
                $bankAccount->current_balance += $request->amount;
                $bankAccount->update();
            }
            $fund_current_balance = FundCurrentBalance::where('fund_id',$request->fund_id)->where('bank_id',$request->bank_id)->first();
            if($fund_current_balance){
                $fund_current_balance->amount += $request->amount;
                $fund_current_balance->update();
            }else{
                $fund_current_balance = new FundCurrentBalance;
                $fund_current_balance->company_id = session()->get('company_id');
                $fund_current_balance->fund_id = $request->fund_id;
                $fund_current_balance->bank_id = $request->bank_id;
                $fund_current_balance->amount = $request->amount;
                $fund_current_balance->save();
            }
        }else{
            $fund_current_balance = FundCurrentBalance::where('fund_id', $request->fund_id)->where('bank_id', null)->first();
            if ($fund_current_balance) {
                $fund_current_balance->amount += $request->amount;
                $fund_current_balance->update();
            } else {
                $fund_current_balance = new FundCurrentBalance;
                $fund_current_balance->company_id = session()->get('company_id');
                $fund_current_balance->fund_id = $request->fund_id;
                $fund_current_balance->amount = $request->amount;
                $fund_current_balance->save();
            }
        }

        $msg = "Contribution Added Successfully";
        return redirect()->back()->with('status',$msg);
    }

    public function sharedWithdraw(Request $request){
        $capital = Capital::where('head_id',$request->head_id)->first();
        //Capital Data Update
        $capital->total_withdraw         += $request->amount;
        $capital->current_capital_amount -= $request->amount;
        $capital->update();

        //Income Data Insert
        $expense   = new Expense;
        $voucher  = Expense::where('expense_type', 'general')->latest()->first();
        $voucherNumber = 'EXP-' . date('Y') . (($voucher ? $voucher->id : 0) + 1);
        $codeNumber    = 'EXP-' . (($voucher ? $voucher->id : 0) + 1);
        $expense->company_id     = Session::get('company_id');
        $expense->category_id    = $request->category;
        $expense->head_id        = $request->head_id;
        $expense->voucher_no     = $voucherNumber;
        $expense->code_no        = $codeNumber;
        $expense->fund_id        = $request->fund_id;
        $expense->payment_type   = $request->payment_type;
        $expense->remarks        = $request->remarks;
        $expense->amount         = $request->amount;
        $expense->expense_type   = 'withdraw';
        $expense->payment_date   = $request->date;
        $expense->created_by     = auth()->user()->id;

        if ($request->attachment != null) {
            $newImageName = time() . '_expense.' . $request->attachment->extension();
            $request->attachment->move(public_path('attachment'), $newImageName);
            $expense->attachment = $newImageName;
        }

        $expense->save();

        $expense_id = $expense->id;

        //EXPENSE BankInfo Insert
        $bank = new ExpenseBankInfo();
        $bank->master_id  = $expense_id;
        $bank->bank_id    = $request->bank_id;
        $bank->account_id = $request->account_id;
        $bank->account_holder = $request->account_holder;
        // $bank->note = $request->note;
        $bank->cheque_no  = $request->cheque_no;
        $bank->cheque_issue_date = $request->cheque_issue_date;
        $bank->amount = $request->amount;
        $bank->save();

        //Fund Log Insert
        $fundLog = new  FundLog;
        $fundLog->company_id = session()->get('company_id');
        $fundLog->fund_id = $request->fund_id;
        $fundLog->type = '2';
        $fundLog->amount = $request->amount;
        $fundLog->transection_type = 'expense';
        $fundLog->transection_id = $expense->id;
        $fundLog->transection_date = $request->date;
        $fundLog->payment_type = $request->payment_type;
        $fundLog->status = '1';
        $fundLog->created_by = auth()->user()->id;
        $fundLog->save();

        $bankAccount = BankAccount::find($request->account_id);
        if($bankAccount){
            // $bankAccount = BankAccount::find($request->account_id);
            if($bankAccount){
                $bankAccount->current_balance -= $request->amount;
                $bankAccount->update();
            }
            $fund_current_balance = FundCurrentBalance::where('fund_id',$request->fund_id)->where('bank_id',$request->bank_id)->first();
            if($fund_current_balance){
                $fund_current_balance->amount -= $request->amount;
                $fund_current_balance->update();
            }else{
                $fund_current_balance = new FundCurrentBalance;
                $fund_current_balance->company_id = session()->get('company_id');
                $fund_current_balance->fund_id = $request->fund_id;
                $fund_current_balance->bank_id = $request->bank_id;
                $fund_current_balance->amount = $request->amount;
                $fund_current_balance->save();
            }
        }else{
            $fund_current_balance = FundCurrentBalance::where('fund_id', $request->fund_id)->where('bank_id', null)->first();
            if ($fund_current_balance) {
                $fund_current_balance->amount -= $request->amount;
                $fund_current_balance->update();
            } else {
                $fund_current_balance = new FundCurrentBalance;
                $fund_current_balance->company_id = session()->get('company_id');
                $fund_current_balance->fund_id = $request->fund_id;
                $fund_current_balance->amount = $request->amount;
                $fund_current_balance->save();
            }
        }

        $msg = "Withdraw Added Successfully";
        return redirect()->back()->with('status',$msg);

    }

    public function transferIndex(){
        $data['main_menu']     = 'Capital';
        $data['child_menu']    = 'capital_transfer';
        $data['capitals']      = Capital::all();
        // $data['accountHeads']  = AccountHead::whereBetween('id',[1347,1375])->get();
        $data['accountHeads']  = AccountHead::all();
        $data['transferData']  = CapitalTransfer::get();
        // dd($data['accountHeads']);
        return view('capital.manage_capital_transfer',$data);
    }

    public function sharedTransfer(Request $request){
        $capital = Capital::where('head_id',$request->from_head_id)->first();
        //Capital Data Update
        // dd($capital);
        if($capital->current_capital_amount == 0){
            $msg = "Insufficient Balance";
            return redirect()->back()->with('status',$msg);
        }
        if($request->amount == null){
            $msg = "Please Enter Amount";
            return redirect()->back()->with('status',$msg);
        }
        if($capital->current_capital_amount < $request->amount){
            $msg = "Insufficient Balance";
            return redirect()->back()->with('status',$msg);
        }

        if($request->from_head_id == $request->to_head_id){
            $msg = "From and To Head Can't be Same";
            return redirect()->back()->with('status',$msg);
        }

        $capital->total_transfer         += $request->amount;
        $capital->current_capital_amount -= $request->amount;
        $capital->update();

        //CapitalTransfer Data Insert

        $capitalTransfer = new CapitalTransfer;
        $capitalTransfer->from_head_id = $request->from_head_id;
        $capitalTransfer->to_head_id   = $request->to_head_id;
        $capitalTransfer->from_capital_id = $capital->id;
        $capitalTransfer->amount = $request->amount;
        $capitalTransfer->date = $request->date;
        $capitalTransfer->remarks = $request->remarks;
        $capitalTransfer->created_by = auth()->user()->id;


        $fromCapital = Capital::find($request->from_capital_id);
        if($fromCapital){
            $fromCapital->current_capital_amount -= $request->amount;
            $fromCapital->update();
        }
        $toCapital = Capital::find($request->to_capital_id);
        if($toCapital){
            $toCapital->current_capital_amount += $request->amount;
            $toCapital->update();
        }else{
            $toCapital = new Capital;
            $toCapital->head_id = $request->to_head_id;
            $toCapital->initial_capital_entry_date = $request->date;
            $toCapital->created_by = auth()->user()->id;
            $toCapital->initial_capital_amount = $request->amount;
            $toCapital->current_capital_amount = $request->amount;
            $toCapital->save();
        }
        $capitalTransfer->to_capital_id = $toCapital->id;

        if($request->attachment != null){
            $newImageName = time().'_transfer.'.$request->attachment->extension();
            $request->attachment->move(public_path('attachment'),$newImageName);
            $capitalTransfer->attachment = $newImageName;
        }

        $capitalTransfer->save();

        $msg = "Transfer Added Successfully";
        return redirect()->back()->with('status',$msg);
    }
}
