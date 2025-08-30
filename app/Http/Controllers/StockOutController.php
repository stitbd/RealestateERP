<?php

namespace App\Http\Controllers;

use App\Models\StockOut;
use App\Models\Project;
use App\Models\Item;
use App\Models\Stock;
use Illuminate\Http\Request;
use Session;
use PDF;

class StockOutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['main_menu']              = 'inventory';
        $data['child_menu']             = 'stock-out';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();

        return view('inventory.stock_out',$data);
    }

    
    public function store(Request $request)
    {
        $project_id             = $request->project_id;
        $out_date               = $request->out_date;
        $qty                    = $request->qty;
        $reason                 = $request->reason;
        $remarks                = $request->remarks;
        $item_id                = $request->item_id;

        if($out_date != null && is_array($qty)){
            for($i=0; count($item_id)>$i; $i++){
                $model                      = new StockOut();
                $model->company_id          = Session::get('company_id');
                $model->project_id          = $project_id;
                $model->out_date            = $out_date;
                $model->item_id             = $item_id[$i];
                $model->qty                 = $qty[$i];
                $model->reason              = $reason[$i];
                $model->remarks             = $remarks[$i];
                $model->status              = '1';
                $model->created_by          = auth()->user()->id;
                $model->save();

                $stock = Stock::where(['status'=>1,'company_id'=>Session::get('company_id'), 'project_id'=>$project_id, 'item_id'=>$item_id[$i]])->first();
                if($stock){
                    $stock->qty = $stock->qty - $qty[$i];
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
            }
            $msg="Stock Out Updated.";
            $request->session()->flash('message',$msg);
        }
        else{
            $msg="Invalid Request.";
            $request->session()->flash('warning',$msg);
        }

        return redirect('stock-out')->with('status', $msg);
    }

    public function print(Request $request)
    {
        $stock_out_data = StockOut::where(['company_id'=>Session::get('company_id')])->with('project','item');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        $stock_out_data = $stock_out_data->where($where);
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $stock_out_data= $stock_out_data->where('out_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['start_date'] = $request->end_date;
            $stock_out_data= $stock_out_data->where('out_date','<=',$request->end_date);
        }
        $stock_out_data = $stock_out_data->get();
        $data['stock_out_data'] = $stock_out_data;
        return view('inventory.stock_out_print',$data);
    }
    

    public function pdf(Request $request)
    {
        $stock_out_data = StockOut::where(['company_id'=>Session::get('company_id')])->with('project','item');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        $stock_out_data = $stock_out_data->where($where);
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $stock_out_data= $stock_out_data->where('out_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['start_date'] = $request->end_date;
            $stock_out_data= $stock_out_data->where('out_date','<=',$request->end_date);
        }
        $stock_out_data = $stock_out_data->get();
        $data['stock_out_data'] = $stock_out_data;

        
        $pdf = PDF::loadView('inventory.stock_out_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('stock-out_'.$string.'.pdf');
    }

    public function stock_out_list(Request $request)
    {
        
        $data['main_menu']              = 'inventory';
        $data['child_menu']             = 'stock-out-list';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();

        $stock_out_data = StockOut::where(['company_id'=>Session::get('company_id')])->with('project','item');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        $stock_out_data = $stock_out_data->where($where);
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $stock_out_data= $stock_out_data->where('out_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['start_date'] = $request->end_date;
            $stock_out_data= $stock_out_data->where('out_date','<=',$request->end_date);
        }
        $stock_out_data = $stock_out_data->paginate(20);
        $stock_out_data->appends($where);
        $data['stock_out_data'] = $stock_out_data;
        return view('inventory.stock_out_list',$data);
    }

    
}
