<?php
/**
 * Author: Tushar Das
 * Sr. Software Engineer
 * tushar2499@gmail.com 
 * 01815920898
 * STITBD
 */

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']      = 'basic_settings';
        $data['child_menu']     = 'company-list';
        $data['company_data']      = Company::all();

        return view('basic_settings.company_list',$data);
    }

    public function store(Request $request){
        $request->validate([
            'logo'          =>'required||mimes:jpg,png,jpeg|max:5048',
            'name'          => 'required'
        ]);
        
        $newImageName = 'company_logo_'.uniqid().'.'.$request->logo->extension();
        $request->logo->move(public_path('upload_images/company_logo/'),$newImageName);

        $model = new Company();
        $model->name                = $request->post('name');
        $model->email               = $request->post('email');
        $model->phone               = $request->post('phone');
        $model->address             = $request->post('address');
        $model->logo                = $newImageName;
        $model->save();

        $msg="Company Inserted.";
        $request->session()->flash('message',$msg);

        return redirect('company-list')->with('status', 'Company created!');
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Company::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Company Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('company-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required',
            'email'     => 'required'
        ]);
        //dd($request->post());
        if($request->logo != null){
            $newImageName = 'company_logo_'.uniqid().'.'.$request->logo->extension();
            $request->logo->move(public_path('upload_images/company_logo/'),$newImageName);
        }
        $model = Company::find($request->post('id'));
        $model->name                = $request->post('name');
        $model->email               = $request->post('email');
        $model->phone               = $request->post('phone');
        $model->address             = $request->post('address');
        if($request->logo != null){
            $model->logo   = $newImageName;
        }
        
        $model->save();

        $msg="Compnay Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('company-list')->with('status', $msg);
    }
}
