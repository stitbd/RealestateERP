<?php
/**
 * Author: Tushar Das
 * Sr. Software Engineer
 * tushar2499@gmail.com 
 * 01815920898
 * STITBD
 * 07/10/2021
 */
namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'schedule-list';
        $data['schedule_data']          = Schedule::all();
        return view('hrm.basic_settings.schedule',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required'
        ]);
        
        $model = new Schedule();
        $model->name                = $request->post('name');
        $model->start_time          = $request->post('start_time');
        $model->end_time            = $request->post('end_time');
        $model->schedule_time       = $request->post('schedule_time');
        $model->save();

        $msg="Schedule Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('schedule-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Schedule::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Schedule Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('schedule-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Schedule::find($request->post('id'));
        $model->name                = $request->post('name');
        $model->start_time          = $request->post('start_time');
        $model->end_time            = $request->post('end_time');
        $model->schedule_time       = $request->post('schedule_time');
        $model->save();

        $msg="Schedule Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('schedule-list')->with('status', $msg);
    }
}
