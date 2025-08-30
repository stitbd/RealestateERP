<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAttendance;
use App\Models\EmployeeAttendanceLog;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;
use Session;
use View;

class EmployeeAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'attendance-list';
        $data['department_data']        = Department::with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();
        $data['employee']               = Employee::where(['company_id'=>Session::get('company_id'),'status'=>1])->get();
        $attendance_data                = EmployeeAttendance::with('employee','employee.department')->where(['company_id'=>Session::get('company_id')])->where('status','!=',0)->orderBy('id','DESC');
        $where                          = [];
        if($request->employee_id){
            $where['employee_id'] = $request->employee_id;
        }
        if($request->attendance_date){
            $where['attendance_date'] = $request->attendance_date;
        }
        //dd($attendance_data);
        $attendance_data = $attendance_data->where($where);
        $attendance_data = $attendance_data->paginate(20);
        $attendance_data->appends($where);
        $data['attendance_data'] = $attendance_data;
        return view('hrm.attendance.list',$data);
    }

    public function create()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'attendance-entry';
        $data['department_data']        = Department::with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();
        $data['employee']               = Employee::where(['company_id'=>Session::get('company_id'),'status'=>1])->get();
        return view('hrm.attendance.entry',$data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'attendance_date' => 'required',
            'attendance_time' => 'required'
        ]);

        $employee = Employee::with('schedule')->where(['id'=>$request->employee_id])->first();

        $attendance_check = EmployeeAttendance::where(['employee_id'=>$request->employee_id,'attendance_date'=>$request->attendance_date])->first();
        if($attendance_check == null){
            $start_time = $employee->schedule->start_time;
            if($start_time >= $request->attendance_time){
                $attendance_status = '1';
            }
            else{
                $attendance_status = '2';
            }

            $attendance_data = array(
                'employee_id'       => $request->employee_id,
                'attendance_date'   => $request->attendance_date,
                'check_in_time'     => $request->attendance_time,
                'check_out_time'    => $request->attendance_out_time,
                'attendance_status' => $attendance_status,
                'company_id'        => Session::get('company_id'),
                'created_by'        => auth()->user()->id
            );
            $attendance_id = EmployeeAttendance::insertGetId($attendance_data);
        }
        else{
            $attendance_check->check_out_time = $request->attendance_out_time;
            $attendance_check->updated_by = auth()->user()->id;
            $attendance_check->save();
        }

        if($request->attendance_time != null){
            $attendance_log_data = array(
                'employee_id'       => $request->employee_id,
                'attendance_date'   => $request->attendance_date,
                'check_time'        => $request->attendance_time,
                'comment'           => $request->comment,
                'company_id'        => Session::get('company_id'),
                'created_by'        => auth()->user()->id
            );
            EmployeeAttendanceLog::insert($attendance_log_data);
        }

        if($request->attendance_out_time != null){
            $attendance_log_data = array(
                'employee_id'       => $request->employee_id,
                'attendance_date'   => $request->attendance_date,
                'check_time'        => $request->attendance_out_time,
                'comment'           => $request->comment,
                'company_id'        => Session::get('company_id'),
                'created_by'        => auth()->user()->id
            );
            EmployeeAttendanceLog::insert($attendance_log_data);
        }
        
        $msg="Employee Attendance Inserted.";
        $request->session()->flash('message',$msg);

        return redirect('attendance-entry')->with('status', $msg);
    }

    
    public function update(Request $request)
    {
        $employee_performance                       = EmployeeAttendance::find($request->id);
        $employee_performance->check_in_time        = $request->check_in_time;
        $employee_performance->check_out_time       = $request->check_out_time;
        $employee_performance->updated_by           = auth()->user()->id;
        $employee_performance->save();

        return redirect('attendance-list')->with('message', 'Employee Attendance Updated Successfully!');
    }

    public function monthly_report(Request $request){
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'attendance-monthly-report';
        $data['department_data']        = Department::with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();
        $data['employee']               = Employee::where(['company_id'=>Session::get('company_id'),'status'=>1])->get();
        return view('hrm.attendance.monthly_report',$data);
    }

    public function monthly_attendance_report_ajax($department_id='',$start_date='',$end_date=''){
        $employee_data = Employee::where(['company_id'=>Session::get('company_id')])->where('status','!=',0);
        
        if($department_id!=0){
            $employee_data = $employee_data->where('department_id',$department_id);
        }
        $data['department'] = Department::where(['id'=>$department_id])->first();
        $data['employee_data']  = $employee_data->get();
        $data['start_date']     = $start_date;
        $data['end_date']       = $end_date;
        $data['department_id']  = $department_id;

        return view('hrm.attendance.monthly_report_ajax',$data);
    }

    public function monthly_attendance_report_print($department_id='',$start_date='',$end_date=''){
        $employee_data = Employee::where(['company_id'=>Session::get('company_id')])->where('status','!=',0);
        
        if($department_id!=0){
            $employee_data = $employee_data->where('department_id',$department_id);
        }
        $data['department'] = Department::where(['id'=>$department_id])->first();
        $data['employee_data']  = $employee_data->get();
        $data['start_date']     = $start_date;
        $data['end_date']       = $end_date;
        $data['department_id']  = $department_id;

        return view('hrm.attendance.monthly_report_print',$data);
    }

    public function job_card(Request $request){
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'job-card';
        $data['company_data']           = Company::where(['status'=>1])->get();
        $data['employee']               = Employee::where(['company_id'=>Session::get('company_id'),'status'=>1])->get();
        return view('hrm.attendance.job_card',$data);
    }

    public function job_card_ajax($employee_id='',$start_date='',$end_date=''){
        $data['employee_data']  = Employee::with('department','branch','company','schedule')->where(['id'=>$employee_id])->first();
        $data['start_date']     = $start_date;
        $data['end_date']       = $end_date;

        return view('hrm.attendance.job_card_ajax',$data);
    }

    public function job_card_print($employee_id='',$start_date='',$end_date=''){
        $data['employee_data']  = Employee::with('department','branch','company','schedule')->where(['id'=>$employee_id])->first();
        $data['start_date']     = $start_date;
        $data['end_date']       = $end_date;

        return view('hrm.attendance.job_card_print',$data);
    }
}
