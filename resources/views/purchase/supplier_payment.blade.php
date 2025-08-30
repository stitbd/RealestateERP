@extends('layouts.app')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Supplier Payment
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('payment-ready-voucher') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="row border m-2 p-2" style="border-color: green !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                        Payment Required Information
                                    </h6>
                                    {{-- <div class="col-lg-6">
                                        <label for="Project">Project</label>
                                        <select onchange="load_supplier_due()" name="project_id" id="project_id" class="form-control" required>
                                            <option value="">Select One</option>
                                            @foreach ($project_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                    <div class="col-lg-6">
                                        <label for="Supplier">Supplier <i class="text-danger">*</i> </label>
                                        <select onchange="filterOrder();" name="supplier_id" id="supplier_id" class="form-control" required>
                                            <option value="">Select One</option>
                                            @foreach ($supplier_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Orders <i class="text-danger">*</i></label>
                                        <select name="order_id" id="order_id" class="form-control" onchange="load_supplier_due();" required>
                                            <option value="">Select One</option>
                                            @foreach ($work_orders as $order)
                                            <option value="{{ $order->id }}">{{ $order->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Supplier">Order Due </label>
                                        <input type="text" id="due" name="due" readonly class="form-control text-danger text-bold"/>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="date">Payment Date <i class="text-danger">*</i></label>
                                        <input type="date" name="payment_date" required class="form-control">
                                    </div>

                                    {{-- <div class="col-lg-6">
                                        <label for="category">Account Main Head</label>
                                        <select class="form-control chosen-select" name="category" id="category"
                                            onchange="filterHead(this);  newCategoryAdd(this)">
                                            <option value="0">Choose Category..</option>
                                            <option value="new_category">New Category</option>
                                            @foreach ($categories as $category)
                                                @php $expenses = json_decode($category->category_type)  @endphp
                                                @if ($expenses && in_array('Payment', $expenses))
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="head">Account Sub Head</label>
                                        <select class=" chosen-select form-control head" name="head" id="head" onchange="newHeadAdd()">
                                            <option value="">Choose Head...</option>
                                            <option value="new_head">New Head</option>
                                            @foreach ($head as $v_head)
                                                <option value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                            @endforeach
                                        </select>
                                      
                                    </div> --}}

                                    <div class="categoryAdd col-lg-12">

                                    </div>

                                    <div class="headAdd col-lg-12">

                                    </div>

                                    <div class="col-lg-6">
                                        <label for="Fund">Fund <i class="text-danger">*</i></label>
                                        <select name="fund_id" id="fund_id" class="form-control" required onchange="checkPaymentType();">
                                            <option value="">Select One</option>
                                            @foreach ($fund_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="Fund">Payment Type <i class="text-danger">*</i></label>
                                        <select name="payment_type" class="form-control" id="payment_type" required>
                                            <option value="">Select One</option>
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="Bank">Bank</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="Supplier">Paid amount <i class="text-danger">*</i> </label>
                                        <input type="text" required name="amount" class="form-control"/>
                                    </div>

                                    <div class="col-lg-12">
                                        <label for="Supplier">Remarks <i class="text-danger">*</i></label>
                                        <input type="text" name="remarks" class="form-control"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 ">
                                <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                        Payment Other Information
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
                                        <label for="bank_name">Bank </label>
                                        <select name="bank_id" id="bank_id"
                                            class="form-control bank_info chosen-select"
                                            onchange="filterAccount(this)">
                                            <option value="">Select A Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6 bank">
                                        <label for="account_no">Bank AC No.</label>
                                        <select name="account_id" id="account"
                                            class="form-control bank_info chosen-select"
                                            onchange="accountHolderName(this)">
                                            <option value="">Select One</option>
                                            @foreach ($bank_accounts as $account)
                                                <option value="{{ $account->id }}">{{ $account->account_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-12 bank">
                                        <label for="account_holder_name">Account Holder Name</label>
                                        <input type="text" name="account_holder_name" id="account_holder_name" class="form-control"
                                            id="account_holder_name_1" />
                                    </div>
                                    <div class="col-lg-6 bank">
                                        <label for="payment_note">Payment Note</label>
                                        <input type="text" name="payment_note" class="form-control" />
                                    </div>
                                    <div class="col-lg-6 bank" >
                                        <label for="cheque_no">Cheque Number</label>
                                        <input type="text" name="cheque_no" class="form-control" id="cheque1"/>
                                    </div>

                                    <div class="col-lg-6 bank mb-3">
                                        <label for="cheque_issue_date">Cheque Issue Date</label>
                                        <input type="date" name="cheque_issue_date" class="form-control"  id="cheque2" />
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

@push('script_js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

<script>

      $(document).ready(function(){
        $(".chosen-select").chosen();
                $('.bank').hide();
                $('.cheque').hide();
                $('.mobile').hide();
                $('.headAdd').hide();
                $('.categoryAdd').hide();
                console.log("ready!");

                
        });
    function load_supplier_due(){
        order_id = $('#order_id').val();
        
        if(order_id != ''){
            url = '{{ url('load-supplier-due/order_id') }}';

            url = url.replace('order_id', order_id);
            //alert(url);
            $.ajax({
                cache   : false,
                type    : "GET",
                error   : function(xhr){ alert("An error occurred: " + xhr.status + " " + xhr.statusText); },
                url : url,
                success : function(response){
                    if(response != 'no'){
                    $('#due').val(response);
                    }
                }
            })
        }
        else{
            $('#due').val('0');
        }
    }



    function filterOrder(){
        supplier_id = $('#supplier_id').val();
        
        if(supplier_id != ''){
            url = '{{ route('filter-order') }}';

            //alert(url);
            $.ajax({
                cache   : false,
                type    : "GET",
               data     : {supplier_id},
                url : url,
                success : function(data){
                    $('#order_id').find('option').remove();
                    $('#order_id').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#order_id').append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                }
            })
        }
    }

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
                        $('#account').append('<option value="' + value.id + '">' + value
                            .account_no +
                            '</option>');
                    });

                    $('#account').trigger("chosen:updated");

                },
            });

            $(".chosen-select").chosen();
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
                    document.getElementById('account_holder_name').value = data
                },
            });
        }


        function checkPaymentType() {
            var fund_id = document.getElementById('fund_id').value;
            if (fund_id == 2) {
                $('.bank').hide();
                $('.cheque').hide();
                $('.mobile').hide().removeAttr('required', true);
                $('.bank_info').removeAttr('required', true);

            }
            if (fund_id == 1) {
                $('.bank').show();
                $('.mobile').hide().removeAttr('required', true);
                $('.bank_info').prop('required', true);
                var payment = document.getElementById('payment_type');
                var paymentTypeOption = payment.querySelector("option[value='Bank']");
                paymentTypeOption.selected = true;
            }

            if (fund_id == 3) {
                $('.bank').hide();
                $('.cheque').hide();
                $('.mobile').show().prop('required', true);
                $('.bank_info').removeAttr('required', true);
            }

            if (fund_id == 4) {
                $('.bank').hide();
                $('.cheque').hide();
                $('.mobile').hide().removeAttr('required', true);
                $('.bank_info').removeAttr('required', true);
            }
        }


        function filterHead() {
            var category_id = document.getElementById('category').value;
            if(category_id == 'new_category'){
                console.log(category_id);
                 $('#head').empty().append('<option value="" disabled selected>Choose Head</option><option value="new_head">New Head</option>').trigger("chosen:updated");
            }else{
                var url = "{{ route('filter-head') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    category_id
                },
                success: function(data) {
                    $('#head').find('option').remove();
                    $('#head').html('');
                    $('#head').append(`<option value="" disabled selected>Choose Head</option> <option value="new_head">New Head</option>`);
                    $.each(data, function(key, value) {
                        $('#head').append(`
                        <option value="` + value.id + `">` + value.head_name +
                            `</option>`);
                    });
                    $('#head').trigger("chosen:updated");
                },
            });
            $(".chosen-select").chosen();
            }
           
        }


        function newCategoryAdd() {
            var category = document.getElementById('category').value;
            
            if (category == 'new_category') {
                $('.categoryAdd').empty().show().append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="category_name" class="form-control" id="" placeholder="Enter Category Name"/>
                                        <input type="hidden" name="category_type[]" class="form-control" id="" value="Payment"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

            $('.categoryAdd').on('click', '.remove', function() {
                $(this).parent().remove();
            });
            }else{
                $('.categoryAdd').hide();
            }

        }

        function newHeadAdd(){
            var head = document.getElementById('head').value;
            if (head == 'new_head') {
                $('.headAdd').empty().show().append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="head_name" class="form-control" id="" placeholder="Enter Head Name"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm  " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

            $('.headAdd').on('click', '.remove', function() {
                $(this).parent().remove();
            });
            }else{
                $('.headAdd').hide();
            }

        }

     </script>

@endpush