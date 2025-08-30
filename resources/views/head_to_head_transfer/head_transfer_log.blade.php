@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title col-sm-11">
                        Head To Head Log
                    </h3>
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>#</th>
                                <th>Transaction Date</th>
                                <th>From Head</th>
                                <th>From Fund</th>
                                <th>Fund Name</th>
                                <th>Account No.</th>
                                {{-- <th>Current Balance</th> --}}
                                <th>Transaction Amount</th>
                                <th>To Head</th>
                                <th>To Fund</th>
                                <th>Fund Name</th>
                                <th>Account No.</th>
                                {{-- <th>Current Balance</th> --}}
                                <th>Transfered By</th>
                                <th>Particulars</th>
                                <th>Attachment</th>
                                  <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @php $sl = 0; @endphp
                           @foreach($head_transfer as $transfer)
                            <tr>
                                <td>{{++$sl}}</td>
                                <td>{{$transfer->transaction_date}}</td>
                                <td>{{$transfer->from_head->head_name}}</td>
                                <td>{{$transfer->from_fund->fund->name}}</td>
                                <td>@if($transfer->from_bank_id != null){{$transfer->from_bank->name}}@else Cash @endif</td>
                                <td>@if($transfer->from_acc_no != null){{$transfer->from_account->account_no}}@else - @endif</td>
                                {{-- <td>@if($transfer->from_acc_no != null){{$transfer->from_account->current_balance}}@else {{$transfer->from_fund->amount}}  @endif</td> --}}
                                <td>{{$transfer->transaction_amount}}</td>
                                <td>{{$transfer->to_head->head_name}}</td>
                                <td>{{$transfer->to_fund->fund->name}}</td>
                                <td>@if($transfer->to_bank){{$transfer->to_bank->name}}@else Cash @endif</td>
                                <td>@if($transfer->to_acc){{$transfer->to_acc->account_no}}@else --- @endif</td>
                                {{-- <td>{{$transfer->to_fund->amount}}</td> --}}
                                <td>{{$transfer->user->name}}</td>
                                <td>{{$transfer->particulars}}</td>
                                <td> @if ($transfer->attachment != null)
                                    <a href="{{ asset('head_attachment/'.$transfer->attachment)}}" target="_blank" class="btn btn-info">
                                        <i class="fa fa-download"></i>
                                    </a>
                                @endif</td>
                                    <td>
                                    <a href="{{route('head-to-head-transfer-status-update',$transfer->id)}}" onclick="alert('Are You Sure Want to delete this?')" class="btn btn-danger btn-sm"><i class="fa fa-trash pr-2" style=""></i>Delete</a>
                                </td>
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
@endsection