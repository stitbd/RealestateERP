@extends('layouts.app')
<style type="text/css">
    #paymentDetails {
        display: none;
    }

    #paymentDetailsDefault {
        display: none;
    }

    .installment-box {
        background-color: #e5ebbbda;
        border-radius: 5px;
        padding: 15px;
        margin-top: 20px;
    }

    .installment-box label.centered-label {
        display: block;
        text-align: center;
        color: #062c03;
        font-size: 1.2em;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .installment-text {
        margin-bottom: 15px;
    }

    .installment-input {
        display: inline-block;
        width: 100px;
        margin-left: 10px;
        margin-right: 10px;
    }

    .installment-input-payment {
        display: inline-block;
        width: 260px;
        margin-left: 10px;
        margin-right: 10px;
    }

    fieldset {
        min-width: 0px;
        padding: 15px;
        margin: 7px;
        border: 2px solid #062c03;
    }

    legend {
        float: none;
        background-image: linear-gradient(to bottom right, #062c03, #062c03);
        padding: 4px;
        width: 50%;
        color: rgb(255, 255, 255);
        border-radius: 7px;
        font-size: 17px;
        font-weight: 700;
        text-align: center;
    }

    label {
        font-weight: 700;
    }

    .line {
        border: 1px solid #000000;
    }


    /* date css End  */
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Application Form
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('update_application_form', $landSale->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Applicant's Information </legend>
                                        <div class="row">
                                            @php
                                                $lastCustomerId = \App\Models\Customer::latest()->first();
                                                $lastNumber = $lastCustomerId ? $lastCustomerId->id : 0;
                                            @endphp

                                            <input type="hidden" name="customer_id" id="customer_id" value="{{ $landSale->customer_id }}">
                                            <div class="col-lg-12">
                                                <label for="type">Select Type<i class="text-danger">*</i></label>
                                                <select name="type" id="type" required class="form-control" disabled>
                                                    <option value="">--Select--</option>
                                                     <option value="Plot" @if($landSale->type === 'Plot') selected @endif>Plot</option>
                                                    <option value="Flat" @if($landSale->type === 'Flat') selected @endif>Flat</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-12 pt-3">
                                                <label for="project_id">Project Name<i class="text-danger">*</i></label>
                                                <select name="project_id" id="project_id" required
                                                    class="form-control chosen-select"
                                                    onchange="flat(); plot();">
                                                    <option value="" >Select Project</option>
                                                    @foreach ($project_data as $project)
                                                        <option value="{{ $project->id }}"@if($landSale->project_id === $project->id) selected @endif>{{ $project->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6 pt-3">
                                                <label for="customer_code">Customer ID No.<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" id="customer_code" name="customer_code" readonly
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->customer_code :''}}"/>
                                            </div>
                                            <div class="col-lg-6 pt-3">
                                                <label for="application_date">Date Of Application<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" required name="application_date"
                                                    class="form-control" value="{{$landSale->application_date}}" />
                                            </div>

                                            @if($landSale->type == 'Plot')
                                                <div class="col-lg-12">
                                                    <label for="customer_name_bangla">আবেদনকারীর নাম<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" class="form-control" id="customer_name_bangla"
                                                        name="customer_name_bangla" placeholder="আবেদনকারীর নাম" value="{{$landSale->customer ? $landSale->customer->customer_name_bangla : ''}}">
                                                </div>
                                            @endif
                                            <div class="col-lg-12">
                                                <label for="customer_name">Applicant's Name<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" id="customer_name"
                                                    name="customer_name" placeholder="Applicant's Name" value="{{$landSale->customer ? $landSale->customer->customer_name : ''}}">
                                            </div>
                                            @if($landSale->type == 'Flat')
                                            <div class="col-lg-12">
                                                <label for="flat_customer_name_bangla">Applicant's Name (In Bangla)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="flat_customer_name_bangla"
                                                    placeholder="Applicant's Name In Bangla" value="{{$landSale->customer ? $landSale->customer->customer_name_bangla : ''}}">
                                            </div>
                                            @endif
                                            @if($landSale->type == 'Plot')
                                            <div class="col-lg-12 pt-3">
                                                <label for="father_name_bangla">পিতার নাম<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="father_name_bangla"
                                                    placeholder="পিতার নাম" value="{{$landSale->customer ? $landSale->customer->father_name : ''}}">
                                            </div>
                                            @endif
                                            <div class="col-lg-12">
                                                <label for="father_name">Father's Name<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="father_name"
                                                    placeholder="Father's Name"  value="{{$landSale->customer ? $landSale->customer->father_name_bangla : ''}}">
                                            </div>
                                             @if($landSale->type == 'Plot')
                                            <div class="col-lg-12 pt-3">
                                                <label for="mother_name_bangla">মাতার নাম<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="mother_name_bangla"
                                                    placeholder="মাতার নাম" value="{{$landSale->customer ? $landSale->customer->mother_name : ''}}">
                                            </div>
                                            @endif
                                            <div class="col-lg-12">
                                                <label for="mother_name">Mother's Name<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="mother_name"
                                                    placeholder="Mother's Name" value="{{$landSale->customer ? $landSale->customer->mother_name_bangla : ''}}">
                                            </div>
                                             @if($landSale->type == 'Plot')
                                            <div class="col-lg-12 pt-3">
                                                <label for="spouse_name_bangla">স্বামী/স্ত্রী'র নাম<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="spouse_name_bangla"
                                                    placeholder="স্বামী/স্ত্রী'র নাম"  value="{{$landSale->customer ? $landSale->customer->spouse_name_bangla : ''}}">
                                            </div>
                                            @endif

                                            <div class="col-lg-12">
                                                <label for="spouse_name">Spouse's Name<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="spouse_name"
                                                    placeholder="Spouse's Name"  value="{{$landSale->customer ? $landSale->customer->spouse_name : ''}}">
                                            </div>
                                             @if($landSale->type == 'Plot')
                                            <div class="col-lg-6 pt-3">
                                                <label for="date_of_birth">জন্ম তারিখ</label><br>
                                                <label for="date_of_birth">Date of Birth<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="date_of_birth_plot" id="date_of_birth_plot"
                                                    class="form-control" required  value="{{$landSale->customer ? $landSale->customer->date_of_birth : ''}}" />

                                            </div>
                                            @endif
                                            @if($landSale->type == 'Plot')
                                            <div class="col-lg-6 pt-3">
                                                <label for="nationality">জাতীয়তা</label><br>
                                                <label for="nationality">Nationality<i class="text-danger">*</i></label>
                                                <input type="text" name="nationality" id="nationality"
                                                    class="form-control" required value="{{$landSale->customer ? $landSale->customer->nationality : ''}}" />
                                            </div>
                                            @endif
                                            @if($landSale->type == 'Plot')
                                                <div class="col-lg-12 pt-3">
                                                    <label for="present_mailing_address_bangla">বর্তমান ঠিকানা<i
                                                            class="text-danger">*</i></label>
                                                    <textarea type="text" cols="3" class="form-control" name="present_mailing_address_bangla"
                                                        id="present_mailing_address_bangla" required placeholder="বর্তমান ঠিকানা">{{$landSale->customer ? $landSale->customer->present_mailing_address_bangla : ''}}</textarea>
                                                </div>
                                            @endif
                                            <div class="col-lg-12">
                                                <label for="present_mailing_address">Present Address<i
                                                        class="text-danger">*</i></label>
                                                <textarea type="text" cols="3" class="form-control" name="present_mailing_address"
                                                    id="present_mailing_address" required placeholder="Present/ Mailing Address">{{$landSale->customer ? $landSale->customer->present_mailing_address : ''}}</textarea>
                                            </div>
                                            @if($landSale->type == 'Plot')
                                                <div class="col-lg-12 pt-3">
                                                    <label for="permanent_address_bangla">স্থায়ী ঠিকানা<i
                                                            class="text-danger">*</i></label>
                                                    <textarea type="text" cols="3" class="form-control" name="permanent_address_bangla"
                                                        id="permanent_address_bangla" required placeholder="স্থায়ী ঠিকানা">{{$landSale->customer ? $landSale->customer->permanent_address : ''}}</textarea>
                                                </div>
                                            @endif

                                            <div class="col-lg-12">
                                                <label for="permanent_address">Permanent Address<i
                                                        class="text-danger">*</i></label>
                                                <textarea type="text" cols="3" class="form-control" name="permanent_address" id="permanent_address"
                                                    required placeholder="Permanent Address">{{$landSale->customer ? $landSale->customer->permanent_address_bangla : ''}}</textarea>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="mobile_no">Contact No.<i class="text-danger">*</i></label>
                                                <input type="number" name="mobile_no" id="mobile_no"
                                                    class="form-control" required placeholder="Mobile No" value="{{$landSale->customer ? $landSale->customer->mobile_no : ''}}">
                                            </div>

                                            <div class="col-md-6">
                                                <label for="email">Email<i class="text-danger">*</i></label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    required value="{{$landSale->customer ? $landSale->customer->email : ''}}">
                                            </div>

                                             @if($landSale->type == 'Plot')
                                            <div class="col-md-12">
                                                <label for="facebook_id"><img src="{{ asset('image/facebook_logo.png') }}"
                                                        style="width: 13px; height: 13px; border-radius: 2px;" >
                                                    ID</label>
                                                <input type="text" name="facebook_id" id="facebook_id"
                                                    class="form-control" placeholder="Facebook ID" value="{{$landSale->customer ? $landSale->customer->facebook_id : ''}}">
                                            </div>
                                            @endif
                                            {{-- <div class="col-md-4">
                                                <label for="phone_no">Phone No.</label>
                                                <input type="number" name="phone_no" class="form-control" required>
                                            </div> --}}
                                            @if($landSale->type == 'Flat')
                                            <div class="col-lg-4 flat-details">
                                                <label for="nationality">Nationality<i class="text-danger">*</i></label>
                                                <input type="text" name="nationality" id="nationality"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->nationality : ''}}" />
                                            </div>
                                            @endif
                                             @if($landSale->type == 'Plot')
                                            <div class="col-lg-4 ">
                                                <label for="religion">Religion<i class="text-danger">*</i></label>
                                                <input type="text" name="religion" id="religion"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->religion : ''}}" />
                                            </div>
                                            @endif
                                            <div class="col-lg-4">
                                                <label for="national_id">National ID<i class="text-danger">*</i></label>
                                                <input type="number" name="national_id" id="national_id"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->national_id : ''}}"  />
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="passport_no">Passport No. (If Any)</label>
                                                <input type="number" name="passport_no" id="passport_no"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->passport_no : ''}}"/>
                                            </div>
                                            @if($landSale->type == 'Flat')
                                            <div class="col-lg-6">
                                                <label for="date_of_birth">Date of Birth<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="date_of_birth" id="date_of_birth"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->date_of_birth : ''}}" />
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="blood_group">Blood Group<i class="text-danger">*</i></label>
                                                <select name="blood_group" id="blood_group" class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="A+" @if('A+'==$landSale->customer->blood_group) selected @endif>A+</option>
                                                    <option value="A-" @if('A-'==$landSale->customer->blood_group) selected @endif>A-</option>
                                                    <option value="B+" @if('B+'==$landSale->customer->blood_group) selected @endif>B+</option>
                                                    <option value="B-" @if('B-'==$landSale->customer->blood_group) selected @endif>B-</option>
                                                    <option value="AB+" @if('AB+'==$landSale->customer->blood_group) selected @endif>AB+</option>
                                                    <option value="AB-" @if('AB-'==$landSale->customer->blood_group) selected @endif>AB-</option>
                                                    <option value="O+" @if('O+'==$landSale->customer->blood_group) selected @endif>O+</option>
                                                    <option value="O-" @if('O-'==$landSale->customer->blood_group) selected @endif>O-</option>
                                                </select>
                                            </div>
                                            @endif

                                            <div class="col-lg-12 tin-field">
                                                <label for="tin_no">Tin (If Any)</label>
                                                <input type="number" name="tin_no" id="tin_no"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->tin_no : ''}}" />
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="customer_photo">Applicant's Photo<i
                                                        class="text-danger">*</i></label>
                                                <br />
                                                <input type="file" name="customer_photo" id="customer_photo"
                                                     />
                                                    <br>
                                                @if ($landSale->customer && $landSale->customer->customer_photo)
                                                    <img src="{{ asset('upload_images/customer_photo/' . $landSale->customer->customer_photo) }}"
                                                        alt="" height="100px" width="100px">
                                                @endif

                                            </div>
                                            <div class="col-lg-6">
                                                <label for="applicant_signature">Applicant's Signature<i
                                                        class="text-danger">*</i></label>
                                                <br />
                                                <input type="file" name="applicant_signature" id="applicant_signature"
                                                     />
                                                <br>
                                                @if ($landSale->customer && $landSale->customer->applicant_signature)
                                                <img src="{{ asset('upload_images/applicant_signature/' . $landSale->customer->applicant_signature) }}"
                                                                alt="" height="100px" width="100x">
                                                @endif
                                            </div>
                                            <div class="col-lg-6 flat-details">
                                                <label for="inheritor">Inheritor<i class="text-danger">*</i></label>
                                                <input type="text" name="inheritor" id="inheritor"
                                                    class="form-control" placeholder="Inheritor Name" value="{{$landSale->customer ? $landSale->customer->inheritor : ''}}"  />
                                            </div>
                                            <div class="col-lg-6 flat-details">
                                                <label for="inheritor_relation">Inheritor Relation<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="inheritor_relation" id="inheritor_relation"
                                                    class="form-control" placeholder="Inheritor Relation" value="{{$landSale->customer ? $landSale->customer->inheritor_relation : ''}}" />
                                            </div>
                                            <div class="col-lg-6 flat-details">
                                                <label for="portion_of_share">Portion of Share<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="portion_of_share" id="portion_of_share"
                                                    class="form-control" placeholder="Portion Of Share" value="{{$landSale->customer ? $landSale->customer->portion_of_share : ''}}" />
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-md-12">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Profession/Occupation
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="office_name">Office Name<i class="text-danger">*</i></label>
                                                <input type="text" name="office_name" id="office_name" required
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->office_name : ''}}">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="office_address">Address<i class="text-danger">*</i></label>
                                                <textarea type="text" name="office_address" id="office_address" required class="form-control">{{$landSale->customer ? $landSale->customer->office_address : ''}}</textarea>
                                            </div>
                                            <div class="col-lg-7">
                                                <label for="designation">Designation<i class="text-danger">*</i></label>
                                                <input type="text" name="designation" id="designation" required
                                                    class="form-control"  value="{{$landSale->customer ? $landSale->customer->designation : ''}}">
                                            </div>
                                            <div class="col-lg-5">
                                                <label for="customer_cell">Cell<i class="text-danger">*</i></label>
                                                <input type="number" name="customer_cell" id="customer_cell" required
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->customer_cell : ''}}">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                @if ($landSale->type == 'Flat')
                                <div class="col-md-12">
                                    <fieldset class="flat-details" >
                                        <legend style="color: hsl(0, 0%, 100%);"> Flat Details
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="flat_id">Flat No.<i class="text-danger">*</i></label>
                                                <select name="flat_id" id="flat_id" onchange="flatDetails()"
                                                    class="form-control chosen-select">
                                                    <option value="" selected>Select Flat</option>
                                                    @foreach ($flat_data as $flat)
                                                        <option value="{{ $flat->id }}" {{$landSale->flat_id == $flat->id ? 'selected' :''}}>{{ $flat->flat_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="floor_no">Floor No.<i class="text-danger">*</i></label>
                                                <input type="text" id="floor_no" required class="form-control"
                                                    readonly value="{{$landSale->flat->flat_floor ? $landSale->flat->flat_floor->floor_no : ''}}">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="flat_size">Flat Size<i class="text-danger">*</i></label>
                                                <input type="text" id="flat_size" required class="form-control"
                                                    readonly value="{{$landSale->flat ? $landSale->flat->flat_size : ''}}">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="flat_quantiy">Flat Quantity<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" id="flat_quantiy" required class="form-control"
                                                    readonly value="1">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                                @endif
                                @if ($landSale->type == 'Plot')
                                <div class="col-md-12">
                                    <fieldset class="">
                                        <legend style="color: hsl(0, 0%, 100%);"> Plot Details
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="plot_id">Plot No.<i class="text-danger">*</i></label>
                                                <select name="plot_id" id="plot_id" onchange="plotDetails()"
                                                    class="form-control chosen-select">
                                                    <option value="" selected>Select Plot</option>
                                                    @foreach ($plot_data as $plot)
                                                        <option value="{{ $plot->id }}" {{ $plot->id == $landSale->plot_id ? 'selected' : ''}}>{{ $plot->plot_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="road_no">Road No.</label>
                                                <input type="text" id="road_no" class="form-control" readonly value="{{$landSale->plot->road ? $landSale->plot->road->road_name : ''}}">
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="sector_no">Sector No.</label>
                                                <input type="text" id="sector_no" class="form-control" readonly value="{{$landSale->plot->sector ? $landSale->plot->sector->sector_name : ''}}">
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="block_no">Block No.</label>
                                                <input type="text" id="block_no" class="form-control" readonly value="{{$landSale->plot ? $landSale->plot->block_no : ''}}">
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="measurement">Measurement</label>
                                                <input type="text" id="measurement" class="form-control" readonly value="{{$landSale->plot ? $landSale->plot->measurement : ''}}">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="facing">Facing</label>
                                                <input type="text" id="facing" class="form-control" readonly value="{{$landSale->plot ? $landSale->plot->facing : ''}}">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="plot_size">Plot Size (Katha)</label>
                                                <input type="text" id="plot_size" class="form-control" readonly value="{{$landSale->plot ? $landSale->plot->plotType->percentage : ''}}">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="plot_type">Plot Type</label>
                                                <input type="text" id="plot_type" class="form-control" readonly value="{{$landSale->plot ? $landSale->plot->plot_type : ''}}" >
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                                <div class="col-md-12">
                                    <fieldset class="">
                                        <legend style="color: hsl(0, 0%, 100%);"> Nominee
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="nominee_name">Name<i class="text-danger">*</i></label>
                                                <input type="text" name="nominee_name" id="nominee_name"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->nominee_name : ''}}">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="nominee_address">Address<i class="text-danger">*</i></label>
                                                <textarea type="text" name="nominee_address" id="nominee_address" class="form-control">
                                                    {{$landSale->customer ? $landSale->customer->nominee_address : ''}}</textarea>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nominee_cell">Phone No.<i class="text-danger">*</i></label>
                                                <input type="number" name="nominee_cell" id="nominee_cell"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->nominee_cell : ''}}" >
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="relation">Relation With the Applicant<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="relation" id="relation"
                                                    class="form-control" value="{{$landSale->customer ? $landSale->customer->relation : ''}}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nominee_date_of_birth">Date Of Birth<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="nominee_date_of_birth"
                                                    id="nominee_date_of_birth" class="form-control" value="{{$landSale->customer ? $landSale->customer->nominee_date_of_birth : ''}}">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nominee_photo">Nominee's Photo<i
                                                        class="text-danger">*</i></label>
                                                <br />
                                                <input type="file" name="nominee_photo" id="nominee_photo"/>
                                                    @if ($landSale->customer && $landSale->customer->nominee_photo)
                                                        <img src="{{ asset('upload_images/nominee_photo/' . $landSale->customer->nominee_photo) }}"
                                                            alt="" height="100px" width="100px">
                                                @endif
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                                  @endif
                                <div class="col-lg-12 pt-3">
                                    <button class="btn btn-success btn-block" id="submit"><i class="fa fa-check"></i>
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>

           $(document).ready(function() {
                        $('#project_id').change(function() {
                            generateCustomerCode();
                        });

                        $('.bank').hide();
                        $('.flat-details').hide();
                        $('.plot-details').hide();
                        $('.flat-price').hide();
                        $('.plot-price').hide();
                        $('.plot-mode').hide();
                        $('.flat-mode').hide();

                        function handleRequiredFields(selectedType) {
                            if (selectedType === 'Flat') {
                                $('#flat_id, #blood_group, #inheritor_relation, #inheritor').prop('required', true);
                                $('#plot_id, #present_mailing_address_bangla, #permanent_address_bangla, #fund_id, #installment_fund, #installment_down_payment, #installment_booking_date, #installment_booking_money, #rate_per_katha, #total_price, #nationality_plot, #date_of_birth_plot')
                                    .prop('required',
                                        false);
                            } else if (selectedType === 'Plot') {
                                $('#plot_id, #present_mailing_address_bangla, #permanent_address_bangla, #fund_id, #installment_fund, #installment_down_payment, #installment_booking_date, #installment_booking_money, #rate_per_katha, #total_price, #nationality_plot, #date_of_birth_plot')
                                    .prop('required',
                                        true);
                                $('#flat_id, #blood_group, #inheritor_relation, #inheritor').prop('required', false);
                            } else {
                                $('#flat_id, #blood_group, #inheritor_relation, #inheritor, #present_mailing_address_bangla, #permanent_address_bangla, #fund_id, #nominee_photo, #plot_id, #installment_fund, #installment_down_payment, #installment_booking_date, #installment_booking_money, #rate_per_katha, #total_price, #nationality_plot, #date_of_birth_plot')
                                    .prop('required', false);
                            }
                        }

                        $('#type').change(function() {
                            var selectedType = $(this).val();
                            handleRequiredFields(selectedType);

                            $('.tin-field').removeClass('col-lg-6 col-lg-12');
                            $('.flat-details').hide();
                            $('.plot-details').hide();
                            $('.flat-price').hide();
                            $('.plot-price').hide();
                            $('.plot-mode').hide();
                            $('.flat-mode').hide();

                            if (selectedType === 'Flat') {
                                $('.tin-field').addClass('col-lg-6 flat-details');
                                $('.flat-details').show();
                                $('.flat-price').show();
                                $('.flat-mode').show();
                            } else if (selectedType === 'Plot') {
                                $('.tin-field').addClass('col-lg-12 plot-details');
                                $('.plot-details').show();
                                $('.plot-price').show();
                                $('.plot-mode').show();
                            }

                            $('#project_id').empty().append('<option value="">Select Project</option>');
                            if (selectedType) {
                                $.ajax({
                                    url: "{{ route('get_projects_by_type') }}",
                                    method: "GET",
                                    data: {
                                        type: selectedType
                                    },
                                    success: function(response) {
                                        $.each(response, function(id, name) {
                                            $('#project_id').append('<option value="' + id + '">' +
                                                name + '</option>');
                                        });
                                        $('#project_id').trigger("chosen:updated");
                                    }
                                });
                            }
                        });

                        $('#customer_name').on('input', function() {
                            var customerName = $(this).val();
                            $('#customer_name_declare').val(customerName);
                        });

                        $('#fund').change(function() {
                            showBankInfo();
                        });

                        $('#installment_fund').change(function() {
                            showInstallmentBankInfo();
                        });

                        $('#bank_id').change(function() {
                            filterAccount();
                        });
                        $('#installment_bank_id').change(function() {
                            filterInstallmentAccount();
                        });

                        handleRequiredFields($('#type').val());
                    });
        function flat() {
            var project_id = document.getElementById('project_id').value;
            var url = "{{ route('getFlat') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    project_id: project_id
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $('#flat_id').empty();
                    $('#flat_id').append('<option value="" selected>Select Flat</option>');

                    $.each(response, function(index, flat) {
                        $('#flat_id').append('<option value="' + flat.id + '">' + flat.flat_no +
                            '</option>');
                    });

                    $('#flat_id').trigger("chosen:updated");
                },
                error: function() {
                    alert('An error occurred while fetching the flats.');
                }
            });
        }

        function plot() {
            var project_id = document.getElementById('project_id').value;
            var url = "{{ route('getPlot') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    project_id: project_id
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    $('#plot_id').empty();
                    $('#plot_id').append('<option value="" selected>Select Plot</option>');

                    $.each(response, function(index, plot) {
                        $('#plot_id').append('<option value="' + plot.id + '">' + plot.plot_no +
                            '</option>');
                    });

                    $('#plot_id').trigger("chosen:updated");
                },
                error: function() {
                    alert('An error occurred while fetching the plots.');
                }
            });
        }
    </script>
@endpush
