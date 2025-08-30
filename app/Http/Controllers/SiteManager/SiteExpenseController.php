<?php

namespace App\Http\Controllers\SiteManager;

use App\Models\Fund;
use App\Models\Item;
use App\Models\Company;
use App\Models\Project;
use App\Models\AccountHead;
use App\Models\SiteExpense;
use Illuminate\Http\Request;
use App\Models\AccountCategory;
use App\Models\SiteOpeningBalance;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class SiteExpenseController extends Controller
{
    public function openingBalance(){
        $data['opening_balance'] = SiteOpeningBalance::where('project_id',auth()->user()->project_id)->get();
        return view('site_manager.expense.opening_balance',$data);
    }

    public function openingBalanceStore(Request $requset){
        $opening_balance             = new SiteOpeningBalance;
        $opening_balance->date       = $requset->date;
        $opening_balance->amount     = $requset->amount;
        $opening_balance->project_id = auth()->user()->project_id;
        $opening_balance->created_by = auth()->user()->id;
        $opening_balance->save();
        $msg="Opening Balance Added...";
        return redirect()->route('site-opening-balance')->with('status', $msg);

    }

    public function siteExpenseList(Request $request){
        $data['main_menu']                   = 'Expense';
        $data['child_menu']                  = 'expense-list';
        $expense_data                        = SiteExpense::where('project_id',auth()->user()->project_id)->where('status',1);


        if($request->category || $request->head || $request->start_date ||$request->end_date){

            if($request->category){
                $where['category_id'] = $request->category;
                $expense_data->where('main_head_id','=',$request->category);
                $data['serachcategoryId'] = $request->category;
                
                // dd($expense_data);
            }
            if($request->head){
                $where['head_id'] = $request->head;
                $expense_data->where('sub_head_id','=',$request->head);
                $data['serachHeadId'] = $request->head;
            }
           
            if($request->start_date ||$request->end_date){
                $where['start_date'] = $request->start_date;
                $expense_data->whereDate('payment_date','>=',$request->start_date);
                $where['end_date'] = $request->end_date;
                $expense_data->whereDate('payment_date','<=',$request->end_date);
                $expense_data->orderBy('payment_date','ASC');
            
            }
        }
        $expense_data                   = $expense_data->get();
        $data['expense']           = $expense_data;

        $data['categories']                  = AccountCategory::where('only_head_office',0)->get();
        $data['head']                        = AccountHead::where('only_head_office',0)->get();
        // dd( $data['expenses']);
        return view('site_manager.expense.index',$data);
    }

    public function siteExpenseEntryform(){
        $data['main_menu']           = 'Expense';
        $data['child_menu']          = 'expense-entry';
        $data['categories']          = AccountCategory::where('only_head_office',0)->get();
        $data['head']                = AccountHead::where('only_head_office',0)->get();
        $data['user_type']           = auth()->user()->role;                                     
        $data['project']             = Project::get();
        $expense_code                = SiteExpense::where('project_id',auth()->user()->project_id)->latest()->first();
        // dd( $expense_code );

        if($expense_code){
            $data['lastExpenseId']           = $expense_code->id;
        }

          $project = Project::where('id',auth()->user()->project_id)->first();
          $data['project_id']    = $project->id;
          $data['project_name']  = $project->name;
          
        //   dd($data['project_name']);

        return view('site_manager.expense.create',$data);
    }


    public function siteExpenseStore(Request $request){
        // return $request->all();
        if($request->amount){
            $lastVoucher = SiteExpense::where('project_id',auth()->user()->project_id)->latest()->first();
            $lastNumber = ($lastVoucher) ? $lastVoucher->id : 0;
            $date = now()->format('Y');
            $voucherNumber = "PROJ00".auth()->user()->project_id."-VHR-" . $date . $lastNumber + 1;

            $expense   = new SiteExpense;

            if($request->attachment != null){
                $newImageName =  time().'_expense.'.$request->attachment->extension();
                $request->attachment->move(public_path('attachment'),$newImageName);
                $expense->attachment = $newImageName;
            }

            $expense->project_id              = $request->project;
            $expense->main_head_id            = $request->category;
            $expense->sub_head_id             = $request->head;
            $expense->code_no                 = $request->code_no;
            $expense->voucher_no              = $voucherNumber;
            $expense->payment_date            = $request->payment_date;
            $expense->amount                  = $request->amount;
            $expense->remarks                 = $request->remarks;
            $expense->expenser_name           = $request->expenser_name;
            $expense->expenser_designation    = $request->designation;
            $expense->status                  = '1';
            $expense->created_by               = auth()->user()->id;

            $expense->save();

            $msg="Expense Data Stored Successfully...";
            return redirect()->back()->with('status', $msg);  

        }else{
            $msg="Something Went Wrong....";
            return redirect()->back()->with('status', $msg); 
        }
    }

    public function printSiteDebitVoucher($id){
        try{
            $model               = SiteExpense::where('id',$id)->first();
            $company_info        = Company::where('id',auth()->user()->company_id)->first();
            
            return view('site_manager.expense.print_voucher',compact('model','company_info'));

        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function editSiteExpense($id){
        $data['editExpense']         = SiteExpense::find($id);
        $data['categories']          = AccountCategory::where('only_head_office',0)->get();
        $data['head']                = AccountHead::where('only_head_office',0)->get();
        $data['project']             = Project::get();
        return view('site_manager.expense.edit',$data);
    }


    public function updateSiteExpense(Request $request,$id){
        if($request->amount){

            $expense    =  SiteExpense::find($id);

            if ($request->hasFile('attachment')) {
                $request->validate([
                    'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', 
                ]);
                if ($expense->attachment && Storage::exists('public/attachment/' . $expense->attachment)) {
                    Storage::delete('public/attachment/' . $expense->attachment);
                }
                $newImageName =  time().'_expense.'.$request->attachment->extension();
                $request->attachment->move(public_path('attachment'),$newImageName);
                $expense->attachment = $newImageName;
            }

            $expense->main_head_id            = $request->category;
            $expense->sub_head_id             = $request->head;
            $expense->code_no                 = $request->code_no;
            $expense->payment_date            = $request->payment_date;
            $expense->amount                  = $request->amount;
            $expense->remarks                 = $request->remarks;
            $expense->expenser_name           = $request->expenser_name;
            $expense->expenser_designation    = $request->designation;
            $expense->updated_by               = auth()->user()->id;
            $expense->update();

            $msg="Expense Data Updated Successfully...";
            return redirect()->back()->with('status', $msg);  

        }else{
            $msg="Something Went Wrong....";
            return redirect()->back()->with('status', $msg); 
        }
    }

    public function delete($id){
        $theExpense = SiteExpense::find($id);
        $theExpense->delete();

        $msg="Data Deleted Successfully...";
        return redirect()->back()->with('status', $msg);  
    }

    
}
