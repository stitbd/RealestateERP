<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Project;
use Illuminate\Http\Request;
Use Session;
Use PDF;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'bill-list';
        $data['child_menu']             = 'bill-list';
        
        $requisitions = Bill::where(['company_id'=>Session::get('company_id')])->with('company','project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        

        if($request->bill_date != null){
            $where['bill_date'] = $request->bill_date;
            $requisitions->where('bill_date','>=',$request->bill_date);
        }
        if($request->bill_submitted_date != null){
            $where['bill_submitted_date'] = $request->bill_submitted_date;
            $requisitions->where('bill_submitted_date','<=',$request->bill_submitted_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['bill_data']             = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        return view('bill.bill_list',$data);
    }

    function print(Request $request){
        $data['title']                  = 'Bill List || '.Session::get('company_name');
        $requisitions = Bill::where(['company_id'=>Session::get('company_id')])->with('company','project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);

            $data['project'] = Project::find($request->project_id);
            //dd($data['project_data']->name);
        }
        

        if($request->bill_date != null){
            $where['bill_date'] = $request->bill_date;
            $requisitions->where('bill_date','>=',$request->bill_date);
        }
        if($request->bill_submitted_date != null){
            $where['bill_submitted_date'] = $request->bill_submitted_date;
            $requisitions->where('bill_submitted_date','<=',$request->bill_submitted_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['bill_data']             = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        return view('bill.bill_print',$data);
    }

    function pdf(Request $request){
        $data['title']                  = 'Bill List || '.Session::get('company_name');
        $requisitions = Bill::where(['company_id'=>Session::get('company_id')])->with('company','project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);

            $data['project'] = Project::find($request->project_id);
            //dd($data['project_data']->name);
        }
        

        if($request->bill_date != null){
            $where['bill_date'] = $request->bill_date;
            $requisitions->where('bill_date','>=',$request->bill_date);
        }
        if($request->bill_submitted_date != null){
            $where['bill_submitted_date'] = $request->bill_submitted_date;
            $requisitions->where('bill_submitted_date','<=',$request->bill_submitted_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['bill_data']             = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        
        
        $pdf = PDF::loadView('bill.bill_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('bill_'.$string.'.pdf');
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'bill_status'                  => 'required'
        ]);
        
        
        $model = new Bill();
        $model->project_id          = $request->post('project_id');
        $model->company_id          = Session::get('company_id');
        $model->bill_status         = $request->post('bill_status');
        $model->bill_no             = $request->post('bill_no');
        $model->bill_date           = $request->post('bill_date');
        $model->bill_submitted_date = $request->post('bill_submitted_date');
        $model->amount              = $request->post('amount');
        $model->authority_name      = $request->post('authority_name');
        $model->description         = $request->post('description');
        if($request->attachment != null){
            $newImageName = time().'_audit_report.'.$request->attachment->extension();
            $request->attachment->move(('attachment'),$newImageName);

            $model->attachment = ('attachment/'.$newImageName);
        }
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Bill Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('bill-list')->with('status', $msg);
    }
    
    function update(Request $request){
        $request->validate([
            'bill_status'     => 'required'
        ]);
        //dd($request->post());
        
        $model = Bill::where('id', '=', $request->post('id'))->first();
        $model->project_id          = $request->post('project_id');
        $model->company_id          = Session::get('company_id');
        $model->bill_status         = $request->post('bill_status');
        $model->bill_no             = $request->post('bill_no');
        $model->bill_date           = $request->post('bill_date');
        $model->bill_submitted_date = $request->post('bill_submitted_date');
        $model->amount              = $request->post('amount');
        $model->authority_name      = $request->post('authority_name');
        $model->description         = $request->post('description');
        if($request->attachment != null){
            $newImageName = time().'_audit_report.'.$request->attachment->extension();
            $request->attachment->move(('attachment'),$newImageName);

            $model->attachment = ('attachment/'.$newImageName);
        }
        $model->updated_by          = auth()->user()->id;
        $model->save();

        $msg="Bill Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('bill-list')->with('status', $msg);
    }
}
