@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        {{-- <h2 class="text-right mt-4 mb-5">Total Advance Expense : <b>{{$total_advance}}</b></h2> --}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-11">
                            Advance Cheque List
                        </h3>
                        <button class="text-end col-sm-1 btn btn-success btn-sm" data-toggle="modal"
                            data-target=".create_modal">+Add</button>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('advance-cheque') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="start_date">Status</label>
                                    <select name="status" id="" class="form-control">
                                        <option value="">Select One</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Paid</option>
                                        <option value="3">Replace</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date"/>
                                </div>
                                <div class="col-lg-3">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date"/>
                                </div>
                                
                                <div class="col-lg-3 mt-4">
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>SL No.</th>
                                    <th>Date</th>
                                    <th>Bank Name</th>
                                    <th>Account No</th>
                                    <th>Account Holder Name</th>
                                    <th>Cheque No.</th>
                                    <th>Cheque Isuue Date</th>
                                    <th>Benificiary</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php  $i = 0 ; @endphp
                            @foreach ($advance_cheques as $cheque)
                                <tr>
                                    <td> @php
                                        $i = ($advance_cheques instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($loop->iteration + ($advance_cheques->perPage() * ($advance_cheques->currentPage() - 1)))  : ++$i;
                                    @endphp {{$i}}
                                    </td>
                                    <td>@if($cheque->date){{date('d/m/Y',strtotime($cheque->date))}} @endif</td>
                                    <td>{{$cheque->bank->name}}</td>
                                    <td>{{$cheque->account->account_no}}</td>
                                    <td>{{$cheque->account_holder}}</td>
                                    <td>{{$cheque->cheque_no}}</td>
                                    <td>@if($cheque->cheque_issue_date) {{date('d/m/Y',strtotime($cheque->cheque_issue_date))}} @endif</td>
                                    <td>{{$cheque->benificiary}}</td>
                                    <td>{{$cheque->amount}}</td>
                                    <td>{{$cheque->remarks}}</td>
                                    <td>@if($cheque->status == 1) <b style="color: green">Pending</b> @elseif($cheque->status == 2) <b style="color: red">Paid</b> @elseif($cheque->status == 3) Replace @endif </td>
                                    <td> 
                                        <a href="{{route('cheque.status.update',$cheque->id)}}" onclick="deleteData({{$cheque->id}})" 
                                            style="padding: 2px; margin-left: 3px;" class="delete btn btn-xs btn-danger btn-sm mr-1 text-white">Reject</a>
                                    </td>
                                </tr>
                                {{-- <div class="modal fade update update-modal-{{$expense->id}}" id="exampleModal"
                                    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header bg-info text-center">
                                                <h5 >Edit Data</h5>
                                                <button type="button" class="close"
                                                data-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="{{route('advance-expense-update',$expense->id)}}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="modal-body"> 
                                                   <div class="form-group row pt-3">
                                                        <label for="details" class="col-sm-3 col-form-label">Expense Details</label>
                                                        <label for="" class="col-sm-1 col-form-label">:</label>
                                                        <div class="col-sm-8">
                                                            <input name="details" type="text" class="form-control" placeholder="Type a Note.." value="{{$expense->details}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row pt-3">
                                                        <label for="category" class="col-sm-3 col-form-label">Amount</label>
                                                        <label for="" class="col-sm-1 col-form-label">:</label>
                                                        <div class="col-sm-8">
                                                            <input type="number" name="amount" id="" class="form-control" value="{{$expense->amount}}">
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
                                </div> --}}
                            @endforeach
                        </tbody>
                        </table>
                        <div class="row pt-3">
                        <div class="col-lg-12">
                            @if($advance_cheques instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{$advance_cheques->links();}}
                          @endif
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
                <div class="modal-header bg-info text-center">
                    <h5>Add Advance Cheque</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ route('advance-cheque-store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6 pt-3">
                                <label for="details" class="col-form-label">Date</label>
                                <label for="" class="col-form-label">:</label>
                                <input name="date" type="date" class="form-control" placeholder="" >
                            </div>
                            <div class="col-sm-6 pt-3">
                                <label for="mobile_no" class="col-form-label">Bank<i class="text-danger">*</i></label>
                                <label for="" class="col-form-label">:</label>
                                <select name="bank_id" id="bank_id" class="form-control form-select chosen-select"
                                    onchange="filterAccount()" required>
                                    <option value="" selected>Select Bank</option>
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 pt-3">
                                <label for="mobile_no" class="">Bank Account<i class="text-danger">*</i></label>
                                <label for="" class="">:</label>
                                <select name="account_id" id="account" class="form-control form-select chosen-select"
                                    onchange="accountHolderName(this)" required>
                                    <option value="" selected>Select a Bank Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-6 pt-3">
                                <label for="category" class="">Account Holder Name <i
                                        class="text-danger">*</i></label>
                                <label for="" class="">:</label>
                                <input type="text" name="account_holder" id="account_holder" class="form-control"
                                    placeholder="Type Account Holder Name..." required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 pt-3">
                                <label for="category" class="">Cheque Number</label>
                                <label for="" class="">:</label>
                                <input type="text" name="cheque_no" id="cheque_no" class="form-control"
                                    placeholder="Type Cheque Number...">
                            </div>
                            <div class="col-sm-6 pt-3">
                                <label for="category" class="">Cheque Issue Date</label>
                                <label for="" class="">:</label>
                                <input type="date" name="cheque_issue_date" id="cheque_issue_date" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6 pt-3">
                                <label for="category" class="">Benificiary</label>
                                <label for="" class="">:</label>
                                <input type="text" name="benificiary" id="benificiary" class="form-control"
                                    placeholder="Type Benificiary">
                            </div>
                            <div class="col-sm-6 pt-3">
                                <label for="category" class="">Amount <i class="text-danger">*</i></label>
                                <label for="" class="">:</label>
                                <input type="number" name="amount" id="" class="form-control" required
                                    placeholder="Type Amount">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 pt-3">
                                <label for="category" class="">Remarks</label>
                                <label for="" class="">:</label>
                                <input type="text" name="remarks" id="" class="form-control"
                                    placeholder="Type Remarks">
                            </div>
                            <div class="col-sm-6 pt-3">
                                <label for="category" class="">Status</label>
                                <label for="" class="">:</label>
                                <select name="status" id="" class="form-control">
                                    <option value="1">Unpaid </option>
                                    <option value="3">Replace </option>
                                </select>
                            </div>
                        </div>
                        {{-- <div class="row pt-3">
                            <div class="col-sm-6">
                                <label for="category" class="">Attachment (If Any)</label>
                                <label for="" class="">:</label>
                                <input type="file" name="attachment" id="" class="">
                            </div>
                            
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- @foreach ($head as $c_head)

@endforeach  --}}
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    function filterAccount() {
        var bank_id = document.getElementById('bank_id').value;
        var url = "{{ route('filter-account') }}";
        $.ajax({
            type: "GET",
            url: url,
            data: {
                bank_id
            },
            success: function(data) {
                $('#account').find('option').remove();
                $('#account').html('<option value="">Select One</option>');
                $.each(data, function(key, value) {
                    $('#account').append('<option value="' + value.id + '">' + value.account_no +
                        '</option>');
                });
                $('#account').trigger("chosen:updated");
            },
        });
    }

    function accountHolderName() {
        var account_id = document.getElementById('account').value;
        var url = "{{ route('account-holder') }}";
        $.ajax({
            type: "GET",
            url: url,
            data: {
                account_id
            },
            success: function(data) {
                document.getElementById('account_holder').value = data
            },
        });
    }
</script>
<script>
    //    function deleteData(id) {
    //     if (confirm('Are you sure you want to reject this cheque?')) {
    //         $.ajax({
    //             url: '/checkstatus/' + id,
    //             type: 'DELETE',
    //             data: {
    //                 _token: '{{ csrf_token() }}'
    //             },
    //             success: function(response) {
    //                 var table = $('#advanceTable');
    //                     var rowToDelete = table.row($('.delete[data-id="' + id + '"]').closest('tr')); // Target specific row using delete button (if applicable)

    //                     if (rowToDelete.length) { // Check if row exists before removal
    //                         rowToDelete.remove().draw();
    //                     } else {
    //                         // Handle potential row not found scenario (optional)
    //                         console.warn('Row with ID ' + id + ' not found in DataTable');
    //                     }
    //             },
    //             error: function(xhr) {
    //                 console.error('Error deleting data:', xhr.responseText);
    //             }
    //         });
    //     }
    // }
</script>
