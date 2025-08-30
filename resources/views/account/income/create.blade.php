@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Income Entry
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('income-voucher') }}" method="post" enctype="multipart/form-data"
                            id="myForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 ">

                                    <div class="row border m-2 p-2" style="border-color: green !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                            Income Required Information
                                        </h6>
                                        <div class="col-lg-6">
                                            <label for="company">Company<i class="text-danger pt-1"
                                                    style="font-size:16px">*</i> </label>
                                            <select name="company" id="company" class="form-control chosen-select"
                                                onchange="filterProject();">
                                                <option value="">Select Company..</option>
                                                @foreach ($company as $v_company)
                                                    <option value="{{ $v_company->id }}">{{ $v_company->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="project">Project</label>
                                            <select name="project" id="project" class="form-control chosen-select">
                                                <option value="">Select Project..</option>
                                                @foreach ($project as $v_project)
                                                    <option value="{{ $v_project->id }}">{{ $v_project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="category">Income Category<i class="text-danger">*</i> </label>
                                            <select name="category" id="category-1"
                                                class="form-control category chosen-select"
                                                onchange="filterHead(this); generateIncomeCode();newCategoryAdd(this);"
                                                required>
                                                <option value="">Select Category..</option>
                                                <option value="new_category">New Category</option>
                                                @foreach ($categories as $category)
                                                    @php $incomes = json_decode($category->category_type)  @endphp
                                                    @if ($incomes && in_array('Income', $incomes))
                                                        <option value="{{ $category->id }}">{{ $category->category_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                                <option value=""></option>
                                            </select>
                                            <div id="categoryAdd-1">

                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="head">Income Head</label>
                                            <select name="head" id="head-1" class="form-control chosen-select"
                                                onchange="newHeadAdd(this);">
                                                <option value="">Select Head...</option>
                                                <option value="new_head">New Head</option>
                                                @foreach ($head as $v_head)
                                                    <option value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- <div class="col-lg-6">
                                        <label for="date">Amount <i
                                    class="text-danger">*</i></label>
                                        <input type="number" name="amount[]" id="amount-1"  class="form-control amount" placeholder="Enter Amount" onkeyup="totalAmount(this); ">
                                    </div> --}}
                                        {{-- <div class="col-lg-1 mt-4">
                                        <button type="button" class="btn btn-success btn-md add-btn" onclick="addMore()">+</button>
                                    </div> --}}
                                        <div class="col-lg-12" id="headAdd-1">

                                        </div>
                                        {{-- <div class="" id="wrapper">

                                    </div> --}}
                                        <div class="col-lg-6">
                                            <label for="date">Date <i class="text-danger">*</i></label>
                                            <input type="date" name="payment_date" required class="form-control">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Code No">Code No <i class="text-danger">*</i></label>
                                            <input type="text" class="form-control" name="code_no" id="code_no"
                                                placeholder="Enter an Inc. Code No.">
                                            <small style="color:red">Code No. Should Be Unique</small>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <label for="Fund">Fund <i class="text-danger">*</i></label>
                                            <select name="fund_id[]" id="fund_id-1" class="form-control" required
                                                onchange="checkPaymentType('1');checkType();">
                                                <option value="">Select One</option>
                                                @foreach ($fund_data as $v_fund)
                                                    <option value="{{ $v_fund->id }}">{{ $v_fund->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="Fund">Payment Type <i class="text-danger">*</i></label>
                                            <select name="payment_type[]" class="form-control" id="payment_type" required>
                                                <option value="">Select One</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Bank">Bank</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="total_amount">Total amount <i class="text-danger">*</i></label>
                                            <input type="text" id="total_amount" required name="total_amount[]"
                                                class="form-control" />
                                            {{-- <input type="hidden" id="hidden_amount" required name="total_amount" class="form-control"/> --}}
                                        </div>
                                        <div class="col-lg-10">
                                            <label for="remarks">Remarks<i class="text-danger">*</i></label>
                                            <input type="text" name="remarks[]" id="remarks" class="form-control"
                                                required />
                                        </div>
                                        <div class="col-lg-1 mt-4">
                                            <button type="button" class="btn btn-success btn-md add-btn"
                                                onclick="addMore()">+</button>
                                        </div>
                                        <div class="" id="wrapper">

                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-6 row-container">

                                    <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                            Receiver Information
                                        </h6>

                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 " name="client_name"
                                                placeholder="Type Client Name" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 " name="client_id"
                                                placeholder="Type Client ID">

                                        </div>
                                        <div id="bankInformation-1" class="col-lg-12 mt-3"></div>
                                        <div class="col-lg-12 mobile">
                                            <label for="mobile_no">Mobile No.<i class="text-danger">*</i></label>
                                            <input type="text" name="mobile_no" class="form-control " />
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="Supplier">Attachment</label>
                                            <input type="file" name="attachment" />
                                        </div>
                                        <div class="col-lg-12 pt-3">
                                            <button class="btn btn-success btn-block"><i class="fa fa-check"></i>
                                                Save</button>
                                        </div>
                                    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

    <script>
        $(".chosen-select").chosen();
        $(document).ready(function() {
            $('.bank').hide();
            $('.cheque').hide();
            $('.mobile').hide();
            console.log("ready!");
        });
        var i = 1;

        function addMore() {
            ++i;
            $('#wrapper').append(`
            <div class="col-md-12 mt-3" id="row-${i}">
                <div class="form-group row pb-3">
                    <div class="col-lg-12">
                        <label for="fund_id">Fund <i class="text-danger">*</i></label>
                        <select name="fund_id[]" id="fund_id-${i}" class="form-control chosen-select" onchange="checkPaymentType(${i})">
                            <option value="">Select One...</option>
                            @foreach ($fund_data as $v_fund)
                                <option value="{{ $v_fund->id }}">{{ $v_fund->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="bankInformation-${i}" class="col-lg-6"></div>
                    <div class="col-lg-6">
                        <label for="payment_type">Payment Type <i class="text-danger">*</i></label>
                        <select name="payment_type[]" class="form-control" id="payment_type-${i}" required>
                            <option value="">Select One</option>
                            <option value="Cash">Cash</option>
                            <option value="Cheque">Cheque</option>
                            <option value="Bank">Bank</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="total_amount">Total Amount <i class="text-danger">*</i></label>
                        <input type="number" name="total_amount[]" id="total_amount-${i}" class="form-control amount" placeholder="Enter Amount">
                    </div>
                    <div class="col-lg-10">
                        <label for="remarks">Remarks <i class="text-danger">*</i></label>
                        <input type="text" name="remarks[]" id="remarks-${i}" class="form-control" placeholder="Remarks">
                    </div>
                    <button class="remove btn btn-md btn-danger text-center mt-4" onclick="removeRow(${i})">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>`);
        }

        function removeRow(rowId) {
            $(`#row-${rowId}`).remove();
        }


        function checkType() {
            var fundSelect = document.getElementById("fund_id");
            var paymentTypeSelect = document.getElementById("payment_type");
            var selectedFundId = fundSelect.value;
            var paymentTypeOption = paymentTypeSelect.querySelector("option[value='Bank']");

            if (selectedFundId === "1") {
                paymentTypeOption.selected = true;
            }

            $(".chosen-select").chosen();
        }

        function checkPaymentType(rowId) {
            var fund_id = $(`#fund_id-${rowId}`).val();
            if (fund_id == 2) {
                $('#bankInformation-1').empty();
                $('.cheque').hide();
                $('.mobile').hide();

            }
            if (fund_id == 1) {
                // $('.bank').show().prop('required', true);
                // $('.cheque').show();
                // $('.mobile').hide();
                var html = `
                                                     <div class="col-lg-12 bank">
                                            <label for="mobile_no">Bank <i class="text-danger">*</i></label>
                                            <select name="bank_id" id="bank_id-` + rowId + `"
                                                class="form-control form-select chosen-select" onchange="filterAccount(`+rowId+`)">
                                                <option value="" selected>Select Bank</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-12 bank">
                                            <label for="mobile_no">Bank Account<i class="text-danger">*</i></label>
                                            <select name="account_id" id="account-`+rowId+`"
                                                class="form-control form-select chosen-select">
                                                <option value="" selected>Select a Bank Account</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">{{ $account->account_no }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                               <div class="col-lg-6 cheque" id="cheque1">
                                            <label for="cheque_no">Cheque Number <i class="text-danger">*</i></label>
                                            <input type="text" name="cheque_no" class="form-control" />
                                        </div>
                                        <div class="col-lg-6 cheque" id="cheque2">
                                            <label for="cheque_issue_date">Cheque Issue Date <i
                                                    class="text-danger">*</i></label>
                                            <input type="date" name="cheque_issue_date" class="form-control" />
                                        </div>
        `;

                $(`#bankInformation-${rowId}`).append(html);

            }
            if (fund_id == 3) {
                $('#bankInformation-1').empty();
                $('.cheque').hide();
                $('.mobile').show();
            }
            if (fund_id == 4) {
                $('#bankInformation-1').empty();
                $('.cheque').hide();
                $('.mobile').hide();
            }
        }

        function filterHead(e) {
            var text = e.id;
            var id = text.replace('category-', '');
            var category_id = document.getElementById('category-' + id).value;
            if (category_id == 'new_category') {
                console.log(category_id);
                $('#head-' + id).empty().append(
                    '<option value="" selected>Choose Head</option><option value="new_head">New Head</option>').trigger(
                    "chosen:updated");
            } else {
                var url = "{{ route('filter-head-data') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id
                    },
                    success: function(data) {
                        $('#head-' + id).find('option').remove();
                        $('#head-' + id).html('');
                        $('#head-' + id).append('<option value="">Choose Head</option>');
                        $('#head-' + id).append('<option value="new_head">New Head</option>');
                        $.each(data, function(key, value) {
                            $('#head-' + id).append('<option value="' + value.id + '">' + value
                                .head_name +
                                '</option>');
                        });

                        $('#head-' + id).trigger("chosen:updated");
                    },
                });
            }
        }

        function generateIncomeCode() {
            var category_id = document.getElementById('category-1').value;
            if (category_id) {
                var lastIncomeId = {{ $income_code ?? 0 }};
                var nextIncomeId = lastIncomeId + 1;
                var incomeCode = 'INC-' + nextIncomeId;
                document.getElementById('code_no').value = incomeCode;
            } else {
                document.getElementById('code_no').value = '';
            }
        }

        function filterProject() {
            var company_id = document.getElementById('company').value;
            var url = "{{ route('filter-project') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    company_id
                },
                success: function(data) {
                    $('#project').find('option').remove();
                    $('#project').html('<option value="" selected >Select Project..</option>');
                    $.each(data, function(key, value) {
                        $('#project').append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                    $('#project').trigger("chosen:updated");
                },
            });
        }

        function filterAccount(id) {
            var bank_id = $(`#bank_id-${id}`).val();
            var url = "{{ route('filter-account') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $(`#account-${id}`).find('option').remove();
                    $(`#account-${id}`).html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $(`#account-${id}`).append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                    $(`#account-${id}`).trigger("chosen:updated");
                },
            });
        }

        function totalAmount(e) {
            var text = e.id;
            var total = 0;
            var id = text.replace('amount-', '');
            var amount = document.getElementById('amount-' + id).value;
            let total_amount = 0;
            let ajaxPromises = [];
            $('[id^="amount-"]').each(function() {
                const currentAmount = parseFloat($(this).val());
                if (currentAmount) {
                    total += currentAmount
                }
            });
            // document.getElementById('total_amount').value = total;
            // document.getElementById('hidden_amount').value = total;
        }

        function newCategoryAdd(e) {
            var text = e.id;
            var id = text.replace('category-', '');
            var category = document.getElementById('category-' + id).value;
            if (category == 'new_category') {
                $('#categoryAdd-' + id).append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="category_name" class="form-control" id="" placeholder="Enter Category Name"/>
                                        <input type="hidden" name="category_type[][]" class="form-control" id="" value="Income"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('#categoryAdd-' + id).on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('#categoryAdd-' + id).hide();
            }

        }

        function newHeadAdd(e) {
            var text = e.id;
            var id = text.replace('head-', '');
            var head = document.getElementById('head-' + id).value;
            if (head == 'new_head') {
                $('#headAdd-' + id).empty().show().append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="head_name" class="form-control" id="" placeholder="Enter Head Name"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm  " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('#headAdd-' + id).on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('#headAdd-' + id).hide();
            }

        }
    </script>
@endpush
