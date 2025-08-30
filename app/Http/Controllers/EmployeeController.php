<?php
/**
 * Author: Tushar Das
 * Sr. Software Engineer
 * tushar2499@gmail.com 
 * 01815920898
 * STITBD
 * 09/10/2021
 */
namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PaymentType;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Section;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'manage-employee';
        $data['paymentType']            = PaymentType::all();
        $data['company']                = Company::all();
        $data['designation']            = Designation::all();
        $data['grade']                  = Grade::all();
        $data['schedule']               = Schedule::all();
        $data['employee']               = Employee::with('department','section','designation','company','grade','payment_type','schedule')->get();
        return view('hrm.employee.manage',$data);
    }

    function load_employee_view($id){
        $data['employee'] = Employee::with('department','section','designation','company','grade','payment_type','schedule')->find($id);
        return view('hrm.employee.view',$data);
    }

    function edit($id){
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'manage-employee';
        $data['paymentType']            = PaymentType::all();
        $data['company']                = Company::all();
        $data['designation']            = Designation::all();
        $data['grade']                  = Grade::all();
        $data['schedule']               = Schedule::all();
        $data['employee']               = Employee::with('department','section','designation','company','grade','payment_type','schedule')->find($id);
        return view('hrm.employee.edit',$data);
    }

    function update(Request $request){
        //dd($request);
        $request->validate([
            'id'                  => 'required',
            'name'                => 'required',
            'company_id'          => 'required',
            'department_id'       => 'required',
            'designation_id'      => 'required',
            'grade_id'            => 'required',
            'schedule_id'         => 'required',
            'gross_salary'        => 'required',
            'payment_type_id'     => 'required'
        ]);

        $model = Employee::find($request->post('id'));
        if($request->employee_image != null){
            $newImageName = 'employee_'.uniqid().'.'.$request->thumbnail->extension();
            $request->employee_image->move(public_path('upload_images/employee'),$newImageName);
            $model->employee_image = public_path('upload_images/employee/'.$newImageName);
        }

        if($request->employee_signature != null){
            $newImageName = 'employee_signature_'.uniqid().'.'.$request->thumbnail->extension();
            $request->employee_signature->move(public_path('upload_images/employee_signature'),$newImageName);
            $model->employee_signature = public_path('upload_images/employee/'.$newImageName);
        }

        if($request->nominee_image != null){
            $newImageName = 'nominee_image_'.uniqid().'.'.$request->thumbnail->extension();
            $request->nominee_image->move(public_path('upload_images/nominee_image'),$newImageName);
            $model->nominee_image = public_path('upload_images/employee/'.$newImageName);
        }

        $model->name                    = $request->post('name');
        $model->card_id                 = $request->post('card_id');
        $model->machine_id              = $request->post('machine_id');
        $model->company_id              = $request->post('company_id');
        $model->department_id           = $request->post('department_id');
        $model->section_id              = $request->post('section_id');
        $model->designation_id          = $request->post('designation_id');
        $model->branch_id               = $request->post('branch_id');
        $model->grade_id                = $request->post('grade_id');
        $model->schedule_id             = $request->post('schedule_id');
        $model->joining_date            = $request->post('joining_date');
        $model->job_location            = $request->post('job_location');
        $model->gross_salary            = $request->post('gross_salary');
        $model->father_name             = $request->post('father_name');
        $model->mother_name             = $request->post('mother_name');
        $model->marital_status          = $request->post('marital_status');
        $model->spouse_name             = $request->post('spouse_name');
        $model->gender                  = $request->post('gender');
        $model->blood_group             = $request->post('blood_group');
        $model->religion                = $request->post('religion');
        $model->nid                     = $request->post('nid');
        $model->etin                    = $request->post('etin');
        $model->birthdate               = $request->post('birthdate');
        $model->payment_type_id         = $request->post('payment_type_id');
        $model->bank_ac_no              = $request->post('bank_ac_no');
        $model->nominee_name            = $request->post('nominee_name');
        $model->nominee_relation        = $request->post('nominee_relation');
        $model->nominee_father_name     = $request->post('nominee_father_name');
        $model->nominee_spouse_name     = $request->post('nominee_spouse_name');
        $model->nominee_nid             = $request->post('nominee_nid');
        $model->nominee_cell_no         = $request->post('nominee_cell_no');
        $model->nominee_address         = $request->post('nominee_address');
        $model->mobile_no               = $request->post('mobile_no');
        $model->present_address         = $request->post('present_address');
        $model->parmanent_address       = $request->post('parmanent_address');
        $model->academic_degree         = $request->post('academic_degree');
        $model->passing_year            = $request->post('passing_year');
        $model->working_experience      = $request->post('working_experience');

        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg="Employee update.";
        //$request->session()->flash('message',$msg);

        return redirect('employee-profile/'.$request->post('id'))->with('status', $msg);
    }

    function profile($id){
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'manage-employee';
        $data['employee'] = Employee::with('department','section','designation','company','grade','payment_type','schedule')->find($id);
        return view('hrm.employee.profile',$data);
    }

    public function status_update(Request $request,$status=1,$id=null){
        
        $model                  = Employee::find($id);
        $model->status          = $status;
        $model->save();

        $msg="Employee Status Updated.";

        return redirect('manage-employee')->with('status', $msg);
    }


    function search(Request $request){
        $data = array();
        if($request->company_id != 'all' && $request->company_id != null){ $data['company_id'] = $request->company_id; }
        if($request->department_id != 'all' && $request->department_id != null){ $data['department_id'] = $request->department_id; }
        if($request->section_id != 'all' && $request->section_id != null){ $data['section_id'] = $request->section_id; }
        if($request->branch_id != 'all' && $request->branch_id != null){ $data['branch_id'] = $request->branch_id; }
        if($request->designation_id != 'all' && $request->designation_id != null){ $data['designation_id'] = $request->designation_id; }
        if($request->grade_id != 'all' && $request->grade_id != null){ $data['grade_id'] = $request->grade_id; }
        if($request->schedule_id != 'all' && $request->schedule_id != null){ $data['schedule_id'] = $request->schedule_id; }
        if($request->paymnet_type_id != 'all' && $request->paymnet_type_id != null){ $data['paymnet_type_id'] = $request->paymnet_type_id; }
        //var_dump($data); exit;
        $data['employee'] = Employee::with('department','section','designation','company','grade','payment_type','schedule')->where($data)->get();
        return view('hrm.employee.search',$data);
    }

    public function create()
    {
        $data['main_menu']              = 'hrm';
        $data['child_menu']             = 'employee-create';
        $data['paymentType']            = PaymentType::all();
        $data['company']                = Company::all();
        $data['designation']            = Designation::all();
        $data['grade']                  = Grade::all();
        $data['schedule']               = Schedule::all();
        return view('hrm.employee.create',$data);
    }

    public function store(Request $request){
        //dd($request);
        $request->validate([
            'name'                  => 'required',
            'company_id'                  => 'required',
            'department_id'                  => 'required',
            'designation_id'                  => 'required',
            'grade_id'                  => 'required',
            'schedule_id'                  => 'required',
            'gross_salary'                  => 'required',
            'payment_type_id'                  => 'required'
        ]);

        $model = new Employee();

        if($request->employee_image != null){
            $newImageName = 'employee_'.uniqid().'.'.$request->employee_image->extension();
            $request->employee_image->move(public_path('upload_images/employee'),$newImageName);
            $model->employee_image = public_path('upload_images/employee/'.$newImageName);
        }

        if($request->employee_signature != null){
            $newImageName = 'employee_signature_'.uniqid().'.'.$request->employee_signature->extension();
            $request->employee_signature->move(public_path('upload_images/employee_signature'),$newImageName);
            $model->employee_signature = public_path('upload_images/employee/'.$newImageName);
        }

        if($request->nominee_image != null){
            $newImageName = 'nominee_image_'.uniqid().'.'.$request->nominee_image->extension();
            $request->nominee_image->move(public_path('upload_images/nominee_image'),$newImageName);
            $model->nominee_image = public_path('upload_images/employee/'.$newImageName);
        }

        $model->name                    = $request->post('name');
        $model->card_id                 = $request->post('card_id');
        $model->machine_id              = $request->post('machine_id');
        $model->company_id              = $request->post('company_id');
        $model->department_id           = $request->post('department_id');
        $model->section_id              = $request->post('section_id');
        $model->designation_id          = $request->post('designation_id');
        $model->branch_id               = $request->post('branch_id');
        $model->grade_id                = $request->post('grade_id');
        $model->schedule_id             = $request->post('schedule_id');
        $model->joining_date            = $request->post('joining_date');
        $model->job_location            = $request->post('job_location');
        $model->gross_salary            = $request->post('gross_salary');
        $model->father_name             = $request->post('father_name');
        $model->mother_name             = $request->post('mother_name');
        $model->marital_status          = $request->post('marital_status');
        $model->spouse_name             = $request->post('spouse_name');
        $model->gender                  = $request->post('gender');
        $model->blood_group             = $request->post('blood_group');
        $model->religion                = $request->post('religion');
        $model->nid                     = $request->post('nid');
        $model->etin                    = $request->post('etin');
        $model->birthdate               = $request->post('birthdate');
        $model->payment_type_id         = $request->post('payment_type_id');
        $model->bank_ac_no              = $request->post('bank_ac_no');
        $model->nominee_name            = $request->post('nominee_name');
        $model->nominee_relation        = $request->post('nominee_relation');
        $model->nominee_father_name     = $request->post('nominee_father_name');
        $model->nominee_spouse_name     = $request->post('nominee_spouse_name');
        $model->nominee_nid             = $request->post('nominee_nid');
        $model->nominee_cell_no         = $request->post('nominee_cell_no');
        $model->nominee_address         = $request->post('nominee_address');
        $model->mobile_no               = $request->post('mobile_no');
        $model->present_address         = $request->post('present_address');
        $model->parmanent_address       = $request->post('parmanent_address');
        $model->academic_degree         = $request->post('academic_degree');
        $model->passing_year            = $request->post('passing_year');
        $model->working_experience      = $request->post('working_experience');

        $model->created_by              = auth()->user()->id;
        $model->save();

        $msg="Employee Inserted.";
        //$request->session()->flash('message',$msg);

        return redirect('employee-create')->with('status', $msg);

    }

    function load_department_by_company_id($company_id){
        if($company_id != null){
            $model = Department::where(['company_id'=>$company_id])->get();
            if($model != null){
                $options = '<option value="">Select One</option>';
                foreach($model as $item){
                    $options .='<option value="'.$item->id.'">'.$item->name.'</option>';
                }
                echo json_encode(['status'=>'success','message'=>'Data found','data'=>$model,'options'=>$options]);
            }
            else{
                echo json_encode(['status'=>'error','message'=>'This ID Not Found In DB.']);
            }
            
        }
        else{
            echo json_encode(['status'=>'error','message'=>'Empty ID']);
        }
    }
    function load_branch_by_company_id($company_id){
        if($company_id != null){
            $model = Branch::where(['company_id'=>$company_id])->get();
            if($model != null){
                $options = '<option value="">Select One</option>';
                foreach($model as $item){
                    $options .='<option value="'.$item->id.'">'.$item->name.'</option>';
                }
                echo json_encode(['status'=>'success','message'=>'Data found','data'=>$model,'options'=>$options]);
            }
            else{
                echo json_encode(['status'=>'error','message'=>'This ID Not Found In DB.']);
            }
            
        }
        else{
            echo json_encode(['status'=>'error','message'=>'Empty ID']);
        }
    }

    function load_section_by_company_id($company_id){
        if($company_id != null){
            $model = Section::where(['company_id'=>$company_id])->get();
            if($model != null){
                $options = '<option value="">Select One</option>';
                foreach($model as $item){
                    $options .='<option value="'.$item->id.'">'.$item->name.'</option>';
                }
                echo json_encode(['status'=>'success','message'=>'Data found','data'=>$model,'options'=>$options]);
            }
            else{
                echo json_encode(['status'=>'error','message'=>'This ID Not Found In DB.']);
            }
            
        }
        else{
            echo json_encode(['status'=>'error','message'=>'Empty ID']);
        }
    }

    

    
}
