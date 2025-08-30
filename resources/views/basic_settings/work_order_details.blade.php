<!-- Main content -->
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h4>
          <img height="50px" src="{{asset('upload_images/company_logo/'.$work_order->company->logo)}}" alt=""> {{$work_order->company->name}}
          <small class="float-right">Date: {{date('d/m/Y',strtotime($work_order->date))}}</small>
        </h4>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        
        <address>
          <strong>{{$work_order->company->name}}</strong><br/>
          {{$work_order->company->address}} <br/>
          {{$work_order->company->email}} <br/>
          {{$work_order->company->phone}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>{{$work_order->supplier->name}}</strong><br>
          Address: {{$work_order->supplier->address}}<br>
          Phone: {{$work_order->supplier->mobile}}<br>
          Contact Person Name: {{$work_order->supplier->contact_person_name}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Invoice No #{{$work_order->invoice_no}}</b><br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Product/Service</th>
            <th>Name</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Amount</th>
          </tr>
          </thead>
          <tbody>
              <tr>
                  <td>{{$work_order->type}}</td>
                  <td>{{$work_order->name}}</td>
                  <td>@if($work_order->type == 'Service'){{$work_order->s_quantity}} @else {{$work_order->p_quantity}} @endif</td>
                  <td>{{$work_order->unit_price}}</td>
                  <td>{{$work_order->amount}}</td>
              </tr>
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

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:40%">Total:</th>
              <td class="text-center">Tk. {{$work_order->amount}}</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    
  </div>
  <!-- /.invoice -->