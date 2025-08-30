<?php
namespace App\Http\Controllers;

use App\Models\WorkStatus;
use App\Models\Project;
use App\Models\WorkStatusLog;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Session;
use PDF;

class WorkStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data['main_menu']              = 'work-status';
        $data['child_menu']             = 'view';
        $data['vendor_data']            = Vendor::where(['status'=>1,'company_id'=>Session::get('company_id')])->with('company')->get();
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $work_status = WorkStatus::with('company','project','vendor')->where(['status'=>1,'company_id'=>Session::get('company_id')]);
        $append_data = array();
        if($request->vendor_id != null){
            $work_status = $work_status->where(['vendor_id'=>$request->vendor_id]);
            $append_data['vendor_id'] = $request->vendor_id;
        }
        if($request->project_id != null){
            $work_status = $work_status->where(['project_id'=>$request->project_id]);
            $append_data['project_id'] = $request->project_id;
        }
        $work_status = $work_status->paginate(20);
        $work_status->appends($append_data);

        $data['work_status']            = $work_status;

        //dd($data['project_data']);
        return view('work_status.view',$data);
    }
    
    public function print(Request $request)
    {
        $work_status = WorkStatus::with('company','project','vendor')->where(['status'=>1,'company_id'=>Session::get('company_id')]);
        $append_data = array();
        if($request->vendor_id != null){
            $work_status = $work_status->where(['vendor_id'=>$request->vendor_id]);
            $append_data['vendor_id'] = $request->vendor_id;
        }
        if($request->project_id != null){
            $work_status = $work_status->where(['project_id'=>$request->project_id]);
            $append_data['project_id'] = $request->project_id;
        }
        $work_status = $work_status->get();

        $data['work_status']            = $work_status;
        return view('work_status.print',$data);
    }

    function pdf(Request $request){
        $work_status = WorkStatus::with('company','project','vendor')->where(['status'=>1,'company_id'=>Session::get('company_id')]);
        $append_data = array();
        if($request->vendor_id != null){
            $work_status = $work_status->where(['vendor_id'=>$request->vendor_id]);
            $append_data['vendor_id'] = $request->vendor_id;
        }
        if($request->project_id != null){
            $work_status = $work_status->where(['project_id'=>$request->project_id]);
            $append_data['project_id'] = $request->project_id;
        }
        $work_status = $work_status->get();

        $data['work_status']            = $work_status;

        $pdf = PDF::loadView('work_status.print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('work-status_'.$string.'.pdf');
    }

    function work_status_log($work_status_id=''){
        $data['main_menu']              = 'work-status';
        $data['child_menu']             = 'view';
        $data['work_status']           = WorkStatus::where(['id'=>$work_status_id])->with('company','vendor','project')->get()->first();
        $data['work_status_log']        = WorkStatusLog::where(['work_status_id'=>$work_status_id])->get();
        return view('work_status.work_status_log',$data);
    }

    public function man_power(Request $request)
    {
        $data['main_menu']              = 'work-status';
        $data['child_menu']             = 'man-power';
        $data['vendor_data']            = Vendor::where(['status'=>1,'company_id'=>Session::get('company_id')])->with('company')->get();
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $work_status = WorkStatus::with('company','project','vendor')->where(['status'=>1,'company_id'=>Session::get('company_id')]);
        $append_data = array();
        if($request->vendor_id != null){
            $work_status = $work_status->where(['vendor_id'=>$request->vendor_id]);
            $append_data['vendor_id'] = $request->vendor_id;
        }
        if($request->project_id != null){
            $work_status = $work_status->where(['project_id'=>$request->project_id]);
            $append_data['project_id'] = $request->project_id;
        }
        $work_status = $work_status->paginate(20);
        $work_status->appends($append_data);

        $data['work_status']            = $work_status;

        //dd($data['project_data']);
        return view('work_status.man_power',$data);
    }
    
    public function man_power_print(Request $request)
    {
        $work_status = WorkStatus::with('company','project','vendor')->where(['status'=>1,'company_id'=>Session::get('company_id')]);
        $append_data = array();
        if($request->vendor_id != null){
            $work_status = $work_status->where(['vendor_id'=>$request->vendor_id]);
            $append_data['vendor_id'] = $request->vendor_id;
        }
        if($request->project_id != null){
            $work_status = $work_status->where(['project_id'=>$request->project_id]);
            $append_data['project_id'] = $request->project_id;
        }
        $work_status = $work_status->get();

        $data['work_status']            = $work_status;
        return view('work_status.man_power_print',$data);
    }

    function man_power_pdf(Request $request){
        $work_status = WorkStatus::with('company','project','vendor')->where(['status'=>1,'company_id'=>Session::get('company_id')]);
        $append_data = array();
        if($request->vendor_id != null){
            $work_status = $work_status->where(['vendor_id'=>$request->vendor_id]);
            $append_data['vendor_id'] = $request->vendor_id;
        }
        if($request->project_id != null){
            $work_status = $work_status->where(['project_id'=>$request->project_id]);
            $append_data['project_id'] = $request->project_id;
        }
        $work_status = $work_status->get();

        $data['work_status']            = $work_status;

        $pdf = PDF::loadView('work_status.man_power_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('man-power_'.$string.'.pdf');
    }

    function man_power_log($work_status_id=''){
        $data['main_menu']              = 'work-status';
        $data['child_menu']             = 'man-power';
        $data['work_status']            = WorkStatus::where(['id'=>$work_status_id])->with('company','vendor','project')->get()->first();
        $data['work_status_log']        = WorkStatusLog::where(['work_status_id'=>$work_status_id])->get();
        return view('work_status.man_power_log',$data);
    }

    
    public function create()
    {
        $data['main_menu']              = 'work-status';
        $data['child_menu']             = 'add';
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['vendor_data']            = Vendor::where(['status'=>1,'company_id'=>Session::get('company_id')])->get();

        return view('work_status.add',$data);
    }

    public function previous_data($project_id){
        $data['data'] = WorkStatus::with('company','vendor')->where(['project_id'=>$project_id])->get();
        return view('work_status.previous_data',$data);
    }

    
    public function store(Request $request)
    {
        $project_id         = $request->project_id;
        $vendor_id          = $request->vendor_id;
        $log_date           = $request->log_date;
        $work_status_id     = $request->work_status_id;
        $work_nature        = $request->work_nature;
        $today_manpower     = $request->today_manpower;
        $description        = $request->description;
        $previous_work      = $request->previous_work;
        $today_work         = $request->today_work;
        $agreement_amount   = $request->agreement_amount;
        $remarks            = $request->remarks;
        $per_manpower_cost  = $request->per_manpower_cost; 

        if($project_id != null){
            if($vendor_id != null && is_array($vendor_id)){
                for($i=0; count($vendor_id)>$i; $i++){
                    if($today_work[$i] != null){
                        if($work_status_id[$i] == 'new'){
                            $model = new WorkStatus();
                            $model->created_by      = auth()->user()->id;
                        }
                        else{
                            $model = WorkStatus::find($work_status_id[$i]);
                            $model->updated_by      = auth()->user()->id;
                        }
    
                        $log = new WorkStatusLog();
    
                        $model->company_id          = Session::get('company_id');
                        $model->project_id          = $project_id;
                        $model->vendor_id           = $vendor_id[$i];
                        $model->work_nature         = $work_nature[$i];
                        //$model->agreement_amount    = $agreement_amount[$i];
                        $model->total_manpower_cost = $model->total_manpower_cost + ($per_manpower_cost[$i]*$today_manpower[$i]);
                        $model->complete_work       = ($previous_work[$i]+$today_work[$i]);
                        $model->status              = 1;
                        
                        $model->save();
    
                        $work_status_id = $model->id;
    
                        $log->work_status_id        = $work_status_id;
                        $log->log_date              = $log_date; 
                        $log->man_power             = $today_manpower[$i];
                        $log->per_manpower_cost     = $per_manpower_cost[$i];
                        $log->total_manpower_cost   = ($per_manpower_cost[$i]*$today_manpower[$i]);
                        $log->description           = $description[$i];
                        $log->previous_work         = $previous_work[$i];
                        $log->today_work            = $today_work[$i];
                        $log->total_work            = ($previous_work[$i]+$today_work[$i]);
                        $log->remarks               = $remarks[$i];
                        $log->created_by            = auth()->user()->id;
                        $log->save();
                    }
                }
                
                $msg="Work Status Updated.";
                $request->session()->flash('message',$msg);
            }
            else{
                $msg="Invalid Request.";
                $request->session()->flash('warning',$msg);
            }
        }
        else{
            $msg="Invalid Request.";
            $request->session()->flash('warning',$msg);
        }

        return redirect('work-status')->with('status', $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function show(WorkStatus $workStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkStatus $workStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkStatus $workStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkStatus  $workStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkStatus $workStatus)
    {
        //
    }
}
