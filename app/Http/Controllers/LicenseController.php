<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Session;
use PDF;

class LicenseController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'licenses-list';
        $data['child_menu']             = 'licenses-list';

        $data['licenses_data']          = License::where(['company_id'=>Session::get('company_id')])->get();

        return view('license.licenses_list',$data);
    }

    function print(){
        $data['title']                  = 'Licenses || '.Session::get('company_name');
        $data['licenses_data']          = License::where(['company_id'=>Session::get('company_id')])->get();
        return view('license.licenses_print',$data);
    }

    function pdf(){
        $data['title'] = 'Licenses || '.Session::get('company_name');
        $data['licenses_data']          = License::where(['company_id'=>Session::get('company_id')])->get();
        $pdf = PDF::loadView('license.licenses_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('license_'.$string.'.pdf');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required'
        ]);
        
        
        $model                      = new License();
        $model->name                = $request->post('name');
        $model->company_id          = Session::get('company_id');
        $model->start_date          = $request->post('start_date');
        $model->expire_date         = $request->post('expire_date');
        $model->renew_amount        = $request->post('renew_amount');
        $model->remarks             = $request->post('remarks');
        if($request->attachment != null){
            $newImageName = time().'_license.'.$request->attachment->extension();
            $request->attachment->move(('attachment'),$newImageName);

            $model->attachment = ('attachment/'.$newImageName);
        }
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Licenses Report Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('licenses-list')->with('status', $msg);
    }
    
    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = License::where('id', '=', $request->post('id'))->first();
        $model->name                = $request->post('name');
        $model->company_id          = Session::get('company_id');
        $model->start_date          = $request->post('start_date');
        $model->expire_date         = $request->post('expire_date');
        $model->renew_amount        = $request->post('renew_amount');
        $model->remarks             = $request->post('remarks');
        if($request->attachment != null){
            $newImageName = time().'_license.'.$request->attachment->extension();
            $request->attachment->move(('attachment'),$newImageName);

            $model->attachment = ('attachment/'.$newImageName);
        }
        $model->updated_by              = auth()->user()->id;
        $model->save();

        $msg="Audit Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('licenses-list')->with('status', $msg);
    }
}
