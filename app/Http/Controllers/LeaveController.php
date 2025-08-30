<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Http\Request;


class LeaveController extends Controller
{

    public function index(Request $request)
    {
        //dd(Leave::leave_status(2,'2021-11-22'));
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'leave-report';
        $data['employees']              = Employee::where('status', 1)->get();
        $data['leave_types']            = LeaveType::where('status', 1)->get();
        $leave = Leave::with('employee','leave_type')->where('status', 1)->orderBy('leave_date', 'desc');
        $where = [];
        if($request->employee_id){
            $where['employee_id'] = $request->employee_id;
        }
        if($request->leave_type_id){
            $where['leave_type_id'] = $request->leave_type_id;
        }
        $leave=$leave->where($where);
        if($request->start_date){
            $where['start_date'] = $request->start_date;
            $leave=$leave->where('leave_date','>=',$request->start_date);
        }
        if($request->end_date){
            $where['end_date'] = $request->end_date;
            $leave=$leave->where('leave_date','<=',$request->end_date);
        }
        $leaves = $leave->paginate(20);
        //dd($leaves);
        $leaves->appends($where);
        $data['leaves'] = $leaves;
        return view('hrm.leave.leave_report',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'leave';
        $data['employees']              = Employee::where('status', 1)->get();
        $data['leave_types']            = LeaveType::where('status', 1)->get();
        return view('hrm.leave.create_leave',$data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'leave_type_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        $end_date       = strtotime($request->end_date);
        $start_date     = strtotime($request->start_date);
        $datediff       = $end_date - $start_date;

        $count_days = round($datediff / (60 * 60 * 24))+1;
        if($request->attachment != null){
            $newImageName = time().'_leave.'.$request->attachment->extension();
            $request->attachment->move(public_path('attachment'),$newImageName);

            $attachment = 'attachment/'.$newImageName;
        }
        for($i=0; $i<$count_days; $i++){
            $leave_date = date('Y-m-d', strtotime($request->start_date . " + $i days"));
            //echo $leave_date; exit;
            $leave                  = new Leave();
            $leave->employee_id     = $request->employee_id;
            $leave->leave_type_id   = $request->leave_type_id;
            $leave->start_date      = $request->start_date;
            $leave->end_date        = $request->end_date;       
            $leave->leave_date      = $leave_date;       
            $leave->day_count       = $count_days;       
            $leave->comment         = $request->comment;
            if($request->attachment != null){     
                $leave->attachment       = $attachment; 
            }
            $leave->status          = 1;
            $leave->save();
        }

        return redirect()->route('leave')->with('success', 'Leave has been added successfully');
    }

    
    public function destroy($id)
    {
        $leave = Leave::find($id);
        $leave->status = 0;
        $leave->save();
        return redirect()->route('leave-report')->with('success', 'Leave has been deleted successfully');
    }
}
