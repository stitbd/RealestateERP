<?php

namespace App\Http\Controllers;

use App\Models\MoneyRequisition;
use App\Models\RequisitionPayment;
use App\Models\RequisitionPaymentDetails;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Fund;
use App\Models\FundLog;
use App\Models\FundCurrentBalance;
use Session;
use PDF;

class MoneyRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'requisition';
        $data['child_menu']             = 'money-requisition';
        $requisitions = MoneyRequisition::where(['company_id'=>Session::get('company_id')])->with('company','project','approved_user');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('requisition_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('requisition_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['requisitions'] = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        return view('requisition.money_requisition',$data);
    }

    public function print(Request $request)
    {
        $requisitions = MoneyRequisition::where(['company_id'=>Session::get('company_id')])->with('company','project','approved_user');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('requisition_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('requisition_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->get();
        $data['requisitions'] = $requisitions;
        return view('requisition.money_requisition_print',$data);
    }

    public function pdf(Request $request)
    {
        $requisitions = MoneyRequisition::where(['company_id'=>Session::get('company_id')])->with('company','project','approved_user');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('requisition_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('requisition_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->get();
        $data['requisitions'] = $requisitions;

        $pdf = PDF::loadView('requisition.money_requisition_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('money-requisition-list_'.$string.'.pdf');
    }


    function approve(Request $request){
        $model = MoneyRequisition::find($request->id);
        $model->status = 1;
        $model->approved_date = date('Y-m-d');
        $model->approved_by = auth()->user()->id;
        $model->approved_amount = $request->approved_amount;
        $model->updated_by = auth()->user()->id;
        $model->save();

        $msg="Requisition Updated..!";
        $request->session()->flash('message',$msg);
        return redirect('money-requisition');
    }

    function change_money_requisition_status(Request $request,$id='',$status='1'){
        if(!empty($id) && !empty($status)){
            $model = MoneyRequisition::find($id);
            $model->status = $status;
            
            if($status == '1'){
                $model->approved_date = date('Y-m-d');
                $model->approved_by = auth()->user()->id;
                $model->approved_amount = $model->amount;
            }

            $model->updated_by = auth()->user()->id;
            $model->save();

            $msg="Requisition Updated..!";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request..!";
            $request->session()->flash('warning',$msg);
        }

        return redirect('money-requisition');
    }

    public function create()
    {
        $data['main_menu']              = 'requisition';
        $data['child_menu']             = 'add-money-requisition';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        return view('requisition.add_money_requisition',$data);
    }

    public function store(Request $request)
    {
        $project_id = $request->project_id;
        $requisition_date = $request->requisition_date;
        $description = $request->description;
        $amount = $request->amount;
        $remarks = $request->remarks;

        if($project_id != null && $requisition_date != null && is_array($amount)){
            for($i=0; count($amount)>$i; $i++){
                $model                      = new MoneyRequisition();
                $model->company_id          = Session::get('company_id');
                $model->project_id          = $project_id;
                $model->requisition_date    = $requisition_date;
                $model->description         = $description[$i];
                $model->amount              = $amount[$i];
                $model->remarks             = $remarks[$i];
                $model->status              = '2';
                $model->created_by          = auth()->user()->id;
                $model->save();
            }
            $msg="Money Requisition Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request.";
            $request->session()->flash('warning',$msg);
        }

        return redirect('money-requisition')->with('status', $msg);
    }

    function payment($id){
        $data['main_menu']              = 'requisition';
        $data['child_menu']             = 'money-requisition';
        $data['fund_data']              = Fund::all();
        $data['requisition_info']       = MoneyRequisition::with('company','project','approved_user')->find($id);

        return view('requisition.money_requisition_payment',$data);
    }


    function save_payment(Request $request){
        //dd($request);
        if($request->requisition_id && $request->amount){
            $model                  = new RequisitionPayment();
            $model->project_id      = $request->project_id;
            $model->company_id      = $request->company_id;
            $model->requisition_id  = $request->requisition_id;
            $model->fund_id         = $request->fund_id;
            $model->payment_date    = $request->payment_date;
            $model->payment_type    = $request->payment_type;
            $model->amount          = $request->amount;
            $model->status          = '1';
            $model->created_by      = auth()->user()->id;
            $model->save();

            $money_requisition_data = MoneyRequisition::find($request->requisition_id);
            $money_requisition_data->paid_amount = $money_requisition_data->paid_amount+$request->amount;
            $money_requisition_data->save();

            $payment_id = $model->id;

            $details                        = new RequisitionPaymentDetails();
            $details->payment_id            = $payment_id;
            $details->receiver_name         = $request->receiver_name;
            $details->mobile_no             = $request->mobile_no;
            $details->nid                   = $request->nid;
            $details->address               = $request->address;
            $details->check_number          = $request->check_number;
            $details->check_issue_date      = $request->check_issue_date;
            $details->bank_name             = $request->bank_name;
            $details->bank_account_no       = $request->bank_account_no;
            $details->account_holder_name   = $request->account_holder_name;
            $details->payment_note          = $request->payment_note;
            $details->remarks               = $request->remarks;
            if($request->attachment != null){
                $newImageName = time().'_requisition_payment.'.$request->attachment->extension();
                $request->attachment->move(public_path('attachment'),$newImageName);

                $details->attachment = public_path('attachment/'.$newImageName);
            }

            $details->status                = '1';
            $details->created_by            = auth()->user()->id;
            $details->save();

            $fund_log                       = new FundLog();
            $fund_log->company_id           = Session::get('company_id');
            $fund_log->fund_id              = $request->fund_id;
            $fund_log->type                 = '2';
            $fund_log->amount               = $request->amount;
            $fund_log->transection_type     = 'requisition_payment';
            $fund_log->transection_id       = $payment_id;
            $fund_log->transection_date     = $request->payment_date;
            $fund_log->status               = '1';
            $fund_log->created_by           = auth()->user()->id;
            $fund_log->save();

            $fund = FundCurrentBalance::where(['fund_id'=>$request->fund_id,'company_id'=>Session::get('company_id'),'status'=>1])->first();
            if($fund != null){
                $fund->amount = $fund->amount - $request->amount;
                $fund->updated_by = auth()->user()->id;
                $fund->update();
            }
            else{
                $fund               = new FundCurrentBalance();
                $fund->company_id   = Session::get('company_id');
                $fund->fund_id      = $request->fund_id;
                $fund->amount       = $request->amount;
                $fund->status       = '1';
                $fund->created_by   = auth()->user()->id;
                $fund->save();
            }
            

            $msg="Payment Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Payment Not Updated.";
            $request->session()->flash('warning',$msg);
        }
        
        return redirect('money-requisition')->with('status', $msg);
    }
}
