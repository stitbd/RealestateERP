<?php
namespace App\Http\Controllers;

use Session;
use App\Models\Income;
use App\Models\Company;
use App\Models\Expense;
use App\Models\FundLog;
use App\Models\License;
use App\Models\Project;
use App\Models\PettyCash;
use Illuminate\Http\Request;
use App\Models\VendorPayment;
use App\Models\ExpenseDetails;
use App\Models\RequisitionPayment;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Session::get('company_id')){
            $data['project_receiveds'] = RequisitionPayment::groupBy('payment_date')
            ->selectRaw('sum(amount) as sum_amount,payment_date')->where('company_id',Session::get('company_id'))->whereYear('payment_date',date('Y'))->whereMonth('payment_date',date('m'))->get();

            $data['vendor_payments'] = VendorPayment::groupBy('payment_date')
            ->selectRaw('sum(amount) as sum_amount,payment_date')->where('company_id',Session::get('company_id'))->whereYear('payment_date',date('Y'))->whereMonth('payment_date',date('m'))->get();
            
            $data['daily_cash_in'] = FundLog::groupBy('payment_type')
            ->selectRaw('sum(amount) as amount, payment_type')
            ->where('company_id',Session::get('company_id'))->where(['status'=>1,'type'=>'1'])->get();
            
            $data['daily_cash_out'] = FundLog::groupBy('payment_type')
            ->selectRaw('sum(amount) as amount, payment_type')
            ->where('company_id',Session::get('company_id'))->where(['status'=>1,'type'=>'2'])->get();
            $expire_date = date('Y-m-d', strtotime('+1 month'));
            $data['licenses'] = License::where('status','=',1)->whereDate('expire_date','<=',$expire_date)->where('company_id','=',Session::get('company_id'))->get();
            $data['date'] = $date = date('Y-m-d');
            $data['petty_cash']  = PettyCash::select(DB::raw('DATE(given_date) as given_date'), DB::raw('SUM(amount) as total_amount'))
                        ->where('user_id', auth()->user()->id)
                        ->groupBy('given_date')
                        ->get();
                        
            $data['project_data']   = Project::where('company_id',session()->get('company_id'))->get();

            $data['recent_debit']   = Expense::where(['company_id' =>session()->get('company_id'),'status'=>1])->orderBy('id', 'desc')->take(10)->get();
            $data['recent_credit']  = Income::where(['company_id' =>session()->get('company_id'),'status'=>1,'income_type'=>'general'])->orderBy('id', 'desc')->take(10)->get();
            $data['allExpense']     = Expense::where(['company_id' =>session()->get('company_id'),'status'=>1])->get();
            $data['allIncome']      = Income::where(['company_id' =>session()->get('company_id'),'status'=>1,'income_type'=>'general'])->get();
            $data['todayIncome']        = Income::where(['company_id' =>session()->get('company_id'),'status'=>1,'income_type'=>'general','payment_date'=>date('Y-m-d')])->sum('amount');
            $data['todayExpense']       = Expense::where(['company_id' =>session()->get('company_id'),'status'=>1,'payment_date'=>date('Y-m-d')])->sum('amount');
            $data['todayProjectReceived']      = Income::where(['company_id' =>session()->get('company_id'),'status'=>1,'income_type'=>'general','payment_date'=>date('Y-m-d')])->whereNotNull('project_id')->sum('amount');
            $data['todayProjectPayment']       = Expense::where(['company_id' =>session()->get('company_id'),'status'=>1,'payment_date'=>date('Y-m-d')])->whereNotNull('project_id')->sum('amount');
            return view('home',$data);
        }
        else{
            if(auth()->user()->company_id != null  && auth()->user()->role != 'SuperAdmin') {
                //dd('exist');
                $company = Company::find(auth()->user()->company_id);
                $company_data['company_id'] = $company->id;
                $company_data['company_name'] = $company->name;
                $company_data['company_logo'] = $company->logo;
                $company_data['company_address'] = $company->address;
                $company_data['company_email'] = $company->email;
                $company_data['company_phone'] = $company->phone;
    
                Session::put($company_data);
    
                return redirect('home');
            }
            else{
                $data['companies']  = Company::all();
                return view('select_company',$data);
            }
        }
        
        
        //dd(auth()->user()->company_name);   
    }

    public function select_company($company_id){
        $company = Company::find($company_id);
        $company_data['company_id'] = $company->id;
        $company_data['company_name'] = $company->name;
        $company_data['company_logo'] = $company->logo;
        $company_data['company_address'] = $company->address;
        $company_data['company_email'] = $company->email;
        $company_data['company_phone'] = $company->phone;

        Session::put($company_data);

        return redirect('home');
    }
}
