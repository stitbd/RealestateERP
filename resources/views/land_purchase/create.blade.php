@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Add Land Purchase
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('save-land-purchase') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <label for="Supplier">Project</label>
                                    <select name="project_id" id="project_id" class="form-control" required>
                                        <option value="">Select One</option>
                                        @foreach ($project_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-4">
                                    <label for="date">Date</label>
                                    <input type="date" name="purchase_date" required class="form-control">
                                </div>
                                <div class="row">


                                    <div class="row border m-2 p-2" style="border-color: green !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                            Purchase Required Information
                                        </h6>
                                        <div class="col-lg-12">
                                            <label for="donorNumber" class="form-label">
                                                Donor Name (দাতার নাম)
                                                <i class="text-danger">*</i>
                                            </label>
                                            <div class="row  align-items-center">
                                                <!-- Input Field -->
                                                <div class="col-md-10">
                                                    <input type="text" id="dataQuantity1" class="form-control"
                                                        name="donor_name[1][1]" placeholder="Write the Donor Name(e.g,XYZ)" required
                                                        {{-- onkeyup="add_data_no(1)" --}} />
                                                </div>
                                                <!-- Add More Button -->
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm" onclick="add_data_no(1)"
                                                        style="background-color: rgb(1, 91, 41); color:#fff">
                                                        <i class="fa fa-plus"></i> Add More Donor
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Dynamic Donor Data -->
                                            <div id="data_name1" class="mt-3 mb-3"></div>
                                        </div>

                                        <div class="col-lg-4">
                                            <label for="Fund">Dolil No (দলিল নং) <i class="text-danger">*</i></label>
                                            <input name="dolil_no[]" placeholder="Dolil No" type="text" required
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Fund">Rs Dag No (আর এস দাগ নং) <i
                                                    class="text-danger">*</i></label>
                                            <input name="rs_dag_no[]" placeholder="Rs Dag No" type="text" required
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Fund">Khatian No (খতিয়ান নং)<i class="text-danger">*</i></label>
                                            <input name="khatian_no[]" placeholder="Khatian No" type="text" required
                                                class="form-control" />
                                        </div>


                                        <div class="col-lg-4">
                                            <label for="Fund">Khazna No (খাজনা নং) <i class="text-danger">*</i></label>
                                            <input name="khazna_no[]" placeholder="Khazna No" type="text" required
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Fund">Percentage (শতাংশ)<i class="text-danger">*</i></label>
                                            <input oninput="calculateTotal(1)" name="shotangso[]" placeholder="Shotangso"
                                                type="text" required class="form-control" id="shotangso1" />
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Fund">Amount Per Percentage (প্রতি শতাংশের দাম) <i
                                                    class="text-danger">*</i></label>
                                            <input name="per_shotangso_rate[]" placeholder="Amount Per (Shotangso)"
                                                type="text" oninput="calculateTotal(1)" required class="form-control"
                                                id="per_shotangso_rate1" />
                                        </div>


                                        <div class="col-lg-4">
                                            <label for="Fund">Total Amount <i class="text-danger"></i></label>
                                            <input readonly name="total_amount[]" placeholder="Total Amount" type="text"
                                                required class="form-control" id="total_amount1" />
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Fund">Middle-man Amount <i class="text-danger">*</i></label>
                                            <input name="medium_amount[]" placeholder="Middle-man Amount" type="text"
                                                required class="form-control" />
                                        </div>
                                        <div class="col-lg-4 ">
                                            <label for="head">Type</label>
                                            <select name="type[]" id="" class="form-control chosen-select"
                                                required>
                                                <option value="">Select Type</option>
                                                <option value="1">Power</option>
                                                <option value="2">Saf Kabala</option>
                                                <option value="3">Namjari</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="project">Attach File</label>
                                            <!-- Initial File Input -->
                                            <div class="row g-3">
                                                <div class="col-md-5">
                                                    <input type="text" id="" name="land_documents_type_name[1][1]"
                                                        class="form-control" placeholder="Document Name" required />
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="file" id="attachFile1" name="land_documents[1][1]"
                                                        class="form-control" required />
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm btn-info w-100"
                                                        onclick="attachFileNo(1)">
                                                        <i class="fa fa-plus"></i> Add More File
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Dynamic File Inputs -->
                                            <div id="attachFileNoDiv1" class="mt-3"></div>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="remarks">Remarks<i class="text-danger">*</i></label>
                                            <input type="text" name="remarks[]" id="remarks" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="wrapper">

                                </div>
                                <div class="col-lg-6 pt-3">
                                    <button type="button" class="btn btn-success" onclick="add_more()"><i
                                            class="fa fa-plus"></i> Add More</button>
                                </div>

                                <div class="col-lg-6 pt-3 text-right">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i>
                                        Save</button>
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
    <script>
        $(document).ready(function() {
            console.log("ready!");
        });
        var i = 1;
        var j = 0;

        function add_more() {
            ++i;
            ++j;
            $('#wrapper').append(`
                          <div class="row border m-2 p-2" style="border-color: green !important" id="row-${i}">
                                     <div class="col-lg-12">
                                            <label for="donorNumber" class="form-label">
                                                Donor Name (দাতার নাম)
                                                <i class="text-danger">*</i>
                                            </label>
                                            <div class="row">
                                                <!-- Input Field -->
                                                <div class="col-md-10">
                                                    <input type="text" id="dataQuantity1" class="form-control"
                                                       name="donor_name[${i}][${j}]" placeholder="Write the Donor Name(e.g,XYZ)" required />
                                                </div>
                                                <!-- Add More Button -->
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm" onclick="add_data_no(${i})"
                                                        style="background-color: rgb(1, 91, 41); color:#fff">
                                                        <i class="fa fa-plus"></i> Add More Donor
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Dynamic Donor Data -->
                                            <div id="data_name${i}" class="mt-3 mb-3"></div>
                                        </div>
                                        <div class="col-lg-4">
                                                <label for="Fund">Dolil No (দলিল নং) <i class="text-danger">*</i></label>
                                            <input name="dolil_no[]" placeholder="Dolil No" type="text" required
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-4">
                                              <label for="Fund">Rs Dag No (আর এস দাগ নং) <i class="text-danger">*</i></label>
                                            <input name="rs_dag_no[]" placeholder="Rs Dag No" type="text" required
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-4">
                                         <label for="Fund">Khatian No (খতিয়ান নং)<i class="text-danger">*</i></label>
                                            <input name="khatian_no[]" placeholder="Khatian No" type="text" required
                                                class="form-control" />
                                        </div>


                                        <div class="col-lg-4">
                                                 <label for="Fund">Khazna No (খাজনা নং) <i class="text-danger">*</i></label>
                                            <input name="khazna_no[]" placeholder="Khazna No" type="text" required
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-4">
                                                   <label for="Fund">Percentage (শতাংশ)<i class="text-danger">*</i></label>
                                            <input oninput="calculateTotal(${i})" name="shotangso[]" placeholder="Shotangso" type="text" required
                                                class="form-control" id="shotangso${i}" />
                                        </div>
                                        <div class="col-lg-4">
                                                <label for="Fund">Amount Per Percentage (প্রতি শতাংশের দাম) <i
                                                    class="text-danger">*</i></label>
                                            <input oninput="calculateTotal(${i})" name="per_shotangso_rate[]" placeholder="Amount Per (Shotangso)"
                                                type="text"  required class="form-control" id="per_shotangso_rate${i}" />
                                        </div>


                                        <div class="col-lg-4">
                                            <label for="Fund">Total Amount <i class="text-danger"></i></label>
                                            <input readonly name="total_amount[]" placeholder="Total Amount" type="text" required
                                                class="form-control" id="total_amount${i}" />
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="Fund">Middle-man Amount <i class="text-danger">*</i></label>
                                            <input name="medium_amount[]" placeholder="Middle-man Amount" type="text"
                                                required class="form-control" />
                                        </div>
                                        <div class="col-lg-4 ">
                                            <label for="head">Type</label>
                                            <select name="type[]" id="" class="form-control chosen-select"
                                                required>
                                                <option value="">Select Type</option>
                                                <option value="1">Power</option>
                                                <option value="2">Saf Kabala</option>
                                                <option value="3">Namjari</option>
                                            </select>
                                        </div>
                                         <div class="col-lg-12">
                                            <label for="project">Attach File</label>
                                            <!-- Initial File Input -->
                                            <div class="row g-3">
                                                <div class="col-md-5">
                                                    <input type="text" id="" name="land_documents_type_name[${i}][${j}]"
                                                        class="form-control" placeholder="Document Name"  required />
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="file" id="attachFile${i}" name="land_documents[${i}][${j}]"
                                                        class="form-control" required />
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-sm btn-info w-100"
                                                        onclick="attachFileNo(${i})">
                                                        <i class="fa fa-plus"></i> Add More File
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- Dynamic File Inputs -->
                                            <div id="attachFileNoDiv${i}" class="mt-3"></div>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="remarks">Remarks<i class="text-danger">*</i></label>
                                            <input type="text" name="remarks[]" id="remarks" class="form-control"
                                                required />
                                        </div>
                                            <button type="button" class="remove btn btn-md btn-danger text-center mt-2 ml-2" onclick="removeRow(${i})">
                        <i class="fa fa-minus"></i>
                    </button>
                                        </div>

                                        `);

        }

        function removeRow(rowId) {
            $(`#row-${rowId}`).remove();
        }


        // function add_data_no(sl) {
        //     quantity = parseFloat(document.getElementById("dataQuantity" + sl).value);
        //     console.log(quantity);

        //     $('#data_name' + sl).html('');
        //     for (j = 0; j < quantity; j++) {
        //         $('#data_name' + sl).append(`<input type = "text" name = "donor_name[` + sl + `][` + j +
        //             `]" class = "form-control mt-1" placeholder="Write The Donor Name  (Such as: Asraf Mridha)" /> `);
        //     }
        // }

        let no = 1;

        function add_data_no(sl) {
            no++;
            $('#data_name' + sl).append(`
                <div class="row g-3 mt-1 align-items-center" id="donor_field_${sl}_${new Date().getTime()}">
                    <!-- Input Field -->
                    <div class="col-md-10">
                        <input
                            type="text"
                            name="donor_name[${sl}][${no}]"
                            class="form-control"
                            placeholder="Write the Donor Name (e.g., XYZ)"
                        />
                    </div>
                    <!-- Remove Button -->
                    <div class="col-md-1">
                        <button
                            type="button"
                            class="btn btn-sm btn-danger w-50"
                            onclick="remove_field(this)"
                        >
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
            `);
        }

        function remove_field(button) {
            $(button).closest('.row').remove();
        }



        let fieldCounters = 1;

        function attachFileNo(sl) {
            fieldCounters++;

            $('#attachFileNoDiv' + sl).append(`
            <div class="row g-3 mt-1 align-items-center" id="file_field_${sl}_${fieldCounters[sl]}">
                <!-- Document Name Input -->
                <div class="col-md-5">
                    <input
                        type="text"
                        name="land_documents_type_name[${sl}][${fieldCounters}]"
                        class="form-control"
                        placeholder="Document Name"
                        required
                    />
                </div>
                <!-- File Input -->
                <div class="col-md-5">
                    <input
                        type="file"
                        name="land_documents[${sl}][${fieldCounters}]"
                        class="form-control"
                        required
                    />
                </div>
                <!-- Remove Button -->
                <div class="col-md-1">
                    <button
                        type="button"
                        class="btn btn-sm btn-danger w-50"
                        onclick="remove_file_field(this, ${sl})"
                    >
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
        `);
        }

        function remove_file_field(button, sl) {
            const row = $(button).closest('.row');
            row.remove();
        }


        function calculateTotal(n) {
            const perShotangsoRate = parseFloat($(`#per_shotangso_rate${n}`).val()) || 0;
            const shotangso = parseFloat($(`#shotangso${n}`).val()) || 0;
            const totalAmount = perShotangsoRate * shotangso;
            $(`#total_amount${n}`).val(totalAmount.toFixed(2));
        };
    </script>
@endpush
