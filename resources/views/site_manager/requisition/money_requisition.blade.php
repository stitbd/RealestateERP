@extends('layouts.app')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-material-design/4.1.1/css/bootstrap-material-design.min.css"
        rel="stylesheet">

    <div class="container mt-2">

        <style>
            .profile-tabs .nav-pills .nav-link {
                border-bottom: 2px solid transparent;
                color: black;
                background-color: transparent;
                padding: 10px 10px;
                border-radius: 0;
                position: relative;
                display: inline-block;
            }


            .profile-tabs .nav-pills .nav-link.active {
                border-bottom: 2px solid #0077ff;
            }

            .profile-tabs .nav-pills .nav-link i {
                margin-right: 4px;
            }
        </style>
        <div class="row" style="background: #dbeaf0; margin-left:2px; margin-right:2px;">
            <div class="col-md-12">

                <div class="profile-tabs">
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('money-requisition-list') ? 'active' : '' }}"
                                href="{{ route('money-requisition-list') }}" style="background: none; color: rgb(50, 53, 52); font-size:16.5px;">
                                <i class="fas fa-list" style="color:rgb(8, 103, 148)"></i>
                                <span class="d-none d-md-inline text-bold">All Requisition</span>
                            </a>
                        </li>

                        <!-- New Requisition Tab -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('money-requisition-entry') ? 'active' : '' }}"
                                href="{{ route('money-requisition-entry') }}" style="background: none; color: black; font-size:16.5px;">
                                <i class="fas fa-plus-circle" style="color:blue"></i>
                                <span class="d-none d-md-inline text-bold">New Requisition</span>
                            </a>
                        </li>

                        <!-- Approve Requisition Tab -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reject-requisition-list') ? 'active' : '' }}"
                                href="{{ route('reject-requisition-list') }}" style="background: none; color: black; font-size:16.5px;">
                                <i class="fas fa-check-circle" style="color:green"></i>
                                <span class="d-none d-md-inline text-bold">Approve Requisition</span>
                            </a>
                        </li>

                        <!-- Reject Requisition Tab -->
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('reject-requisition-list') ? 'active' : '' }}"
                                href="{{ route('reject-requisition-list') }}" style="background: none; color: black; font-size:16.5px;">
                                <i class="fas fa-times-circle" style="color:rgb(150, 10, 10)"></i>
                                <span class="d-none d-md-inline text-bold">Reject Requisition</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-2" id="">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <p class="text-bold h6" >
                            Money Requisition List
                        </p> 
                      
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('money-requisition-list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="start_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-info btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('money-requisition/site-print?start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <a href="{{ url('money-requisition/site-pdf?&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead class="bg-info">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                    <th>Approved Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $sl = 0;
                                    $total = 0;
                                    $approved_amount = 0;
                                @endphp

                                @foreach($all_requisition as $v_requisition)
                                    <tr>
                                        <td>{{++$sl }}</td>
                                        <td>{{ $v_requisition->requisition_date }}</td>
                                        <td>{{ $v_requisition->amount }}</td>
                                        <td>{{ $v_requisition->remarks }}</td>
                                        <td>
                                            @if ($v_requisition->status == '0')
                                                <span class="btn btn-sm btn-outline-warning">Pending</span>
                                            @elseif ($v_requisition->status == '1')
                                                <span class="btn btn-sm btn-outline-success">Approved</span>
                                            @elseif ($v_requisition->status == '2')
                                                <span class="btn btn-sm btn-outline-danger">Canceled</span>
                                            @endif
                                        </td>
                                        <td>@if ($v_requisition->status == '1')
                                            @php
                                                $approved_amount += $v_requisition->approved_amount;
                                            @endphp
                                                Approved by: <b class="text-info">Tk.  </b><br/>
                                                Approved Date: <b class="text-info">{{date('d/m/Y',strtotime($v_requisition->approved_date))}} </b><br/>
                                                Approved Amount: <b class="text-info">Tk. {{$v_requisition->approved_amount}}</b>
                                            @endif</td>
                                        <td>
                                            @if ($v_requisition->status == '1' && $v_requisition->amount == $v_requisition->approved_amount)
                                            <a class="btn btn-sm btn-success m-1" href=""><i class="fas fa-ruble-sign"></i> Approved</a>
                                            @endif

                                            @if ($v_requisition->status == '0')
                                                <button data-toggle="modal" onclick="load_edit_body('{{$v_requisition->id}}','{{$v_requisition->amount}}','{{$v_requisition->remarks}}')" data-target="#modal-edit" class="btn btn-sm btn-info"><i class="fas fa-edit"></i> Edit</button>
                                            @endif
    
                                        </td>
                                    </tr>
                                    @php
                                    $total += $v_requisition->amount;
                                @endphp
                                @endforeach
                                <tr>

                                    <th colspan="2">Total</th>
                                    <th>{{$total}}</th>
                                    <th></th>
                                    <th></th>
                                    <th>{{$approved_amount}}</th>
                                    <th></th>
                                </tr>


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
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="modal-edit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">Edit Money Requisition</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('money-requisition-update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="money_requisition_id">
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Requisition Amount</label>
                                    <input type="text" id="amount" name="amount" class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3">
                                    <label class="form-label">Remarks</label>
                                    <input type="text" id="remarks" name="remarks"
                                        class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    @push('script_js')
        <script>
            function load_edit_body(money_requisition_id, amount,remarks) {
                $('#money_requisition_id').val(money_requisition_id);
                $('#amount').val(amount);
                $('#remarks').val(remarks);
            }
        </script>

        <script></script>
    @endpush
@endsection
