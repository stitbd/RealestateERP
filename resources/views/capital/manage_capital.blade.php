@extends('layouts.app')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
        padding-left: 15px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        background: transparent;
        color: white;
    }
</style>
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title">Capital List</h3>
                            <div class="d-flex gap-2">
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModal">
                                    +Add Initial Capital
                                </button>
                                <button class="btn btn-primary btn-sm ml-2" data-toggle="modal"
                                    data-target="#addIncomeCapitalModal">
                                    +Add Contribution
                                </button>
                                <button class="btn btn-warning btn-sm ml-2" data-toggle="modal"
                                    data-target="#withdrawModal">
                                    -Withdraw
                                </button>

                                <!-- Print button -->
                                <button onclick="printCapitalTable()" class="btn btn-danger btn-sm ml-2">
                                    <i class="fas fa-print"></i> Print Capital Report
                                </button>
                            </div>
                        </div>

                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <table id="CapitalTable" class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    {{-- <th>Name</th> --}}
                                    <th>Unique ID</th>
                                    <th>Account Main Head</th>
                                    <th>Initial Capital Amount</th>
                                    <th>Total Contribution</th>
                                    <th>Total Withdraws</th>
                                    <th>Total Transfer</th>
                                    <th>Current Capital Amount</th>
                                    {{-- <th>Status</th> --}}
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($capitals as $v_capital)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        {{-- <td>{{ $v_capital->name }}</td> --}}
                                        <td>{{ $v_capital->head ? $v_capital->head->head_name : '' }}</td>
                                        <td>{{ $v_capital->head && $v_capital->head->category ? $v_capital->head->category->category_name : '' }}
                                        </td>
                                        <td>{{ $v_capital->initial_capital_amount }}</td>
                                        <td>{{ $v_capital->total_contribution }}</td>
                                        <td>{{ $v_capital->total_withdraw }}</td>
                                        <td>{{ $v_capital->total_transfer }}</td>
                                        <td>{{ $v_capital->current_capital_amount }}</td>
                                        {{-- <td>{{ $v_capital->date }}</td> --}}
                                        {{-- <td>
                                <a data-toggle="modal"
                                    data-target=".update-modal-{{$v_capital->id}}"
                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                   <i class="fas fa-edit"></i>
                                </a>
                                {{-- <a href="{{route('category-delete',$v_capital->id)}}"
                                    onclick="return confirm('Are you sure you want to delete?');"
                                    style="padding: 2px;" class="delete btn btn-xs btn-danger  mr-1">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade create_modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-center">
                    <h5>Add Initial Capital</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('capital-store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group row pt-3">
                            <label for="category_name" class="col-sm-3 col-form-label">Select Account Main Head</label>
                            <label for="" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-8">
                                <select name="main_head" id="main_head_initial" class="form-control select2" width="100%"
                                    onchange="loadHeads(this.value, 'sub_head_initial')">
                                    <option value="">Select One</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <label for="category_name" class="col-sm-3 col-form-label">Select Head</label>
                            <label for="" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-8">
                                <select name="head_id" id="sub_head_initial" class="form-control select2" width="100%">
                                    <option value="">Select One</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row pt-3">
                            <label for="category_name" class="col-sm-3 col-form-label">Capital Amount</label>
                            <label for="" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-8">
                                <input type="text" name="capital_amount" class="form-control">
                            </div>
                        </div>
                        <div class="form-group row pt-3">
                            <label for="category_name" class="col-sm-3 col-form-label">Entry Date</label>
                            <label for="" class="col-sm-1 col-form-label">:</label>
                            <div class="col-sm-8">
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade create_modal" id="addIncomeCapitalModal" tabindex="-1" role="dialog"
        aria-labelledby="addIncomeCapitalModallabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-center">
                    <h5>Add Contribution </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('share-income') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group pt-3">
                            <label for="main_head" class="form-label">Account Main Head <span
                                    class="text-danger">*</span>:</label>
                            <select name="main_head" id="main_head_contribution" class="form-control select2"
                                onchange="loadHeads(this.value, 'head_id_contribution')" required>
                                <option value="">Select One</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group pt-3">
                            <label for="head_id" class="form-label">Select Shareholder Head <span
                                    class="text-danger">*</span>:</label>
                            <select name="head_id" id="head_id_contribution" class="form-control select2" required>
                                <option value="">Select One</option>
                            </select>
                        </div>

                        <!-- Fund and Payment Type -->
                        <div class="row pt-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="fund_id" class="form-label">Fund <span
                                            class="text-danger">*</span>:</label>
                                    <select name="fund_id" id="fund_id" class="form-control"
                                        onchange="toggleBankInfo()" required>
                                        <option value="">Select Fund</option>
                                        @foreach ($funds as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="payment_type" class="form-label">Payment Type <span
                                            class="text-danger">*</span>:</label>
                                    <select name="payment_type" id="payment_type" class="form-control" required>
                                        <option value="">Select One</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Name and Bank Account -->
                        <div id="bank_info" style="display: none;">
                            <div class="row pt-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bank_id" class="form-label">Bank Name:</label>
                                        <select name="bank_id" id="bank_id" class="form-control"
                                            onchange="filterAccount()">
                                            <option value="">Select A Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="account_id" class="form-label">Bank Account:</label>
                                        <select name="account_id" id="account_id" class="form-control">
                                            <option value="">Select An Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label for="cheque_no">Cheque Number</label>
                                    <input type="text" name="cheque_no" class="form-control" id="cheque1"
                                        placeholder="Cheque No." />
                                </div>

                                <div class="col-lg-6  mt-3">
                                    <label for="cheque_issue_date">Cheque Issue Date</label>
                                    <input type="date" name="cheque_issue_date" class="form-control"
                                        id="cheque2" />
                                </div>
                            </div>
                        </div>

                        <!-- Capital Amount and Investment Date -->
                        <div class="row pt-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">Amount: <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="amount" id="amount" class="form-control"
                                        placeholder="Enter amount">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date: <span
                                            class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Particulars : <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="remarks" id="remarks" class="form-control"
                                        placeholder="Enter Particulars">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Attachment :</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control"
                                        placeholder="Enter Particulars">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="modal fade create_modal" id="withdrawModal" tabindex="-1" role="dialog"
        aria-labelledby="withdrawModalModallabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-warning text-center">
                    <h5>Withdraw</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('share-withdraw') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group pt-3">
                            <label for="main_head" class="form-label">Account Main Head <span
                                    class="text-danger">*</span>:</label>
                            <select name="main_head" id="main_head_withdraw" class="form-control select2"
                                onchange="loadHeads(this.value, 'head_id_withdraw')" required>
                                <option value="">Select One</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group pt-3">
                            <label for="head_id" class="form-label">Select Shareholder Head <span
                                    class="text-danger">*</span>:</label>
                            <select name="head_id" id="head_id_withdraw" class="form-control select2" required>
                                <option value="">Select One</option>
                            </select>
                        </div>

                        <!-- Fund and Payment Type -->
                        <div class="row pt-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="fund_id" class="form-label">Fund <span
                                            class="text-danger">*</span>:</label>
                                    <select name="fund_id" id="fund_id" class="form-control"
                                        onchange="toggleBankInfo()" required>
                                        <option value="">Select Fund</option>
                                        @foreach ($funds as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="payment_type" class="form-label">Payment Type <span
                                            class="text-danger">*</span>:</label>
                                    <select name="payment_type" id="payment_type" class="form-control" required>
                                        <option value="">Select One</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Bank">Bank</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Bank Name and Bank Account -->
                        <div id="bank_info" style="display: none;">
                            <div class="row pt-3">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="bank_id" class="form-label">Bank Name:</label>
                                        <select name="bank_id" id="bank_id" class="form-control"
                                            onchange="filterAccount()">
                                            <option value="">Select A Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="account_id" class="form-label">Bank Account:</label>
                                        <select name="account_id" id="account_id" class="form-control">
                                            <option value="">Select An Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 mt-3">
                                    <label for="cheque_no">Cheque Number</label>
                                    <input type="text" name="cheque_no" class="form-control" id="cheque1"
                                        placeholder="Cheque No." />
                                </div>

                                <div class="col-lg-6  mt-3">
                                    <label for="cheque_issue_date">Cheque Issue Date</label>
                                    <input type="date" name="cheque_issue_date" class="form-control"
                                        id="cheque2" />
                                </div>
                            </div>
                        </div>

                        <!-- Capital Amount and Investment Date -->
                        <div class="row pt-3">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="amount" class="form-label">Amount:</label>
                                    <input type="text" name="amount" id="amount" class="form-control"
                                        placeholder="Enter amount">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Particulars :</label>
                                    <input type="text" name="remarks" id="remarks" class="form-control"
                                        placeholder="Enter Particulars">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="remarks" class="form-label">Attachment :</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control"
                                        placeholder="Enter Particulars">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @foreach ($capitals as $capital)
        <div class="modal fade update update-modal-{{ $capital->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Edit Capital Information</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('capital-update', $capital->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group row pt-3">
                                <label for="category_name" class="col-sm-3 col-form-label">Category Name</label>
                                <label for="" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-8">
                                    <select name="category" id="" class="form-control">
                                        @foreach ($categories as $v_category)
                                            <option value="{{ $v_category->id }}"
                                                @if ($v_category->id == $capital->category_id) selected @endif>
                                                {{ $v_category->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row pt-3">
                                <label for="category_name" class="col-sm-3 col-form-label">Capital Amount</label>
                                <label for="" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-8">
                                    <input type="text" name="edit_capital_amount" class="form-control"
                                        value="{{ $capital->capital_amount }}">
                                </div>
                            </div>
                            <div class="form-group row pt-3">
                                <label for="category_name" class="col-sm-3 col-form-label">Date</label>
                                <label for="" class="col-sm-1 col-form-label">:</label>
                                <div class="col-sm-8">
                                    <input type="date" name="date" class="form-control"
                                        value="{{ $capital->date }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js" defer></script>
<script>
    function getCategoryID() {
        var head_id = document.getElementById('head_id').value;
        var category_id = $('#head_id option:selected').attr('data-category-id');
        document.getElementById('category_id').value = category_id;
    }


    $(document).ready(function() {
        $('.create_modal').on('shown.bs.modal', function() {
            $('.select2').select2({
                width: '100%'
            });

            $('.js-example-basic-multiple').select2();
        });
        $('.update').on('shown.bs.modal', function() {
            $('.js-example-basic-multiple').select2();
        });
    });

    function filterAccount() {
        var bank_id = document.getElementById('bank_id').value;
        var url = "{{ route('filter-bank-account') }}";
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
<script>
    function toggleBankInfo() {
        var fundId = document.getElementById('fund_id').value;
        var bankInfo = document.getElementById('bank_info');
        if (fundId == 1) {
            bankInfo.style.display = 'block';
        } else {
            bankInfo.style.display = 'none';
        }
    }

    // Call the function on page load to set the initial state
    document.addEventListener('DOMContentLoaded', function() {
        toggleBankInfo();
    });
</script>

{{-- model Select Account Main Head --}}
<script>
    // ফাংশনটি হেডগুলো লোড করবে - সকল মডেলের জন্য কাজ করবে
    function loadHeads(categoryId, targetElementId = 'head_id') {
        if (!categoryId) {
            $('#' + targetElementId).html('<option value="">Select One</option>');
            return;
        }

        $.ajax({
            url: '/get-heads-by-category',
            type: 'GET',
            data: {
                category_id: categoryId
            },
            success: function(response) {
                var options = '<option value="">Select One</option>';
                response.heads.forEach(function(head) {
                    options += '<option value="' + head.id + '" data-category-id="' + head
                        .category_id + '">' + head.head_name + '</option>';
                });
                $('#' + targetElementId).html(options);

                // Select2 ইনিশিয়ালাইজ করুন যদি ব্যবহার করা হয়
                if ($('#' + targetElementId).hasClass('select2')) {
                    $('#' + targetElementId).trigger('change');
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    // Payment type পরিবর্তন হলে ব্যাংক তথ্য দেখাবে/লুকাবে
    function toggleBankInfo() {
        var paymentType = $('#payment_type').val();
        if (paymentType === 'Cheque' || paymentType === 'Bank') {
            $('#bank_info').show();
        } else {
            $('#bank_info').hide();
        }
    }

    // ব্যাংক নির্বাচন করলে অ্যাকাউন্ট ফিল্টার করবে
    function filterAccount() {
        var bankId = $('#bank_id').val();
        if (!bankId) {
            $('#account_id').html('<option value="">Select An Account</option>');
            return;
        }

        $.ajax({
            url: '/get-accounts-by-bank',
            type: 'GET',
            data: {
                bank_id: bankId
            },
            success: function(response) {
                var options = '<option value="">Select An Account</option>';
                response.accounts.forEach(function(account) {
                    options += '<option value="' + account.id + '">' + account.account_no +
                        '</option>';
                });
                $('#account_id').html(options);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
            }
        });
    }

    // DOM লোড হলে ইভেন্ট লিসেনার যোগ করুন
    $(document).ready(function() {
        // Payment type change event for all modals
        $('body').on('change', '#payment_type', toggleBankInfo);

        // Bank change event for all modals
        $('body').on('change', '#bank_id', filterAccount);

        // Initialize select2 on all select elements
        $('.select2').select2();
    });
</script>

<!-- jQuery এবং DataTables স্ক্রিপ্ট যোগ করুন -->
<script>
    $(document).ready(function() {
        var table = $('#CapitalTable').DataTable({
            "paging": true,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            "searching": true,
            "responsive": true,
            "order": [
                [5, "asc"]
            ], // TDC কলাম সর্টিং
            "columnDefs": [{
                    "orderable": false,
                    "targets": 0
                }, // SL কলাম সর্ট অফ
                {
                    "orderable": true,
                    "targets": 5
                } // TDC কলাম সর্ট অন
            ]
        });

        // পেজিনেশন বা সর্ট করলে সিরিয়াল নম্বর আপডেট হবে না
        table.on('order.dt search.dt', function() {
            table.column(0, {
                search: 'applied',
                order: 'applied'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1;
            });
        }).draw();
    });
</script>

<!-- প্রিন্ট ফাংশন -->
<script>
    function printCapitalTable() {
        var printContents = document.getElementById('CapitalTable').outerHTML;
        var today = new Date();
        var formattedDate = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();

        // You can customize the company name here
        var companyName = "Capital Report";

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; margin: 20px; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        printWindow.document.write('th { background-color: #17a2b8; color: white; }');
        printWindow.document.write('.header { text-align: center; margin-bottom: 20px; }');
        printWindow.document.write('tr:nth-child(even) { background-color: #f2f2f2; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write('<div class="header">');
        printWindow.document.write('<h2>' + companyName + '</h2>');
        printWindow.document.write('<p>Date: ' + formattedDate + '</p>');
        printWindow.document.write('</div>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>
