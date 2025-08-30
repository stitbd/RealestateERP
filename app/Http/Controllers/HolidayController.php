<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $data['main_menu']                  = 'hrm';
        $data['child_menu']                 = 'holiday';
        $data['holiday_data']               = Holiday::all();
        return view('hrm.basic_settings.holiday',$data);
    }

    public function store(Request $request){
        $request->validate([
            'holy_date'                  => 'required'
        ]);
        
        $model = new Holiday();
        $model->holy_date                   = $request->post('holy_date');
        $model->name                        = $request->post('name');
        $model->comment                     = $request->post('comment');
        $model->status                      = 1;
        $model->created_by                  = auth()->user()->id;
        $model->save();

        $msg="Holiday Inserted.";
        $request->session()->flash('message',$msg);

        return redirect('holiday')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Holiday::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Holiday Status Updated.";
        $request->session()->flash('message',$msg);

        return redirect('holiday')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'holy_date'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Holiday::find($request->post('id'));
        $model->holy_date                   = $request->post('holy_date');
        $model->name                        = $request->post('name');
        $model->comment                     = $request->post('comment');
        $model->updated_by                  = auth()->user()->id;
        
        $model->save();

        $msg="Holiday Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('holiday')->with('status', $msg);
    }
}
