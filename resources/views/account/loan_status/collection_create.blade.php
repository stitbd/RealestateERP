@extends('layouts.app')
<style>
    .current-amount-field {
        margin-top: 10px;
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
                            Loan Collection Entry
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('loan-collection-voucher') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @if ($loanStatusCount != 0)
                                <div class="alert alert-warning">
                                    {{ $message }}
                                </div>
                            @endif
                            <div class="row">
                                <div class="col-lg-6">
                                    <label for="date">Loan Collection Date<i class="text-danger">*</i></label>
                                    <input type="date" name="date" required class="form-control"
                                        value="{{ date('Y-m-d') }}" data-date-format="yyyy-mm-dd">
                                </div>
                                <div class="col-md-6">
                                    <label for="employee">Employee<i class="text-danger">*</i></label>
                                    <select name="loan_id" id="loan" class="form-control chosen-select" onchange="generateLoanCollectionCode()" required>
                                        <option value="">Select Employee </option>
                                        @foreach ($loans as $loan)
                                            <option value="{{ $loan->id }}">{{ $loan->loanee_name }} ({{ $loan->designation }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6 current-amount-field">
                                    <div class="mb-3">
                                        <b>Maturity Date</b> <input type="text" class="form-control" name="valid_date" id="valid_date" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6 current-amount-field">
                                    <div class="mb-3">
                                        <b>Remaining Collection</b> <input type="text"  class="form-control" name="current_amount" id="current_amount" readonly>
                                    </div>
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
                                    <select name="payment_type" id="type" class="form-control" required
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

                                <div class="col-lg-6">
                                    <label for="collection_code">Loan Collection Code</label>
                                    <input type="text" class="form-control" name="collection_code" id="collection_code"
                                        readonly placeholder="Loan Collection Code No.">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Collection Amount<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="collect_amount" placeholder="Amount" required>
                                </div>
                                <div class="col-lg-12  mb-3">
                                    <label class="form-label">Remarks<i class="text-danger">*</i></label>
                                    <textarea class="form-control" name="note" placeholder="Remark"></textarea>
                                </div>

                                <button type="submit" class="btn btn-success float-right col-md-4 mt-2"><i class="fa fa-check"></i>
                                    Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $(".chosen-select").chosen();

        $('.bank').hide();

        $('#loan').change(function() {
            var loanId = $(this).val();
            if (loanId) {
                $.ajax({
                    type: 'GET',
                    url: '/get-current-amount/' + loanId,
                    success: function(data) {
                        $('#current_amount').val(data.current_amount);
                        $('#valid_date').val(data.valid_date);
                    }
                });
            } else {
                $('#current_amount').val('');
                $('#valid_date').val('');
            }
            generateLoanCollectionCode();
        });
    });

    function generateLoanCollectionCode() {
        var loan_id = $('#loan').val();
        console.log(loan_id);
        if (loan_id) {
            var lastCollectionId = {{ $lastCollectionId ?? 0 }};
            var nextLoanCollectionId = lastCollectionId + 1;
            var loanCollectionCode = 'LCLT-' + nextLoanCollectionId;

            $('#collection_code').val(loanCollectionCode);
        } else {
            $('#collection_code').val('');
        }
    }

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

</script>

@endpush

