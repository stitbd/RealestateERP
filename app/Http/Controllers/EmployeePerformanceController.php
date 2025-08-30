<?php

namespace App\Http\Controllers;

use App\Models\EmployeePerformance;
use Illuminate\Http\Request;
use App\Models\Employee;
use Session;

class EmployeePerformanceController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'employee-performance';
        $data['employee']               = Employee::where(['company_id'=>Session::get('company_id'),'status'=>1])->get();
        $data['employee_performance']    = EmployeePerformance::where(['company_id'=>Session::get('company_id')])->with('employee')->get();
        return view('hrm.performance_list',$data);
    }

   
    public function store(Request $request)
    {

        $employee_performance               = new EmployeePerformance();
        $employee_performance->employee_id  = $request->employee_id;
        $employee_performance->rating       = $request->rating;
        $employee_performance->description  = $request->description;
        $employee_performance->company_id   = Session::get('company_id');
        $employee_performance->status       = 1;
        $employee_performance->created_by   = auth()->user()->id;
        $employee_performance->save();

        return redirect('employee-performance')->with('message', 'Employee Performance Added Successfully!');
    }

    
    public function update(Request $request)
    {
        $employee_performance               = EmployeePerformance::find($request->id);
        $employee_performance->employee_id  = $request->employee_id;
        $employee_performance->rating       = $request->rating;
        $employee_performance->description  = $request->description;
        $employee_performance->company_id   = Session::get('company_id');
        $employee_performance->status       = 1;
        $employee_performance->updated_by   = auth()->user()->id;
        $employee_performance->save();

        return redirect('employee-performance')->with('message', 'Employee Performance Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeePerformance  $employeePerformance
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmployeePerformance $employeePerformance)
    {
        //
    }
}
