<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'designation-list';
        $data['designation_data']       = Designation::all();
        return view('hrm.basic_settings.designation',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required'
        ]);
        
        $model = new Designation();
        $model->name                = $request->post('name');
        $model->save();

        $msg="Designation Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('designation-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Designation::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Designation Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('designation-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Designation::find($request->post('id'));
        $model->name                = $request->post('name');
        
        $model->save();

        $msg="Designation Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('designation-list')->with('status', $msg);
    }
}
