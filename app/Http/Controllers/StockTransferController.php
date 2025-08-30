<?php

namespace App\Http\Controllers;

use App\Models\StockTransfer;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Item;
use App\Models\Company;
use App\Models\Stock;
use Session;
use PDF;

class StockTransferController extends Controller
{
    
    public function index()
    {
        $data['main_menu']              = 'inventory';
        $data['child_menu']             = 'stock-transfer';
        $data['company_data']           = Company::where(['status'=>1])->get();
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();

        return view('inventory.stock_transfer',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function transfer_project($company_id)
    {
        $projects = Project::where(['company_id'=>$company_id])->with('company')->get();
        $option='<option value="">Select One</option>';
        foreach ($projects as $key => $value) {
            $option.='<option value="'.$value->id.'">'.$value->name.'</option>';
        }

        echo $option;
    }

    public function store(Request $request)
    {
        $project_id             = $request->project_id;
        $to_project_id          = $request->to_project_id;
        $to_company_id          = $request->to_company_id;
        $out_date               = $request->out_date;
        $qty                    = $request->qty;
        $remarks                = $request->remarks;
        $item_id                = $request->item_id;

        if($project_id != null && $out_date != null && is_array($qty)){
            for($i=0; count($item_id)>$i; $i++){
                $model                      = new StockTransfer();
                $model->company_id          = Session::get('company_id');
                $model->project_id          = $project_id;
                $model->to_company_id       = $to_company_id;
                $model->to_project_id       = $to_project_id;
                $model->transfer_date       = $out_date;
                $model->item_id             = $item_id[$i];
                $model->qty                 = $qty[$i];
                $model->remarks             = $remarks[$i];
                $model->status              = '1';
                $model->created_by          = auth()->user()->id;
                $model->save();

                $stock = Stock::where(['status'=>1,'company_id'=>Session::get('company_id'), 'project_id'=>$project_id, 'item_id'=>$item_id[$i]])->first();
                if($stock){
                    $stock->qty = $stock->qty - $qty[$i];
                    $stock->updated_by          = auth()->user()->id;
                    $stock->save();
                }
                else{
                    $stock = new Stock();
                    $stock->company_id          = Session::get('company_id');
                    $stock->project_id          = $project_id;
                    $stock->item_id             = $item_id[$i];
                    $stock->qty                 = -$qty[$i];
                    $stock->status              = '1';
                    $stock->created_by          = auth()->user()->id;
                    $stock->save();
                }

                $stock = Stock::where(['status'=>1,'company_id'=>$to_company_id, 'project_id'=>$to_project_id, 'item_id'=>$item_id[$i]])->first();
                //dd($stock);
                if($stock){
                    $stock->qty = $stock->qty + $qty[$i];
                    $stock->save();
                }
                else{
                    $stock = new Stock();
                    $stock->company_id          = $to_company_id;
                    $stock->project_id          = $to_project_id;
                    $stock->item_id             = $item_id[$i];
                    $stock->qty                 = $qty[$i];
                    $stock->status              = '1';
                    $stock->created_by          = auth()->user()->id;
                    $stock->save();
                }
            }
            $msg="Stock Transfer Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request.";
            $request->session()->flash('warning',$msg);
        }

        return redirect('stock-transfer')->with('status', $msg);
    }


    function transfer_list(Request $request){
        $data['main_menu']              = 'inventory';
        $data['child_menu']             = 'stock-transfer-list';
        $data['company_data']           = Company::where(['status'=>'1'])->get();
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();

        $stock_out_data = StockTransfer::where(['company_id'=>Session::get('company_id')])->with('project','item','to_company','to_project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        if($request->to_company_id != null){
            $where['to_company_id'] = $request->to_company_id;
        }
        if($request->to_project_id != null){
            $where['to_project_id'] = $request->to_project_id;
        }

        $stock_out_data = $stock_out_data->where($where);
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $stock_out_data= $stock_out_data->where('transfer_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['start_date'] = $request->end_date;
            $stock_out_data= $stock_out_data->where('transfer_date','<=',$request->end_date);
        }
        $stock_out_data = $stock_out_data->paginate(20);
        $stock_out_data->appends($where);
        $data['stock_transfer_data'] = $stock_out_data;
        return view('inventory.stock_transfer_list',$data);
    }


    public function print(Request $request)
    {
        $stock_out_data = StockTransfer::where(['company_id'=>Session::get('company_id')])->with('project','item','to_company','to_project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        if($request->to_company_id != null){
            $where['to_company_id'] = $request->to_company_id;
        }
        if($request->to_project_id != null){
            $where['to_project_id'] = $request->to_project_id;
        }

        $stock_out_data = $stock_out_data->where($where);
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $stock_out_data= $stock_out_data->where('transfer_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['start_date'] = $request->end_date;
            $stock_out_data= $stock_out_data->where('transfer_date','<=',$request->end_date);
        }
        $stock_out_data = $stock_out_data->get();
        $data['stock_transfer_data'] = $stock_out_data;

        return view('inventory.stock_transfer_print',$data);
    }
    

    public function pdf(Request $request)
    {
        $stock_out_data = StockTransfer::where(['company_id'=>Session::get('company_id')])->with('project','item','to_company','to_project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        if($request->to_company_id != null){
            $where['to_company_id'] = $request->to_company_id;
        }
        if($request->to_project_id != null){
            $where['to_project_id'] = $request->to_project_id;
        }

        $stock_out_data = $stock_out_data->where($where);
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $stock_out_data= $stock_out_data->where('transfer_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['start_date'] = $request->end_date;
            $stock_out_data= $stock_out_data->where('transfer_date','<=',$request->end_date);
        }
        $stock_out_data = $stock_out_data->get();
        $data['stock_transfer_data'] = $stock_out_data;

        
        $pdf = PDF::loadView('inventory.stock_transfer_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('stock-transfer_'.$string.'.pdf');
    }
}
