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
                        <form action="{{ route('save_application_form') }}" method="post" enctype="multipart/form-data"
                            target="_blank">
                            @csrf
                            <div class="row">

                                <div class="col-md-12" id="additional-applicants-container">
                                    <fieldset class="applicant-form">
                                        <legend style="color: hsl(0, 0%, 100%);">Applicant's Information</legend>
                                        <div class="row">
                                            @php
                                                $lastCustomerId = \App\Models\Customer::latest()->first();
                                                $lastNumber = $lastCustomerId ? $lastCustomerId->id : 0;
                                            @endphp

                                            <input type="hidden" id="last_customer_id" value="{{ $lastNumber }}">
                                            <div class="col-lg-12">
                                                <label for="type">Select Type<i class="text-danger">*</i></label>
                                                <select name="type" id="type" required class="form-control">
                                                    <option value="">--Select--</option>
                                                    <option value="Plot">Plot</option>
                                                    <option value="Flat">Flat</option>
                                                    <option value="Land">Land</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-12 pt-3">
                                                <label for="project_id">Project Name<i class="text-danger">*</i></label>
                                                <select name="project_id" id="project_id" required
                                                    class="form-control chosen-select"
                                                    onchange="flat(); plot(); land(); generateCustomerCode()">
                                                    <option value="" selected>Select Project</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-6 pt-3">
                                                <label for="customer_code">Customer ID No.<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" id="customer_code" name="customer_code"
                                                    class="form-control" />
                                            </div>
                                            <div class="col-lg-6 pt-3">
                                                <label for="application_date">Date Of Application<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" required name="application_date"
                                                    class="form-control" />
                                            </div>

                                            <!-- Rest of the form fields -->
                                            <div class="col-lg-12 plot-details" style="display: none;">
                                                <label for="customer_name_bangla">আবেদনকারীর নাম<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" id="customer_name_bangla"
                                                    name="customer_name_bangla" placeholder="আবেদনকারীর নাম">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="customer_name">Applicant's Name<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" id="customer_name"
                                                    name="customer_name" placeholder="Applicant's Name">
                                            </div>
                                            <div class="col-lg-12 flat-details land-details" style="display: none;">
                                                <label for="flat_customer_name_bangla">Applicant's Name (In Bangla)<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="flat_customer_name_bangla"
                                                    placeholder="Applicant's Name In Bangla">
                                            </div>
                                            <div class="col-lg-12 pt-3 plot-details" style="display: none;">
                                                <label for="father_name_bangla">পিতার নাম<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="father_name_bangla"
                                                    placeholder="পিতার নাম">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="father_name">Father's Name<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="father_name"
                                                    placeholder="Father's Name">
                                            </div>

                                            <div class="col-lg-12 pt-3 plot-details" style="display: none;">
                                                <label for="mother_name_bangla">মাতার নাম<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="mother_name_bangla"
                                                    placeholder="মাতার নাম">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="mother_name">Mother's Name<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="mother_name"
                                                    placeholder="Mother's Name">
                                            </div>

                                            <div class="col-lg-12 pt-3 plot-details" style="display: none;">
                                                <label for="spouse_name_bangla">স্বামী/স্ত্রী'র নাম<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="spouse_name_bangla"
                                                    placeholder="স্বামী/স্ত্রী'র নাম">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="spouse_name">Spouse's Name<i class="text-danger">*</i></label>
                                                <input type="text" class="form-control" name="spouse_name"
                                                    placeholder="Spouse's Name">
                                            </div>
                                            <div class="col-lg-6 pt-3 plot-details" style="display: none;">
                                                <label for="date_of_birth">জন্ম তারিখ</label><br>
                                                <label for="date_of_birth">Date of Birth<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="date_of_birth_plot" id="date_of_birth_plot"
                                                    class="form-control" required />

                                            </div>
                                            <div class="col-lg-6 pt-3 plot-details" style="display: none;">
                                                <label for="nationality">জাতীয়তা</label><br>
                                                <label for="nationality">Nationality<i class="text-danger">*</i></label>
                                                <input type="text" name="nationality_plot" id="nationality_plot"
                                                    class="form-control" required />
                                            </div>
                                            <div class="col-lg-12 pt-3 plot-details" style="display: none;">
                                                <label for="present_mailing_address_bangla">বর্তমান ঠিকানা<i
                                                        class="text-danger">*</i></label>
                                                <textarea type="text" cols="3" class="form-control" name="present_mailing_address_bangla"
                                                    id="present_mailing_address_bangla" required placeholder="বর্তমান ঠিকানা"></textarea>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="present_mailing_address">Present Address<i
                                                        class="text-danger">*</i></label>
                                                <textarea type="text" cols="3" class="form-control" name="present_mailing_address"
                                                    id="present_mailing_address" required placeholder="Present/ Mailing Address"></textarea>
                                            </div>
                                            <div class="col-lg-12 pt-3 plot-details" style="display: none;">
                                                <label for="permanent_address_bangla">স্থায়ী ঠিকানা<i
                                                        class="text-danger">*</i></label>
                                                <textarea type="text" cols="3" class="form-control" name="permanent_address_bangla"
                                                    id="permanent_address_bangla" required placeholder="স্থায়ী ঠিকানা"></textarea>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="permanent_address">Permanent Address<i
                                                        class="text-danger">*</i></label>
                                                <textarea type="text" cols="3" class="form-control" name="permanent_address" id="permanent_address"
                                                    required placeholder="Permanent Address"></textarea>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="mobile_no">Contact No<i class="text-danger">*</i></label>
                                                <input type="number" name="mobile_no" id="mobile_no"
                                                    class="form-control" required placeholder="Mobile No">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email">Email<i class="text-danger">*</i></label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    required>
                                            </div>
                                            <div class="col-md-12 plot-details" style="display: none;">
                                                <label for="facebook_id"><img src="{{ asset('image/facebook_logo.png') }}"
                                                        style="width: 13px; height: 13px; border-radius: 2px;">
                                                    ID</label>
                                                <input type="text" name="facebook_id" id="facebook_id"
                                                    class="form-control" placeholder="Facebook ID">
                                            </div>

                                            <div class="col-lg-4 flat-details land-details">
                                                <label for="nationality">Nationality<i class="text-danger">*</i></label>
                                                <input type="text" name="nationality" id="nationality"
                                                    class="form-control" />
                                            </div>
                                            <div class="col-lg-4 plot-details">
                                                <label for="religion">Religion<i class="text-danger">*</i></label>
                                                <input type="text" name="religion" id="religion"
                                                    class="form-control" />
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="national_id">National ID<i class="text-danger">*</i></label>
                                                <input type="number" name="national_id" id="national_id"
                                                    class="form-control" />
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="passport_no">Passport No. (If Any)</label>
                                                <input type="text" name="passport_no" id="passport_no"
                                                    class="form-control" />
                                            </div>
                                            <div class="col-lg-6 flat-details land-details">
                                                <label for="date_of_birth">Date of Birth<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="date_of_birth" id="date_of_birth"
                                                    class="form-control" />
                                            </div>
                                            <div class="col-lg-6 flat-details land-details">
                                                <label for="blood_group">Blood Group<i class="text-danger">*</i></label>
                                                <select name="blood_group" id="blood_group" class="form-control">
                                                    <option value="">--Select--</option>
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
                                            <div class="col-lg-6 tin-field">
                                                <label for="tin_no">Tin (If Any)</label>
                                                <input type="number" name="tin_no" id="tin_no"
                                                    class="form-control" />
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="customer_photo">Applicant's Photo<i
                                                        class="text-danger">*</i></label>
                                                <br />
                                                <input type="file" name="customer_photo" id="customer_photo"
                                                    required />
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="applicant_signature">Applicant's Signature<i
                                                        class="text-danger">*</i></label>
                                                <br />
                                                <input type="file" name="applicant_signature" id="applicant_signature"
                                                    required />
                                            </div>
                                            <div class="col-lg-6 flat-details land-details">
                                                <label for="inheritor">Inheritor<i class="text-danger">*</i></label>
                                                <input type="text" name="inheritor" id="inheritor"
                                                    class="form-control" placeholder="Inheritor Name" />
                                            </div>
                                            <div class="col-lg-6 flat-details land-details">
                                                <label for="inheritor_relation">Inheritor Relation<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="inheritor_relation" id="inheritor_relation"
                                                    class="form-control" placeholder="Inheritor Relation" />
                                            </div>
                                            <div class="col-lg-6 flat-details land-details">
                                                <label for="portion_of_share">Portion of Share<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="portion_of_share" id="portion_of_share"
                                                    class="form-control" placeholder="Portion Of Share" />
                                            </div>
                                        </div>
                                    </fieldset>

                                    <!-- Add More button -->
                                    <div class="row mt-3">
                                        <div class="col-lg-12">
                                            <button type="button" class="btn btn-primary" id="add-more-btn">Add More
                                                Applicant</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Container for additional applicants -->
                                <div id="additional-applicants"></div>



                                <div class="col-md-12">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Profession/Occupation
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="office_name">Office Name<i class="text-danger">*</i></label>
                                                <input type="text" name="office_name" id="office_name" required
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="office_address">Address<i class="text-danger">*</i></label>
                                                <textarea type="text" name="office_address" id="office_address" required class="form-control"></textarea>
                                            </div>
                                            <div class="col-lg-7">
                                                <label for="designation">Designation<i class="text-danger">*</i></label>
                                                <input type="text" name="designation" id="designation" required
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-5">
                                                <label for="customer_cell">Cell<i class="text-danger">*</i></label>
                                                <input type="number" name="customer_cell" id="customer_cell" required
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>

                                <div class="col-md-12">
                                    <fieldset class="flat-details" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);">Flat Details</legend>
                                        <div id="flat-container">
                                            <div class="flat-item row">
                                                <div class="col-lg-12">
                                                    <label for="flat_id_0">Flat No.<i class="text-danger">*</i></label>
                                                    <select name="flat_id[]" id="flat_id_0" onchange="flatDetails(0)"
                                                        class="form-control chosen-select">
                                                        <option value="" selected>Select Flat</option>
                                                        @foreach ($flat_data as $flat)
                                                            <option value="{{ $flat->id }}">
                                                                {{ $flat->flat_floor->project->name }} -
                                                                {{ $flat->flat_floor->floor_no }} - {{ $flat->flat_no }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="floor_no_0">Floor No.<i class="text-danger">*</i></label>
                                                    <input type="text" name="floor_no[]" id="floor_no_0" required
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="flat_size_0">Flat Size<i class="text-danger">*</i></label>
                                                    <input type="text" name="flat_size[]" id="flat_size_0" required
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="flat_quantiy_0">Flat Quantity<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="flat_quantiy[]" id="flat_quantiy_0"
                                                        required class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" onclick="addMoreFlat()">Add
                                            More Flat</button>
                                    </fieldset>
                                </div>

                                <div class="col-md-12">
                                    <fieldset class="plot-details" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);">Plot Details</legend>
                                        <div id="plot-container">
                                            <div class="plot-item row">
                                                <div class="col-lg-12">
                                                    <label for="plot_id_0">Plot No.<i class="text-danger">*</i></label>
                                                    <select name="plot_id[]" id="plot_id_0" onchange="plotDetails(0)"
                                                        class="form-control chosen-select">
                                                        <option value="" selected>Select Plot</option>
                                                        @foreach ($plot_data as $plot)
                                                            <option value="{{ $plot->id }}">
                                                                {{ $plot->plot_no }} - {{ $plot->road->road_name }} -
                                                                {{ $plot->sector->sector_name }} -
                                                                {{ $plot->project->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="road_no_0">Road No.</label>
                                                    <input type="text" name="road_no[]" id="road_no_0"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="sector_no_0">Sector No.</label>
                                                    <input type="text" name="sector_no[]" id="sector_no_0"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="block_no_0">Block No.</label>
                                                    <input type="text" name="block_no[]" id="block_no_0"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="measurement_0">Measurement</label>
                                                    <input type="text" name="measurement[]" id="measurement_0"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="facing_0">Facing</label>
                                                    <input type="text" name="facing[]" id="facing_0"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="plot_size_0">Plot Size (Katha)</label>
                                                    <input type="text" name="plot_size[]" id="plot_size_0"
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="plot_type_0">Plot Type</label>
                                                    <input type="text" name="plot_type[]" id="plot_type_0"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" onclick="addMorePlot()">Add
                                            More Plot</button>
                                    </fieldset>
                                </div>

                                <!-- Land Details Section -->
                                <div class="col-md-12">
                                    <fieldset class="land-details">
                                        <legend style="color: hsl(0, 0%, 100%);">Land Share Details</legend>
                                        <div id="land-container">
                                            <div class="land-item row">
                                                <div class="col-lg-12">
                                                    <label for="land_id_0">Land Share<i class="text-danger">*</i></label>
                                                    <select name="land_id[]" id="land_id_0" onchange="landDetails(0)"
                                                        class="form-control chosen-select">
                                                        <option value="" selected>Select Land Share</option>
                                                        @foreach ($landshares as $land)
                                                            <option value="{{ $land->id }}">
                                                                {{ $land->project->name ?? '' }} - Share Qty:
                                                                {{ $land->shareqty }} - শতাংশ: {{ $land->sotangsho }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="share_qty_0">Share Quantity<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="share_qty[]" id="share_qty_0" required
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="sotangsho_0">Percentage (শতাংশ)<i
                                                            class="text-danger">*</i></label>
                                                    <input type="text" name="sotangsho[]" id="sotangsho_0" required
                                                        class="form-control" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="size_0">Size<i class="text-danger">*</i></label>
                                                    <input type="text" name="size[]" id="size_0" required
                                                        class="form-control" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary mt-2" onclick="addMoreLand()">Add
                                            More Land Share</button>
                                    </fieldset>
                                </div>


                                {{-- <div class="col-md-12">
                                    <fieldset class="land-price" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Price
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="land_total_price">Total Value (Tk.)<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" id="land_total_price" name="land_total_price"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div> --}}

                                <div class="col-md-12">
                                    <fieldset class="land-price" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Price</legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="land_total_price">Total Value (Tk.)<i class="text-danger">*</i></label>
                                                <input type="number" id="land_total_price" name="land_total_price" class="form-control">
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>




                                <div class="col-md-12">
                                    <fieldset class="flat-price" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Price
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="flat_total_price">Total Value (Tk.)<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" id="flat_total_price" name="flat_total_price"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                                <div class="col-md-12">
                                    <fieldset class="plot-price" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Price
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="rate_per_katha">Rate Per Katha/Decimal (Tk.)<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" name="rate_per_katha" id="rate_per_katha"
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="rate_per_katha_words">In Words</label>
                                                <input type="text" name="rate_per_katha_words"
                                                    id="rate_per_katha_words" class="form-control" readonly>

                                            </div>
                                            <div class="col-lg-6">
                                                <label for="total_price">Total Amount (Tk.)<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" id="total_price" name="total_price"
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="total_amount_in_words">In Words</label>
                                                <input type="text" id="total_amount_in_words"
                                                    name="total_amount_in_words" class="form-control" readonly>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>


                                <div class="col-md-12">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Mode Of Payment
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="check1"
                                                        name="paymentOption" value="initial"
                                                        onchange="initialPaymentDetails()">
                                                    <label class="form-check-label" for="check1">Full Payment</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="check2"
                                                        name="paymentOption" value="notMade"
                                                        onchange="defaultPaymentDetails()">
                                                    <label class="form-check-label" for="check2">Installment</label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div id="paymentDetails" style="display: none;">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <fieldset>
                                                        <legend style="color: hsl(0, 0%, 100%);"> Full Payment
                                                        </legend>
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <label for="booking_money">Booking Tk.<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="number" id="booking_money"
                                                                    name="booking_money" required class="form-control">
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <label for="booking_date">Date<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="date" id="booking_date"
                                                                    name="booking_date" required class="form-control">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="booking_cheque_no">Cash/ Cheque/ Pay Order
                                                                    No.<i class="text-danger">*</i></label>
                                                                <input type="text" name="booking_cheque_no"
                                                                    class="form-control" id="booking_cheque_no"
                                                                    placeholder="">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <label for="down_payment">Down Payment Tk.<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="number" id="down_payment"
                                                                    name="down_payment" required class="form-control">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <label for="down_payment_word">In Words<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="text" id="down_payment_word"
                                                                    name="down_payment_word" readonly
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="down_payment_cheque_no">Cash/ Cheque/ Pay Order
                                                                    No.<i class="text-danger">*</i></label>
                                                                <input type="text" name="down_payment_cheque_no"
                                                                    class="form-control" id="down_payment_cheque_no"
                                                                    placeholder="">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="note">Payment Note
                                                                    <i class="text-danger">*</i></label>
                                                                <input type="text" name="note" class="form-control"
                                                                    id="note" placeholder="">
                                                            </div>
                                                        </div>
                                                    </fieldset>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="fund">Fund<i class="text-danger">*</i></label>
                                                            <select name="fund_id" id="fund" class="form-control"
                                                                onchange="showBankInfo()">
                                                                <option value="">Select a Fund </option>
                                                                @foreach ($fund_types as $fund)
                                                                    <option value="{{ $fund->id }}">
                                                                        {{ $fund->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6 bank">
                                                            <label for="">Bank <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="bank_id" id="bank_id" class="form-control"
                                                                onchange="filterAccount()">
                                                                <option value="">Select a Bank</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 bank">
                                                            <label for="account">Account <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="account_id" id="account_id"
                                                                class="form-control" onchange="showAccountBranch()">
                                                                <option value="">Select An Account</option>
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        {{ $account->account_no }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 bank">
                                                            <label for="branch">Branch <i
                                                                    class="text-danger">*</i></label>
                                                            <input type="text" id="branch" class="form-control">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label for="payment_type_id">Payment Method<i
                                                                    class="text-danger">*</i></label>
                                                            <select name="payment_type_id" id="payment_type_id"
                                                                class="form-control">
                                                                <option value="">Select a Method</option>
                                                                @foreach ($payment_types as $payment_type)
                                                                    <option value="{{ $payment_type->id }}">
                                                                        {{ $payment_type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="initial_payment_made_date">Initial Payment made on
                                                                date <i class="text-danger">*</i></label>
                                                            <input type="date" name="initial_payment_made_date"
                                                                class="form-control" id="initial_payment_made_date"
                                                                value="{{ date('Y-m-d') }}" placeholder="">
                                                        </div>

                                                    </div>

                                                    <div class="installment-box">
                                                        <label class="centered-label">Remaining Amount Details</label>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label for="remaining_amount">Remaining Amount<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="number" id="remaining_amount"
                                                                    name="remaining_amount" readonly class="form-control">
                                                            </div>
                                                            {{-- <div class="col-lg-4">
                                            <label for="remaining_amount_date">Date</label>
                                            <input type="date" id="remaining_amount_date" name="remaining_amount_date"
                                                class="form-control">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="remaining_amount_cheque_no">Cash/ Cheque/ Pay Order No.</label>
                                            <input type="text" name="remaining_amount_cheque_no" class="form-control"
                                                id="remaining_amount_cheque_no" placeholder="">
                                        </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div id="paymentDetailsDefault" style="display: none;">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <fieldset>
                                                        {{-- <legend style="color: hsl(0, 0%, 100%);"> Full Payment
                                    </legend> --}}
                                                        <div class="row">
                                                            <div class="col-lg-8">
                                                                <label for="booking_money">Booking Tk.<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="number" id="installment_booking_money"
                                                                    name="installment_booking_money" class="form-control">
                                                            </div>
                                                            <div class="col-lg-4">
                                                                <label for="booking_date">Date<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="date" id="installment_booking_date"
                                                                    name="installment_booking_date" class="form-control">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="booking_cheque_no">Cash/ Cheque/ Pay Order
                                                                    No.<i class="text-danger">*</i></label>
                                                                <input type="text" name="installment_booking_cheque_no"
                                                                    class="form-control"
                                                                    id="installment_booking_cheque_no" placeholder="">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <label for="down_payment">Down Payment Tk.<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="number" id="installment_down_payment"
                                                                    name="installment_down_payment" class="form-control">
                                                            </div>
                                                            <div class="col-lg-12">
                                                                <label for="down_payment_word">In Words<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="text" id="installment_down_payment_word"
                                                                    name="installment_down_payment_word" readonly
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="down_payment_cheque_no">Cash/ Cheque/ Pay Order
                                                                    No.<i class="text-danger">*</i></label>
                                                                <input type="text"
                                                                    name="installment_down_payment_cheque_no"
                                                                    class="form-control"
                                                                    id="installment_down_payment_cheque_no"
                                                                    placeholder="">
                                                            </div>
                                                            <div class="col-md-12">
                                                                <label for="note_installment">Payment Note
                                                                    <i class="text-danger">*</i></label>
                                                                <input type="text" name="note_installment"
                                                                    class="form-control" id="note_installment"
                                                                    placeholder="">
                                                            </div>

                                                        </div>
                                                    </fieldset>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <label for="fund">Fund<i class="text-danger">*</i></label>
                                                            <select name="installment_fund_id" id="installment_fund"
                                                                class="form-control" onchange="showInstallmentBankInfo()">
                                                                <option value="">Select a Fund </option>
                                                                @foreach ($fund_types as $fund)
                                                                    <option value="{{ $fund->id }}">
                                                                        {{ $fund->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-6 bank">
                                                            <label for="">Bank <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="installment_bank_id" id="installment_bank_id"
                                                                class="form-control"
                                                                onchange="filterInstallmentAccount()">
                                                                <option value="">Select a Bank</option>
                                                                @foreach ($banks as $bank)
                                                                    <option value="{{ $bank->id }}">
                                                                        {{ $bank->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 bank">
                                                            <label for="account">Account <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="installment_account_id"
                                                                id="installment_account_id" class="form-control"
                                                                onchange="showInstallmentAccountBranch()">
                                                                <option value="">Select An Account</option>
                                                                @foreach ($accounts as $account)
                                                                    <option value="{{ $account->id }}">
                                                                        {{ $account->account_no }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 bank">
                                                            <label for="branch">Branch <i
                                                                    class="text-danger">*</i></label>
                                                            <input type="text" id="installment_branch"
                                                                class="form-control">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label for="payment_type_id">Payment Method<i
                                                                    class="text-danger">*</i></label>
                                                            <select name="installment_payment_type_id"
                                                                id="installment_payment_type_id" class="form-control">
                                                                <option value="">Select a Method</option>
                                                                @foreach ($payment_types as $payment_type)
                                                                    <option value="{{ $payment_type->id }}">
                                                                        {{ $payment_type->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label for="initial_payment_made_date">Initial Payment made
                                                                on date<i class="text-danger">*</i></label>
                                                            <input type="date"
                                                                name="installment_initial_payment_made_date"
                                                                class="form-control"
                                                                id="installment_initial_payment_made_date" placeholder="">
                                                        </div>
                                                    </div>

                                                    <div class="installment-box">
                                                        <label class="centered-label">For Installment</label>
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <label for="remaining_amount">Remaining Amount<i
                                                                        class="text-danger">*</i></label>
                                                                <input type="number" id="installment_remaining_amount"
                                                                    name="installment_remaining_amount" readonly
                                                                    class="form-control">
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="total_installment_number">Number Of
                                                                    Installment<i class="text-danger">*</i></label>
                                                                <input type="number" name="total_installment_number"
                                                                    id="total_installment_number" class="form-control"
                                                                    placeholder="">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <label for="monthly_installment">Monthly
                                                                    Installment (Tk.)<i class="text-danger">*</i></label>
                                                                <input type="number" name="monthly_installment"
                                                                    id="monthly_not_made_installment" class="form-control"
                                                                    placeholder="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                                <div class="col-md-12">
                                    <fieldset class="flat-mode" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Declaration
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-12 pt-3 installment-text">
                                                <label>I, </label>
                                                <input type="text" id="customer_name_declare_flat"
                                                    class="installment-input-payment" placeholder="" readonly><label>, do
                                                    hereby,
                                                    declare that I have clearly read, understood & agreed to follow the
                                                    terms & conditions of this company.</label><label> So, want to request
                                                    the authority to
                                                    grant me as a Flat (1200/1800/2400 sq/ft) land share of Unity
                                                    Condominium project.</label>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="land-mode" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Declaration
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-12 pt-3 installment-text">
                                                <label>I, </label>
                                                <input type="text" id="customer_name_declare_land"
                                                    class="installment-input-payment" placeholder="" readonly><label>, do
                                                    hereby,
                                                    declare that I have clearly read, understood & agreed to follow the
                                                    terms & conditions of this company.</label><label> So, want to request
                                                    the authority to
                                                    grant me as a Flat (1200/1800/2400 sq/ft) land share of Unity
                                                    Condominium project.</label>
                                            </div>
                                        </div>
                                </div>
                                <div class="col-md-12">
                                    <fieldset class="plot-details" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Nominee
                                        </legend>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="nominee_name">Name<i class="text-danger">*</i></label>
                                                <input type="text" name="nominee_name" id="nominee_name"
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="nominee_address">Address<i class="text-danger">*</i></label>
                                                <textarea type="text" name="nominee_address" id="nominee_address" class="form-control"></textarea>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nominee_cell">Phone No.<i class="text-danger">*</i></label>
                                                <input type="number" name="nominee_cell" id="nominee_cell"
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="relation">Relation With the Applicant<i
                                                        class="text-danger">*</i></label>
                                                <input type="text" name="relation" id="relation"
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nominee_date_of_birth">Date Of Birth<i
                                                        class="text-danger">*</i></label>
                                                <input type="date" name="nominee_date_of_birth"
                                                    id="nominee_date_of_birth" class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nominee_photo">Nominee's Photo<i
                                                        class="text-danger">*</i></label>
                                                <br />
                                                <input type="file" name="nominee_photo" id="nominee_photo" required />
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                                <div class="col-md-12">
                                    <fieldset class="plot-mode" style="display: none;">
                                        <legend style="color: hsl(0, 0%, 100%);"> Declaration
                                        </legend>
                                        <div class="row">
                                            <div class="col-md-12 pt-3 installment-text">
                                                <label>I do hereby declare that the information and particulars furnished by
                                                    me
                                                    here in
                                                    before
                                                    are true to the best of my knowledge and that I have no concealed or
                                                    misapprehended
                                                    anything. I further declare that I have gone through the prospectus of
                                                    the
                                                    Company's
                                                    project
                                                    and have seen the relevant plan & specifications and have understood the
                                                    terms &
                                                    conditions
                                                    of allotment of a plot. It's ultimate transfer to the allot tee after
                                                    it's
                                                    completion and
                                                    full payment of the cost. I accept the company's absolute right either
                                                    to
                                                    accept or
                                                    reject
                                                    my application for allotment of a plot.</label>

                                            </div>
                                        </div>
                                </div>

                                <div class="col-md-12">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);">Seller Information</legend>
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <label for="director_id">Director<i class="text-danger">*</i></label>
                                                <select name="director_id" id="director_id" class="form-control"
                                                    required>
                                                    <option value="">Select Director</option>
                                                    @foreach ($land_sale_employee as $employee)
                                                        <option value="{{ $employee->id }}">
                                                            {{ $employee->employee_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="directors_incentive">Incentive (%)<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" name="directors_incentive" id="directors_incentive"
                                                    required min="0" max="100" step="0.01"
                                                    class="form-control">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="directors_incentive_amount">Incentive Amount</label>
                                                <input type="number" name="directors_incentive_amount"
                                                    id="directors_incentive_amount" readonly class="form-control">
                                            </div>
                                            {{-- <div class="col-lg-3">
                                                <label for="directors_left_amount">Left Amount (Director's)</label>
                                                <input type="number" name="directors_left_amount"
                                                    id="directors_left_amount" readonly class="form-control">
                                            </div> --}}
                                        </div>
                                        <hr style="border: #686868 solid 1px;">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div id="coordinator-section">
                                                    <div class="row"></div>

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div id="shareholder-section">
                                                    <div class="row"></div>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 mt-3 text-left">
                                                <button type="button" class="btn btn-success"
                                                    id="addCoordinatorBtn">+Add
                                                    Co-ordinator</button>
                                            </div>
                                            <div class="col-lg-6 mt-3 text-right">
                                                <button type="button" class="btn btn-success"
                                                    id="addShareholderBtn">+Add
                                                    Shareholder</button>
                                            </div>
                                        </div>
                                        <hr style="border: #686868 solid 1px;">
                                        <div class="row">
                                            <div class="col-lg-6 mt-3 text-right">

                                            </div>
                                            <div class="col-lg-6 mt-3 text-right">
                                                <button type="button" class="btn btn-success" id="addOutsiderBtn">+Add
                                                    Outsider</button>
                                            </div>
                                            <div class="col-lg-6">

                                            </div>
                                            <div class="col-lg-6">
                                                <div id="outsider-section">
                                                    <div class="row"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>


                                <div class="col-lg-12 pt-3">
                                    <button class="btn btn-success btn-block" id="submit"><i class="fa fa-check"></i>
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            @endsection
            @push('script_js')
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

                {{-- Incentive --}}
                {{-- <script>
                    $(".chosen-select").chosen({
                        width: "100%"
                    });

                    document.addEventListener("DOMContentLoaded", function() {
                        // Element references
                        const total_price = document.getElementById('total_price');
                        const flat_total_price = document.getElementById('flat_total_price');
                        const booking_money = document.getElementById('booking_money');
                        const down_payment = document.getElementById('down_payment');
                        const directors_incentive = document.getElementById('directors_incentive');
                        const installment_booking_money = document.getElementById('installment_booking_money');
                        const installment_down_payment = document.getElementById('installment_down_payment');
                        const total_installment_number = document.getElementById('total_installment_number');
                        const directorSelect = document.getElementById('director_id');
                        const typeSelect = document.getElementById('type');

                        let i = 0;
                        let j = 0;
                        let k = 0;

                        // Initialize director incentive field based on type
                        function initDirectorIncentiveField() {
                            if (typeSelect.value === 'Flat') {
                                directors_incentive.removeAttribute('max');
                                directors_incentive.removeAttribute('min');
                                directors_incentive.step = "any";
                            } else {
                                directors_incentive.min = "0";
                                directors_incentive.max = "100";
                                directors_incentive.step = "0.01";
                            }
                        }
                        initDirectorIncentiveField();

                        // Event listeners for form field changes
                        total_price.addEventListener('input', updateTotalPrice);
                        flat_total_price.addEventListener('input', updateTotalPrice);
                        booking_money.addEventListener('input', updateTotalPrice);
                        down_payment.addEventListener('input', updateTotalPrice);
                        directors_incentive.addEventListener('input', calculateDirectorIncentive);
                        installment_booking_money.addEventListener('input', updateInstallmentTotalPrice);
                        installment_down_payment.addEventListener('input', updateInstallmentTotalPrice);
                        total_installment_number.addEventListener('input', updateInstallmentTotalPrice);
                        typeSelect.addEventListener('change', updateIncentiveFields);

                        // Add new coordinator and shareholder
                        document.getElementById('addCoordinatorBtn').addEventListener('click', addCoordinator);
                        document.getElementById('addShareholderBtn').addEventListener('click', addShareholder);
                        document.getElementById('addOutsiderBtn').addEventListener('click', addOutsider);

                        let cachedCoordinators = [];

                        function updateIncentiveFields() {
                            const isFlat = typeSelect.value === 'Flat';

                            // Update labels
                            $('label[for="directors_incentive"]').text(isFlat ? 'Director Fixed Amount' :
                                'Director Incentive (%)');
                            $('[name="coordinators_incentive[]"]').closest('.col-lg-3').find('label').text(isFlat ?
                                'Fixed Amount' : 'Incentive (%)');
                            $('[name="shareholders_incentive[]"]').closest('.col-lg-3').find('label').text(isFlat ?
                                'Fixed Amount' : 'Incentive (%)');
                            $('[name="outsiders_incentive[]"]').closest('.col-lg-3').find('label').text(isFlat ?
                                'Fixed Amount' : 'Incentive (%)');

                            // Update director incentive field attributes
                            if (isFlat) {
                                directors_incentive.removeAttribute('max');
                                directors_incentive.removeAttribute('min');
                                directors_incentive.step = "any";
                            } else {
                                directors_incentive.min = "0";
                                directors_incentive.max = "100";
                                directors_incentive.step = "0.01";
                            }

                            // Update existing incentive inputs
                            document.querySelectorAll('[name="coordinators_incentive[]"]').forEach(input => {
                                input.step = isFlat ? "any" : "0.01";
                                if (!isFlat) {
                                    input.min = "0";
                                    input.max = "100";
                                } else {
                                    input.removeAttribute('min');
                                    input.removeAttribute('max');
                                }
                            });

                            document.querySelectorAll('[name="shareholders_incentive[]"]').forEach(input => {
                                input.step = isFlat ? "any" : "0.01";
                                if (!isFlat) {
                                    input.min = "0";
                                    input.max = "100";
                                } else {
                                    input.removeAttribute('min');
                                    input.removeAttribute('max');
                                }
                            });

                            document.querySelectorAll('[name="outsiders_incentive[]"]').forEach(input => {
                                input.step = isFlat ? "any" : "0.01";
                                if (!isFlat) {
                                    input.min = "0";
                                    input.max = "100";
                                } else {
                                    input.removeAttribute('min');
                                    input.removeAttribute('max');
                                }
                            });

                            // Recalculate incentives
                            calculateDirectorIncentive();
                        }

                        function handleDirectorChange() {
                            var directorId = $("#director_id").val();

                            if (directorId) {
                                fetch(`/coordinator-director-wise/${directorId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        cachedCoordinators = data;
                                        updateCoordinatorDropdowns();
                                    })
                                    .catch(error => console.error('Error fetching coordinators:', error));
                            } else {
                                cachedCoordinators = [];
                                updateCoordinatorDropdowns();
                            }
                        }

                        function populateCoordinatorSelect(select, data) {
                            select.innerHTML = '<option value="">Select Co-ordinator</option>';
                            data.forEach(executive => {
                                const option = document.createElement('option');
                                option.value = executive.id;
                                option.textContent = executive.employee_name;
                                select.appendChild(option);
                            });
                        }

                        function fetchShareholdersForDirectorOrCoordinator(directorId, selectedCoordinatorId,
                            shareholderSelectId) {
                            let endpoint;

                            if (selectedCoordinatorId) {
                                endpoint = `/shareholder-coordinator-wise/${directorId}/${selectedCoordinatorId}`;
                            } else if (directorId) {
                                endpoint = `/shareholder-director-wise/${directorId}`;
                            } else {
                                console.error('No director or coordinator selected.');
                                return;
                            }

                            fetch(endpoint)
                                .then(response => response.json())
                                .then(data => {
                                    const select = document.getElementById(shareholderSelectId);
                                    if (select) {
                                        const currentValue = select.value;
                                        select.innerHTML = '<option value="">Select Shareholder</option>';
                                        data.forEach(shareholder => {
                                            const option = document.createElement('option');
                                            option.value = shareholder.id;
                                            option.textContent = shareholder.employee_name;
                                            select.appendChild(option);
                                        });
                                        select.value = currentValue;
                                    }
                                })
                                .catch(error => console.error('Error fetching shareholders:', error));
                        }

                        function fetchOutsidersForDirectorOrCoordinator(directorId, selectedCoordinatorId, outsiderSelectId) {
                            let endpoint;

                            if (selectedCoordinatorId) {
                                endpoint = `/outsider-coordinator-wise/${directorId}/${selectedCoordinatorId}`;
                            } else if (directorId) {
                                endpoint = `/outsider-director-wise/${directorId}`;
                            } else {
                                console.error('No director or coordinator selected.');
                                return;
                            }

                            fetch(endpoint)
                                .then(response => response.json())
                                .then(data => {
                                    const select = document.getElementById(outsiderSelectId);
                                    if (select) {
                                        const currentValue = select.value;
                                        select.innerHTML = '<option value="">Select Outsider</option>';
                                        data.forEach(outsider => {
                                            const option = document.createElement('option');
                                            option.value = outsider.id;
                                            option.textContent = outsider.employee_name;
                                            select.appendChild(option);
                                        });
                                        select.value = currentValue;
                                    }
                                })
                                .catch(error => console.error('Error fetching outsiders:', error));
                        }

                        function addShareholder() {
                            j++;
                            const isFlat = typeSelect.value === 'Flat';
                            const template = `
                                <div class="row shareholder-entry" id="shareholder-entry_${j}">
                                    <div class="col-lg-4">
                                        <label for="shareholder_id_${j}">Shareholder</label>
                                        <select name="shareholder_id[]" id="shareholder_id_${j}" class="form-control select2">
                                            <option value="">Select Shareholder</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="shareholders_incentive_${j}">${isFlat ? 'Fixed Amount' : 'Incentive (%)'}</label>
                                        <input type="number" name="shareholders_incentive[]" id="shareholders_incentive_${j}" class="form-control"
                                               step="${isFlat ? 'any' : '0.01'}" ${isFlat ? '' : 'min="0" max="100"'} >
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="shareholders_incentive_amount_${j}">Incentive Amount</label>
                                        <input type="number" name="shareholders_incentive_amount[]" id="shareholders_incentive_amount_${j}" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <br>
                                        <button type="button" class="btn btn-danger remove-shareholder-btn">Remove</button>
                                    </div>
                                </div>`;

                            document.getElementById('shareholder-section').insertAdjacentHTML('beforeend', template);

                            document.getElementById(`shareholders_incentive_${j}`).addEventListener('input', function() {
                                const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                                const totalPrice = parseFloat(total_price.value) || 0;
                                const basePrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;
                                updateShareholderIncentiveAmounts(basePrice, flatTotalPrice > 0);
                            });

                            const selectedCoordinatorId = document.getElementById(`coordinator_id_${i}`) ?
                                document.getElementById(`coordinator_id_${i}`).value : null;
                            fetchShareholdersForDirectorOrCoordinator(directorSelect.value, selectedCoordinatorId,
                                `shareholder_id_${j}`);
                        }

                        function addOutsider() {
                            k++;
                            const isFlat = typeSelect.value === 'Flat';
                            const template = `
                                <div class="row outsider-entry" id="outsider-entry_${k}">
                                    <div class="col-lg-4">
                                        <label for="outsider_id_${k}">Outsider</label>
                                        <select name="outsider_id[]" id="outsider_id_${k}" class="form-control chosen-select">
                                            <option value="">Select Outsider</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="outsiders_incentive_${k}">${isFlat ? 'Fixed Amount' : 'Incentive (%)'}</label>
                                        <input type="number" name="outsiders_incentive[]" id="outsiders_incentive_${k}" class="form-control"
                                               step="${isFlat ? 'any' : '0.01'}" ${isFlat ? '' : 'min="0" max="100"'} >
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="outsiders_incentive_amount_${k}">Incentive Amount</label>
                                        <input type="number" name="outsiders_incentive_amount[]" id="outsiders_incentive_amount_${k}" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <br>
                                        <button type="button" class="btn btn-danger remove-outsider-btn">Remove</button>
                                    </div>
                                </div>`;

                            document.getElementById('outsider-section').insertAdjacentHTML('beforeend', template);

                            document.getElementById(`outsiders_incentive_${k}`).addEventListener('input', function() {
                                const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                                const totalPrice = parseFloat(total_price.value) || 0;
                                const basePrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;
                                updateOutsiderIncentiveAmounts(basePrice, flatTotalPrice > 0);
                            });

                            const selectedCoordinatorId = document.getElementById(`coordinator_id_${i}`) ?
                                document.getElementById(`coordinator_id_${i}`).value : null;
                            fetchOutsidersForDirectorOrCoordinator(directorSelect.value, selectedCoordinatorId,
                                `outsider_id_${k}`);
                        }

                        function addCoordinator() {
                            i++;
                            const isFlat = typeSelect.value === 'Flat';
                            const template = `
                                <div class="row coordinator-entry" id="coordinator-entry_${i}">
                                    <div class="col-lg-4">
                                        <label for="coordinator_id_${i}">Co-ordinator</label>
                                        <select name="coordinator_id[]" id="coordinator_id_${i}" class="form-control chosen-select">
                                            <option value="">Select Co-ordinator</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="coordinators_incentive_${i}">${isFlat ? 'Fixed Amount' : 'Incentive (%)'}</label>
                                        <input type="number" name="coordinators_incentive[]" id="coordinators_incentive_${i}" class="form-control"
                                               step="${isFlat ? 'any' : '0.01'}" ${isFlat ? '' : 'min="0" max="100"'} >
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="coordinators_incentive_amount_${i}">Incentive Amount</label>
                                        <input type="number" name="coordinators_incentive_amount[]" id="coordinators_incentive_amount_${i}" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <br>
                                        <button type="button" class="btn btn-danger remove-coordinator-btn">Remove</button>
                                    </div>
                                </div>`;

                            document.getElementById('coordinator-section').insertAdjacentHTML('beforeend', template);

                            const coordinatorSelect = document.getElementById(`coordinator_id_${i}`);
                            cachedCoordinators.forEach(coordinator => {
                                const option = document.createElement('option');
                                option.value = coordinator.id;
                                option.textContent = coordinator.employee_name;
                                coordinatorSelect.appendChild(option);
                            });

                            setupCoordinatorChangeEvent(coordinatorSelect);

                            document.getElementById(`coordinators_incentive_${i}`).addEventListener('input', function() {
                                const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                                const totalPrice = parseFloat(total_price.value) || 0;
                                const basePrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;
                                updateCoordinatorIncentiveAmounts(basePrice, flatTotalPrice > 0);
                            });
                        }

                        function setupCoordinatorChangeEvent(coordinatorSelect) {
                            coordinatorSelect.addEventListener('change', function() {
                                const selectedCoordinatorId = this.value;
                                const directorId = directorSelect.value;

                                const parentEntry = this.closest('.coordinator-entry');
                                if (parentEntry) {
                                    const shareholderSelect = parentEntry.querySelector('[id^="shareholder_id_"]');
                                    const outsiderSelect = parentEntry.querySelector('[id^="outsider_id_"]');

                                    if (shareholderSelect) {
                                        fetchShareholdersForDirectorOrCoordinator(directorId, selectedCoordinatorId,
                                            shareholderSelect.id);
                                    }

                                    if (outsiderSelect) {
                                        fetchOutsidersForDirectorOrCoordinator(directorId, selectedCoordinatorId,
                                            outsiderSelect.id);
                                    }
                                }
                            });
                        }

                        function updateCoordinatorDropdowns() {
                            const coordinatorDropdowns = document.querySelectorAll("[name='coordinator_id[]']");
                            coordinatorDropdowns.forEach(select => {
                                select.innerHTML = '<option value="">Select Co-ordinator</option>';
                                cachedCoordinators.forEach(coordinator => {
                                    const option = document.createElement('option');
                                    option.value = coordinator.id;
                                    option.textContent = coordinator.employee_name;
                                    select.appendChild(option);
                                });
                            });
                        }

                        directorSelect.addEventListener('change', handleDirectorChange);

                        function calculateDirectorIncentive() {
                            const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                            const totalPrice = parseFloat(total_price.value) || 0;
                            const directorsIncentiveInput = parseFloat(directors_incentive.value) || 0;
                            const basePrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;

                            let directorsIncentiveAmount;
                            if (flatTotalPrice > 0) {
                                // Flat: Direct Amount (No Percentage Calculation)
                                directorsIncentiveAmount = directorsIncentiveInput;
                            } else {
                                // Plot: Percentage Calculation
                                directorsIncentiveAmount = (directorsIncentiveInput / 100) * basePrice;
                            }

                            document.getElementById('directors_incentive_amount').value = directorsIncentiveAmount.toFixed(2);

                            updateCoordinatorIncentiveAmounts(basePrice, flatTotalPrice > 0);
                            updateShareholderIncentiveAmounts(basePrice, flatTotalPrice > 0);
                            updateOutsiderIncentiveAmounts(basePrice, flatTotalPrice > 0);

                            calculateAndDisplayLeftAmount();
                        }

                        function updateCoordinatorIncentiveAmounts(basePrice, isFlat) {
                            const incentiveInputs = document.querySelectorAll('[name="coordinators_incentive[]"]');
                            incentiveInputs.forEach((input, index) => {
                                const incentiveValue = parseFloat(input.value) || 0;
                                let incentiveAmount;

                                if (isFlat) {
                                    // Flat: Direct Amount (No Percentage)
                                    incentiveAmount = incentiveValue;
                                } else {
                                    // Plot: Percentage Calculation
                                    incentiveAmount = (incentiveValue / 100) * basePrice;
                                }

                                document.querySelectorAll('[name="coordinators_incentive_amount[]"]')[index].value =
                                    incentiveAmount.toFixed(2);
                            });
                            calculateAndDisplayLeftAmount();
                        }

                        function updateShareholderIncentiveAmounts(basePrice, isFlat) {
                            const incentiveInputs = document.querySelectorAll('[name="shareholders_incentive[]"]');
                            incentiveInputs.forEach((input, index) => {
                                const incentiveValue = parseFloat(input.value) || 0;
                                let incentiveAmount;

                                if (isFlat) {
                                    // Flat: Direct Amount (No Percentage)
                                    incentiveAmount = incentiveValue;
                                } else {
                                    // Plot: Percentage Calculation
                                    incentiveAmount = (incentiveValue / 100) * basePrice;
                                }

                                document.querySelectorAll('[name="shareholders_incentive_amount[]"]')[index].value =
                                    incentiveAmount.toFixed(2);
                            });
                            calculateAndDisplayLeftAmount();
                        }

                        function updateOutsiderIncentiveAmounts(basePrice, isFlat) {
                            const incentiveInputs = document.querySelectorAll('[name="outsiders_incentive[]"]');
                            incentiveInputs.forEach((input, index) => {
                                const incentiveValue = parseFloat(input.value) || 0;
                                let incentiveAmount;

                                if (isFlat) {
                                    // Flat: Direct Amount (No Percentage)
                                    incentiveAmount = incentiveValue;
                                } else {
                                    // Plot: Percentage Calculation
                                    incentiveAmount = (incentiveValue / 100) * basePrice;
                                }

                                document.querySelectorAll('[name="outsiders_incentive_amount[]"]')[index].value =
                                    incentiveAmount.toFixed(2);
                            });
                            calculateAndDisplayLeftAmount();
                        }

                        function calculateAndDisplayLeftAmount() {
                            const directorsIncentiveAmount = parseFloat(document.getElementById('directors_incentive_amount')
                                .value) || 0;
                            let totalIncentiveAmount = 0;

                            document.querySelectorAll('[name="coordinators_incentive_amount[]"]').forEach(input => {
                                totalIncentiveAmount += parseFloat(input.value) || 0;
                            });
                            document.querySelectorAll('[name="shareholders_incentive_amount[]"]').forEach(input => {
                                totalIncentiveAmount += parseFloat(input.value) || 0;
                            });
                            document.querySelectorAll('[name="outsiders_incentive_amount[]"]').forEach(input => {
                                totalIncentiveAmount += parseFloat(input.value) || 0;
                            });

                            const remainingAmount = directorsIncentiveAmount - totalIncentiveAmount;
                            document.getElementById('directors_left_amount').value = remainingAmount.toFixed(2);
                        }

                        function updateTotalPrice() {
                            const totalPrice = parseFloat(total_price.value) || 0;
                            const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                            const bookingMoney = parseFloat(booking_money.value) || 0;
                            const downPayment = parseFloat(down_payment.value) || 0;
                            const finalPrice = totalPrice > 0 ? totalPrice : flatTotalPrice;
                            const remainingAmount = finalPrice - (bookingMoney + downPayment);
                            document.getElementById('remaining_amount').value = remainingAmount >= 0 ? remainingAmount : 0;
                            calculateDirectorIncentive();
                        }

                        function updateInstallmentTotalPrice() {
                            const totalPrice = parseFloat(total_price.value) || 0;
                            const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                            const bookingMoney = parseFloat(installment_booking_money.value) || 0;
                            const downPayment = parseFloat(installment_down_payment.value) || 0;
                            const totalInstallmentNumber = parseFloat(total_installment_number.value) || 0;

                            const finalPrice = totalPrice > 0 ? totalPrice : flatTotalPrice;
                            const remainingAmount = finalPrice - (bookingMoney + downPayment);

                            document.getElementById('installment_remaining_amount').value = remainingAmount >= 0 ?
                                remainingAmount : 0;

                            if (totalInstallmentNumber > 0) {
                                const monthlyInstallment = remainingAmount / totalInstallmentNumber;
                                document.getElementById('monthly_not_made_installment').value = monthlyInstallment.toFixed(2);
                            } else {
                                document.getElementById('monthly_not_made_installment').value = 0;
                            }
                        }

                        document.getElementById('outsider-section').addEventListener('click', function(event) {
                            if (event.target.classList.contains('remove-outsider-btn')) {
                                const entry = event.target.closest('.outsider-entry');
                                if (entry) {
                                    entry.remove();
                                    calculateAndDisplayLeftAmount();
                                }
                            }
                        });

                        document.getElementById('shareholder-section').addEventListener('click', function(event) {
                            if (event.target.classList.contains('remove-shareholder-btn')) {
                                const entry = event.target.closest('.shareholder-entry');
                                if (entry) {
                                    entry.remove();
                                    calculateAndDisplayLeftAmount();
                                }
                            }
                        });

                        document.getElementById('coordinator-section').addEventListener('click', function(event) {
                            if (event.target.classList.contains('remove-coordinator-btn')) {
                                const entry = event.target.closest('.coordinator-entry');
                                if (entry) {
                                    entry.remove();
                                    calculateAndDisplayLeftAmount();
                                }
                            }
                        });

                        // Initialize fields based on current type
                        updateIncentiveFields();
                    });
                </script> --}}

                <script>
                    $(".chosen-select").chosen({
                        width: "100%"
                    });

                    document.addEventListener("DOMContentLoaded", function() {
                        // Element references
                        const total_price = document.getElementById('total_price');
                        const flat_total_price = document.getElementById('flat_total_price');
                        const land_total_price = document.getElementById('land_total_price');
                        const booking_money = document.getElementById('booking_money');
                        const down_payment = document.getElementById('down_payment');
                        const directors_incentive = document.getElementById('directors_incentive');
                        const installment_booking_money = document.getElementById('installment_booking_money');
                        const installment_down_payment = document.getElementById('installment_down_payment');
                        const total_installment_number = document.getElementById('total_installment_number');
                        const directorSelect = document.getElementById('director_id');
                        const typeSelect = document.getElementById('type');

                        let i = 0;
                        let j = 0;
                        let k = 0;

                        // Initialize director incentive field based on type
                        function initDirectorIncentiveField() {
                            if (typeSelect.value === 'Flat' || typeSelect.value === 'Land') {
                                directors_incentive.removeAttribute('max');
                                directors_incentive.removeAttribute('min');
                                directors_incentive.step = "any";
                            } else if (typeSelect.value === 'Plot') {
                                directors_incentive.min = "0";
                                directors_incentive.max = "100";
                                directors_incentive.step = "0.01";
                            }
                        }
                        initDirectorIncentiveField();

                        // Event listeners for form field changes
                        total_price.addEventListener('input', updateTotalPrice);
                        flat_total_price.addEventListener('input', updateTotalPrice);
                        land_total_price.addEventListener('input', updateTotalPrice);
                        booking_money.addEventListener('input', updateTotalPrice);
                        down_payment.addEventListener('input', updateTotalPrice);
                        directors_incentive.addEventListener('input', calculateDirectorIncentive);
                        installment_booking_money.addEventListener('input', updateInstallmentTotalPrice);
                        installment_down_payment.addEventListener('input', updateInstallmentTotalPrice);
                        total_installment_number.addEventListener('input', updateInstallmentTotalPrice);
                        typeSelect.addEventListener('change', updateIncentiveFields);

                        // Add new coordinator and shareholder
                        document.getElementById('addCoordinatorBtn').addEventListener('click', addCoordinator);
                        document.getElementById('addShareholderBtn').addEventListener('click', addShareholder);
                        document.getElementById('addOutsiderBtn').addEventListener('click', addOutsider);

                        let cachedCoordinators = [];

                        function updateIncentiveFields() {
                            const type = typeSelect.value;
                            const isFlat = type === 'Flat';
                            const isPlot = type === 'Plot';
                            const isLand = type === 'Land';

                            // Update labels
                            $('label[for="directors_incentive"]').text(
                                isPlot ? 'Director Incentive (%)' : 'Director Fixed Amount'
                            );

                            $('[name="coordinators_incentive[]"]').closest('.col-lg-3').find('label').text(
                                isPlot ? 'Incentive (%)' : 'Fixed Amount'
                            );

                            $('[name="shareholders_incentive[]"]').closest('.col-lg-3').find('label').text(
                                isPlot ? 'Incentive (%)' : 'Fixed Amount'
                            );

                            $('[name="outsiders_incentive[]"]').closest('.col-lg-3').find('label').text(
                                isPlot ? 'Incentive (%)' : 'Fixed Amount'
                            );

                            // Update director incentive field attributes
                            if (isPlot) {
                                directors_incentive.min = "0";
                                directors_incentive.max = "100";
                                directors_incentive.step = "0.01";
                            } else {
                                directors_incentive.removeAttribute('max');
                                directors_incentive.removeAttribute('min');
                                directors_incentive.step = "any";
                            }

                            // Update existing incentive inputs
                            document.querySelectorAll('[name="coordinators_incentive[]"]').forEach(input => {
                                if (isPlot) {
                                    input.min = "0";
                                    input.max = "100";
                                    input.step = "0.01";
                                } else {
                                    input.removeAttribute('min');
                                    input.removeAttribute('max');
                                    input.step = "any";
                                }
                            });

                            document.querySelectorAll('[name="shareholders_incentive[]"]').forEach(input => {
                                if (isPlot) {
                                    input.min = "0";
                                    input.max = "100";
                                    input.step = "0.01";
                                } else {
                                    input.removeAttribute('min');
                                    input.removeAttribute('max');
                                    input.step = "any";
                                }
                            });

                            document.querySelectorAll('[name="outsiders_incentive[]"]').forEach(input => {
                                if (isPlot) {
                                    input.min = "0";
                                    input.max = "100";
                                    input.step = "0.01";
                                } else {
                                    input.removeAttribute('min');
                                    input.removeAttribute('max');
                                    input.step = "any";
                                }
                            });

                            // Recalculate incentives
                            calculateDirectorIncentive();
                        }

                        function handleDirectorChange() {
                            var directorId = $("#director_id").val();

                            if (directorId) {
                                fetch(`/coordinator-director-wise/${directorId}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        cachedCoordinators = data;
                                        updateCoordinatorDropdowns();
                                    })
                                    .catch(error => console.error('Error fetching coordinators:', error));
                            } else {
                                cachedCoordinators = [];
                                updateCoordinatorDropdowns();
                            }
                        }

                        function populateCoordinatorSelect(select, data) {
                            select.innerHTML = '<option value="">Select Co-ordinator</option>';
                            data.forEach(executive => {
                                const option = document.createElement('option');
                                option.value = executive.id;
                                option.textContent = executive.employee_name;
                                select.appendChild(option);
                            });
                        }

                        function fetchShareholdersForDirectorOrCoordinator(directorId, selectedCoordinatorId, shareholderSelectId) {
                            let endpoint;

                            if (selectedCoordinatorId) {
                                endpoint = `/shareholder-coordinator-wise/${directorId}/${selectedCoordinatorId}`;
                            } else if (directorId) {
                                endpoint = `/shareholder-director-wise/${directorId}`;
                            } else {
                                console.error('No director or coordinator selected.');
                                return;
                            }

                            fetch(endpoint)
                                .then(response => response.json())
                                .then(data => {
                                    const select = document.getElementById(shareholderSelectId);
                                    if (select) {
                                        const currentValue = select.value;
                                        select.innerHTML = '<option value="">Select Shareholder</option>';
                                        data.forEach(shareholder => {
                                            const option = document.createElement('option');
                                            option.value = shareholder.id;
                                            option.textContent = shareholder.employee_name;
                                            select.appendChild(option);
                                        });
                                        select.value = currentValue;
                                    }
                                })
                                .catch(error => console.error('Error fetching shareholders:', error));
                        }

                        function fetchOutsidersForDirectorOrCoordinator(directorId, selectedCoordinatorId, outsiderSelectId) {
                            let endpoint;

                            if (selectedCoordinatorId) {
                                endpoint = `/outsider-coordinator-wise/${directorId}/${selectedCoordinatorId}`;
                            } else if (directorId) {
                                endpoint = `/outsider-director-wise/${directorId}`;
                            } else {
                                console.error('No director or coordinator selected.');
                                return;
                            }

                            fetch(endpoint)
                                .then(response => response.json())
                                .then(data => {
                                    const select = document.getElementById(outsiderSelectId);
                                    if (select) {
                                        const currentValue = select.value;
                                        select.innerHTML = '<option value="">Select Outsider</option>';
                                        data.forEach(outsider => {
                                            const option = document.createElement('option');
                                            option.value = outsider.id;
                                            option.textContent = outsider.employee_name;
                                            select.appendChild(option);
                                        });
                                        select.value = currentValue;
                                    }
                                })
                                .catch(error => console.error('Error fetching outsiders:', error));
                        }

                        function addShareholder() {
                            j++;
                            const type = typeSelect.value;
                            const isPlot = type === 'Plot';

                            const template = `
                                <div class="row shareholder-entry" id="shareholder-entry_${j}">
                                    <div class="col-lg-4">
                                        <label for="shareholder_id_${j}">Shareholder</label>
                                        <select name="shareholder_id[]" id="shareholder_id_${j}" class="form-control select2">
                                            <option value="">Select Shareholder</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="shareholders_incentive_${j}">${isPlot ? 'Incentive (%)' : 'Fixed Amount'}</label>
                                        <input type="number" name="shareholders_incentive[]" id="shareholders_incentive_${j}" class="form-control"
                                            step="${isPlot ? '0.01' : 'any'}" ${isPlot ? 'min="0" max="100"' : ''} >
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="shareholders_incentive_amount_${j}">Incentive Amount</label>
                                        <input type="number" name="shareholders_incentive_amount[]" id="shareholders_incentive_amount_${j}" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <br>
                                        <button type="button" class="btn btn-danger remove-shareholder-btn">Remove</button>
                                    </div>
                                </div>`;

                            document.getElementById('shareholder-section').insertAdjacentHTML('beforeend', template);

                            document.getElementById(`shareholders_incentive_${j}`).addEventListener('input', function() {
                                calculateIncentives();
                            });

                            const selectedCoordinatorId = document.getElementById(`coordinator_id_${i}`) ?
                                document.getElementById(`coordinator_id_${i}`).value : null;
                            fetchShareholdersForDirectorOrCoordinator(directorSelect.value, selectedCoordinatorId,
                                `shareholder_id_${j}`);
                        }

                        function addOutsider() {
                            k++;
                            const type = typeSelect.value;
                            const isPlot = type === 'Plot';

                            const template = `
                                <div class="row outsider-entry" id="outsider-entry_${k}">
                                    <div class="col-lg-4">
                                        <label for="outsider_id_${k}">Outsider</label>
                                        <select name="outsider_id[]" id="outsider_id_${k}" class="form-control chosen-select">
                                            <option value="">Select Outsider</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="outsiders_incentive_${k}">${isPlot ? 'Incentive (%)' : 'Fixed Amount'}</label>
                                        <input type="number" name="outsiders_incentive[]" id="outsiders_incentive_${k}" class="form-control"
                                            step="${isPlot ? '0.01' : 'any'}" ${isPlot ? 'min="0" max="100"' : ''} >
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="outsiders_incentive_amount_${k}">Incentive Amount</label>
                                        <input type="number" name="outsiders_incentive_amount[]" id="outsiders_incentive_amount_${k}" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <br>
                                        <button type="button" class="btn btn-danger remove-outsider-btn">Remove</button>
                                    </div>
                                </div>`;

                            document.getElementById('outsider-section').insertAdjacentHTML('beforeend', template);

                            document.getElementById(`outsiders_incentive_${k}`).addEventListener('input', function() {
                                calculateIncentives();
                            });

                            const selectedCoordinatorId = document.getElementById(`coordinator_id_${i}`) ?
                                document.getElementById(`coordinator_id_${i}`).value : null;
                            fetchOutsidersForDirectorOrCoordinator(directorSelect.value, selectedCoordinatorId,
                                `outsider_id_${k}`);
                        }

                        function addCoordinator() {
                            i++;
                            const type = typeSelect.value;
                            const isPlot = type === 'Plot';

                            const template = `
                                <div class="row coordinator-entry" id="coordinator-entry_${i}">
                                    <div class="col-lg-4">
                                        <label for="coordinator_id_${i}">Co-ordinator</label>
                                        <select name="coordinator_id[]" id="coordinator_id_${i}" class="form-control chosen-select">
                                            <option value="">Select Co-ordinator</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="coordinators_incentive_${i}">${isPlot ? 'Incentive (%)' : 'Fixed Amount'}</label>
                                        <input type="number" name="coordinators_incentive[]" id="coordinators_incentive_${i}" class="form-control"
                                            step="${isPlot ? '0.01' : 'any'}" ${isPlot ? 'min="0" max="100"' : ''} >
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="coordinators_incentive_amount_${i}">Incentive Amount</label>
                                        <input type="number" name="coordinators_incentive_amount[]" id="coordinators_incentive_amount_${i}" class="form-control" readonly>
                                    </div>
                                    <div class="col-lg-2">
                                        <br>
                                        <button type="button" class="btn btn-danger remove-coordinator-btn">Remove</button>
                                    </div>
                                </div>`;

                            document.getElementById('coordinator-section').insertAdjacentHTML('beforeend', template);

                            const coordinatorSelect = document.getElementById(`coordinator_id_${i}`);
                            cachedCoordinators.forEach(coordinator => {
                                const option = document.createElement('option');
                                option.value = coordinator.id;
                                option.textContent = coordinator.employee_name;
                                coordinatorSelect.appendChild(option);
                            });

                            setupCoordinatorChangeEvent(coordinatorSelect);

                            document.getElementById(`coordinators_incentive_${i}`).addEventListener('input', function() {
                                calculateIncentives();
                            });
                        }

                        function setupCoordinatorChangeEvent(coordinatorSelect) {
                            coordinatorSelect.addEventListener('change', function() {
                                const selectedCoordinatorId = this.value;
                                const directorId = directorSelect.value;

                                const parentEntry = this.closest('.coordinator-entry');
                                if (parentEntry) {
                                    const shareholderSelect = parentEntry.querySelector('[id^="shareholder_id_"]');
                                    const outsiderSelect = parentEntry.querySelector('[id^="outsider_id_"]');

                                    if (shareholderSelect) {
                                        fetchShareholdersForDirectorOrCoordinator(directorId, selectedCoordinatorId,
                                            shareholderSelect.id);
                                    }

                                    if (outsiderSelect) {
                                        fetchOutsidersForDirectorOrCoordinator(directorId, selectedCoordinatorId,
                                            outsiderSelect.id);
                                    }
                                }
                            });
                        }

                        function updateCoordinatorDropdowns() {
                            const coordinatorDropdowns = document.querySelectorAll("[name='coordinator_id[]']");
                            coordinatorDropdowns.forEach(select => {
                                select.innerHTML = '<option value="">Select Co-ordinator</option>';
                                cachedCoordinators.forEach(coordinator => {
                                    const option = document.createElement('option');
                                    option.value = coordinator.id;
                                    option.textContent = coordinator.employee_name;
                                    select.appendChild(option);
                                });
                            });
                        }

                        directorSelect.addEventListener('change', handleDirectorChange);

                        function calculateDirectorIncentive() {
                            const type = typeSelect.value;
                            const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                            const totalPrice = parseFloat(total_price.value) || 0;
                            const landTotalPrice = parseFloat(land_total_price.value) || 0;
                            const directorsIncentiveInput = parseFloat(directors_incentive.value) || 0;

                            let basePrice, directorsIncentiveAmount;

                            if (type === 'Flat') {
                                basePrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;
                                directorsIncentiveAmount = directorsIncentiveInput; // Fixed amount for Flat
                            }
                            else if (type === 'Plot') {
                                basePrice = totalPrice;
                                directorsIncentiveAmount = (directorsIncentiveInput / 100) * basePrice; // Percentage for Plot
                            }
                            else if (type === 'Land') {
                                basePrice = landTotalPrice > 0 ? landTotalPrice : totalPrice;
                                directorsIncentiveAmount = directorsIncentiveInput; // Fixed amount for Land
                            }

                            document.getElementById('directors_incentive_amount').value = directorsIncentiveAmount.toFixed(2);
                            calculateIncentives();
                        }

                        function calculateIncentives() {
                            const type = typeSelect.value;
                            const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                            const totalPrice = parseFloat(total_price.value) || 0;
                            const landTotalPrice = parseFloat(land_total_price.value) || 0;

                            let basePrice;

                            if (type === 'Flat') {
                                basePrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;
                            }
                            else if (type === 'Plot') {
                                basePrice = totalPrice;
                            }
                            else if (type === 'Land') {
                                basePrice = landTotalPrice > 0 ? landTotalPrice : totalPrice;
                            }

                            updateCoordinatorIncentiveAmounts(basePrice, type);
                            updateShareholderIncentiveAmounts(basePrice, type);
                            updateOutsiderIncentiveAmounts(basePrice, type);
                            calculateAndDisplayLeftAmount();
                        }

                        function updateCoordinatorIncentiveAmounts(basePrice, type) {
                            const incentiveInputs = document.querySelectorAll('[name="coordinators_incentive[]"]');
                            incentiveInputs.forEach((input, index) => {
                                const incentiveValue = parseFloat(input.value) || 0;
                                let incentiveAmount;

                                if (type === 'Plot') {
                                    incentiveAmount = (incentiveValue / 100) * basePrice; // Percentage for Plot
                                } else {
                                    incentiveAmount = incentiveValue; // Fixed amount for Flat and Land
                                }

                                document.querySelectorAll('[name="coordinators_incentive_amount[]"]')[index].value =
                                    incentiveAmount.toFixed(2);
                            });
                        }

                        function updateShareholderIncentiveAmounts(basePrice, type) {
                            const incentiveInputs = document.querySelectorAll('[name="shareholders_incentive[]"]');
                            incentiveInputs.forEach((input, index) => {
                                const incentiveValue = parseFloat(input.value) || 0;
                                let incentiveAmount;

                                if (type === 'Plot') {
                                    incentiveAmount = (incentiveValue / 100) * basePrice; // Percentage for Plot
                                } else {
                                    incentiveAmount = incentiveValue; // Fixed amount for Flat and Land
                                }

                                document.querySelectorAll('[name="shareholders_incentive_amount[]"]')[index].value =
                                    incentiveAmount.toFixed(2);
                            });
                        }

                        function updateOutsiderIncentiveAmounts(basePrice, type) {
                            const incentiveInputs = document.querySelectorAll('[name="outsiders_incentive[]"]');
                            incentiveInputs.forEach((input, index) => {
                                const incentiveValue = parseFloat(input.value) || 0;
                                let incentiveAmount;

                                if (type === 'Plot') {
                                    incentiveAmount = (incentiveValue / 100) * basePrice; // Percentage for Plot
                                } else {
                                    incentiveAmount = incentiveValue; // Fixed amount for Flat and Land
                                }

                                document.querySelectorAll('[name="outsiders_incentive_amount[]"]')[index].value =
                                    incentiveAmount.toFixed(2);
                            });
                        }

                        function calculateAndDisplayLeftAmount() {
                            const directorsIncentiveAmount = parseFloat(document.getElementById('directors_incentive_amount')
                                .value) || 0;
                            let totalIncentiveAmount = 0;

                            document.querySelectorAll('[name="coordinators_incentive_amount[]"]').forEach(input => {
                                totalIncentiveAmount += parseFloat(input.value) || 0;
                            });
                            document.querySelectorAll('[name="shareholders_incentive_amount[]"]').forEach(input => {
                                totalIncentiveAmount += parseFloat(input.value) || 0;
                            });
                            document.querySelectorAll('[name="outsiders_incentive_amount[]"]').forEach(input => {
                                totalIncentiveAmount += parseFloat(input.value) || 0;
                            });

                            const remainingAmount = directorsIncentiveAmount - totalIncentiveAmount;
                            document.getElementById('directors_left_amount').value = remainingAmount.toFixed(2);
                        }

                        function updateTotalPrice() {
                            const type = typeSelect.value;
                            const totalPrice = parseFloat(total_price.value) || 0;
                            const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                            const landTotalPrice = parseFloat(land_total_price.value) || 0;
                            const bookingMoney = parseFloat(booking_money.value) || 0;
                            const downPayment = parseFloat(down_payment.value) || 0;

                            let finalPrice;

                            if (type === 'Flat') {
                                finalPrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;
                            }
                            else if (type === 'Plot') {
                                finalPrice = totalPrice;
                            }
                            else if (type === 'Land') {
                                finalPrice = landTotalPrice > 0 ? landTotalPrice : totalPrice;
                            }

                            const remainingAmount = finalPrice - (bookingMoney + downPayment);
                            document.getElementById('remaining_amount').value = remainingAmount >= 0 ? remainingAmount : 0;
                            calculateIncentives();
                        }

                        function updateInstallmentTotalPrice() {
                            const type = typeSelect.value;
                            const totalPrice = parseFloat(total_price.value) || 0;
                            const flatTotalPrice = parseFloat(flat_total_price.value) || 0;
                            const landTotalPrice = parseFloat(land_total_price.value) || 0;
                            const bookingMoney = parseFloat(installment_booking_money.value) || 0;
                            const downPayment = parseFloat(installment_down_payment.value) || 0;
                            const totalInstallmentNumber = parseFloat(total_installment_number.value) || 0;

                            let finalPrice;

                            if (type === 'Flat') {
                                finalPrice = flatTotalPrice > 0 ? flatTotalPrice : totalPrice;
                            }
                            else if (type === 'Plot') {
                                finalPrice = totalPrice;
                            }
                            else if (type === 'Land') {
                                finalPrice = landTotalPrice > 0 ? landTotalPrice : totalPrice;
                            }

                            const remainingAmount = finalPrice - (bookingMoney + downPayment);

                            document.getElementById('installment_remaining_amount').value = remainingAmount >= 0 ?
                                remainingAmount : 0;

                            if (totalInstallmentNumber > 0) {
                                const monthlyInstallment = remainingAmount / totalInstallmentNumber;
                                document.getElementById('monthly_not_made_installment').value = monthlyInstallment.toFixed(2);
                            } else {
                                document.getElementById('monthly_not_made_installment').value = 0;
                            }
                        }

                        document.getElementById('outsider-section').addEventListener('click', function(event) {
                            if (event.target.classList.contains('remove-outsider-btn')) {
                                const entry = event.target.closest('.outsider-entry');
                                if (entry) {
                                    entry.remove();
                                    calculateAndDisplayLeftAmount();
                                }
                            }
                        });

                        document.getElementById('shareholder-section').addEventListener('click', function(event) {
                            if (event.target.classList.contains('remove-shareholder-btn')) {
                                const entry = event.target.closest('.shareholder-entry');
                                if (entry) {
                                    entry.remove();
                                    calculateAndDisplayLeftAmount();
                                }
                            }
                        });

                        document.getElementById('coordinator-section').addEventListener('click', function(event) {
                            if (event.target.classList.contains('remove-coordinator-btn')) {
                                const entry = event.target.closest('.coordinator-entry');
                                if (entry) {
                                    entry.remove();
                                    calculateAndDisplayLeftAmount();
                                }
                            }
                        });

                        // Initialize fields based on current type
                        updateIncentiveFields();
                    });
                </script>


                <script>
                    function generateCustomerCode() {
                        var projectSelect = $('#project_id');
                        var projectId = projectSelect.val();
                        var projectName = projectSelect.find('option:selected').text();
                        var lastCustomerId = $('#last_customer_id').val();

                        if (projectId && projectName) {
                            var nextCustomerId = parseInt(lastCustomerId) + 1;
                            var initials = projectName.split(' ').map(word => word.charAt(0).toUpperCase()).join('');
                            $('#customer_code').val(initials + '-' + nextCustomerId);
                        } else {
                            $('#customer_code').val('');
                        }
                    }

                    function showBankInfo() {
                        var fund_id = $('#fund').val();
                        if (fund_id == 1) {
                            $('.bank').show();
                            $('#bank_id, #account_id').prop('required', true);
                        } else {
                            $('.bank').hide();
                            $('#bank_id, #account_id').prop('required', false);
                        }
                    }

                    function showInstallmentBankInfo() {
                        var fund_id = $('#installment_fund').val();
                        if (fund_id == 1) {
                            $('.bank').show();
                            $('#installment_bank_id, #installment_account_id').prop('required', true);
                        } else {
                            $('.bank').hide();
                            $('#installment_bank_id, #installment_account_id').prop('required', false);
                        }
                    }

                    function filterAccount() {
                        var bank_id = $('#bank_id').val();
                        $.ajax({
                            type: "GET",
                            url: "{{ route('filter-bank-fund') }}",
                            data: {
                                bank_id: bank_id
                            },
                            success: function(data) {
                                $('#account_id').html('<option value="">Select One</option>');
                                $.each(data, function(key, value) {
                                    $('#account_id').append('<option value="' + value.id + '">' + value
                                        .account_no + '</option>');
                                });
                            },
                        });
                    }

                    function filterInstallmentAccount() {
                        var bank_id = $('#installment_bank_id').val();
                        $.ajax({
                            type: "GET",
                            url: "{{ route('filter-bank-fund') }}",
                            data: {
                                bank_id: bank_id
                            },
                            success: function(data) {
                                $('#installment_account_id').html('<option value="">Select One</option>');
                                $.each(data, function(key, value) {
                                    $('#installment_account_id').append('<option value="' + value.id +
                                        '">' + value.account_no + '</option>');
                                });
                            },
                        });
                    }

                    $(document).ready(function() {
                        $('#project_id').change(function() {
                            generateCustomerCode();
                        });

                        $('.bank').hide();
                        $('.flat-details').hide();
                        $('.plot-details').hide();
                        $('.land-details').hide();
                        $('.flat-price').hide();
                        $('.plot-price').hide();
                        $('.land-price').hide();
                        $('.plot-mode').hide();
                        $('.flat-mode').hide();
                        $('.land-mode').hide();

                        function handleRequiredFields(selectedType) {
                            if (selectedType === 'Flat') {
                                $('#flat_id, #blood_group, #inheritor_relation, #inheritor').prop('required', true);
                                $('#plot_id, #landshares_id, #present_mailing_address_bangla, #permanent_address_bangla, #fund_id, #nominee_photo, #installment_fund, #installment_down_payment, #installment_booking_date, #installment_booking_money, #rate_per_katha, #total_price, #nationality_plot, #date_of_birth_plot')
                                    .prop('required',
                                        false);
                            } else if (selectedType === 'Plot') {
                                $('#plot_id, #present_mailing_address_bangla, #permanent_address_bangla, #fund_id, #nominee_photo, #installment_fund, #installment_down_payment, #installment_booking_date, #installment_booking_money, #rate_per_katha, #total_price, #nationality_plot, #date_of_birth_plot')
                                    .prop('required',
                                        true);
                                $('#flat_id, #landshares_id, #blood_group, #inheritor_relation, #inheritor').prop('required', false);
                            } else if (selectedType === 'Land') {
                                $('#landshares_id, #blood_group, #inheritor_relation, #inheritor').prop('required', true);
                                $('#flat_id, #plot_id, #present_mailing_address_bangla, #permanent_address_bangla, #fund_id, #nominee_photo, #installment_fund, #installment_down_payment, #installment_booking_date, #installment_booking_money, #rate_per_katha, #total_price, #nationality_plot, #date_of_birth_plot')
                                    .prop('required', false);
                            } else {
                                $('#flat_id, #landshares_id, #blood_group, #inheritor_relation, #inheritor, #present_mailing_address_bangla, #permanent_address_bangla, #fund_id, #nominee_photo, #plot_id, #installment_fund, #installment_down_payment, #installment_booking_date, #installment_booking_money, #rate_per_katha, #total_price, #nationality_plot, #date_of_birth_plot')
                                    .prop('required', false);
                            }
                        }

                        $('#type').change(function() {
                            var selectedType = $(this).val();
                            handleRequiredFields(selectedType);

                            $('.tin-field').removeClass('col-lg-6 col-lg-12');
                            $('.flat-details').hide();
                            $('.plot-details').hide();
                            $('.land-details').hide();
                            $('.flat-price').hide();
                            $('.plot-price').hide();
                            $('.land-price').hide();
                            $('.plot-mode').hide();
                            $('.flat-mode').hide();
                            $('.land-mode').hide();

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
                            } else if (selectedType === 'Land') {
                                $('.tin-field').addClass('col-lg-6 land-details');
                                $('.land-details').show();
                                $('.land-price').show();
                                $('.land-mode').show();
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
                            $('#customer_name_declare_flat').val(customerName);
                            $('#customer_name_declare_land').val(customerName);
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
                </script>


                {{-- Add More Plot & Flat --}}
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>
                    let flatCounter = 1;
                    let plotCounter = 1;

                    function addMoreFlat() {
                        const container = document.getElementById('flat-container');
                        const newItem = document.createElement('div');
                        newItem.className = 'flat-item row mt-3';
                        newItem.innerHTML = `
                            <div class="col-lg-12">
                                <label for="flat_id_${flatCounter}">Flat No.<i class="text-danger">*</i></label>
                                <select name="flat_id[]" id="flat_id_${flatCounter}" onchange="flatDetails(${flatCounter})" class="form-control select2">
                                    <option value="" selected>Select Flat</option>
                                    @foreach ($flat_data as $flat)
                                        <option value="{{ $flat->id }}">
                                            {{ $flat->flat_floor->project->name }} - {{ $flat->flat_floor->floor_no }} - {{ $flat->flat_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="floor_no_${flatCounter}">Floor No.<i class="text-danger">*</i></label>
                                <input type="text" name="floor_no[]" id="floor_no_${flatCounter}" required class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="flat_size_${flatCounter}">Flat Size<i class="text-danger">*</i></label>
                                <input type="text" name="flat_size[]" id="flat_size_${flatCounter}" required class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="flat_quantiy_${flatCounter}">Flat Quantity<i class="text-danger">*</i></label>
                                <input type="text" name="flat_quantiy[]" id="flat_quantiy_${flatCounter}" required class="form-control" readonly>
                            </div>
                            <div class="col-lg-12 text-right">
                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeItem(this, 'flat')">Remove</button>
                            </div>
                        `;
                        container.appendChild(newItem);

                        // Initialize Select2 for the new select element
                        $(`#flat_id_${flatCounter}`).select2({
                            placeholder: "Select Flat",
                            allowClear: true
                        });

                        flatCounter++;
                    }

                    function addMorePlot() {
                        const container = document.getElementById('plot-container');
                        const newItem = document.createElement('div');
                        newItem.className = 'plot-item row mt-3';
                        newItem.innerHTML = `
                            <div class="col-lg-12">
                                <label for="plot_id_${plotCounter}">Plot No.<i class="text-danger">*</i></label>
                                <select name="plot_id[]" id="plot_id_${plotCounter}" onchange="plotDetails(${plotCounter})" class="form-control select2">
                                    <option value="" selected>Select Plot</option>
                                    @foreach ($plot_data as $plot)
                                        <option value="{{ $plot->id }}">
                                            {{ $plot->plot_no }} - {{ $plot->road->road_name }} - {{ $plot->sector->sector_name }} - {{ $plot->project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label for="road_no_${plotCounter}">Road No.</label>
                                <input type="text" name="road_no[]" id="road_no_${plotCounter}" class="form-control" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label for="sector_no_${plotCounter}">Sector No.</label>
                                <input type="text" name="sector_no[]" id="sector_no_${plotCounter}" class="form-control" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label for="block_no_${plotCounter}">Block No.</label>
                                <input type="text" name="block_no[]" id="block_no_${plotCounter}" class="form-control" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label for="measurement_${plotCounter}">Measurement</label>
                                <input type="text" name="measurement[]" id="measurement_${plotCounter}" class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="facing_${plotCounter}">Facing</label>
                                <input type="text" name="facing[]" id="facing_${plotCounter}" class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="plot_size_${plotCounter}">Plot Size (Katha)</label>
                                <input type="text" name="plot_size[]" id="plot_size_${plotCounter}" class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="plot_type_${plotCounter}">Plot Type</label>
                                <input type="text" name="plot_type[]" id="plot_type_${plotCounter}" class="form-control" readonly>
                            </div>
                            <div class="col-lg-12 text-right">
                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeItem(this, 'plot')">Remove</button>
                            </div>
                        `;
                        container.appendChild(newItem);

                        // Initialize Select2 for the new select element
                        $(`#plot_id_${plotCounter}`).select2({
                            placeholder: "Select Plot",
                            allowClear: true
                        });

                        plotCounter++;
                    }

                    function removeItem(button, type) {
                        const item = button.closest(type === 'flat' ? '.flat-item' : '.plot-item');

                        // Destroy Select2 before removing the item to prevent memory leaks
                        if (type === 'flat') {
                            $(item).find('select').select2('destroy');
                        } else {
                            $(item).find('select').select2('destroy');
                        }

                        item.remove();
                    }

                    function flatDetails(index) {
                        var flat_id = document.getElementById('flat_id_' + index).value;
                        var url = "{{ route('getFlatData') }}";

                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                flat_id: flat_id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response) {
                                    $('#floor_no_' + index).val(response.flat_floor.floor_no);
                                    $('#flat_size_' + index).val(response.flat_size);
                                    $('#flat_quantiy_' + index).val(1);
                                } else {
                                    alert('No data found for the selected flat.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching flat data: ', error);
                                alert('An error occurred while fetching the flat data.');
                            }
                        });
                    }

                    function plotDetails(index) {
                        var plot_id = document.getElementById('plot_id_' + index).value;
                        var url = "{{ route('getPlotData') }}";

                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                plot_id: plot_id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response) {
                                    $('#road_no_' + index).val(response.road.road_name);
                                    $('#sector_no_' + index).val(response.sector.sector_name);
                                    $('#block_no_' + index).val(response.block_no);
                                    $('#measurement_' + index).val(response.measurement);
                                    $('#facing_' + index).val(response.facing);
                                    $('#plot_size_' + index).val(response.plot_size);
                                    $('#plot_type_' + index).val(response.plot_type);
                                } else {
                                    alert('No data found for the selected plot.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching plot data: ', error);
                                alert('An error occurred while fetching the plot data.');
                            }
                        });
                    }

                    // Initialize Select2 for any existing selects when the page loads
                    $(document).ready(function() {
                        $('.select2').select2({
                            placeholder: "Select an option",
                            allowClear: true
                        });
                    });

                    // Land Details Functions
                    let landCounter = 1;

                    function addMoreLand() {
                        const container = document.getElementById('land-container');
                        const newItem = document.createElement('div');
                        newItem.className = 'land-item row mt-3';
                        newItem.innerHTML = `
                            <div class="col-lg-12">
                                <label for="land_id_${landCounter}">Land Share<i class="text-danger">*</i></label>
                                <select name="land_id[]" id="land_id_${landCounter}" onchange="landDetails(${landCounter})" class="form-control chosen-select">
                                    <option value="" selected>Select Land Share</option>
                                    @foreach ($landshares as $land)
                                        <option value="{{ $land->id }}">
                                            {{ $land->project->name ?? '' }} - Share Qty: {{ $land->shareqty }} - শতাংশ: {{ $land->sotangsho }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label for="share_qty_${landCounter}">Share Quantity<i class="text-danger">*</i></label>
                                <input type="text" name="share_qty[]" id="share_qty_${landCounter}" required class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="sotangsho_${landCounter}">Percentage (শতাংশ)<i class="text-danger">*</i></label>
                                <input type="text" name="sotangsho[]" id="sotangsho_${landCounter}" required class="form-control" readonly>
                            </div>
                            <div class="col-lg-4">
                                <label for="size_${landCounter}">Size<i class="text-danger">*</i></label>
                                <input type="text" name="size[]" id="size_${landCounter}" required class="form-control" readonly>
                            </div>
                            <div class="col-lg-12 text-right">
                                <button type="button" class="btn btn-danger btn-sm mt-2" onclick="removeItem(this, 'land')">Remove</button>
                            </div>
                        `;
                        container.appendChild(newItem);
                        $(`#land_id_${landCounter}`).chosen({width: "100%"});
                        landCounter++;
                    }

                    function landDetails(index) {
                        var land_id = document.getElementById('land_id_' + index).value;
                        var url = "{{ route('getLandData') }}";

                        $.ajax({
                            type: "GET",
                            url: url,
                            data: {
                                land_id: land_id
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response) {
                                    $('#share_qty_' + index).val(response.shareqty);
                                    $('#sotangsho_' + index).val(response.sotangsho);
                                    $('#size_' + index).val(response.size);

                                    // Calculate total price when land is selected
                                    calculateLandPrice();
                                } else {
                                    alert('No data found for the selected land share.');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error fetching land data: ', error);
                                alert('An error occurred while fetching the land data.');
                            }
                        });
                    }

                    function removeItem(button, type) {
                        const item = button.closest('.' + type + '-item');
                        item.remove();

                        // Recalculate price when item is removed
                        if (type === 'land') {
                            calculateLandPrice();
                        }
                    }
                </script>



                <script>
                    $(document).ready(function() {
                        $('#rate_per_katha').on('input', function() {
                            var rate_per_katha = $(this).val();
                            // console.log(rate_per_katha);

                            if (rate_per_katha) {
                                var ratePerKathaInWords = getBangladeshCurrency(parseFloat(rate_per_katha));
                                $('#rate_per_katha_words').val(ratePerKathaInWords);
                            } else {
                                $('#rate_per_katha_words').val('');
                            }
                        });
                        $('#total_price').on('input', function() {
                            var total_price = $(this).val();
                            // console.log(total_price);

                            if (total_price) {
                                var totalAmountInWords = getBangladeshCurrency(parseFloat(total_price));
                                $('#total_amount_in_words').val(totalAmountInWords);
                            } else {
                                $('#total_amount_in_words').val('');
                            }
                        });
                        $('#down_payment').on('input', function() {
                            var down_payment = $(this).val();
                            // console.log(down_payment);

                            if (down_payment) {
                                var downPaymentWord = getBangladeshCurrency(parseFloat(down_payment));
                                $('#down_payment_word').val(downPaymentWord);
                            } else {
                                $('#down_payment_word').val('');
                            }
                        });
                        $('#installment_down_payment').on('input', function() {
                            var installment_down_payment = $(this).val();
                            // console.log(installment_down_payment);

                            if (installment_down_payment) {
                                var downPaymentInstallmentWord = getBangladeshCurrency(parseFloat(
                                    installment_down_payment));
                                $('#installment_down_payment_word').val(downPaymentInstallmentWord);
                            } else {
                                $('#installment_down_payment_word').val('');
                            }
                        });
                    });

                    function initialPaymentDetails() {
                        var check1 = document.getElementById('check1');
                        var check2 = document.getElementById('check2');
                        var paymentDetails = document.getElementById('paymentDetails');

                        if (check1.checked) {
                            check2.checked = false;
                            paymentDetails.style.display = 'block';
                            document.getElementById('paymentDetailsDefault').style.display = 'none';

                            // Make full payment fields required
                            $('#booking_money, #booking_date, #down_payment, #down_payment_word, #total_initial_payment, #balance_amount, #balance_amount_word, #fund, #bank_id, #account_id, #payment_type_id, #initial_payment_made_date, #down_payment_cheque_no, #note, #booking_cheque_no, #remaining_amount')
                                .prop('required', true);

                            // Remove required attribute from installment fields
                            $('#installment_booking_money, #installment_booking_date, #installment_booking_cheque_no, #installment_down_payment, #installment_down_payment_word, #installment_down_payment_cheque_no, #note, #installment_fund, #installment_bank_id, #installment_account_id, #installment_payment_type_id, #installment_remaining_amount, #total_installment_number, #monthly_not_made_installment, #installment_initial_payment_made_date')
                                .prop('required', false);
                        } else {
                            paymentDetails.style.display = 'none';
                        }
                    }

                    function defaultPaymentDetails() {
                        var check1 = document.getElementById('check1');
                        var check2 = document.getElementById('check2');
                        var paymentDetailsDefault = document.getElementById('paymentDetailsDefault');

                        if (check2.checked) {
                            check1.checked = false;
                            paymentDetailsDefault.style.display = 'block';
                            document.getElementById('paymentDetails').style.display = 'none';

                            // Make installment fields required
                            $('#installment_booking_money, #installment_booking_date, #installment_booking_cheque_no, #installment_down_payment, #installment_down_payment_word, #installment_down_payment_cheque_no, #note_installment, #installment_fund, #installment_bank_id, #installment_account_id, #installment_payment_type_id, #installment_remaining_amount, #total_installment_number, #monthly_not_made_installment, #installment_initial_payment_made_date')
                                .prop('required', true);

                            // Remove required attribute from full payment fields
                            $('#booking_money, #booking_date, #down_payment, #down_payment_word, #total_initial_payment, #balance_amount, #balance_amount_word, #fund, #bank_id, #account_id, #payment_type_id, #initial_payment_made_date, #down_payment_cheque_no, #note_installment, #booking_cheque_no, #remaining_amount')
                                .prop('required', false);
                        } else {
                            paymentDetailsDefault.style.display = 'none';
                        }
                    }
                </script>
                <script>
                    function getBangladeshCurrency(number) {
                        var decimal = Math.round((number - Math.floor(number)) * 100);
                        var no = Math.floor(number);
                        var hundred = null;
                        var digits_length = no.toString().length;
                        var i = 0;
                        var str = [];
                        var words = {
                            0: '',
                            1: 'One',
                            2: 'Two',
                            3: 'Three',
                            4: 'Four',
                            5: 'Five',
                            6: 'Six',
                            7: 'Seven',
                            8: 'Eight',
                            9: 'Nine',
                            10: 'Ten',
                            11: 'Eleven',
                            12: 'Twelve',
                            13: 'Thirteen',
                            14: 'Fourteen',
                            15: 'Fifteen',
                            16: 'Sixteen',
                            17: 'Seventeen',
                            18: 'Eighteen',
                            19: 'Nineteen',
                            20: 'Twenty',
                            30: 'Thirty',
                            40: 'Forty',
                            50: 'Fifty',
                            60: 'Sixty',
                            70: 'Seventy',
                            80: 'Eighty',
                            90: 'Ninety'
                        };
                        var digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];

                        while (i < digits_length) {
                            var divider = (i == 2) ? 10 : 100;
                            var number = Math.floor(no % divider);
                            no = Math.floor(no / divider);
                            i += divider == 10 ? 1 : 2;

                            if (number) {
                                var plural = ((counter = str.length) && number > 9) ? 's' : '';
                                var hundred = (counter == 1 && str[0]) ? ' and ' : '';
                                str.push((number < 21) ? words[number] + ' ' + digits[counter] + plural + ' ' + hundred : words[Math
                                        .floor(number / 10) * 10] + ' ' + words[number % 10] + ' ' + digits[counter] + plural +
                                    ' ' + hundred);
                            } else {
                                str.push(null);
                            }
                        }

                        var taka = str.reverse().join('');
                        var paisa = (decimal) ? " and " + (words[Math.floor(decimal / 10)] + " " + words[decimal % 10]) + ' Paisa' : '';
                        return (taka ? taka : '') + paisa;
                    }
                </script>

                <script>
                    $(document).ready(function() {
                        // Counter to keep track of additional applicants
                        let applicantCount = 1;

                        // Function to clone and clean the applicant form
                        function cloneApplicantForm() {
                            // Clone the entire fieldset
                            const originalFieldset = $('.applicant-form').first();
                            const clonedFieldset = originalFieldset.clone();

                            // Remove the fields that should not be in additional applicants
                            clonedFieldset.find('#type, #project_id, #customer_code, [name="application_date"]').closest(
                                '.col-lg-12, .col-lg-6').remove();

                            // Clear all input values in the cloned form except the hidden fields
                            clonedFieldset.find('input[type="text"]').not('[type="hidden"]').val('');
                            clonedFieldset.find('input[type="number"]').val('');
                            clonedFieldset.find('input[type="email"]').val('');
                            clonedFieldset.find('input[type="date"]').not('[name="application_date"]').val('');
                            clonedFieldset.find('input[type="file"]').val('');
                            clonedFieldset.find('textarea').val('');
                            clonedFieldset.find('select').not('#type, #project_id').prop('selectedIndex', 0);

                            // Update IDs and names to make them unique
                            clonedFieldset.find('[id]').each(function() {
                                const originalId = $(this).attr('id');
                                if (!['last_customer_id'].includes(originalId)) {
                                    $(this).attr('id', originalId + '_' + applicantCount);
                                }
                            });

                            clonedFieldset.find('[name]').each(function() {
                                const originalName = $(this).attr('name');
                                if (!['type', 'project_id', 'customer_code', 'application_date'].includes(
                                    originalName)) {
                                    $(this).attr('name', originalName + '_' + applicantCount);
                                }
                            });

                            // Update the legend to show this is an additional applicant
                            clonedFieldset.find('legend').text('Additional Applicant #' + applicantCount);

                            // Add a remove button
                            clonedFieldset.append(
                                '<button type="button" class="btn btn-danger remove-applicant-btn" style="margin-top: 10px;">Remove This Applicant</button>'
                                );

                            // Increment the counter
                            applicantCount++;

                            return clonedFieldset;
                        }

                        // Add more applicant button click handler
                        $('#add-more-btn').click(function() {
                            const newForm = cloneApplicantForm();
                            $('#additional-applicants').append(newForm);

                            // Reinitialize any plugins if needed (like chosen-select)
                            if ($.fn.chosen) {
                                newForm.find('.chosen-select').chosen();
                            }
                        });

                        // Remove applicant button handler (using event delegation)
                        $(document).on('click', '.remove-applicant-btn', function() {
                            $(this).closest('fieldset').remove();
                            // Renumber remaining applicants if needed
                        });

                        // Handle type change to show/hide appropriate fields
                        $(document).on('change', '[id^="type"]', function() {
                            const type = $(this).val();
                            const parentFieldset = $(this).closest('fieldset');

                            // Hide all detail sections first
                            parentFieldset.find('.plot-details').hide();
                            parentFieldset.find('.flat-details').hide();
                            parentFieldset.find('.land-details').hide();

                            // Show the appropriate section based on type
                            if (type === 'Plot') {
                                parentFieldset.find('.plot-details').show();
                            } else if (type === 'Flat') {
                                parentFieldset.find('.flat-details').show();
                            } else if (type === 'Land') {
                                parentFieldset.find('.land-details').show();
                            }
                        });
                    });
                </script>
            @endpush
