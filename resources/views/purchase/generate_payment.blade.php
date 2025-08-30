<!-- Main content -->
<div class="p-3 mb-3">
    @if($due > 0)

    <form action="save-payment" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="purchase_id" value="{{$purchase_info->id}}">
        <input type="hidden" name="supplier_id" value="{{$purchase_info->supplier_id}}">
        <div class="row">
            <div class="col-6">
                <div class="mb-3">
                    <label class="form-label">Payment Date<i
                        class="text-danger">*</i></label>
                    <input type="date" class="form-control" name="date" id="date" required value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label class="form-label">Purchase Amount<i
                        class="text-danger">*</i></label>
                    <input type="number" class="form-control" name="purchase_amount"  readonly id=""
                        value="{{ $due }}" >
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label class="form-label">Payment Amount<i
                        class="text-danger">*</i></label>
                    <input type="number" class="form-control" name="amount"  required id="amount"
                        placeholder="Enter Payment Amount" oninput="generatePaymentCode();">
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label class="form-label">Payment Code<i
                        class="text-danger">*</i></label>
                    <input type="text" class="form-control" name="code_no"  required id="code_no"
                        placeholder="">
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label class="form-label">Fund<i
                        class="text-danger">*</i></label>
                    <select name="fund_id" id="fund_id" class="form-control" required  onchange="checkPaymentType();checkType();">
                        <option value="">Select a Fund</option>
                        @foreach($fund as $v_fund)
                          <option value="{{$v_fund->id}}">{{$v_fund->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="mb-3">
                    <label class="form-label">Payment Type<i
                        class="text-danger">*</i></label>
                    <select name="payment_type" class="form-control" id="payment_type" required>
                        <option value="">Select One</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Bank">Bank</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
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

            <div class="col-6 bank">
                <div class="mb-3">
                    <label class="form-label">Cheque(If any)</label>
                    <input type="text" class="form-control" name="cheque_no" id="date"
                    placeholder="Enter Your Cheque Number">
                </div>
            </div>

            <div class="col-6 bank">
                <div class="mb-3">
                    <label class="form-label">Cheque Issue Date(If any)</label>
                    <input type="date" class="form-control" name="cheque_issue_date" id="date"
                    placeholder="Enter Your Cheque Number">
                </div>
            </div>

            <div class="col-lg-12 mobile">
                <label for="mobile_no">Mobile No.<i
                    class="text-danger">*</i></label>
                <input type="text" name="mobile_no" class="form-control "/>
            </div>

            <div class="col-12" >
                <div class="mb-3">
                    <label class="form-label">Please provide any details <i
                        class="text-danger">*</i></label>
                    <textarea name="remarks" id="" cols="10" rows="2" required class="form-control"></textarea>
                </div>
            </div>

            <div class="col-12" >
                <div class="mb-3">
                    <label class="form-label">Include Attachment(If Any) </label>
                        <input type="file" name="attachment">
                </div>
            </div>

            <div class="col-3 pt-3">
                <button class="btn btn-success btn-block"><i class="fa fa-check"></i> Save</button>
            </div>
        </div>
    </form>
    
    @else
    <div class="row">
        <div class="col-sm-12 text-center">
            <h4 class="text-success">Payment Complete</h4>

        </div>
    </div>
    @endif
</div>
<script>
     $( document ).ready(function() {
        $('.bank').hide();
        $('.cheque').hide();
        $('.mobile').hide();
    });

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


       function filterAccount(){
            var bank_id = document.getElementById('bank_id').value;
            var url = "{{route('filter-account')}}";
            $.ajax({
                type:"GET",
                url:url,
                data:{bank_id},
                success:function(data){
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

       function generatePaymentCode() {
        var amount = document.getElementById('amount').value;
            if (amount) {
            var lastId = {{$payment_id ?? 0}};
            var nextId = lastId + 1; 
            let date = new Date();
            let year = date.getFullYear();
            console.log(year);
            var paymentCode = 'P-'+year+ nextId;
            document.getElementById('code_no').value = paymentCode;
            } else {
                document.getElementById('code_no').value = ''; 
            }
        }
       
</script>
<!-- /.invoice -->
