<?php

namespace App\Http\Controllers\SiteManager;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\SiteExpense;
use App\Models\SiteOpeningBalance;
use Illuminate\Http\Request;

class SiteReportController extends Controller
{
    public function dailyLedger(){
        $data['main_menu']        = 'Ledger';
        $data['child_menu']       = 'daily-ledger';
        $data['site_pay']         = Expense::where(['project_id'=>auth()->user()->project_id,'category_id'=>1,'status'=>1,'payment_date'=>date('Y-m-d')])->first();
        $data['opening_balance']  = SiteOpeningBalance::where(['project_id'=>auth()->user()->project_id,'date'=>date('Y-m-d')])->first();
        $data['site_expense']     = SiteExpense::where(['project_id'=>auth()->user()->project_id,'payment_date'=>date('Y-m-d')])->get();

        $prev_site_pay            = Expense::where(['project_id'=>auth()->user()->project_id,'category_id'=>1,'status'=>1])->where('payment_date','<',date('Y-m-d'))->sum('amount');
        $prev_site_opening        = SiteOpeningBalance::where(['project_id'=>auth()->user()->project_id])->where('date','<',date('Y-m-d'))->sum('amount');
        $prev_site_expense        = SiteExpense::where(['project_id'=>auth()->user()->project_id,'status'=>1])->where('payment_date','<',date('Y-m-d'))->sum('amount');

        $prev_balance = 0;
        if($prev_site_pay){
            $prev_balance += $prev_site_pay;
        }

        if($prev_site_opening){
            $prev_balance += $prev_site_opening;
        }

        if($prev_site_expense){
            $prev_balance -= $prev_site_expense;
        }
        $data['prev_balance']  = $prev_balance;
        
        return view('site_manager.report.daily_ledger',$data);
    }

    public function datewiseDailyLedger(Request $request){

        $data['start_date'] =   $start_date    = $request->start_date;
        $data['end_date']   =   $end_date      = $request->end_date;

        $prev_site_pay            = Expense::where(['project_id'=>auth()->user()->project_id,'category_id'=>1,'status'=>1])->where('payment_date','<',$start_date)->sum('amount');
        $prev_site_opening        = SiteOpeningBalance::where(['project_id'=>auth()->user()->project_id])->where('date','<',$start_date)->sum('amount');
        $prev_site_expense        = SiteExpense::where(['project_id'=>auth()->user()->project_id,'status'=>1])->where('payment_date','<',$start_date)->sum('amount');

        $prev_balance = 0;
        if($prev_site_pay){
            $prev_balance += $prev_site_pay;
        }

        if($prev_site_opening){
            $prev_balance += $prev_site_opening;
        }

        if($prev_site_expense){
            $prev_balance -= $prev_site_expense;
        }
        $data['prev_balance']  = $prev_balance;

        $data['site_pay']         = Expense::where(['project_id'=>auth()->user()->project_id,'category_id'=>1,'status'=>1])->where('payment_date','>=',$start_date)->where('payment_date','<=',$end_date)->first();
        $data['opening_balance']  = SiteOpeningBalance::where(['project_id'=>auth()->user()->project_id ])->where('date','>=',$start_date)->where('date','<=',$end_date)->first();
        $data['site_expense']     = SiteExpense::where(['project_id'=>auth()->user()->project_id])->where('payment_date','>=',$start_date)->where('payment_date','<=',$end_date)->get();
        


        return view('site_manager.report.datewise_daily_ledger',$data);
    }
}
