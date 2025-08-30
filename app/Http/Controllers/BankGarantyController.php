<?php

namespace App\Http\Controllers;

use App\Models\BankGaranty;
use Illuminate\Http\Request;
use App\Models\Project;
Use Session;
use PDF;

class BankGarantyController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']      = 'accounts';
        $data['child_menu']     = 'garanty-list';
        $data['projects']       = Project::where(['company_id'=>Session::get('company_id')])->get();
        $bank_garanties         = BankGaranty::with('project')->where(['status'=>1]);
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

        if($request->garanty_date){
            $where['garanty_date'] = $request->garanty_date;
            $bank_garanties = $bank_garanties->where('garanty_date','=',$request->garanty_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id','desc')->paginate(20);
        $bank_garanties->appends($where);
        $data['bank_garanties'] = $bank_garanties;

        return view('account.bank_garanty.index',$data);
    }

    public function print(Request $request)
    {
        $bank_garanties         = BankGaranty::with('project')->where(['status'=>1]);
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

        if($request->garanty_date){
            $where['garanty_date'] = $request->garanty_date;
            $bank_garanties = $bank_garanties->where('garanty_date','=',$request->garanty_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id','desc')->get();
        $data['bank_garanties'] = $bank_garanties;
        return view('account.bank_garanty.print',$data);
    }

    public function pdf(Request $request)
    {
        $bank_garanties         = BankGaranty::with('project')->where(['status'=>1]);
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

        if($request->garanty_date){
            $where['garanty_date'] = $request->garanty_date;
            $bank_garanties = $bank_garanties->where('garanty_date','=',$request->garanty_date);
        }

        $bank_garanties = $bank_garanties->orderBy('id','desc')->get();
        $data['bank_garanties'] = $bank_garanties;

        $pdf = PDF::loadView('account.bank_garanty.print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('bank-garanty-list_'.$string.'.pdf');
    }

    
    public function create()
    {
        $data['main_menu']              = 'accounts';
        $data['child_menu']             = 'create-garanty';
        $data['projects']       = Project::where(['company_id'=>Session::get('company_id')])->get();
        return view('account.bank_garanty.create',$data);
    }

    public function store(Request $request)
    {
        $model = new BankGaranty();
        $model->garanty_date = $request->garanty_date;
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
            $request->attachment->move('attachment',$newImageName);

            $model->attachment = ('attachment/'.$newImageName);
        }
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Bank Garanty Inserted.";
        $request->session()->flash('message',$msg);

        return redirect('garanty-list')->with('status', $msg);
    }

   
}
