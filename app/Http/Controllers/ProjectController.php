<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\Income;
use App\Models\Company;
use App\Models\Expense;
use App\Models\Project;
use App\Models\Subproject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']              = 'project';
        $data['child_menu']             = 'main_project';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->paginate(15);
        $data['company_data']           = Company::where(['status'=>1])->get();
        return view('project.main_project',$data);
    }

    function print(){
        $data['title']                  = 'Project List || '.Session::get('company_name');
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        return view('basic_settings.project_print',$data);
    }


    function pdf(){
        $data['title']                  = 'Project List || '.Session::get('company_name');
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        $pdf = PDF::loadView('basic_settings.project_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('project-list_'.$string.'.pdf');
    }

    public function store(Request $request){
        $request->validate([
            'name'                  => 'required',
            'location'              => 'required',
            'authority'             => 'required',
            'status'                => 'required',
        ]);
        
        
        $model = new Project();
        $model->name                    = $request->post('name');
        $model->company_id              = Session::get('company_id');
        $model->location                = $request->post('location');
        $model->description             = $request->post('description');
        $model->authority               = $request->post('authority');
        $model->start_date              = $request->post('start_date');
        $model->end_date                = $request->post('end_date');
        $model->project_amount          = $request->post('project_amount');
        $model->estimated_cost          = $request->post('estimated_cost');
        $model->estimated_profit        = $request->post('estimated_profit');
        $model->status                  = $request->post('status');
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Project Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('project')->with('status', $msg);
    }

    function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Project::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Project Status Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('project')->with('status', $msg);
    }

    function update(Request $request, $id){
        // dd($request->all());
        $request->validate([
            'name'                  => 'required',
            'location'              => 'required',
            'authority'             => 'required',
            'status'                => 'required',
        ]);
        //dd($request->post());
        
        $model = Project::find($id);
        $model->name                = $request->name;
        $model->company_id          = Session::get('company_id');
        $model->location            = $request->location;
        $model->description         = $request->description;
        $model->authority           = $request->authority;
        $model->start_date          = $request->start_date;
        $model->end_date            = $request->end_date;
        $model->project_amount      = $request->project_amount;
        $model->estimated_cost      = $request->estimated_cost;
        $model->estimated_profit    = $request->estimated_profit;
        $model->status              = $request->status;
        $model->updated_by          = auth()->user()->id;
        
        $model->update();

        $msg="Project Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('project')->with('status', $msg);
    }


    public function subProjectIndex(){
        $data['main_menu']              = 'project';
        $data['child_menu']             = 'sub_project';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['sub_projects']           = Subproject::where(['company_id'=>Session::get('company_id')])->with('company')->paginate(20);
        $data['company_data']           = Company::where(['status'=>1])->get();
        return view('project.sub_project',$data);
    }

    public function printsubproject(){
        $data['title']                  = 'Sub Project List || '.Session::get('company_name');
        $data['sub_projects']           = Subproject::where(['company_id'=>Session::get('company_id')])->with('company','project')->get();
        return view('basic_settings.sub_project_print',$data);

    }

    public function storeSubProject(Request $request){
        $request->validate([
            'project_id'            => 'required',
            'name'                  => 'required',
            'location'              => 'required',
            'authority'             => 'required',
            'status'                => 'required',
        ]);
        
        
        $model = new Subproject();
        $model->name                    = $request->post('name');
        $model->company_id              = Session::get('company_id');
        $model->project_id              = $request->post('project_id');
        $model->location                = $request->post('location');
        $model->description             = $request->post('description');
        $model->authority               = $request->post('authority');
        $model->start_date              = $request->post('start_date');
        $model->end_date                = $request->post('end_date');
        $model->project_amount          = $request->post('project_amount');
        $model->estimated_cost          = $request->post('estimated_cost');
        $model->estimated_profit        = $request->post('estimated_profit');
        $model->status                  = $request->post('status');
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Sub Project Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('sub-project')->with('status', $msg);
    }

    public function updateSubProject(Request $request){
        // return $request->all();
        $request->validate([
            'project_id'            => 'required',
            'name'                  => 'required',
            'location'              => 'required',
            'authority'             => 'required',
            'status'                => 'required',
        ]);
        
        
        $model = Subproject::where('id', '=', $request->post('id'))->first();
        $model->name                    = $request->post('name');
        $model->company_id              = Session::get('company_id');
        $model->project_id              = $request->post('project_id');
        $model->location                = $request->post('location');
        $model->description             = $request->post('description');
        $model->authority               = $request->post('authority');
        $model->start_date              = $request->post('start_date');
        $model->end_date                = $request->post('end_date');
        $model->project_amount          = $request->post('project_amount');
        $model->estimated_cost          = $request->post('estimated_cost');
        $model->estimated_profit        = $request->post('estimated_profit');
        $model->status                  = $request->post('status');
        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg = "Sub Project Updated.";
        //$request->session()->flash('message',$msg);

        return redirect('sub-project')->with('status', $msg); 
    }


    public function filtersubProject(Request $request){
        // dd($request->project_id);
        $project_id = $request->project_id;
        $sub_projects = Subproject::where('project_id',$project_id)->get();
        // dd($sub_projects);
        if(count($sub_projects)>0){
            return response()->json($sub_projects);
        }
    }

    public function projectLedger(){
        $data['projects']       =  Project::where('company_id',Session::get('company_id'))->get();
        // dd($data['projects']);
        $data['sub_projects']   =  SubProject::where('company_id',Session::get('company_id'))->get();

        return view('project.project_ledger',$data);
    }

     public function projectLedgerList(Request $request){
        try{
            $start_date                 = $request->start_date; 
            $end_date                   = $request->end_date; 
            $project_id                 = $request->project_id;
            $category_id                = $request->category_id;
            $head_id                    = $request->head_id;
            $company_name               = Session::get('company_name');
            $project = '';
            $sub_project = '';

            $query = Project::where('company_id', session()->get('company_id'));
        
            if ($project_id){
                $query->where('project_id', $project_id);
                $project = Project::where('id',$project_id)->first();
            }
        
            // dd($project);

            return view('project.project_ledger_list',compact('start_date','end_date','company_name','project_id','project','category_id','head_id'));
        }catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
