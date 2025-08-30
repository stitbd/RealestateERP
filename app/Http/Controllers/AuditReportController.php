<?php
namespace App\Http\Controllers;

use App\Models\AuditReport;
use App\Models\Project;
use Illuminate\Http\Request;
use Session;
use PDF;

class AuditReportController extends Controller
{
    public function index(Request $request)
    {
        $data['main_menu']              = 'audit-list';
        $data['child_menu']             = 'audit-list';
        
        $requisitions = AuditReport::where(['company_id'=>Session::get('company_id')])->with('company','project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
        }
        

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('audit_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('audit_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['audit_data']             = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        return view('audit.audit_list',$data);
    }

    function pdf(Request $request){
        $data['title']                  = 'Audit List || '.Session::get('company_name');
        $requisitions = AuditReport::where(['company_id'=>Session::get('company_id')])->with('company','project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
            $data['project'] = Project::find($request->project_id);
        }
        
        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('audit_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('audit_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['audit_data']             = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        
        $pdf = PDF::loadView('audit.audit_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('audit-report_'.$string.'.pdf');
    }

    function print(Request $request){
        $data['title']                  = 'Audit List || '.Session::get('company_name');
        $requisitions = AuditReport::where(['company_id'=>Session::get('company_id')])->with('company','project');
        $where = array();
        if($request->project_id != null){
            $where['project_id'] = $request->project_id;
            $requisitions->where('project_id','=',$request->project_id);
            $data['project'] = Project::find($request->project_id);
        }
        

        if($request->start_date != null){
            $where['start_date'] = $request->start_date;
            $requisitions->where('audit_date','>=',$request->start_date);
        }
        if($request->end_date != null){
            $where['end_date'] = $request->end_date;
            $requisitions->where('audit_date','<=',$request->end_date);
        }
        $requisitions = $requisitions->paginate(20);
        $requisitions->appends($where);
        $data['audit_data']             = $requisitions;
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        return view('audit.audit_print',$data);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'type'                  => 'required'
        ]);
        
        
        $model = new AuditReport();
        $model->project_id          = $request->post('project_id');
        $model->company_id          = Session::get('company_id');
        $model->type                = $request->post('type');
        $model->description         = $request->post('description');
        $model->audit_date          = $request->post('audit_date');
        $model->audit_person        = $request->post('audit_person');
        if($request->attachment != null){
            $newImageName = time().'_audit_report.'.$request->attachment->extension();
            $request->attachment->move('attachment',$newImageName);

            $model->attachment = 'attachment/'.$newImageName;
        }
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Audit Report Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('audit-list')->with('status', $msg);
    }
    
    function update(Request $request){
        $request->validate([
            'type'     => 'required'
        ]);
        //dd($request->post());
        
        $model = AuditReport::where('id', '=', $request->post('id'))->first();
        $model->project_id          = $request->post('project_id');
        $model->company_id          = Session::get('company_id');
        $model->type                = $request->post('type');
        $model->description         = $request->post('description');
        $model->audit_date          = $request->post('audit_date');
        $model->audit_person        = $request->post('audit_person');
        $model->updated_by          = auth()->user()->id;
        if($request->attachment != null){
            $newImageName = time().'_audit_report.'.$request->attachment->extension();
            $request->attachment->move('attachment',$newImageName);

            $model->attachment = ('attachment/'.$newImageName);
        }
        $model->save();

        $msg="Audit Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('audit-list')->with('status', $msg);
    }

}
