@extends('layouts.app')

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">
                        Employee Profile
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th class="bg-info text-center" colspan="3">
                                        Employee Job Information
                                    </th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->name }}</th>
                                </tr>
                                <tr>
                                    <th>Card ID</th>
                                    <th>:</th>
                                    <th>{{ $employee->card_id }}</th>
                                </tr>
                                <tr>
                                    <th>Machine ID</th>
                                    <th>:</th>
                                    <th>{{ $employee->machine_id }}</th>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->company !=  null)
                                            {{ $employee->company->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Department</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->department !=  null)
                                            {{ $employee->department->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Section</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->section !=  null)
                                            {{ $employee->section->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Designation</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->designation !=  null)
                                            {{ $employee->designation->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Branch Office</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->branch !=  null)
                                            {{ $employee->branch->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Grade</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->grade !=  null)
                                            {{ $employee->grade->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Schedule</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->schedule !=  null)
                                            {{ $employee->schedule->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Joining Date</th>
                                    <th>:</th>
                                    <th>{{ date('d/m/Y',strtotime($employee->joining_date)); }}</th>
                                </tr>
                                <tr>
                                    <th>Job Location</th>
                                    <th>:</th>
                                    <th>{{ $employee->job_location }}</th>
                                </tr>
                                <tr>
                                    <th>Gross Salary</th>
                                    <th>:</th>
                                    <th>Tk.{{ $employee->gross_salary }}</th>
                                </tr>
                            </table>
                        </div>
                    
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th class="bg-info text-center" colspan="3">
                                        Employee Personal Information
                                    </th>
                                </tr>
                    
                                <tr>
                                    <th>Father Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->father_name }}</th>
                                </tr>
                                <tr>
                                    <th>Mother Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->mother_name }}</th>
                                </tr>
                                <tr>
                                    <th>Marital Status</th>
                                    <th>:</th>
                                    <th>{{ $employee->marital_status }}</th>
                                </tr>
                                <tr>
                                    <th>Spouse Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->spouse_name }}</th>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <th>:</th>
                                    <th>{{ $employee->gender }}</th>
                                </tr>
                                <tr>
                                    <th>Blood Group</th>
                                    <th>:</th>
                                    <th>{{ $employee->blood_group }}</th>
                                </tr>
                                <tr>
                                    <th>Religion</th>
                                    <th>:</th>
                                    <th>{{ $employee->religion }}</th>
                                </tr>
                                <tr>
                                    <th>NID</th>
                                    <th>:</th>
                                    <th>{{ $employee->nid }}</th>
                                </tr>
                                <tr>
                                    <th>E-Tin</th>
                                    <th>:</th>
                                    <th>{{ $employee->etin }}</th>
                                </tr>
                                <tr>
                                    <th>Birth Date</th>
                                    <th>:</th>
                                    <th>{{ date('d/m/Y',strtotime($employee->birthdate)) }}</th>
                                </tr>
                                <tr>
                                    <th>Payment Type</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->payment_type !=  null)
                                            {{ $employee->payment_type->name }}
                                        @endif
                                    </th>
                                </tr>
                                <tr>
                                    <th>Bank Account No.</th>
                                    <th>:</th>
                                    <th>{{ $employee->bank_ac_no }}</th>
                                </tr>
                    
                            </table>
                        </div>
                        
                    
                    
                        
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th class="bg-info text-center" colspan="3">
                                        Nominee Information
                                    </th>
                                </tr>
                    
                                <tr>
                                    <th>Nominee Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_name }}</th>
                                </tr>
                                <tr>
                                    <th>Nominee Relation</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_relation }}</th>
                                </tr>
                                <tr>
                                    <th>Father Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_father_name }}</th>
                                </tr>
                                <tr>
                                    <th>Mother Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_mother_name }}</th>
                                </tr>
                                <tr>
                                    <th>Spouse Name</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_spouse_name }}</th>
                                </tr>
                                <tr>
                                    <th>NID</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_nid }}</th>
                                </tr>
                                <tr>
                                    <th>Cell No.</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_cell_no }}</th>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <th>:</th>
                                    <th>{{ $employee->nominee_address }}</th>
                                </tr>
                                <tr>
                                    <th>Picture</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->nominee_image != null)
                                            <img src="{{ asset($employee->nominee_image) }}"  width="100px"/> 
                                        @endif
                                    </th>
                                </tr>
                    
                            </table>
                        </div>
                    
                    
                        
                        <div class="col-md-6">
                            <table class="table table-striped">
                                <tr>
                                    <th class="bg-info text-center" colspan="3">
                                        Employee Contact Information
                                    </th>
                                </tr>
                    
                                <tr>
                                    <th>Mobile No</th>
                                    <th>:</th>
                                    <th>{{ $employee->mobile_no }}</th>
                                </tr>
                                <tr>
                                    <th>Present Address</th>
                                    <th>:</th>
                                    <th>{{ $employee->present_address }}</th>
                                </tr>
                                <tr>
                                    <th>Parmanent Address</th>
                                    <th>:</th>
                                    <th>{{ $employee->parmanent_address }}</th>
                                </tr>
                                <tr>
                                    <th>Picture</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->employee_image != null)
                                            <img src="{{ asset($employee->employee_image) }}"  width="100px"/> 
                                        @endif
                                    </th>
                                </tr>
                                
                                <tr>
                                    <th>Signature</th>
                                    <th>:</th>
                                    <th>
                                        @if ($employee->employee_signature != null)
                                            <img src="{{ asset($employee->employee_signature) }}"  width="100px"/> 
                                        @endif
                                    </th>
                                </tr>
                    
                                <tr>
                                    <th class="bg-info text-center" colspan="3">
                                        Educational Qualification & Work Experience
                                    </th>
                                </tr>
                    
                                <tr>
                                    <th>Academic Degree</th>
                                    <th>:</th>
                                    <th>{{ $employee->academic_degree }}</th>
                                </tr>
                                <tr>
                                    <th>Academic institute</th>
                                    <th>:</th>
                                    <th>{{ $employee->acdemic_institute }}</th>
                                </tr>
                                <tr>
                                    <th>Passing year</th>
                                    <th>:</th>
                                    <th>{{ $employee->passing_year }}</th>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div><!-- /.card-body -->
              </div>
        </div>
    </div>
</div>


  
@endsection

