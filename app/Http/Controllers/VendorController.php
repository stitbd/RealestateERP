<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Company;
use Illuminate\Http\Request;
use Session;
use PDF;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']              = 'vendor';
        $data['child_menu']             = 'vendor-list';
        $data['vendor_data']            = Vendor::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['company_data']           = Company::where(['status'=>1])->get();

        return view('basic_settings.vendor',$data);
    }

    function print(){
        $data['title']                  = 'Vendor List || '.Session::get('company_name');
        $data['vendor_data']           = Vendor::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        return view('basic_settings.vendor_print',$data);
    }

    function pdf(){
        $data['title']                  = 'Vendor List || '.Session::get('company_name');
        $data['vendor_data']           = Vendor::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        $pdf = PDF::loadView('basic_settings.vendor_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('vendor-list_'.$string.'.pdf');
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required'
        ]);
        
        
        $model = new Vendor();
        $model->name                = $request->post('name');
        $model->company_id          = Session::get('company_id');
        $model->mobile                = $request->post('mobile');
        $model->address                = $request->post('address');
        $model->other_details                = $request->post('other_details');
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Vendor Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('vendor-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Vendor::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Vendor Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('vendor-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Vendor::where('id', '=', $request->post('id'))->first();;
        $model->name                = $request->post('name');
        //$model->company_id          = $request->post('company_id');
        $model->mobile                = $request->post('mobile');
        $model->address                = $request->post('address');
        $model->other_details                = $request->post('other_details');
        $model->updated_by              = auth()->user()->id;
        
        $model->save();

        $msg="Vendor Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('vendor-list')->with('status', $msg);
    }
}
