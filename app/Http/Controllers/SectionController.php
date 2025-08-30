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

use App\Models\Section;
use App\Models\Company;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'section-list';
        $data['branch_data']            = Section::with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();

        return view('hrm.basic_settings.section',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required',
            'company_id'            => 'required'
        ]);
        
        
        $model = new Section();
        $model->name                = $request->post('name');
        $model->company_id          = $request->post('company_id');
        $model->save();

        $msg="Section Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('section-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Section::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Section Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('section-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Section::find($request->post('id'));
        $model->name                = $request->post('name');
        $model->company_id          = $request->post('company_id');
        
        $model->save();

        $msg="Section Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('section-list')->with('status', $msg);
    }
}
