@extends('layouts.app')
<style type="text/css">
    fieldset {
        min-width: 0px;
        padding: 15px;
        margin: 7px;
        border: 2px solid #000000;
    }

    legend {
        float: none;
        background-image: linear-gradient(to bottom right, #0e0e0e, #000000);
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
                            Loan Entry
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('loan-voucher') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Loan Information </legend>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="date">Loan Provide Date<i class="text-danger">*</i></label>
                                                <input type="date" name="loan_date" required class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="valid_date">Maturity Date<i class="text-danger">*</i></label>
                                                <input type="date" name="valid_date" required class="form-control" />
                                            </div>
                                    
                                            {{-- @dd($categories) --}}
                                            <div class="col-lg-12">
                                                <label for="category">Account Main Head<i class="text-danger">*</i></label>
                                                <select class="form-control chosen-select" name="category" id="category"
                                                    onchange="filterHead(this); generateLoanCode(); newCategoryAdd()">
                                                    <option value="0">Choose Main Head..</option>
                                                    <option value="new_category">New Main Head</option>
                                                    @foreach ($categories as $category)
                                                        @php $loans = json_decode($category->category_type) @endphp
                                                        @if ($loans && in_array('Loan', $loans))
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->category_name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                    <option value=""></option>
                                                </select>
                                                <div class="categoryAdd">

                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="head">Account Sub Head<i class="text-danger">*</i></label>
                                                <select class=" chosen-select form-control head" name="head"
                                                    id="head" onchange="newHeadAdd()">
                                                    <option value="">Choose Sub Head...</option>
                                                    <option value="new_head">New Sub Head</option>
                                                    @foreach ($head as $v_head)
                                                        <option value="{{ $v_head->id }}">{{ $v_head->head_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="headAdd col-lg-12">

                                                </div>
                                            </div>



                                            <div class="col-lg-6">
                                                <label for="amount">Amount<i class="text-danger">*</i></label>
                                                <input type="number" required name="amount" class="form-control" />
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="date">Loan Code</label>
                                                <input type="text" class="form-control" name="loan_code" id="loan_code" readonly
                                                    placeholder=" Loan Code No.">
                                            </div>

                                      

                                            <div class="col-md-6">
                                                <label for="fund">Fund<i class="text-danger">*</i></label>
                                                <select name="fund_id" id="fund" class="form-control" required
                                                    onchange="showBankInfo()">
                                                    <option value="">Select a Fund </option>
                                                    @foreach ($fund_types as $fund)
                                                        <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="fund">Payment Type<i class="text-danger">*</i></label>
                                                <select name="payment_type" id="fund" class="form-control" required
                                                    onchange="">
                                                    <option value="">Select Payment Type </option>
                                                    <option value="Cash">Cash</option>
                                                    <option value="Bank">Bank</option>
                                                    <option value="Cheque">Cheque</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 bank">
                                                <label for="">Bank <i class="text-danger">*</i></label>
                                                <select name="bank_id" id="bank_id" class="form-control"
                                                    onchange="filterAccount()">
                                                    <option value="">Select a Bank</option>
                                                    @foreach ($banks as $bank)
                                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 bank">
                                                <label for="account">Account <i class="text-danger">*</i></label>
                                                <select name="account_id" id="account_id" class="form-control ">
                                                    <option value="">Select An Account</option>
                                                    @foreach ($accounts as $account)
                                                        <option value="{{ $account->id }}">{{ $account->account_no }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 bank">
                                                <label for="cheque">Cheque No.</label>
                                                <input type="text" name="cheque_no" class="form-control" id="" placeholder="Type Cheque Number">
                                            </div>

                                            <div class="col-md-6 bank">
                                                <label for="cheque">Cheque Issue Date.</label>
                                                <input type="date" name="cheque_issue_date" class="form-control" id="">
                                            </div>
 
                                            {{-- <div class="col-lg-6">
                                                    <label for="reference">Reference</label>
                                                    <input type="text" name="reference" required class="form-control">
                                                </div> --}}
                                            {{-- <div class="col-lg-6">
                                                    <label for="bank_credit_limit">Bank Credit Limit </label>
                                                    <input type="number" required name="bank_credit_limit" class="form-control" />
                                                </div> --}}

                                            {{-- <div class="col-lg-6">
                                                <label for="interest_percentage">Interest Percentage (%)<i
                                                        class="text-danger">*</i></label>
                                                <input type="number" name="interest_percentage" required
                                                    class="form-control" />
                                            </div> --}}
                                            <div class="col-lg-6">
                                                <label for="remarks">Remarks<i class="text-danger">*</i></label>
                                                <input type="text" name="remarks" class="form-control" required/>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="attachment">Attachment<i class="text-danger">*</i></label>
                                                <br />
                                                <input type="file" name="attachment" required />
                                            </div>
                                            <div class="col-lg-12 mb-3">
                                                <label for="description">Purpose of Loan<i class="text-danger">*</i></label>
                                                <textarea name="description" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                <div class="col-md-6">
                                    <fieldset>
                                        <legend style="color: hsl(0, 0%, 100%);"> Employee's Information </legend>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="loanee_name">Name<i class="text-danger">*</i></label>
                                                <input type="text" name="loanee_name" required class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="department">Department<i class="text-danger">*</i></label>
                                                <input type="text" name="department" required class="form-control">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="designation">Designation<i class="text-danger">*</i></label>
                                                <input type="text" name="designation" required class="form-control">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="company_id">Company<i class="text-danger">*</i></label>
                                                <select name="employee_company_id" class="form-control" required>
                                                    <option value="">Select a Company</option>
                                                    @foreach ($companies as $company)
                                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="phone">Mobile no.<i class="text-danger">*</i></label>
                                                <input type="number" name="phone" class="form-control" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="email">Email<i class="text-danger">*</i></label>
                                                <input type="email" name="email" class="form-control" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="nid">NID<i class="text-danger">*</i></label> <br />
                                                <input type="file" name="nid" required />
                                            </div>
                                            <div class="col-lg-12 mb-3">
                                                <label for="address">Address<i class="text-danger">*</i></label>
                                                <textarea name="address" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-success  text-center col-md-12 mb-3"><i class="fa fa-check"></i>
                                            Save</button>
                                    </fieldset>

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
            $('.headAdd').hide();
        });

        function showBankInfo() {
            var fund_id = document.getElementById('fund').value;
            console.log(fund_id);
            if (fund_id == 1) {
                $('.bank').show();
                $('#bank_id').prop('required', true);
                $('#account_id').prop('required', true);
            } else {
                $('.bank').hide();
            }

        }

        function filterAccount() {
            var bank_id = document.getElementById('bank_id').value;
            var url = "{{ route('filter-bank-fund') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#account_id').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account_id').append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                },
            });
        }

        var j = 1;
        function filterHead() {
            var category_id = document.getElementById('category').value;
            if (category_id == 'new_category') {
                console.log(category_id);
                $('#head').empty().append(
                        '<option value="" disabled selected>Choose Head</option><option value="new_head">New Head</option>')
                    .trigger("chosen:updated");
            } else {
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
                        $('#head').append(
                            `<option value="" disabled selected>Choose Head</option> <option value="new_head">New Head</option>`
                            );
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

        function generateLoanCode() {
            var category_id = document.getElementById('category').value;
            console.log(category_id);
            if (category_id) {
                var lastLoanId = {{ $lastLoanId ?? 0 }};
                var nextLoanId = lastLoanId + 1;
                var loanCode = 'L-' + nextLoanId;

                document.getElementById('loan_code').value = loanCode;
            } else {
                document.getElementById('loan_code').value = '';
            }
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
                                        <input type="hidden" name="category_type[]" class="form-control" id="" value="Loan"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('.categoryAdd').on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('.categoryAdd').hide();
            }

        }

        function newHeadAdd() {
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
            } else {
                $('.headAdd').hide();
            }

        }
    </script>
@endpush
