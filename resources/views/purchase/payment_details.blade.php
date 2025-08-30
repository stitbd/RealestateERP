<!-- Main content -->
<div class="invoice p-3 mb-3">
    <div class="row">
      <div class="col-12">
        <h4>
          {{-- <img height="50px" src="{{asset('upload_images/company_logo/'.$payment->company->logo)}}" alt=""> {{$payment->company->name}}
          <small class="float-right">Date: {{date('d/m/Y',strtotime($payment->payment_date))}}</small> --}}
        </h4>
      </div>
    </div>
    {{-- <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        
        <address>
          <strong>{{$purchase_info->company->name}}</strong><br/>
          {{$purchase_info->company->address}} <br/>
          {{$purchase_info->company->email}} <br/>
          {{$purchase_info->company->phone}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>{{$purchase_info->supplier->name}}</strong><br>
          Address: {{$purchase_info->supplier->address}}<br>
          Phone: {{$purchase_info->supplier->mobile}}<br>
          {{$purchase_info->supplier->other_details}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Invoice No #PUR-{{$purchase_info->id}}</b><br>
      </div>
      <!-- /.col -->
    </div> --}}
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Date</th>
            <th>Reference/Code</th>
            <th>Amount</th>
            <th>Attachment</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($payment as $v_payment)
              <tr>
                  <td>{{$v_payment->payment_date}}</td>
                  <td>{{$v_payment->code_no}}</td>
                  <td>{{$v_payment->amount}}</td>
                  <td> @if ($v_payment->payment_details->attachment != null)
                    <a href="{{ asset('attachment/'.$v_payment->payment_details->attachment)}}" target="_blank" class="btn btn-info">
                        <i class="fa fa-download"></i>
                    </a>
                @endif</td>
              </tr>
          @endforeach
          </tbody>

        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
       &nbsp;
      </div>
      <!-- /.col -->
      <div class="col-6">

        {{-- <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Total:</th>
              <td class="text-right">Tk. {{$purchase_info->invoice_amount}}</td>
            </tr>
          </table>
        </div> --}}
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    
  </div>
  <!-- /.invoice -->