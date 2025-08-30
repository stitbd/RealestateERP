@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title col-sm-10">
                            Consumer Investors List
                        </h3>
                        <button class="text-end col-sm-2 btn btn-success btn-sm" data-toggle="modal" data-target="#modal-add">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add
                        </button>
                    </div> <!-- /.card-body -->
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr class="bg-info text-center">
                                    <th>SL</th>
                                    <th>Consumer Name</th>
                                    <th>Code</th>
                                    <th>Invest Amount</th>
                                    <th>Investment Length</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $serial = 0;
                                @endphp
                                @foreach ($consumer_investors as $consumer_investor)
                                    <tr>
                                        <td class="text-center">{{ ++$serial }}</td>
                                        <td class="text-center">{{ $consumer_investor->name ?? '' }}</td>
                                        <td class="text-center">{{ $consumer_investor->code ?? '' }}</td>
                                        <td class="text-center">{{ $consumer_investor->invest_amount ?? '' }}</td>
                                        <td class="text-center">{{ $consumer_investor->invest_validity ?? '' }}</td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $consumer_investor->id }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a data-toggle="modal" data-target=".update-modal-{{ $consumer_investor->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a data-toggle="modal"
                                                    data-target=".invest-payment-modal-{{ $consumer_investor->id }}"
                                                    style="padding:2px; color:white" class="btn btn-xs btn-primary  mr-1">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>

                                            @if ($consumer_investor->status == 1)
                                                <a class=" btn btn-xs btn-success  mr-1"
                                                    href="{{ route('updateConsumerStatus', $consumer_investor->id) }}"
                                                    style="padding: 2px; color: white;">
                                                    <i class="fas fa-arrow-up"></i>
                                                </a>
                                            @else
                                                <a class=" btn btn-xs btn-danger mr-1"
                                                    href="{{ route('updateConsumerStatus', $consumer_investor->id) }}"
                                                    style="padding: 2px; color: white;">
                                                    <i class="fas fa-arrow-down"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="modal-add">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Add Consumer</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('save_consumer_investors') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Name<i class="text-danger">*</i></label>
                                    <input type="text" class="form-control" name="name" placeholder="Consumer Name"
                                        required>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Email<i class="text-danger">*</i></label>
                                    <input type="email" class="form-control" name="email" placeholder="Consumer Email"
                                        required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="phone"
                                        placeholder="Consumer Phone no." required>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Address<i class="text-danger">*</i></label>
                                    <textarea type="text" cols="3" class="form-control" name="address" placeholder="Address.."></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Invest Amount<i class="text-danger">*</i></label>
                                    <input type="number" class="form-control" name="invest_amount"
                                        placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label class="form-label">Investment Length<i class="text-danger">*</i></label>
                                    <input type="date" class="form-control" name="invest_validity" required>
                                </div>
                            </div>
                            
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

    <!-- Edit Modal -->
    @foreach ($consumer_investors as $consumer_investor)
        {{-- @dd($consumer_investor) --}}
        <div class="modal fade update update-modal-{{ $consumer_investor->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Update Consumer</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="{{ route('update_consumer_investors', $consumer_investor->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Name<i class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="name" placeholder="Consumer Name" value="{{$consumer_investor->name}}"
                                            required>
                                    </div>
                                </div>
    
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email<i class="text-danger">*</i></label>
                                        <input type="email" class="form-control" name="email" placeholder="Consumer Email" value="{{$consumer_investor->email}}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" name="phone" value="{{$consumer_investor->phone}}"
                                            placeholder="Consumer Phone no." required>
                                    </div>
                                </div>
    
    
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label">Address<i class="text-danger">*</i></label>
                                        <textarea type="text" cols="3" class="form-control" name="address" placeholder="Address..">{{$consumer_investor->address}}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Invest Amount<i class="text-danger">*</i></label>
                                        <input type="number" class="form-control" name="invest_amount" value="{{$consumer_investor->invest_amount}}"
                                            placeholder="Amount" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-3">
                                        <label class="form-label">Investment Length<i class="text-danger">*</i></label>
                                        <input type="date" class="form-control" name="invest_validity" required value="{{$consumer_investor->invest_validity}}">
                                    </div>
                                </div>
                                
                                <div class="modal-footer">
                                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> --}}
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <!--Invest Payment Modal -->
    @foreach ($consumer_investors as $item)
        {{-- @dd($item) --}}
        <div class="modal fade invest-payment-modal-{{ $item->id }}" id="exampleModal-{{ $item->id }}"
            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center d-flex justify-content-between align-items-center">
                            <h5>Collect Invest Amount</h5>
                        <div>
                            <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <div class="row">
                                <!-- Existing form content -->
                         
                                    <div class="col-md-12">
                                        <label class="form-label">Consumer:<span style="color: red;">
                                                {{ $item->name ?? '' }} ({{ $item->code }})</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Amount To Pay:<span style="color: red;">
                                                {{ $item->invest_amount ?? '' }} Tk.</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Investment Length: <span
                                                style="color: red;">{{ $item->invest_validity ?? '' }}</span>
                                        </label>
                                    </div>                     
                            </div>
                        </div>
                        <hr>
                    </div>
                    <form action="{{ route('store_collect_invest') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" value="{{ $item->id }}" name="consumer_investor_id">
                                    <div class="col-md-12">
                                        <label for="collect_amount">Collect Amount(Tk.)</label>
                                        <input type="number" id="collect_amount-{{ $item->id }}" name="collect_amount"
                                            class="form-control" placeholder="" required data-invest-amount="{{ $item->invest_amount }}">
                                    </div>
                
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="fund">Fund<i class="text-danger">*</i></label>
                                            <select name="fund_id" id="fund-{{ $item->id }}" class="form-control"
                                                required onchange="showBankInfo({{ $item->id }})">
                                                <option value="">Select a Fund </option>
                                                @foreach ($fund_types as $fund)
                                                    <option value="{{ $fund->id }}">{{ $fund->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 bank-{{ $item->id }}" style="display: none;">
                                            <label for="">Bank <i class="text-danger">*</i></label>
                                            <select name="bank_id" id="bank_id-{{ $item->id }}" class="form-control"
                                                onchange="filterAccount({{ $item->id }})">
                                                <option value="">Select a Bank</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->id }}">{{ $bank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 bank-{{ $item->id }}" style="display: none;">
                                            <label for="account">Account <i class="text-danger">*</i></label>
                                            <select name="account_id" id="account_id-{{ $item->id }}"
                                                class="form-control" onchange="showAccountBranch({{ $item->id }})">
                                                <option value="">Select An Account</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">
                                                        {{ $account->account_no }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 bank-{{ $item->id }}" style="display: none;">
                                            <label for="branch">Branch <i class="text-danger">*</i></label>
                                            <input type="text" id="branch-{{ $item->id }}" class="form-control">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="payment_type_id">Payment Method<i
                                                    class="text-danger">*</i></label>
                                            <select name="payment_type_id" id="payment_type_id-{{ $item->id }}"
                                                required class="form-control">
                                                <option value="">Select a Method</option>
                                                @foreach ($payment_types as $payment_type)
                                                    <option value="{{ $payment_type->id }}">
                                                        {{ $payment_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="check_no">Cash/ Cheque/ Pay Order No.
                                                <i class="text-danger">*</i></label>
                                            <input type="text" name="check_no" class="form-control"
                                                id="check_no-{{ $item->id }}" placeholder=""
                                                required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="date">Collect Date<i class="text-danger">*</i></label>
                                            <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                                class="form-control" placeholder="" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="note">Note</label>
                                            <input type="text" name="note"
                                                class="form-control" placeholder="Note (if any)">
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->

    <!-- View Modal -->
    {{-- @foreach ($plots as $plot)
        <div class="modal fade view-modal-{{ $plot->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-m">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>View Plot</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Plot No: </th>
                                    <td>{{ $plot->plot_no }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Project: </th>
                                    <td>
                                        {{ $plot->sector->project->name ?? '' }}
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Sector No: </th>
                                    <td>
                                        {{ $plot->sector->sector_name ?? '' }}
                                    </td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Road No: </th>
                                    <td>{{ $plot->road->road_name ?? '' }}</td>
                                </tr>
                            </table>
                            <table class="table table-sm">
                                <tr>
                                    <th width="20%">Block No: </th>
                                    <td>{{ $plot->block_no ?? '' }}</td>
                                </tr>
                            </table>

                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Measurement: </th>
                                    <td>{{ $plot->measurement ?? '' }}</td>
                                </tr>

                            </table>
                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Facing: </th>
                                    <td>{{ $plot->facing }}</td>
                                </tr>

                            </table>
                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Plot Size: </th>
                                    <td>{{ $plot->plot_size }}</td>
                                </tr>

                            </table>
                            <table class="table table-sm">

                                <tr>
                                    <th width="20%">Plot Type: </th>
                                    <td>{{ $plot->plot_type }}</td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach --}}
    <!-- /.modal -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.modal').on('show.bs.modal', function(event) {
            var modal = $(this);
            var itemId = modal.attr('id').split('-')[1];
            // console.log(itemId);
            var collectAmount = modal.find(`input[data-invest-amount]`).data('invest-amount');

            modal.find(`#collect_amount-${itemId}`).val(collectAmount);
        });
    });

    $(document).ready(function() {
            $('.bank').hide();
        });

        function showBankInfo(id) {
            var fund_id = document.getElementById('fund-' + id).value;
            console.log(fund_id);
            if (fund_id == 1) {
                $('.bank-' + id).show();
                $('#bank_id-' + id).prop('required', true);
                $('#account_id-' + id).prop('required', true);
            } else {
                $('.bank-' + id).hide();
            }

        }

        function filterAccount(id) {
            var bank_id = document.getElementById('bank_id-' + id).value;
            var url = "{{ route('filter-bank-fund') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#account_id-' + id).html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account_id-' + id).append('<option value="' + value.id + '">' + value
                            .account_no +
                            '</option>');
                    });
                },
            });
        }

        function showAccountBranch(id) {
            var account_id = document.getElementById('account_id-' + id).value;
            console.log(account_id);
            var url = "{{ route('getAccountBranch') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    account_id: account_id
                },
                dataType: "json",
                success: function(response) {
                    if (response) {
                        $('#branch-' + id).val(response.branch);
                    } else {
                        alert('No data found for the selected account.');
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the account data.');
                }
            });
        }

</script>
