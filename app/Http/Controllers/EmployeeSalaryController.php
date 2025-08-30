<?php

namespace App\Http\Controllers;

use App\Models\Fund;
use App\Models\Company;
use App\Models\FundLog;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\EmployeeSalary;
use App\Models\EmployeeAttendance;
use App\Models\FundCurrentBalance;
use App\Models\EmployeeSalaryPayment;
use Illuminate\Support\Facades\Session;
use App\Models\EmployeeSalaryPaymentDetails;

class EmployeeSalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'employee-salary';
        $data['department_data']        = Department::with('company')->where(['status'=>1])->get();
        $data['company_data']           = Company::where(['status'=>1])->get();
        return view('hrm.salary.salary_generate',$data);
    }

    function select_salary_details($department_id,$start_date,$end_date,$month)
    {
        //dd('test');
        $data['company_data']           = Company::where(['status'=>1,'id'=>Session::get('company_id')])->first();
        $data['department_data']        = Department::find($department_id);

        $data['employee_data']          = Employee::where(['company_id'=>Session::get('company_id')]);
        if($department_id != 'all')
        {
            $data['employee_data']      = $data['employee_data']->where('department_id','=',$department_id);
        }
        $data['employee_data']          = $data['employee_data']->get();
        $data['start_date']             = $start_date;
        $data['end_date']               = $end_date;
        $data['month']                  = $month;
        $data['department_id']          = $department_id;
        return view('hrm.salary.salary_details',$data);
    }

    
    public function store(Request $request)
    {
        //dd($request);
        $month          = $request->month;
        $department_id  = $request->department_id;
        $start_date     = $request->start_date;
        $end_date       = $request->end_date;
        $employee_id    = $request->employee_id;
        $gross_salary   = $request->gross_salary;
        $addition       = $request->addition;
        $deduction      = $request->deduction;
        $total_salary   = $request->total_salary;
        $remarks        = $request->remarks;
        
        $company_data         = Company::where(['status'=>1,'id'=>Session::get('company_id')])->first();
        //dd($company_data);
        if($department_id == 'all')
        {
            $salary_id = substr($company_data->name,0,3).'-ALL-'.$month;
        }
        else
        {
            $department = Department::find($department_id);
            $salary_id = substr($company_data->name,0,3).'-'.substr($department->name,0,3).'-'.$month;
        }

       $salary_id      =  strtoupper($salary_id);

       foreach($employee_id as $key=>$value)
       {
            $employee_salary_data               = new EmployeeSalary();
            $employee_salary_data->salary_id    = $salary_id;
            $employee_salary_data->company_id   = Session::get('company_id');
            $employee_salary_data->employee_id  = $value;
            $employee_salary_data->month        = $month;
            $employee_salary_data->start_date   = $start_date;
            $employee_salary_data->end_date     = $end_date;
            $employee_salary_data->gross_salary = $gross_salary[$key];
            $employee_salary_data->addition     = $addition[$key];
            $employee_salary_data->deduction    = $deduction[$key];
            $employee_salary_data->total_salary = $total_salary[$key];
            $employee_salary_data->remarks      = $remarks[$key];
            $employee_salary_data->created_by   = auth()->user()->id;
            $employee_salary_data->status       = 1;
            $employee_salary_data->save();
       }
        $msg="Employee Salary Generated Successfully!";
        $request->session()->flash('message',$msg);
       return redirect('employee-salary')->with('message', 'Employee Salary Generated Successfully!');
    }

    function print_salary_details($salary_id)
    {
        $data['company_data']           = Company::where(['status'=>1,'id'=>Session::get('company_id')])->first();
        $data['salary_data']            = EmployeeSalary::with('employee')->where(['salary_id'=>$salary_id])->get();
        $data['salary_id']              = $salary_id;
        
        $data['department']             = Department::find($data['salary_data'][0]->employee->department_id);
        return view('hrm.salary.print_salary_details',$data);
    }


    function list(Request $request){
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'employee-salary-list';
        $data['department_data']        = Department::with('company')->where(['status'=>1])->get();
        
        $salary_data            = EmployeeSalary::with('employee')->where(['company_id'=>Session::get('company_id')])->orderBy('id','DESC');
        if($request->month){
            $salary_data = $salary_data->where('month','=',$request->month);
        }
        else{
            $salary_data = $salary_data->limit(20);
        }
        $data['salary_data'] = $salary_data->get();
        return view('hrm.salary.salary_list',$data);
    }

    public function salary_payment($salary_id){
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'employee-salary-list';
        $data['salary_data']            = EmployeeSalary::with('employee')->where(['id'=>$salary_id])->first();
        $data['salary_id']              = $salary_id;
        //dd($data['salary_data']);
        $data['fund_data']              = Fund::where(['status'=>1])->get();
        return view('hrm.salary.salary_payment',$data);
    }

    public function save_salary_payment(Request $request){
        //dd($request);
        $employee_salary_id = $request->employee_salary_id;
        $employee_id = $request->employee_id;
        $payment_date = $request->payment_date;
        $fund_id = $request->fund_id;
        $payment_type = $request->payment_type;
        $amount = $request->amount;
        $receiver_name = $request->receiver_name;
        $mobile_no = $request->mobile_no;
        $nid = $request->nid;
        $address = $request->address;
        $check_number = $request->check_number;
        $check_issue_date = $request->check_issue_date;
        $bank_name = $request->bank_name;
        $bank_account_no = $request->bank_account_no;
        $account_holder_name = $request->account_holder_name;
        $payment_note = $request->payment_note;
        $remarks = $request->remarks;
        if($employee_salary_id != null && $amount != null && $employee_id != null && $payment_date != null && $fund_id != null){
            $model = new EmployeeSalaryPayment();
            $model->company_id      = Session::get('company_id');
            $model->employee_salary_id      = $employee_salary_id;
            $model->employee_id       = $employee_id;
            $model->payment_date    = $payment_date;
            $model->fund_id         = $fund_id;
            $model->payment_type    = $payment_type;
            $model->amount          = $amount;
            $model->status          = '1';
            $model->created_by      = auth()->user()->id;
            $model->save();

            $employee_salary_data = EmployeeSalary::find($employee_salary_id);
            $employee_salary_data->paid_status = 1;
            $employee_salary_data->save();

            $payment_id = $model->id;

            $details = new EmployeeSalaryPaymentDetails();
            $details->payment_id = $payment_id;
            $details->receiver_name = $receiver_name;
            $details->mobile_no = $mobile_no;
            $details->nid = $nid;
            $details->address = $address;
            $details->check_number = $check_number;
            $details->check_issue_date = $check_issue_date;
            $details->bank_name = $bank_name;
            $details->bank_account_no = $bank_account_no;
            $details->account_holder_name = $account_holder_name;
            $details->payment_note = $payment_note;
            $details->remarks = $remarks;
            if($request->attachment != null){
                $newImageName = time().'_supplier_payment.'.$request->attachment->extension();
                $request->attachment->move(public_path('attachment'),$newImageName);

                $details->attachment = public_path('attachment/'.$newImageName);
            }

            $details->status          = '1';
            $details->created_by      = auth()->user()->id;
            $details->save();
            

            

            $fund_log                       = new FundLog();
            $fund_log->company_id           = Session::get('company_id');
            $fund_log->fund_id              = $request->fund_id;
            $fund_log->type                 = '2';
            $fund_log->amount               = $amount;
            $fund_log->transection_type     = 'salary_payment';
            $fund_log->transection_id       = $payment_id;
            $fund_log->transection_date     = $payment_date;
            $fund_log->status               = '1';
            $fund_log->created_by           = auth()->user()->id;
            $fund_log->save();

            $fund = FundCurrentBalance::where(['fund_id'=>$fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
            if($fund != null){
                $fund->amount = $fund->amount - $amount;
                $fund->updated_by = auth()->user()->id;
                $fund->update();
            }
            else{
                $fund = new FundCurrentBalance();
                $fund->company_id = Session::get('company_id');
                $fund->fund_id = $fund_id;
                $fund->amount = $amount;
                $fund->status = '1';
                $fund->created_by = auth()->user()->id;
                $fund->save();
            }
            

            $msg="Payment Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Payment Not Updated.";
            $request->session()->flash('warning',$msg);
        }
        
        return redirect('employee-salary-list')->with('status', $msg);

    }
    
}
