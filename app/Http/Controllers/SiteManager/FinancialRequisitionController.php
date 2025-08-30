<?php

namespace App\Http\Controllers\SiteManager;

use Illuminate\Http\Request;
use App\Models\MoneyRequisition;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class FinancialRequisitionController extends Controller
{
    public function requisitionList(Request $request){
        $data['main_menu'] = 'money-requisition';
        $data['main_menu'] = 'money-requisition-list';
        $requisitions = MoneyRequisition::where('project_id',auth()->user()->project_id);
        $where = array();

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

        $data['all_requisition'] = $requisitions;

        return view('site_manager.requisition.money_requisition',$data);
    }

    public function requisitionForm(){
      
        return view('site_manager.requisition.add_money_requisition');
    }

    public function storeRequisition(Request $request){

        if($request->requisition_date &&$request->amount ){
            $model                      = new MoneyRequisition();
            $model->project_id          = auth()->user()->project_id;
            $model->requisition_date    =  $request->requisition_date;
            $model->amount              =  $request->amount;
            $model->remarks             =  $request->purpose;
            $model->created_by          = auth()->user()->id;
            $model->status              = '0'; //0 means pending
            $model->save();
            $msg="Money Requisition Sent Successfully.";
            // $request->session()->flash('message',$msg);
        }else{
            $msg="Invalid Request.";
            // $request->session()->flash('warning',$msg);
        }
        return redirect()->route('money-requisition-list')->with('status', $msg);
              
    }
    public function updateRequisition(Request $request){
        $model = MoneyRequisition::find($request->id);
        $model->amount = $request->amount;
        $model->remarks = $request->remarks;
        $model->updated_by = auth()->user()->id;
        $model->save();
        $msg="Requisition Updated..!";
        return redirect()->route('money-requisition-list')->with('status', $msg);
    }

    public function print(Request $request)
    {
        $requisitions = MoneyRequisition::where('project_id',auth()->user()->project_id)->with('company','project','approved_user');
        $where = array();
       
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
        $requisitions = MoneyRequisition::where('project_id',auth()->user()->project_id)->with('company','project','approved_user');
        $where = array();
        // if($request->project_id != null){
        //     $where['project_id'] = $request->project_id;
        //     $requisitions->where('project_id','=',$request->project_id);
        // }
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


    public function rejectRequisitionList(){

    }
}
