<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'leave-type-list';
        $data['leaveType_data']             = LeaveType::all();
        return view('hrm.basic_settings.leave_type',$data);
    }

    public function store(Request $request){
        $request->validate([
            'leave_name'                  => 'required'
        ]);
        
        $model = new LeaveType();
        $model->leave_name                = $request->post('leave_name');
        $model->save();

        $msg="Grade Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('leave-type-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = LeaveType::find($id);
        $model->status          = $status;
        $model->save();

        $msg="LeaveType Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('leave-type-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'leave_name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = LeaveType::find($request->post('id'));
        $model->leave_name                = $request->post('leave_name');
        
        $model->save();

        $msg="LeaveType Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('leave-type-list')->with('status', $msg);
    }
}
