<?php

namespace App\Http\Controllers;

use App\Models\VendorProject;
use App\Models\Company;
use App\Models\Vendor;
use App\Models\VendorDue;
use App\Models\Project;
use Illuminate\Http\Request;
use Session;
use PDF;

class VendorProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['main_menu']              = 'vendor';
        $data['child_menu']             = 'vendor-project-list';
        $data['vendor_data']            = Vendor::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['project_data']           = Project::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        $data['vendor_project_data']    = VendorProject::where(['company_id'=>Session::get('company_id')])->with('vendor','project')->get();
        return view('vendor.vendor_project_list',$data);
    }

    function print(){
        $data['title']                  = 'Vendor Project List || '.Session::get('company_name');
        $data['vendor_project_data']           = VendorProject::where(['company_id'=>Session::get('company_id')])->with('company')->get();
        return view('vendor.vendor_project_print',$data);
    }

    function pdf(){
        $data['title']                  = 'Vendor Project List || '.Session::get('company_name');
        $data['vendor_project_data']           = VendorProject::where(['company_id'=>Session::get('company_id')])->with('company')->get();

        $pdf = PDF::loadView('vendor.vendor_project_print', $data)->setOptions(['defaultFont' => 'sans-serif']);
        $string = str_replace(' ', '_', Session::get('company_name'));
        return $pdf->download('vendor-list_'.$string.'.pdf');
    }

    
    public function store(Request $request)
    {
        $vendor_project = new VendorProject;
        $vendor_project->vendor_id = $request->vendor_id;
        $vendor_project->project_id = $request->project_id;
        $vendor_project->amount = $request->amount;
        $vendor_project->remarks = $request->remarks;
        $vendor_project->status = 1;
        $vendor_project->company_id = Session::get('company_id');
        $vendor_project->created_by = auth()->user()->id;
        $vendor_project->save();

        $due = VendorDue::where(['vendor_id'=>$request->vendor_id,'project_id'=>$request->project_id])->first();
        if($due){
            $due->due_amount = $request->amount;
            $due->updated_by = auth()->user()->id;
            $due->save();
        }
        else{
            $due = new VendorDue;
            $due->vendor_id = $request->vendor_id;
            $due->project_id = $request->project_id;
            $due->company_id = Session::get('company_id');
            $due->due_amount = $request->amount;
            $due->created_by = auth()->user()->id;
            $due->save();
        }
        $msg = "Vendor Project Inserted.";
        $request->session()->flash('message',$msg);
        return redirect()->back()->with('success', 'Vendor Project Added Successfully!');
    }

    public function update(Request $request)
    {
        $vendor_project = VendorProject::find($request->id);
        $pev_amount = $vendor_project->amount;
        $vendor_project->vendor_id = $request->vendor_id;
        $vendor_project->project_id = $request->project_id;
        $vendor_project->amount = $request->amount;
        $vendor_project->remarks = $request->remarks;
        $vendor_project->status = 1;
        $vendor_project->company_id = Session::get('company_id');
        $vendor_project->updated_by = auth()->user()->id;
        $vendor_project->save();

        $due = VendorDue::where(['vendor_id'=>$request->vendor_id,'project_id'=>$request->project_id])->first();
        if($due){
            $due_amount = $pev_amount-$request->amount;
            $due->due_amount = $due->due_amount-$due_amount;
            $due->updated_by = auth()->user()->id;
            $due->save();
        }
        else{
            $due = new VendorDue;
            $due->vendor_id = $request->vendor_id;
            $due->project_id = $request->project_id;
            $due->company_id = Session::get('company_id');
            //$due_amount = $pev_amount-$request->amount;
            $due->due_amount = $request->amount;
            $due->created_by = auth()->user()->id;
            $due->save();
        }

        $msg = "Vendor Project Updated.";
        $request->session()->flash('message',$msg);

        return redirect()->back()->with('success', 'Vendor Project Updated Successfully!');
    }

    
}
