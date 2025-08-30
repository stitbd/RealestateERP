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
                            Collect Invest Amount
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('save-collect-invest') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-lg-11" id="collection-container">
                                    <div class="collection-entry row mb-2">
                                        <div class="col-lg-3">
                                            <label for="month">Collection Month<i class="text-danger">*</i></label>
                                            <input type="month" name="collect_month[]" required class="form-control">
                                        </div>
                                        <div class="col-lg-3">
                                            <label for="collect_amount">Collect Amount</label>
                                            <input type="number" name="collect_amount[]" id="collect_amount" class="form-control" placeholder="Collect Amount">
                                        </div>
                                        <div class="col-lg-4">
                                            <label for="remarks">Remarks</label>
                                            <input type="text" name="remarks[]" id="remarks" class="form-control" placeholder="Remarks">
                                        </div>
                                        <div class="col-lg-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger remove-entry d-none">Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-1 mt-3">
                                     <button type="button" class="btn btn-success mt-2" id="add-more-button">+ Add</button>
                                </div>
                               
                                
                                
                                <div class="col-md-6">
                                    <label for="consumer_investor_id">Consumer Investor<i class="text-danger">*</i></label>
                                    <select name="consumer_investor_id" id="consumer_investor_id"
                                        class="chosen-select form-control" onchange="generateCollectInvestCode()" required>
                                        <option value="">Select Investor</option>
                                        @foreach ($invests as $invest)
                                            <option value="{{ $invest->id }}"
                                                style="background-color: rgb(255, 193, 7); font-weight: bold;">
                                                {{ $invest->consumer_name }} ({{ $invest->invest_code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-6">
                                    <label for="collect_code">Invest Collect Code</label>
                                    <input type="text" class="form-control" name="collect_code" id="collect_code"
                                        readonly placeholder="Collect Invest Code No.">
                                </div>
                                <div class="col-lg-6">
                                    <label for="date">Collect Date<i class="text-danger">*</i></label>
                                    <input type="date" name="date" required class="form-control"
                                        value="{{ date('Y-m-d') }}" data-date-format="yyyy-mm-dd">
                                </div>
                                <div class="col-lg-6">
                                    <label for="due_amount">Due Amount (Missed month's)</label>
                                    <input type="text" name="due_amount" id="due_amount" class="form-control" readonly>
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

                                <div class="col-md-6 bank">
                                    <label for="">Bank<i class="text-danger">*</i></label>
                                    <select name="bank_id" id="bank_id" class="form-control bank bank_info"
                                        onchange="filterAccount()">
                                        <option value="">Select a Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 bank">
                                    <label for="account">Account<i class="text-danger">*</i></label>
                                    <select name="account_id" id="account_id" class="form-control bank bank_info">
                                        <option value="">Select An Account</option>
                                        @foreach ($accounts as $account)
                                            <option value="{{ $account->id }}">{{ $account->account_no }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="payment_type">Payment Type<i class="text-danger">*</i></label>
                                    <select class="form-control" name="payment_type" required>
                                        <option value="">Select One</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 bank">
                                    <label for="cheque_no">Cheque No.<i class="text-danger">*</i></label>
                                    <input type="number" name="cheque_no" class="form-control" />
                                </div>
                                <div class="col-lg-6 bank">
                                    <label for="check_issue_date">Cheque Issue Date<i class="text-danger">*</i></label>
                                    <input type="date" name="check_issue_date" class="form-control" />
                                </div>
                                <div class="col-lg-12">
                                    <label for="note">Note</label>
                                    <textarea name="note" cols="2" class="form-control" placeholder="Note..."></textarea>
                                </div>

                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success float-right"><i
                                            class="fa fa-check"></i>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
        $(".chosen-select").chosen();
        $(document).ready(function() {
            $('.bank').hide();

            // $('#consumer_investor_id').change(function() {
            //     var investId = $(this).val();
            //     if (investId) {
            //         $.ajax({
            //             type: 'GET',
            //             url: '/get-invest-amount/' + investId,
            //             success: function(data) {
            //                 $('#collect_amount').val(data.invest_amount);
            //                 $('#due_amount').val(data.due_amount);
            //             }
            //         });
            //     }
            // });

            $('#consumer_investor_id').change(function() {
                var investId = $(this).val();
                // var collectionMonth = $('#collection_month').val();


                if (investId) {
                    $.ajax({
                        type: 'GET',
                        url: '/get-due-amount-month-wise/' + investId,
                        success: function(data) {
                            $('#collect_amount').val(data
                                .invest_amount);
                            $('#due_amount').val(data.due_amount);
                        }
                    });
                }
            });



        });

        function showBankInfo() {
            var fund_id = document.getElementById('fund').value;
            console.log(fund_id);
            if (fund_id == 1) {
                $('.bank').show();
                $('.bank_info').prop('required', true);
            } else {
                $('.bank').hide();
                $('.bank_info').hide();
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

        function generateCollectInvestCode() {
            var invest_id = $('#consumer_investor_id').val();
            // console.log(invest_id);
            if (invest_id) {
                var lastCollectId = {{ $lastCollectId ?? 0 }};
                var nextCollectId = lastCollectId + 1;
                var investCollectCode = 'IR-' + nextCollectId;

                $('#collect_code').val(investCollectCode);

            } else {
                $('#collect_code').val('');
            }
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addMoreButton = document.getElementById('add-more-button');
            const container = document.getElementById('collection-container');
        
            function addNewCollectionEntry() {
                const newEntry = document.createElement('div');
                newEntry.classList.add('collection-entry', 'row', 'mb-2');
                
                newEntry.innerHTML = `
                    <div class="col-lg-3">
                        <input type="month" name="collect_month[]" required class="form-control" placeholder="Collection Month">
                    </div>
                    <div class="col-lg-3">
                        <input type="number" name="collect_amount[]" class="form-control" placeholder="Collect Amount">
                    </div>
                    <div class="col-lg-4">
                        <input type="text" name="remarks[]" class="form-control" placeholder="Remarks">
                    </div>
                    <div class="col-lg-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-entry">Remove</button>
                    </div>
                `;
                
                container.appendChild(newEntry);
            }
        
            addMoreButton.addEventListener('click', addNewCollectionEntry);
        
            container.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-entry')) {
                    e.target.closest('.collection-entry').remove();
                }
            });
        });
        </script>
        
@endpush
