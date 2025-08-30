@extends('layouts.app')
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Generate Invoice
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('save-invoice') }}" method="post" enctype="multipart/form-data">
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
                                <label for="date">Sales Date</label>
                                <input type="date" name="purchase_date" required class="form-control">
                            </div>

                            <div class="col-lg-4">
                                <label for="Supplier">Customer Name</label>
                                <input type="text" name="customer_name" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label for="Supplier">Customer Phone</label>
                                <input type="text" name="customer_phone" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label for="Supplier">Customer Address</label>
                                <input type="text" name="customer_address" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label for="Supplier">Customer Email</label>
                                <input type="text" name="customer_email" class="form-control">
                            </div>
                            
                            <div class="col-lg-6 pt-3">
                                <button type="button" class="btn btn-success" onclick="add_more()"><i class="fa fa-plus"></i> Add More</button>
                            </div>

                            <div class="col-lg-12 pt-3">
                                <table class="table table-bordered">
                                    <thead class="bg-info">
                                        <tr>
                                            <th>Item</th>
                                            <th>Current Stock</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Total Amount </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row_1" class="row_tr">
                                            <td>
                                                <select name="item_id[]" class="form-control item_select" id="item_1" onchange="itemInfo(this)">
                                                    <option value="">Select One</option>
                                                    @foreach ($item_data as $item)
                                                        <option value="{{$item->id}}"> {{ $item->name.' - '.$item->size_type.' ('.$item->unit.')' }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                           
                                            <td>
                                                <input type="text" id="stock_1" name="stock[]" class="form-control stock">

                                                <input type="hidden" id="hidden_stock_1" class="form-control h_stock">
                                            </td>
                                            <td>
                                                <input type="text" id="qty_1" name="qty[]" class="form-control qty" oninput="calculateAmount(this)">
                                            </td>
                                            <td>
                                                <input type="text" id="rate_1" name="rate[]" class="form-control rate" oninput="calculateAmount(this)">
                                            </td>
                                            <td>
                                                <input type="text" id="amount_1" name="amount[]" class="form-control amount">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                            
                        </div>


                        <div class="row">
                            <div class="col-lg-6 ">
                                <div class="row border m-2 p-2" style="border-color: green !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                        Payment Required Information
                                    </h6>
                                    <div class="col-lg-12">
                                        <label for="Supplier">Total Sales Amount </label>
                                        <input type="text" readonly name="total_amount" id="totalAmount" class="form-control"/>
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
                                  
                                    <div class="col-lg-12">
                                        <label for="Supplier">Paid amount </label>
                                        <input type="text" required name="paid_amount" id="paid_amount" class="form-control"/>
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="Supplier">Remarks</label>
                                        <input type="text" name="remarks" class="form-control"/>
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
                                   
                                    <div class="col-lg-6 bank">
                                        <label for="mobile_no">Bank <i
                                        class="text-danger">*</i></label>
                                        <select name="bank_id" id="bank_id" class="form-control form-select chosen-select" onchange="filterAccount()">
                                            <option value="" selected>Select Bank</option>
                                            @foreach($banks as $bank)
                                                <option value="{{$bank->id}}">{{$bank->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                        
                                    <div class="col-lg-6 bank">
                                        <label for="mobile_no">Bank Account<i
                                        class="text-danger">*</i></label>
                                        <select name="account_id" id="account" class="form-control form-select chosen-select">
                                            <option value="" selected>Select a Bank Account</option>
                                            @foreach($accounts as $account)
                                                <option value="{{$account->id}}">{{$account->account_no}}</option>
                                            @endforeach
                                        </select>
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


<script>
    var i = 1;
    function add_more(){
        ++i;
        var row_data = $('.row_tr').html();
        var row_id = "row_" + i; 
        var new_row = '<tr id="' + row_id + '">' + row_data + '</tr>';
        $("table tbody").append(new_row);

        var next_row_item = $('#'+row_id).find('.item_select');
        var next_row_rate = $('#'+row_id).find('.rate');
        var next_row_stock = $('#'+row_id).find('.stock');
        var next_row_hidden_stock = $('#'+row_id).find('.h_stock');
        var next_row_qty = $('#'+row_id).find('.qty');
        var next_row_amount = $('#'+row_id).find('.amount');

        var next_item_id  = 'item_'+i;
        var next_rate_id  = 'rate_'+i;
        var next_stock_id  = 'stock_'+i;
        var next_hidden_id  = 'hidden_stock_'+i;
        var next_qty_id  =  'qty_'+i;
        var next_amount  =  'amount_'+i;

        next_row_item.attr('id',next_item_id);
        next_row_rate.attr('id',next_rate_id);
        next_row_stock.attr('id',next_stock_id);
        next_row_qty.attr('id',next_qty_id);
        next_row_amount.attr('id',next_amount);
        next_row_hidden_stock.attr('id',next_hidden_id);
        
        }

    
    function itemInfo(e){
        let text = e.id;
        let id = text.replace('item_','');
        let item_id = $('#item_'+id).val();
        let url = "{{route('filterItem')}}";

        $.ajax({
            type:"POST",
            url:url,
            data:{item_id},
            success:function(r){
                if(r <= 7){
                    $('#stock_'+id).css({
                        'font-weight': 'bold',
                        'color': 'red',
                    }).val(r);
                    $('#hidden_stock_'+id).val(r);
                }else{
                    $('#stock_'+id).css({
                        'font-weight': 'bold',
                    }).val(r);
                    $('#hidden_stock_'+id).val(r);
                }
            }
        });
    }

    function calculateAmount(e){
    let text  = e.id;
    let id = e.id.split('_')[1];
    let qty   = parseInt($('#qty_'+id).val());
    let stock = parseInt($('#hidden_stock_'+id).val());
    let rate  = parseInt($('#rate_'+id).val());

    console.log(rate);

    if(qty > stock){
        $('#qty_'+id).val(0);
        alert('Item Stock is Less Than Quantity');
    } else {
        let current_stock = stock - qty;
        $('#stock_'+id).val(current_stock);

        let amount = qty * rate;
        console.log(amount);
        $('#amount_' + id).val(amount);

         calculateTotalAmount();
    }

  }

  
function calculateTotalAmount() {
    let totalAmount = 0;
    $('.amount').each(function() {
        let amountValue = parseInt($(this).val());
        if (!isNaN(amountValue)) {
            totalAmount += amountValue;
        }
    });
    
    $('#totalAmount').val(totalAmount);
}
    
</script>

@endsection