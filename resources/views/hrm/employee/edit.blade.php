@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Edit Employee
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{ route('update-employee') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $employee->id }}"/>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title text-success text-bold">
                                                Employee Job Information
                                            </h3> 
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="name">Name<span class="text-danger">*</span></label>
                                                <input type="text" value="{{ $employee->name }}" class="form-control" placeholder="Employee Name" name="name" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Card ID</label>
                                                <input type="text" value="{{ $employee->card_id }}" class="form-control" placeholder="Card ID" name="card_id">
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Machine ID</label>
                                                <input type="text" value="{{ $employee->machine_id }}" class="form-control" placeholder="Machine ID" name="machine_id">
                                            </div>
                                            <div class="form-group">
                                                <label for="company_id">Company<span class="text-danger">*</span></label>
                                                <select name="company_id" class="form-control" required onchange="load_department(this.value); load_section(this.value); load_branch(this.value);">
                                                    @if ($employee->company !=  null)
                                                        <option selected="" value="{{ $employee->company->id }}">{{ $employee->company->name }}</option>
                                                    @endif
                                                    <option value="">Select A Company</option>
                                                    @foreach ($company as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="department_id">Department<span class="text-danger">*</span></label>
                                                <select name="department_id" class="form-control" required id="department_id">
                                                    @if ($employee->department !=  null)
                                                        <option  selected="" value="{{ $employee->department->id }}">{{ $employee->department->name }}</option>
                                                    @endif
                                                    <option value="">Select A Department</option>
                                                </select>
                                            </div>
        
                                            <div class="form-group">
                                                <label for="section_id">Section</label>
                                                <select name="section_id" class="form-control" id="section_id">
                                                    @if ($employee->section !=  null)
                                                        <option  selected="" value="{{ $employee->section->id }}">{{ $employee->section->name }}</option>
                                                    @endif
                                                    <option value="">Select A Section</option>
                                                    
                                                </select>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="designation_id">Designation<span class="text-danger">*</span></label>
                                                <select name="designation_id" class="form-control" required >
                                                    @if ($employee->designation !=  null)
                                                        <option  selected="" value="{{ $employee->designation->id }}">{{ $employee->designation->name }}</option>
                                                    @endif
                                                    <option value="">Select A Designation</option>
                                                    @foreach ($designation as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
        
                                            <div class="form-group">
                                                <label for="branch_id">Branch Office</label>
                                                <select name="branch_id" class="form-control" id="branch_id">
                                                    @if ($employee->branch !=  null)
                                                        <option  selected=""  value="{{ $employee->branch->id }}">{{ $employee->branch->name }}</option>
                                                    @endif
                                                    <option value="">Select A Branch Office</option>
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="grade_id">Grade<span class="text-danger">*</span></label>
                                                <select name="grade_id" class="form-control" required >
                                                    @if ($employee->grade !=  null)
                                                        <option  selected=""  value="{{ $employee->grade->id }}">{{ $employee->grade->name }}</option>
                                                    @endif
                                                    <option value="">Select A Grade</option>
                                                    @foreach ($grade as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="schedule_id">Schedule<span class="text-danger">*</span></label>
                                                <select name="schedule_id" class="form-control" required >
                                                    @if ($employee->schedule !=  null)
                                                        <option  selected=""  value="{{ $employee->schedule->id }}">{{ $employee->schedule->name }}</option>
                                                    @endif
                                                    <option value="">Select A Schedule</option>
                                                    @foreach ($schedule as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="joining_date">Joining Date</label>
                                                <input type="date" name="joining_date" value="{{ $employee->joining_date }}" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="job_location">Job Location</label>
                                                <input type="text" name="job_location" value="{{ $employee->job_location }}" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="gross_salary">Gross Salary<span class="text-danger">*</span></label>
                                                <input type="number" placeholder="Sallary" value="{{ $employee->gross_salary }}" name="gross_salary" class="form-control" required/>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title  text-success text-bold">
                                                Employee Personal Information
                                            </h3> 
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="father_name">Father Name</label>
                                                <input type="text" value="{{ $employee->father_name }}" class="form-control" placeholder="Father Name" name="father_name">
                                            </div>
                                            <div class="form-group">
                                                <label for="mother_name">Mother Name</label>
                                                <input type="text" value="{{ $employee->mother_name }}" class="form-control" placeholder="Mother Name" name="mother_name">
                                            </div>
                                            <div class="form-group">
                                                <label for="marital_status">Marital Status</label>
                                                <select name="marital_status" class="form-control">
                                                    @if ($employee->marital_status == 'Unmarried')
                                                    <option selected="" value="Unmarried" >Unmarried</option>
                                                    <option value="Married">Married</option>
                                                    @else
                                                    <option value="Unmarried" >Unmarried</option>
                                                    <option selected="" value="Married">Married</option>
                                                    @endif
                                                    
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="spouse_name">Spouse Name  [If available]</label>
                                                <input type="text" value="{{ $employee->spouse_name }}" class="form-control" placeholder="Spouse Name" name="spouse_name">
                                            </div>
                                            <div class="form-group">
                                                <label for="gender">Gender</label>
                                                <select name="gender" class="form-control">
                                                    @if ($employee->gender == 'Unmarried')
                                                    <option selected="" value="Male" >Male</option>
                                                    <option value="Female">Female</option>
                                                    @else
                                                    <option value="Male" >Male</option>
                                                    <option selected="" value="Female">Female</option>
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="blood_group">Blood Group</label>
                                                <select name="blood_group" class="form-control">
                                                    <option value="{{ $employee->blood_group }}">{{ $employee->blood_group }}</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B-">B-</option>
                                                    <option value="AB+">AB+</option>
                                                    <option value="AB-">AB-</option>
                                                    <option value="O+">O+</option>
                                                    <option value="O-">O-</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="religion">Religion</label>
                                                <select name="religion" class="form-control">
                                                    <option value="{{ $employee->religion }}">{{ $employee->religion }}</option>
                                                    <option value="Islam">Islam</option>
                                                    <option value="Hindu">Hindu</option>
                                                    <option value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="nid">NID</label>
                                                <input type="text" value="{{ $employee->nid }}" class="form-control" placeholder="National ID" name="nid">
                                            </div>
                                            <div class="form-group">
                                                <label for="etin">E-Tin</label>
                                                <input type="text" value="{{ $employee->etin }}" class="form-control" placeholder="E-Tin" name="etin">
                                            </div>
                                            <div class="form-group">
                                                <label for="birthdate">Birthdate</label>
                                                <input type="date" value="{{ $employee->birthdate }}" class="form-control" placeholder="Birthdate" name="birthdate">
                                            </div>
                                            <div class="form-group">
                                                <label for="payment_type_id">Payment Type<span class="text-danger">*</span></label>
                                                <select name="payment_type_id" class="form-control" required >
                                                    @if ($employee->payment_type !=  null)
                                                        <option value="{{ $employee->payment_type->id }}">{{ $employee->payment_type->name }}</option>
                                                    @endif
                                                    <option value="">Select A Payment Type</option>
                                                    @foreach ($paymentType as $item)
                                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label for="bank_ac_no">Account No</label>
                                                <input type="text" value="{{ $employee->bank_ac_no }}" class="form-control" placeholder="Bank AC No." name="bank_ac_no">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title  text-success text-bold">
                                                Nominee Information
                                            </h3> 
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="nominee_name">Nominee Nmae</label>
                                                <input type="text" value="{{ $employee->nominee_name }}" name="nominee_name" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_relation">Nominee Relation</label>
                                                <input type="text" value="{{ $employee->nominee_relation }}" name="nominee_relation" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_father_name">Father Name</label>
                                                <input type="text" value="{{ $employee->nominee_father_name }}" name="nominee_father_name" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_mother_name">Mother Name</label>
                                                <input type="text" value="{{ $employee->nominee_mother_name }}" name="nominee_mother_name" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_spouse_name">Spouse Name</label>
                                                <input type="text" value="{{ $employee->nominee_spouse_name }}" name="nominee_spouse_name" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_nid">NID</label>
                                                <input type="text" value="{{ $employee->nominee_nid }}" name="nominee_nid" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_cell_no">Cell No</label>
                                                <input type="text" value="{{ $employee->nominee_cell_no }}" name="nominee_cell_no" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_address">Address</label>
                                                <input type="text" value="{{ $employee->nominee_address }}" name="nominee_address" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="nominee_image">Nominee Picture</label>
                                                <input type="file" name="nominee_image" class="form-control"/>
                                                @if ($employee->nominee_image != null)
                                                    <img src="{{ asset($employee->nominee_image) }}"  width="100px"/> 
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title  text-success text-bold">
                                                Employee Contact Information
                                            </h3> 
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="mobile_no">Mobile No</label>
                                                <input type="text" value="{{ $employee->mobile_no }}" class="form-control" placeholder="Mobile No" name="mobile_no">
                                            </div>
                                            <div class="form-group">
                                                <label for="present_address">Present Address</label>
                                                <input type="text" value="{{ $employee->present_address }}"  class="form-control" placeholder="Present Address" name="present_address">
                                            </div>
                                            <div class="form-group">
                                                <label for="parmanent_address">Parmanent Address</label>
                                                <input type="text" value="{{ $employee->parmanent_address }}" class="form-control" placeholder="Present Address" name="parmanent_address">
                                            </div>
                                            <div class="form-group">
                                                <label for="employee_image"> Picture</label>
                                                <input type="file" name="employee_image" class="form-control"/>
                                                @if ($employee->employee_image != null)
                                                    <img src="{{ asset($employee->employee_image) }}"  width="100px"/> 
                                                @endif
                                            </div>
                                            <div class="form-group">
                                                <label for="employee_signature">Signature</label>
                                                <input type="file" name="employee_signature" class="form-control"/>
                                                @if ($employee->employee_signature != null)
                                                    <img src="{{ asset($employee->employee_signature) }}"  width="100px"/> 
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title  text-success text-bold">
                                                Educational Qualification & Work Experience
                                            </h3> 
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="academic_degree">Academic Degree</label>
                                                <input type="text" value="{{ $employee->academic_degree }}" name="academic_degree" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="acdemic_institute">Academic Institute</label>
                                                <input type="text" value="{{ $employee->acdemic_institute }}" name="acdemic_institute" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="passing_year">Passing Year</label>
                                                <input type="text" value="{{ $employee->passing_year }}" name="passing_year" class="form-control"/>
                                            </div>
                                            <div class="form-group">
                                                <label for="working_experience">Working Experience (In Years)</label>
                                                <input type="text" value="{{ $employee->working_experience }}" name="working_experience" class="form-control"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-success btn-block" type="submit"><i class="fa fa-check"></i> Update Employee</button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
</div>


  
<script>
    function load_department(company_id){
        url = '{{ route('load_department_by_company_id', ":company_id") }}';
        url = url.replace(':company_id', company_id);
        //alert(url);
        $.ajax({
          cache   : false,
          type    : "GET",
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
            response_data = JSON.parse(response);
            if(response_data.status == 'success'){
              $('#department_id').html(response_data.options);
              
            }
          }
      })
    }
    function load_branch(company_id){
        url = '{{ route('load_branch_by_company_id', ":company_id") }}';
        url = url.replace(':company_id', company_id);
        //alert(url);
        $.ajax({
          cache   : false,
          type    : "GET",
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
            response_data = JSON.parse(response);
            if(response_data.status == 'success'){
              $('#branch_id').html(response_data.options);
            }
          }
      })
    }
    function load_section(company_id){
        url = '{{ route('load_section_by_company_id', ":company_id") }}';
        url = url.replace(':company_id', company_id);
        //alert(url);
        $.ajax({
          cache   : false,
          type    : "GET",
          error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
          url : url,
          success : function(response){
            response_data = JSON.parse(response);
            if(response_data.status == 'success'){
              $('#section_id').html(response_data.options);
              
            }
          }
      })
    }
</script>
@endsection

