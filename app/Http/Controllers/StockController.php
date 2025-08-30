<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Project;
use App\Models\Item;
use Illuminate\Http\Request;
use Session;
use PDF;

class StockController extends Controller
{
    
    public function index(Request $request)
    {
        $data['main_menu']              = 'inventory';
        $data['child_menu']             = 'stock-report';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['item_data']              = Item::where(['company_id'=>Session::get('company_id'),'status'=>1])->with('company')->get();

        $stock_out_data = Stock::where(['company_id'=>Session::get('company_id')])->with('project','item');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        $stock_out_data = $stock_out_data->where($where);
        $stock_out_data = $stock_out_data->paginate(20);
        $stock_out_data->appends($where);
        $data['stock_data'] = $stock_out_data;
        return view('inventory.stock_report',$data);
    }

    public function print(Request $request)
    {
        $stock_out_data = Stock::where(['company_id'=>Session::get('company_id')])->with('project','item');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        $stock_out_data = $stock_out_data->where($where);
        $stock_out_data = $stock_out_data->get();
        $data['stock_data'] = $stock_out_data;

        return view('inventory.stock_report_print',$data);
    }
    

    public function pdf(Request $request)
    {
        $stock_out_data = Stock::where(['company_id'=>Session::get('company_id')])->with('project','item');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
        }
        if($request->item_id != null){
            $where['item_id'] = $request->item_id;
        }
        $stock_out_data = $stock_out_data->where($where);
        $stock_out_data = $stock_out_data->get();
        $data['stock_data'] = $stock_out_data;

        
        $pdf = PDF::loadView('inventory.stock_report_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('stock-report_'.$string.'.pdf');
    }
}
