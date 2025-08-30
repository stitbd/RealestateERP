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
                                    +Transfer Capital
                                </button>

                            </div>
                        </div>

                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>From Head ID</th>
                                    <th>To Head ID</th>
                                    <th>Transfer Amount</th>
                                    <th>Transfer Date</th>
                                    <th>Remarks</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php $i = 0; @endphp
                                @foreach ($transferData as $v_data)
                                    <tr>
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $v_data->fromHead ? $v_data->fromHead->head_name :'' }}</td>
                                        <td>{{ $v_data->toHead ? $v_data->toHead->head_name : '' }}</td>
                                        <td>{{ $v_data->amount }}</td>
                                        <td>{{ date('d-m-Y', strtotime($v_data->date)) }}</td>
                                        <td>{{$v_data->remarks}}</td>

                                {{-- <td>
                                <a data-toggle="modal"
                                    data-target=".update-modal-{{$v_capital->id}}"
                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                   <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{route('category-delete',$v_capital->id)}}"
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

    <div class="modal fade create_modal" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModallabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-center">
                    <h5>Transfer Capital</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('share-transfer') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- From Account Head -->
                                <div class="form-group col-6">
                                    <label for="fromHeadId">From Account Head</label>
                                    <select name="from_head_id" id="fromHeadId" class="form-control select2" required>
                                        <option value="" disabled selected>Select Account Head</option>
                                        @foreach ($accountHeads as $head)
                                            <option value="{{ $head->id }}">{{ $head->head_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- To Account Head -->
                                <div class="form-group col-6">
                                    <label for="toHeadId">To Account Head</label>
                                    <select name="to_head_id" id="toHeadId" class="form-control select2" required>
                                        <option value="" disabled selected>Select Account Head</option>
                                        @foreach ($accountHeads as $head)
                                            <option value="{{ $head->id }}">{{ $head->head_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- <div class="row">
                                <!-- From Capital -->
                                <div class="form-group col-6">
                                    <label for="fromCapitalId">From Capital</label>
                                    <select name="from_capital_id" id="fromCapitalId" class="form-control" required>
                                        <option value="" disabled selected>Select Shareholder</option>
                                        @foreach ($capitals as $capital)
                                            <option value="{{ $capital->id }}">{{ $capital->shareholder_name }}
                                                ({{ $capital->capital_amount }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- To Capital -->
                                <div class="form-group col-6">
                                    <label for="toCapitalId">To Capital</label>
                                    <select name="to_capital_id" id="toCapitalId" class="form-control" required>
                                        <option value="" disabled selected>Select Shareholder</option>
                                        @foreach ($capitals as $capital)
                                            <option value="{{ $capital->id }}">{{ $capital->shareholder_name }}
                                                ({{ $capital->capital_amount }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}

                            <div class="row">
                                <!-- Transfer Amount -->
                                <div class="form-group col-6">
                                    <label for="amount">Transfer Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                                        placeholder="Enter amount" required>
                                </div>

                                <!-- Transfer Date -->
                                <div class="form-group col-6">
                                    <label for="date">Transfer Date</label>
                                    <input type="date" name="date" id="date" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Remarks -->
                                <div class="form-group col-6">
                                    <label for="remarks">Remarks</label>
                                    <textarea name="remarks" id="remarks" class="form-control" placeholder="Optional remarks"></textarea>
                                </div>

                                <!-- Attachment -->
                                <div class="form-group col-6">
                                    <label for="attachment">Attachment</label>
                                    <input type="file" name="attachment" id="attachment" class="form-control-file">
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


    {{-- @foreach ($capitals as $capital)
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
    @endforeach --}}
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
