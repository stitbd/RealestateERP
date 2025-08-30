@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title col-sm-10">
                        Bank Account List
                    </h3> 
                    <button class="text-end col-sm-2 btn btn-success btn-sm"  data-toggle="modal"
                    data-target="#exampleModal" >+Add Account</button> 
                </div> 
                <div class="card-body p-3">  
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>#</th>
                                <th>Bank Name</th>
                                <th>Account Number</th>
                                <th>Account Holder Name</th>
                                <th>Route Number</th>
                                <th>Account Balance</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 0;
                            @endphp
                            @foreach ($bank_accounts as $account)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>{{$account->bank->name}}</td>
                                <td>{{$account->account_no}}</td>
                                <td>{{$account->account_holder_name}}</td>
                                <td>{{$account->route_no}}</td>
                                <td>{{$account->current_balance}}</td>
                                <td>{{$account->bank_address}}</td>
                                <td> <a data-toggle="modal"
                                    data-target=".update-modal-{{$account->id}}"
                                    style="padding:2px; color:white" class="btn btn-xs btn-info  mr-1">
                                   <i class="fas fa-edit"></i>
                                </a></td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            {{-- {{$banks->links();}} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade create_modal" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Add Bank</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('store-account') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body"> 
                    <div class="row">  
                        <div class="col-lg-6">
                            <label for="name">Bank Name</label>
                            <select name="bank_id" id="" class="form-control" required>
                                <option value="">Select A Bank</option>
                                @foreach($banks as $bank)
                                 <option value="{{$bank->id}}">{{$bank->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="account_no">Bank ACC. NO.</label>
                            <input type="text" name="account_no" required class="form-control" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="account_holder_name">Account Holder Name</label>
                            <input type="text" name="account_holder_name" required class="form-control">
                        </div>
                   
                        <div class="col-lg-6">
                            <label for="bank_address">Bank Address</label>
                            <input type="text" name="bank_address" required class="form-control" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="branch">Branch Name</label>
                            <input type="text"  name="branch" class="form-control" required/>
                        </div>
                        <div class="col-lg-6">
                            <label for="route_no">Route No.</label>
                            <input type="text"  name="route_no" class="form-control" required/>
                        </div>
                        <div class="col-lg-12">
                            <label for="balance">Account Balance</label>
                            <input type="text"  name="balance" class="form-control" />
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
@foreach($bank_accounts as $v_account)
@php $account = App\Models\BankAccount::where('id',$v_account->id)->first(); @endphp
<div class="modal fade update update-modal-{{$v_account->id}}" id="exampleModal"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-center">
                <h5 >Edit Bank Name</h5>
                <button type="button" class="close"
                data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('update-account',$v_account->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body"> 
                   <div class="form-group row pt-3">
                    <div class="col-lg-6">
                        <label for="name">Bank Name</label>
                        <select name="bank_id" id="" class="form-control">
                            <option value="">Select A Bank</option>
                            @foreach($banks as $bank)
                             <option value="{{$bank->id}}" @if($bank->id == $account->bank_id) selected @endif>{{$bank->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-6">
                        <label for="account_no">Bank ACC. NO.</label>
                        <input type="text" name="account_no" required class="form-control" value="{{$account->account_no}}">
                    </div>
                    <div class="col-lg-6">
                        <label for="account_holder_name">Account Holder Name</label>
                        <input type="text" name="account_holder_name" required class="form-control" value="{{$account->account_holder_name}}">
                    </div>
               
                    <div class="col-lg-6">
                        <label for="bank_address">Bank Address</label>
                        <input type="text" name="bank_address" required class="form-control" value="{{$account->bank_address}}">
                    </div>
                    <div class="col-lg-6">
                        <label for="branch">Branch Name</label>
                        <input type="text"  name="branch" class="form-control" value="{{$account->branch}}"/>
                    </div>
                    <div class="col-lg-6">
                        <label for="route_no">Route No.</label>
                        <input type="text"  name="route_no" class="form-control"  value="{{$account->route_no}}"/>
                    </div>
                    <div class="col-lg-12">
                        <label for="balance">Account Balance</label>
                        <input type="text"  name="balance" class="form-control" value="{{$account->current_balance}}"/>
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