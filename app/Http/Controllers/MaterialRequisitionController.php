<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequisition;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Item;
use Session;
use PDF;

class MaterialRequisitionController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'requisition';
        $data['child_menu']             = 'material-requisition';
        $requisitions = MaterialRequisition::where(['company_id'=>Session::get('company_id')])->with('company','project','item');
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

        return view('requisition.material_requisition',$data);
    }

    public function print(Request $request)
    {
        $requisitions = MaterialRequisition::where(['company_id'=>Session::get('company_id')])->with('company','project','item');
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
        return view('requisition.material_requisition_print',$data);
    }

    public function pdf(Request $request)
    {
        $requisitions = MaterialRequisition::where(['company_id'=>Session::get('company_id')])->with('company','project','item');
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

        $pdf = PDF::loadView('requisition.material_requisition_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('material-requisition-list_'.$string.'.pdf');
    }

    function change_requisition_status(Request $request,$id='',$status='1'){
        if(!empty($id) && !empty($status)){
            $model = MaterialRequisition::find($id);
            $model->status = $status;
            if($status == '1'){
                $model->approved_date = date('Y-m-d');
                $model->approved_by = auth()->user()->id;
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

        return redirect('material-requisition');
    }

    public function create()
    {
        $data['main_menu']              = 'requisition';
        $data['child_menu']             = 'add-requisition';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();

        return view('requisition.add_material_requisition',$data);
    }

    public function store(Request $request)
    {
        $project_id             = $request->project_id;
        $requisition_date       = $request->requisition_date;
        $estimated_qty          = $request->estimated_qty;
        $additional_qty         = $request->additional_qty;
        $total_required_qty     = $request->total_required_qty;
        $received_qty           = $request->received_qty;
        $consumed_qty           = $request->consumed_qty;
        $stock_qty              = $request->stock_qty;
        $balance_required_qty   = $request->balance_required_qty;
        $work_progress          = $request->work_progress;
        $remarks                = $request->remarks;
        $item_id                = $request->item_id;

        if($project_id != null && $requisition_date != null && is_array($estimated_qty)){
            for($i=0; count($estimated_qty)>$i; $i++){
                $model                      = new MaterialRequisition();
                $model->company_id          = Session::get('company_id');
                $model->project_id          = $project_id;
                $model->requisition_date    = $requisition_date;
                $model->item_id             = $item_id[$i];
                $model->estimated_qty       = $estimated_qty[$i];
                $model->additional_qty      = $additional_qty[$i];
                $model->total_required_qty  = $total_required_qty[$i];
                $model->received_qty        = $received_qty[$i];
                $model->consumed_qty        = $consumed_qty[$i];
                $model->stock_qty           = $stock_qty[$i];
                $model->balance_required_qty      = $balance_required_qty[$i];
                $model->work_progress       = $work_progress[$i];
                $model->remarks             = $remarks[$i];
                //$model->total_required_qty  = $estimated_qty[$i]+$additional_qty[$i];
                $model->status              = '2';
                $model->created_by          = auth()->user()->id;
                $model->save();
            }
            $msg="Material Requisition Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request.";
            $request->session()->flash('warning',$msg);
        }

        return redirect('material-requisition')->with('status', $msg);
    }
}
