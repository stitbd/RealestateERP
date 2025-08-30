@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Add Purchase
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-purchase') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            {{-- <div class="col-lg-4">
                                <label for="Project">Project</label>
                                <select name="project_id" id="project_id" class="form-control" required>
                                    <option value="">Select One</option>
                                    @foreach ($project_data as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            <div class="col-lg-4">
                                <label for="Supplier">Supplier</label>
                                <select name="supplier_id" id="supplier_id" class="form-control" required>
                                    <option value="">Select One</option>
                                    @foreach ($supplier_data as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4">
                                <label for="date">Date</label>
                                <input type="date" name="purchase_date" required class="form-control">
                            </div>
                            <div class="col-lg-12 pt-3">
                                <table class="table table-bordered">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Unit Price</th>
                                            <th>Discount(Per)</th>
                                            <th>Discount(Flat)</th>
                                            <th>Vat </th>
                                            <th>Tax </th>
                                            <th>Att </th>
                                            <th>Shipping Cost </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row_1" class="row_tr">
                                            <td>
                                                <select name="item_id[]" class="form-control">
                                                    <option value="">Select One</option>
                                                    @foreach ($item_data as $item)
                                                        <option value="{{$item->id}}">{{ $item->name.' - '.$item->size_type.' ('.$item->unit.')' }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="qty[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="unit_price[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="discount_per[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="discount_flat[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="vat[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="tax[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="att[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="shipping_cost[]" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>

                            <div class="col-lg-6 pt-3">
                                <button type="button" class="btn btn-success" onclick="add_more()"><i class="fa fa-plus"></i> Add More</button>
                            </div>

                            <div class="col-lg-6 pt-3 text-right">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Save</button>
                            </div>

                        </div>

                        <div class="row">
                            {{-- <div class="col-lg-6 ">
                                <div class="row border m-2 p-2" style="border-color: green !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                        Payment Required Information
                                    </h6>
                                    <div class="col-lg-6">
                                        <label for="Fund">Fund</label>
                                        <select name="fund_id" id="fund_id" class="form-control" required onchange="checkPaymentType();checkType();">
                                            <option value="">Select One</option>
                                            @foreach ($fund_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Fund">Payment Type</label>
                                        <select name="payment_type" class="form-control" id="payment_type" required>
                                            <option value="">Select One</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Check">Check</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="Supplier">Payment Date</label>
                                        <input type="text" required name="paid_amount" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Payment Code </label>
                                        <input type="text" required name="paid_amount" class="form-control"/>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="Supplier">Paid amount </label>
                                        <input type="text" required name="paid_amount" class="form-control"/>
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="remarks">Particular</label>
                                        <input type="text" name="remarks" class="form-control" required/>
                                    </div>  
                                </div>

                            </div> --}}

                            {{-- <div class="col-lg-6 ">
                                
                                <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                        Payment Other Information
                                    </h6>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control mt-3 " name="receiver_name"
                                            placeholder="Type Receiver Name">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control mt-3 " name="designation"
                                            placeholder="Type Designation">

                                    </div>
                                    <div class="col-lg-12 bank">
                                        <label for="mobile_no">Bank <i
                                        class="text-danger">*</i></label>
                                        <select name="bank_id" id="bank_id" class="form-control form-select chosen-select" onchange="filterAccount()">
                                            <option value="" selected>Select Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-12 bank">
                                        <label for="mobile_no">Bank Account<i
                                        class="text-danger">*</i></label>
                                        <select name="account_id" id="account" class="form-control form-select chosen-select">
                                            <option value="" selected>Select a Bank Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 mobile">
                                        <label for="mobile_no">Mobile No.<i
                                    class="text-danger">*</i></label>
                                        <input type="text" name="mobile_no" class="form-control "/>
                                    </div>
                                    <div class="col-lg-6 cheque" id="cheque1">
                                        <label for="cheque_no">Cheque Number <i
                                    class="text-danger">*</i></label>
                                        <input type="text" name="cheque_no" class="form-control"/>
                                    </div>
                                    <div class="col-lg-6 cheque" id="cheque2">
                                        <label for="cheque_issue_date">Cheque Issue Date <i
                                    class="text-danger">*</i></label>
                                        <input type="date" name="cheque_issue_date" class="form-control"/>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="Supplier">Attachment</label>
                                        <input type="file" name="attachment" />
                                    </div>
                                   
                                    <div class="col-lg-12 pt-3">
                                        <button class="btn btn-success btn-block"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </div>
                            </div> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script_js')

<script>

$( document ).ready(function() {
        $('.bank').hide();
        $('.cheque').hide();
        $('.mobile').hide();
    console.log( "ready!" );
    });
    function add_more(){
        var row_data = $('.row_tr').html();
        row_data = '<tr>'+row_data+'</tr>';

        $("table tbody").append(row_data);
    }


    function checkType() {
            var fundSelect = document.getElementById("fund_id");
            var paymentTypeSelect = document.getElementById("payment_type");
            var selectedFundId = fundSelect.value;
            var paymentTypeOption = paymentTypeSelect.querySelector("option[value='Bank']");

            if (selectedFundId === "1") {
                paymentTypeOption.selected = true;
            }else{
                paymentTypeOption.selected = false ;
            }

            $(".chosen-select").chosen();
        }


       function checkPaymentType(){
        var fund_id = document.getElementById('fund_id').value;
        if(fund_id == 2){
            $('.bank').hide().removeAttr('required', true);
            $('.cheque').hide();
            $('.mobile').hide();

        }
        if(fund_id == 1){
            $('.bank').show().prop('required', true);
            $('.cheque').show();
            $('.mobile').hide();

        }
        if(fund_id == 3){
            $('.bank').hide().removeAttr('required', true);
            $('.cheque').hide();
            $('.mobile').show();
        }
        if(fund_id == 4){
            $('.bank').hide().removeAttr('required', true);
            $('.cheque').hide();
            $('.mobile').hide();
        }
       }
    
</script>
@endpush