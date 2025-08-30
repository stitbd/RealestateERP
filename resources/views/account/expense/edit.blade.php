@extends('layouts.app')
<style>
    .form-control {
        border: 0.8px solid rgb(180, 179, 179) !important;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Expense Edit
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('update-expense',$expense->id) }}" method="post" enctype="multipart/form-data"
                            id="myForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 ">
                                    <div class="row border m-2 p-2" style="border-color: green !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                            Expense Required Information
                                        </h6>
                                        <div class="col-lg-6">
                                            <label for="company">Company</label>
                                            <select class="form-control chosen-select" name="company_id" id="company"
                                                onchange="filterProject();">
                                                @foreach ($company as $v_company)
                                                    <option value="{{ $v_company->id }}"
                                                        @if($v_company->id == $expense->other_company_id && $expense->other_company_id != 0) selected @elseif($v_company->id == $expense->company_id) selected  @endif>
                                                        {{ $v_company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="project">Project</label>
                                            <select class="form-control chosen-select" name="project" id="project">
                                                <option value="">Select Project..</option>
                                                @foreach ($project as $v_project)
                                                    <option value="{{ $v_project->id }}" @if($v_project->id == $expense->project_id) selected @endif>{{ $v_project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        {{-- <div class="col-lg-12">
                                            <label>Expense Type:</label>
                                            <div class="form-check row">
                                                <input class="form-check-input col-sm-1 " type="radio" name="expense_type" 
                                                    id="current" value="current" required onclick="showAdvanceExpense()" @if($expense->expense_type == 'current') checked @endif>
                                                <label class="form-check-label col-sm-2 ml-1" for="current">Current</label>
                                                <input class="form-check-input col-sm-1" type="radio" name="expense_type"
                                                    id="advance" value="advance" required onclick="showAdvanceExpense()" @if($expense->expense_type == 'advance') checked @endif>
                                                <label class="form-check-label col-sm-2 ml-1" for="advance">Advance</label>
                                            </div>
                                        </div> --}}
                                        {{-- @dd($expense->advance_expense_id) --}}
                                        @if($expense->advance_expense_id != null)
                                        <div class="col-lg-12" id="">
                                            @php  $advance = App\Models\AdvanceExpense::where('id',$expense->advance_expense_id)->first(); @endphp
                                            <input type="hidden" class="form-control" name="prev_advance" value="{{$advance->id}}"> 
                                           
                                        </div>
                                        @endif
                                        {{-- <div class="col-lg-12" id="advance-expense">
                                            <label for="advance">Advance Expense</label>
                                            <select class="form-control chosen-select" name="advance" id="advance-id"
                                                onchange="advanceAmountShow()">
                                                @foreach ($advance_expenses as $advance)
                                                    <option value="{{ $advance->id }}">{{ $advance->details }}</option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col-lg-12">
                                            <label for="category">Expense For</label>
                                            <select class="form-control chosen-select" name="category" id="category"
                                                onchange="filterHead(this); generateExpenseCode(); newCategoryAdd()">
                                                @foreach ($categories as $category)
                                                    @php $expenses = json_decode($category->category_type)  @endphp
                                                    @if ($expenses && in_array('Expense', $expenses))
                                                        <option value="{{ $category->id }}" @if($category->id == $expense->category_id) selected @endif>{{ $category->category_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                                <option value="new_category">New Category</option>
                                            </select>
                                            <div class="categoryAdd">

                                            </div>
                                        </div>
                                        {{-- @dd($expense->code_no) --}}
                                        <div class="col-lg-6">
                                            <label for="head">Account Head</label>
                                            <select class=" chosen-select form-control head" name="head" id="head" onchange="newHeadAdd()">
                                                @foreach ($head as $v_head)
                                                    <option value="{{ $v_head->id }}" @if ($v_head->id == $expense->head_id) selected @endif>{{ $v_head->head_name }}</option>
                                                @endforeach
                                                <option value="new_head">New Head</option>

                                            </select>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="date">Expense Amount</label>
                                            <input type="number" name="amount" id="amount" class="form-control amount"
                                                placeholder="Enter Amount" onkeyup="totalAmount(this)" value="{{$expense->amount}}">
                                        </div>

                                        <div class="headAdd col-lg-12">

                                        </div>

                                        {{-- <div class="col-lg-1 mt-4">
                                        <button type="button" class="btn btn-success btn-md add-btn" onclick="addMore()">+</button>
                                    </div> --}}
                                        <div class="mt-3" id="wrapper">

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="date">Date</label>
                                            <input type="date" name="payment_date" required class="form-control" value="{{$expense->payment_date}}">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Code No">Code No</label>
                                            <input type="text" class="form-control" name="code_no" id="code_no"
                                                placeholder="Enter an Exp. Code No." value="{{$expense->code_no}}">
                                            {{-- <small style="color:red">Code No. Should Be Unique</small> --}}
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Fund">Fund</label>
                                            <select name="fund_id" id="fund_id" class="form-control" required
                                                onchange="checkPaymentType();checkType()">
                                                @foreach ($fund_data as $v_fund)
                                                    <option value="{{ $v_fund->id }}" @if($v_fund->id == $expense->fund_id) selected @endif >{{ $v_fund->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Fund">Payment Type</label>
                                            <select name="payment_type" class="form-control" id="payment_type" required>
                                                <option value="Cash" @if ($expense->payment_type == 'Cash') selected @endif >Cash</option>
                                                <option value="Cheque" @if ($expense->payment_type == 'Cheque') selected @endif >Cheque</option>
                                                <option value="Bank" @if ($expense->payment_type == 'Bank') selected @endif >Bank</option>
                                                <option value="Others" @if ($expense->payment_type == 'Others') selected @endif >Others</option>
                                            </select>
                                        </div>
                                        {{-- <div class="col-lg-12">
                                        <label for="total_amount">Expense amount </label>
                                        <input type="text" id="total_amount" required name="total_amount" class="form-control"/>
                                        <input type="hidden" id="hidden_amount" required name="total_amount" class="form-control"/>
                                    </div> --}}
                                        <div class="col-lg-12">
                                            <label for="remarks">Particulars</label>
                                            <input type="text" name="remarks" class="form-control" value="{{$expense->remarks}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 ">

                                    <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                            Expense Optional Information
                                        </h6>
                                        {{-- <div class="col-lg-12">
                                            <label for="Supplier">Expenser </label>
                                            <select name="employee" id="expenser_id"
                                                class="form-select form-control chosen-select"
                                                onchange="hideExpenserName();">
                                                <option value="0" selected>Select Expenser Name</option>
                                                @foreach ($employee as $v_employee)
                                                    <option value="{{ $v_employee->id }}">{{ $v_employee->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div> --}}
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 expenser" name="expenser_name"
                                                placeholder="Type Expernser Name" value="{{$expense->expenser_name}}">
                                        </div>
                                        {{-- <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 expenser"
                                                name="expenser_mobile_no" placeholder="Type Expernser Mobile No.">
                                        </div> --}}

                                        {{-- <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 expenser" name="department"
                                                placeholder="Type Department">
                                        </div> --}}
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 expenser" name="designation"
                                                placeholder="Type Designation" value="{{$expense->designation}}">

                                        </div>
                                        <div class="col-lg-12 mobile">
                                            <label for="mobile_no">Mobile No.</label>
                                            <input type="text" name="mobile_no" class="form-control " value="{{$expense->mobile_no}}"/>
                                        </div>
                                        @if($expense->fund_id == 1)
                                            <div class="col-md-10 mt-3 ">
                                                <h6 class="text-center text-bold">Bank Inforamtion</h6>
                                            </div>
                                            <div class="col-md-2 mt-3 ">
                                                <button type="button" class=" btn btn-success" id="add"
                                                    onclick="addNewBank(this)">+
                                                    Add</button>
                                            </div>
                                            <hr>
                                            @php $bankInfo = App\Models\ExpenseBankInfo::where('master_id',$expense->id)->get(); @endphp
                                            @if($bankInfo)
                                            @foreach($bankInfo as $bank_expense)
                                                <div class="col-lg-6 ">
                                                    <label for="bank_name">Bank </label>
                                                    <select name="bank_id[]" id="bank_id-1"
                                                        class="form-control bank_info chosen-select"
                                                        onchange="filterAccount(this)">
                                                        <option value="">Select A Bank</option>
                                                        @foreach ($banks as $bank)
                                                            <option value="{{ $bank->id }}" @if ($bank_expense->bank_id == $bank->id) selected @endif>{{ $bank->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 ">
                                                    <label for="account_no">Bank AC No.</label>
                                                    <select name="account_id[]" id="account-1"
                                                        class="form-control bank_info chosen-select"
                                                        onchange="accountHolderName(this)">
                                                        <option value="">Select One</option>
                                                        @foreach ($bank_accounts as $account)
                                                            <option value="{{ $account->id }}" @if($account->id == $bank_expense->account_id) selected @endif>{{ $account->account_no }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-6 ">
                                                    <label for="account_holder_name">Account Holder Name</label>
                                                    <input type="text" name="account_holder_name[]" class="form-control"
                                                        id="account_holder_name_1" value="{{$bank_expense->account_holder}}" />
                                                </div>
                                                <div class="col-lg-6 ">
                                                    <label for="payment_note">Payment Note</label>
                                                    <input type="text" name="payment_note[]" class="form-control" value="{{$bank_expense->note}}" />
                                                </div>
                                                <div class="col-lg-12 " id="">
                                                    <label for="bank_amount">Amount</label>
                                                    <input type="text" name="bank_amount[]" class="form-control bank_info" value="{{$bank_expense->amount}}"/>
                                                </div>
                                                <div class="col-lg-6 " id="cheque1">
                                                    <label for="cheque_no">Cheque Number</label>
                                                    <input type="text" name="cheque_no[]" class="form-control" value="{{$bank_expense->cheque_no}}" />
                                                </div>

                                                <div class="col-lg-6  mb-3" id="cheque2">
                                                    <label for="cheque_issue_date">Cheque Issue Date</label>
                                                    <input type="date" name="cheque_issue_date[]" class="form-control" value="{{$bank_expense->cheque_issue_date}}"/>
                                                </div>
                                            @endforeach
                                            @endif
                                            <div class="bankWrapper">

                                            </div>
                                        @endif
                                        <div class="col-lg-12 mt-3">
                                            <label for="Supplier">Attachment</label>
                                            <input type="file" name="attachment"/>
                                        </div>
                                        <div class="col-lg-12 pt-3">
                                            <button class="btn btn-success btn-block" id="submit"><i
                                                    class="fa fa-check"></i> Update</button>
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
                $('.headAdd').hide();
                $('#advance-expense').hide();

                console.log("ready!");
            });
            var i = 1;

        function addMore() {
            ++i;
            var category_id = document.getElementById('category').value;
            if (category_id != 0) {
                var url = "{{ route('filter-head') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id
                    },
                    success: function(data) {
                        var html = ``;
                        html += `
                        <div class="col-md-12">
                            <div class="form-group row pb-3">
                                <div class="col-lg-6">
                                    <label for="head">Expense Head</label>;
                                    <select name="head[]" class="form-control chosen-select">
                                        <option value="">Select Head...</option>`;
                        $.each(data, function(key, value) {
                            html += `<option value="${value.id}">${value.head_name}</option>`;
                        });
                        html += `</select>
                                        </div>
                                        <div class="col-lg-6">
                                        <label for="date">Amount</label>
                                        <input type="number" name="amount[]" class="form-control amount" id="amount-` +
                            i + `" placeholder="Enter Amount" onkeyup="totalAmount(this)">
                                    </div>
                                <button class="remove btn btn-md btn-danger text-center mt-4"><i class="fa fa-minus"></i></button>
                            </div>
                        </div>`;

                        $('#wrapper').append(html);
                        // Event delegation for dynamically added "remove" buttons
                        $('#wrapper').on('click', '.remove', function() {
                            $(this).parent().remove();
                            totalAmount();
                        });
                    },
                });
            } else {
                alert('Please select a category first.');
            }
        }
        function hideExpenserName() {
            var expenser = document.getElementById('expenser_id').value;
            console.log(expenser);
            $('.expenser').show();
            if (expenser == "0") {
                $('.expenser').show();
            } else {
                $('.expenser').hide();
            }

            $(".chosen-select").chosen();
        }

        function showAdvanceExpense() {
            $('#advance-expense').hide();
            var advance = document.getElementById('advance');
            var current = document.getElementById('current');
            if (advance.checked) {
                $('#advance-expense').show();
            } else {
                $('#advance-expense').hide();
            }

            $(".chosen-select").chosen();
        }

        function advanceAmountShow() {
            var advance_id = document.getElementById('advance-id').value;
            var url = "{{ route('advance-expense-amount') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    advance_id
                },
                success: function(data) {
                    document.getElementById('amount').value = data
                },
            });

            $(".chosen-select").chosen();
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

        
        var j = 1;

        function addNewBank() {
            ++j
            $('.bankWrapper').append(`
        <div class="col-md-12  mt-3 append-` + j + `">
            <div style="border-bottom: 1px solid black; margin-bottom:15px;"></div>
            <div class="form-group row pb-3">
                <div class="col-lg-6 bank" >
                    <label for="bank_name">Bank </label>
                    <select name="bank_id[]" id="bank_id-` + j + `" class="form-control chosen-select" onchange="filterAccount(this)" >
                        <option value="">Select One</option>
                        @foreach ($banks as $bank)
                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 bank">
                    <label for="account_no">Bank AC No.</label>
                    <select name="account_id[]" id="account-` + j + `" class="form-control chosen-select" onchange="accountHolderName(this)">
                        <option value="">Select A Bank</option>
                        @foreach ($bank_accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                        @endforeach
                    </select>  
                </div>
                <div class="col-lg-6 bank" >
                    <label for="account_holder_name">Account Holder Name</label>
                    <input type="text" name="account_holder_name[]" class="form-control" id="account_holder_name_` +
                j + `"/>
                    </div>
                <div class="col-lg-6 bank">
                    <label for="payment_note">Payment Note</label>
                    <input type="text" name="payment_note[]" class="form-control"/>
                </div>
                <div class="col-lg-12 bank" id="">
                    <label for="bank_amount">Amount</label>
                    <input type="text" name="bank_amount[]" class="form-control"/>
                </div>
                <div class="col-lg-6 bank" id="cheque1">
                    <label for="cheque_no">Cheque Number</label>
                    <input type="text" name="cheque_no[]" class="form-control"/>
                </div>
                <div class="col-lg-6 bank mb-3" id="cheque2">
                    <label for="cheque_issue_date">Cheque Issue Date</label>
                    <input type="date" name="cheque_issue_date[]" class="form-control"/>
                </div>
            </div>
            <span class="remove btn btn-danger text-center" style="width: fit-content; margin-bottom:12px"><i class="fa fa-times"></i></span>
        </div>

    `);
            $('.append-' + j).on('click', '.remove', function() {
                $(this).parent().remove();
            });

            $(".chosen-select").chosen();
        }


        function accountHolderName(e) {
            var text = e.id;
            var id = text.replace('account-', '');
            var account_id = document.getElementById('account-' + id).value;
            var url = "{{ route('account-holder') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    account_id
                },
                success: function(data) {
                    document.getElementById('account_holder_name_' + id).value = data
                },
            });
        }


        function checkPaymentType() {
            var fund_id = document.getElementById('fund_id').value;
            if (fund_id == 2) {
                $('.bank').hide();
                $('.cheque').hide();
                $('.mobile').hide().removeAttr('required', true);
                $('.bank_info').removeAttr('required', true);

            }
            if (fund_id == 1) {
                $('.bank').show();
                $('.mobile').hide().removeAttr('required', true);
                $('.bank_info').prop('required', true);
            }

            if (fund_id == 3) {
                $('.bank').hide();
                $('.cheque').hide();
                $('.mobile').show().prop('required', true);
                $('.bank_info').removeAttr('required', true);
            }

            if (fund_id == 4) {
                $('.bank').hide();
                $('.cheque').hide();
                $('.mobile').hide().removeAttr('required', true);
                $('.bank_info').removeAttr('required', true);
            }
        }

        function filterHead() {
            var category_id = document.getElementById('category').value;
            if(category_id == 'new_category'){
                console.log(category_id);
                 $('#head').empty().append('<option value="" disabled selected>Choose Head</option><option value="new_head">New Head</option>').trigger("chosen:updated");
            }else{
                var url = "{{ route('filter-head') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    category_id
                },
                success: function(data) {
                    $('#head').find('option').remove();
                    $('#head').html('');
                    $('#head').append(`<option value="" disabled selected>Choose Head</option> <option value="new_head">New Head</option>`);
                    $.each(data, function(key, value) {
                        $('#head').append(`
                        <option value="` + value.id + `">` + value.head_name +
                            `</option>`);
                    });
                    $('#head').trigger("chosen:updated");
                },
            });
            $(".chosen-select").chosen();
            }
           
        }

        function filterAccount(e) {
            var text = e.id;
            var id = text.replace('bank_id-', '');
            var bank_id = document.getElementById('bank_id-' + id).value;
            var url = "{{ route('filter-account') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#account-' + id).find('option').remove();
                    $('#account-' + id).html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account-' + id).append('<option value="' + value.id + '">' + value
                            .account_no +
                            '</option>');
                    });

                    $('#account-' + id).trigger("chosen:updated");

                },
            });

            $(".chosen-select").chosen();
        }

        function generateExpenseCode() {
            var category_id = document.getElementById('category').value;
            if (category_id) {
                var lastExpenseId = {{ $lastExpenseId ?? 0 }}; // Set lastExpenseId to 0 if it's not available
                var nextExpenseId = lastExpenseId + 1; // Increment last expense ID or start from 1 if no previous expenses
                var expenseCode = 'EXP-' + nextExpenseId;
                document.getElementById('code_no').value = expenseCode;
            } else {
                document.getElementById('code_no').value = ''; // Reset if no category selected
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
            document.getElementById('total_amount').value = total;
            document.getElementById('hidden_amount').value = total;
        }


        function newCategoryAdd() {
            var category = document.getElementById('category').value;
            if (category == 'new_category') {
                ++j
                $('.categoryAdd').append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="category_name" class="form-control" id="" placeholder="Enter Category Name"/>
                                        <input type="hidden" name="category_type[]" class="form-control" id="" value="Expense"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

            $('.categoryAdd').on('click', '.remove', function() {
                $(this).parent().remove();
            });
            }else{
                $('.categoryAdd').hide();
            }

        }

        function newHeadAdd(){
            var head = document.getElementById('head').value;
            if (head == 'new_head') {
                $('.headAdd').empty().show().append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="head_name" class="form-control" id="" placeholder="Enter Head Name"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm  " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

            $('.headAdd').on('click', '.remove', function() {
                $(this).parent().remove();
            });
            }else{
                $('.headAdd').hide();
            }

        }
    </script>
@endpush
