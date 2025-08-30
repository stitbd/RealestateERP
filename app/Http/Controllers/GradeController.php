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

use App\Models\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'grade-list';
        $data['grade_data']             = Grade::all();
        return view('hrm.basic_settings.grade',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required'
        ]);
        
        $model = new Grade();
        $model->name                = $request->post('name');
        $model->save();

        $msg="Grade Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('grade-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Grade::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Grade Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('grade-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Grade::find($request->post('id'));
        $model->name                = $request->post('name');
        
        $model->save();

        $msg="Grade Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('grade-list')->with('status', $msg);
    }
}
