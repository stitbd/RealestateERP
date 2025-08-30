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

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'branch-list';
        $data['branch_data']            = Branch::with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();

        return view('hrm.basic_settings.branch',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required',
            'company_id'            => 'required'
        ]);
        
        
        $model = new Branch();
        $model->name                = $request->post('name');
        $model->company_id          = $request->post('company_id');
        $model->save();

        $msg="Branch Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('branch-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Branch::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Branch Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('branch-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Branch::find($request->post('id'));
        $model->name                = $request->post('name');
        $model->company_id          = $request->post('company_id');
        
        $model->save();

        $msg="Branch Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('branch-list')->with('status', $msg);
    }
}
