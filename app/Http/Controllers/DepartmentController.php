<?php
/**
 * Author: Tushar Das
 * Sr. Software Engineer
 * tushar2499@gmail.com 
 * 01815920898
 * STITBD
 * 06/10/2021
 */
namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Company;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'department-list';
        $data['department_data']        = Department::with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();

        return view('hrm.basic_settings.department',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required',
            'company_id'            => 'required'
        ]);
        
        
        $model = new Department();
        $model->name                = $request->post('name');
        $model->company_id          = $request->post('company_id');
        $model->save();

        $msg="Department Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('department-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Department::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Department Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('department-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Department::find($request->post('id'));
        $model->name                = $request->post('name');
        $model->company_id          = $request->post('company_id');
        
        $model->save();

        $msg="Department Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('department-list')->with('status', $msg);
    }
}
