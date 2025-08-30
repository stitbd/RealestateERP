<!-- Main content -->
<div class="invoice p-3 mb-3">
    <div class="row">
      <div class="col-12">
        {{-- <h4>
          <img height="50px" src="{{asset('upload_images/company_logo/'.$sales_info->company->logo)}}" alt=""> {{$sales_info->company->name}}
          <small class="float-right">Date: {{date('d/m/Y',strtotime($sales_info->sales_date))}}</small>
        </h4> --}}
      </div>
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Sl</th>
            <th>Date</th>
            <th>Inv</th>
            <th>Fund</th>
            <th>Payment Type</th>
            <th>Amount</th>
            <th>Receiver Info</th>
            <th>Remarks</th>
          </tr>
          </thead>
          <tbody>
            @php $i = 0; @endphp
            @foreach($payment as $v_payment)
            <tr>
                <td>{{++$i}}</td>
                <td>{{$v_payment->payment_date}}</td>
                <td>{{$v_payment->inv_no}}</td>
                <td>
                    <strong>{{$v_payment->fund->name}}</strong><br>
                    @if($v_payment->bank_id)<strong>{{$v_payment->bank->name}}</strong><br>@endif
                    @if($v_payment->bank_account_no)<strong>{{$v_payment->account->account_no}}</strong><br>@endif
                    @if($v_payment->check_number)<strong>{{$v_payment->check_number}}</strong><br>@endif
                    @if($v_payment->check_issue_date)<strong>{{$v_payment->check_issue_date}}</strong><br>@endif
                </td>
                <td>{{$v_payment->payment_type}}</td>
                <td>{{$v_payment->amount}}</td>
                <td>{{$v_payment->payment_details->receiver_name}}</td>
                <td>{{$v_payment->payment_details->remarks}}</td>
            </tr>
            @endforeach
          </tbody>

        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

   

    
  </div>
  <!-- /.invoice -->