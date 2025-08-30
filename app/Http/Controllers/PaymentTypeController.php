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

use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'paymentType-list';
        $data['paymentType_data']       = PaymentType::all();
        return view('hrm.basic_settings.payment_type',$data);
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required'
        ]);
        
        $model = new PaymentType();
        $model->name                = $request->post('name');
        $model->save();

        $msg="PaymentType Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('paymentType-list')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = PaymentType::find($id);
        $model->status          = $status;
        $model->save();

        $msg="PaymentType Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('paymentType-list')->with('status', $msg);
    }

    function update(Request $request){
        $request->validate([
            'name'     => 'required'
        ]);
        //dd($request->post());
        
        $model = PaymentType::find($request->post('id'));
        $model->name                = $request->post('name');
        
        $model->save();

        $msg="PaymentType Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('paymentType-list')->with('status', $msg);
    }
}
