<?php

namespace App\Http\Controllers;

use App\Models\PgAmount;
use Illuminate\Http\Request;
use App\Models\Project;
use Session;


class PgAmountController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']      = 'accounts';
        $data['child_menu']     = 'pg-list';
        $data['projects']       = Project::where(['company_id'=>Session::get('company_id')])->get();
        $bank_garanties         = PgAmount::with('project')->where(['status'=>1]);
        $where = array();
        if($request->bank_name){
            $where['bank_name'] = $request->bank_name;
            $bank_garanties = $bank_garanties->where('bank_name','like','%'.$request->bank_name.'%');
        }
        if($request->project_id){
            $where['project_id'] = $request->project_id;
            $bank_garanties = $bank_garanties->where('project_id','=',$request->project_id);
        }

        if($request->valid_date){
            $where['valid_date'] = $request->valid_date;
            $bank_garanties = $bank_garanties->where('valid_date','=',$request->valid_date);
        }

        if($request->pg_date){
            $where['pg_date'] = $request->pg_date;
            $bank_garanties = $bank_garanties->where('pg_date','=',$request->garanty_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id','desc')->paginate(20);
        $bank_garanties->appends($where);
        $data['pg_amounts'] = $bank_garanties;

        return view('account.pg_amount.index',$data);
    }

    
    public function print(Request $request)
    {
        $bank_garanties         = PgAmount::with('project')->where(['status'=>1]);
        $where = array();
        if($request->bank_name){
            $where['bank_name'] = $request->bank_name;
            $bank_garanties = $bank_garanties->where('bank_name','like','%'.$request->bank_name.'%');
        }
        if($request->project_id){
            $where['project_id'] = $request->project_id;
            $bank_garanties = $bank_garanties->where('project_id','=',$request->project_id);
        }

        if($request->valid_date){
            $where['valid_date'] = $request->valid_date;
            $bank_garanties = $bank_garanties->where('valid_date','=',$request->valid_date);
        }

        if($request->pg_date){
            $where['pg_date'] = $request->pg_date;
            $bank_garanties = $bank_garanties->where('pg_date','=',$request->garanty_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id','desc')->get();
        $data['pg_amounts'] = $bank_garanties;
        return view('account.pg_amount.print',$data);
    }

    public function pdf(Request $request)
    {
        $bank_garanties         = PgAmount::with('project')->where(['status'=>1]);
        $where = array();
        if($request->bank_name){
            $where['bank_name'] = $request->bank_name;
            $bank_garanties = $bank_garanties->where('bank_name','like','%'.$request->bank_name.'%');
        }
        if($request->project_id){
            $where['project_id'] = $request->project_id;
            $bank_garanties = $bank_garanties->where('project_id','=',$request->project_id);
        }

        if($request->valid_date){
            $where['valid_date'] = $request->valid_date;
            $bank_garanties = $bank_garanties->where('valid_date','=',$request->valid_date);
        }

        if($request->pg_date){
            $where['pg_date'] = $request->pg_date;
            $bank_garanties = $bank_garanties->where('pg_date','=',$request->garanty_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id','desc')->get();
        $data['pg_amounts'] = $bank_garanties;

        $pdf = PDF::loadView('account.pg_amount.print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('pg-amounts-list_'.$string.'.pdf');
    }

    public function create()
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'create-pg';
        $data['projects']       = Project::where(['company_id'=>Session::get('company_id')])->get();
        return view('account.pg_amount.create',$data);
    }

    public function store(Request $request)
    {
        $model = new PgAmount();
        $model->pg_date = $request->pg_date;
        $model->project_id = $request->project_id;
        $model->bank_name = $request->bank_name;
        $model->description = $request->description;
        $model->reference = $request->reference;
        $model->amount = $request->amount;
        $model->bank_credit_limit = $request->bank_credit_limit;
        $model->purpose = $request->purpose;
        $model->valid_date = $request->valid_date;
        $model->remarks = $request->remarks;
        if($request->attachment != null){
            $newImageName = time().'_bank_garanty.'.$request->attachment->extension();
            $request->attachment->move(public_path('attachment'),$newImageName);

            $model->attachment = public_path('attachment/'.$newImageName);
        }
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "PG Inserted.";
        $request->session()->flash('message',$msg);

        return redirect('pg-list')->with('status', $msg);
    }
}
