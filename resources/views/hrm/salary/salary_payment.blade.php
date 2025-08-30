@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Salary Payment
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-salary-payment') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 ">
                                <input type="hidden" name="employee_salary_id" value="{{$salary_data->id}}">
                                <input type="hidden" name="employee_id" value="{{$salary_data->employee_id}}">
                                <div class="row border m-2 p-2" style="border-color: green !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                        Payment Required Information
                                    </h6>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Employee Name</label>
                                            <input type="text" name="employee_name" class="form-control" value="{{ $salary_data->employee->name }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Salary ID</label>
                                            <input type="text" name="salary_id" class="form-control" value="{{ $salary_data->salary_id }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Salary Month</label>
                                            <input type="text" name="month" class="form-control" value="{{ $salary_data->month }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Salary Amount</label>
                                            <input type="text" name="total_salary" class="form-control" value="{{ $salary_data->total_salary }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="date">Date</label>
                                        <input type="date" name="payment_date" required class="form-control">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Fund">Fund</label>
                                        <select name="fund_id" id="fund_id" class="form-control" required>
                                            <option value="">Select One</option>
                                            @foreach ($fund_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Fund">Payment Type</label>
                                        <select name="payment_type" class="form-control" required>
                                            <option value="">Select One</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Check">Check</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Paid amount </label>
                                        <input type="text" required name="amount" class="form-control"/>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-lg-6 ">
                                
                                <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                        Payment Optional Information
                                    </h6>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Receiver Name </label>
                                        <input type="text" name="receiver_name" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Receiver Mobile no </label>
                                        <input type="text" name="mobile_no" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Receiver NID </label>
                                        <input type="text" name="nid" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Address </label>
                                        <input type="text" name="address" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Check Number</label>
                                        <input type="text" name="check_number" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Check Issue Date</label>
                                        <input type="text" name="check_issue_date" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Bank Name</label>
                                        <input type="text" name="bank_name" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Bank AC Name</label>
                                        <input type="text" name="bank_account_no" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Account Holder Name</label>
                                        <input type="text" name="account_holder_name" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Payment Note</label>
                                        <input type="text" name="payment_note" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Remarks</label>
                                        <input type="text" name="remarks" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Attachment</label>
                                        <input type="file" name="attachment" />
                                    </div>
                                    <div class="col-lg-12 pt-3">
                                        <button class="btn btn-success btn-block"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection