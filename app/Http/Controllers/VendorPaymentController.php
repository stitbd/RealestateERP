<?php

namespace App\Http\Controllers;

use App\Models\VendorPayment;
use App\Models\VendorPaymentDetails;
use App\Models\Project; 
use App\Models\Vendor; 
use App\Models\Fund;
use App\Models\FundCurrentBalance;
use App\Models\FundLog;
use App\Models\VendorDue;
use Illuminate\Http\Request;
use Session;
use PDF;

class VendorPaymentController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'vendor';
        $data['child_menu']             = 'vendor-payment-list';
        $requisitions = VendorPayment::where(['company_id'=>Session::get('company_id')])->with('company','project','vendor','fund','payment_details');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisitions->where('fund_id','=',$request->fund_id);
        }
        if($request->vendor_id != null){
            $where['vendor_id'] = $request->vendor_id;
            $requisitions->where('vendor_id','=',$request->vendor_id);
        }

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('payment_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('payment_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['payments']               = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['vendor_data']            = Vendor::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();
        $data['fund_data']              = Fund::where(['status'=>1])->get();

        return view('vendor.payment_list',$data);
    }

    public function print(Request $request)
    {
        $requisitions = VendorPayment::where(['company_id'=>Session::get('company_id')])->with('company','project','vendor','fund','payment_details');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisitions->where('fund_id','=',$request->fund_id);
        }
        if($request->vendor_id != null){
            $where['vendor_id'] = $request->vendor_id;
            $requisitions->where('vendor_id','=',$request->vendor_id);
        }

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('payment_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('payment_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->get();
        $data['payments']               = $requisitions;

        return view('vendor.payment_print',$data);
    }

    function pdf(Request $request){
        $requisitions = VendorPayment::where(['company_id'=>Session::get('company_id')])->with('company','project','vendor','fund','payment_details');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->fund_id != null){
            $where['fund_id'] = $request->fund_id;
            $requisitions->where('fund_id','=',$request->fund_id);
        }
        if($request->vendor_id != null){
            $where['vendor_id'] = $request->vendor_id;
            $requisitions->where('vendor_id','=',$request->vendor_id);
        }

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('payment_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('payment_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->get();
        $data['payments']               = $requisitions;

        $pdf = PDF::loadView('vendor.payment_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('vendor-payment_'.$string.'.pdf');
    }

    public function create()
    {
        $data['main_menu']              = 'vendor';
        $data['child_menu']             = 'vendor-payment';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['vendor_data']            = Vendor::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();
        $data['fund_data']              = Fund::where(['status'=>1])->get();
        
        return view('vendor.vendor_payment',$data);
    }

    function load_vendor_due($vendor_id='',$project_id=''){
        $vendor_due = VendorDue::where(['company_id'=>Session::get('company_id'),'vendor_id'=>$vendor_id,'project_id'=>$project_id])->first();
        if($vendor_due != null){
            echo $vendor_due->due_amount;
        }
        else{
            echo 0;
        }
    }

    // public function printVoucher(){
    //     return view('vendor.print_vendor_payment');
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $project_id = $request->project_id;
        $vendor_id = $request->vendor_id;
        $payment_date = $request->payment_date;
        $fund_id = $request->fund_id;
        $payment_type = $request->payment_type;
        $amount = $request->amount;
        $receiver_name = $request->receiver_name;
        $mobile_no = $request->mobile_no;
        $nid = $request->nid;
        $address = $request->address;
        $check_number = $request->check_number;
        $check_issue_date = $request->check_issue_date;
        $bank_name = $request->bank_name;
        $bank_account_no = $request->bank_account_no;
        $account_holder_name = $request->account_holder_name;
        $payment_note = $request->payment_note;
        $remarks = $request->remarks;
        if($project_id != null && $amount != null && $vendor_id != null && $payment_date != null && $fund_id != null){
            $model = new VendorPayment();
            $model->company_id      = Session::get('company_id');
            $model->project_id      = $project_id;
            $model->vendor_id       = $vendor_id;
            $model->payment_date    = $payment_date;
            $model->fund_id         = $fund_id;
            $model->payment_type    = $payment_type;
            $model->amount          = $amount;
            $model->status          = '1';
            $model->created_by      = auth()->user()->id;
            $model->save();

            $vendor = Vendor::where('id',$vendor_id)->first();
            $vendor_name = $vendor->name;

            $payment_id = $model->id;

            $details = new VendorPaymentDetails();
            $details->vendor_payment_id = $payment_id;
            $details->receiver_name = $receiver_name;
            $details->mobile_no = $mobile_no;
            $details->nid = $nid;
            $details->address = $address;
            $details->check_number = $check_number;
            $details->check_issue_date = $check_issue_date;
            $details->bank_name = $bank_name;
            $details->bank_account_no = $bank_account_no;
            $details->account_holder_name = $account_holder_name;
            $details->payment_note = $payment_note;
            $details->remarks = $remarks;
            if($request->attachment != null){
                $newImageName = time().'_supplier_payment.'.$request->attachment->extension();
                $request->attachment->move(('attachment'),$newImageName);

                $details->attachment = ('attachment/'.$newImageName);
            }

            $details->status          = '1';
            $details->created_by      = auth()->user()->id;
            $details->save();
            

            $supplier_due = VendorDue::where(['company_id'=>Session::get('company_id'),'vendor_id'=>$request->vendor_id,'project_id'=>$project_id])->first();
            if($supplier_due != null){
                $supplier_due->due_amount = $supplier_due->due_amount-$amount;
                $supplier_due->save();
            }
            else{
                $supplier_due               = new VendorDue();
                $supplier_due->company_id   = Session::get('company_id');
                $supplier_due->project_id   = $request->project_id;
                $supplier_due->vendor_id    = $request->vendor_id;
                $supplier_due->due_amount   = -$amount;
                $supplier_due->save();
            }


            $fund_log                       = new FundLog();
            $fund_log->company_id           = Session::get('company_id');
            $fund_log->fund_id              = $request->fund_id;
            $fund_log->type                 = '2';
            $fund_log->amount               = $amount;
            $fund_log->transection_type     = 'vendor_payment';
            $fund_log->transection_id       = $payment_id;
            $fund_log->transection_date     = $payment_date;
            $fund_log->status               = '1';
            $fund_log->created_by           = auth()->user()->id;
            $fund_log->save();

            $fund = FundCurrentBalance::where(['fund_id'=>$fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
            if($fund != null){
                $fund->amount = $fund->amount - $amount;
                $fund->updated_by = auth()->user()->id;
                $fund->update();
            }else{
                $fund = new FundCurrentBalance();
                $fund->fund_id = $request->fund_id;
                $fund->company_id = Session::get('company_id');
                $fund->amount = $request->amount;
                $fund->status = '1';
                $fund->created_by = auth()->user()->id;
                $fund->save();
            }

            $msg="Payment Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Payment Not Updated.";
            $request->session()->flash('warning',$msg);
        }
        
        return view('vendor.print_vendor_payment',compact('model','details','vendor_name'));
    }

    
}
