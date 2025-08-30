@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<style>

</style>
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Head To Head Transfer
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('head-to-head-transfer-store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 border p-3">
                                    <label for="from_fund">From Head</label>
                                    <select name="from_head_id" id="from_head" class="chosen-select" required>
                                        <option value="">Select Head </option>
                                        @foreach ($heads as $head)
                                            <option value="{{ $head->id }}">{{ $head->head_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="from_fund">From Fund</label>
                                    <select name="from_fund_id" id="from_fund" class="form-control" required
                                        onchange="showFromBankInfo()">
                                        <option value="">Select a Fund </option>
                                        @foreach ($fund_types as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="" class="from_bank">Bank</label>
                                    <select name="from_bank_id" id="from_bank_id" class="form-control from_bank"
                                        onchange="filterAccount()">
                                        <option value="">Select a Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="account" class="from_bank">Account</label>
                                    <select name="from_acc_no" id="from_account_id" class="form-control from_bank">
                                        <option value="">Select An Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 border p-3">
                                    <label for="">To Head</label>
                                    <select name="to_head_id" id="to_head_id" class="chosen-select" required>
                                        <option value="">Select Head</option>
                                        @foreach ($heads as $head)
                                            <option value="{{ $head->id }}">{{ $head->head_name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="">To Fund</label>
                                    <select name="to_fund_id" id="to_fund" class="form-control" required
                                        onchange="showToBankInfo()">
                                        <option value="">Select a Fund </option>
                                        @foreach ($fund_types as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="to_bank" class="to_bank">Bank</label>
                                    <select name="to_bank_id" id="to_bank" class="form-control to_bank"
                                        onchange="filtertoAccount()">
                                        <option value="">Select a Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="account-to" class="to_bank">Account</label>
                                    <select name="to_acc_no" id="account-to" class="form-control to_bank">
                                        <option value="">Select An Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                                        @endforeach
                                    </select>

                                    <label for="">Transfer Amount</label>
                                    <input type="number" class="form-control" name="amount"
                                        placeholder="Enter Transfer Amount" required>

                                    <label for=""> Transfer Date </label>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}"
                                        required>

                                    <label for=""> Particulars </label>
                                    <input type="text" name="particulars" placeholder="Type Particulars"
                                        class="form-control" value="" required>

                                    <label for="" class="mt-2"> Attachment </label>
                                    <input type="file" name="attachment" placeholder="" class="mt-2" value=""
                                        required>
                                </div>
                            </div>
                            <div class="row text-center mt-4">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success col-sm-3">Transfer</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".chosen-select").chosen({
                width: "100%",
                no_results_text: "No results found!"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.from_bank').hide();
            $('.to_bank').hide();
        });

        function showFromBankInfo() {
            var fund_id = document.getElementById('from_fund').value;
            console.log(fund_id);
            if (fund_id == 1) {
                $('.from_bank').show();
            } else {
                $('.from_bank').hide();
            }

        }

        function showToBankInfo() {
            var fund_id = document.getElementById('to_fund').value;
            if (fund_id == 1) {
                $('.to_bank').show();

            } else {
                $('.to_bank').hide();
            }
        }

        function filterAccount() {
            var bank_id = document.getElementById('from_bank_id').value;
            var url = "{{ route('filter-bank-fund') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#from_account_id').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#from_account_id').append('<option value="' + value.id + '">' + value
                            .account_no +
                            '</option>');
                    });
                },
            });
        }

        function filtertoAccount() {
            var bank_id = document.getElementById('to_bank').value;
            var url = "{{ route('filter-bank-fund') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#account-to').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account-to').append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                },
            });
        }
    </script>
@endpush
