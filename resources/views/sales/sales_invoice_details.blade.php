<!-- Main content -->
<div class="invoice p-3 mb-3">
    <div class="row">
      <div class="col-12">
        <h4>
          <img height="50px" src="{{asset('upload_images/company_logo/'.$sales_info->company->logo)}}" alt=""> {{$sales_info->company->name}}
          <small class="float-right">Date: {{date('d/m/Y',strtotime($sales_info->sales_date))}}</small>
        </h4>
      </div>
    </div>
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        
        <address>
          <strong>{{$sales_info->company->name}}</strong><br/>
          {{$sales_info->company->address}} <br/>
          {{$sales_info->company->email}} <br/>
          {{$sales_info->company->phone}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <address>
          <strong>{{$sales_info->customer_name}}</strong><br>
          Address: {{$sales_info->customer_address}}<br>
          Phone:  {{$sales_info->customer_phone}}<br>
          Email:  {{$sales_info->customer_email}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Reference No #{{$sales_info->ref_no}}</b><br>
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
            <th>Product</th>
            <th>Qty</th>
            <th>Unit</th>
            <th>Rate</th>
            <th>Discount(%)</th>
            <th>Discount(Flat)</th>
            <th>Vat</th>
            <th>Tax</th>
            <th>Att</th>
            <th>Shipping Cost</th>
            <th>Amount</th>
          </tr>
          </thead>
          <tbody>
          @foreach ($sales_details_info as $item)
              <tr>
                  <td>{{$item->item->name.' - '.$item->item->size_type}}</td>
                  <td>{{$item->qty}}</td>
                  <td>{{$item->item->unit}}</td>
                  <td>{{$item->unit_price}}</td>
                  <td>{{$item->discount_per}} %</td>
                  <td>{{$item->discount_flat}}</td>
                  <td>{{$item->vat}}</td>
                  <td>{{$item->tax}}</td>
                  <td>{{$item->att}}</td>
                  <td>{{$item->shipping_cost}}</td>
                  <td>{{$item->amount}}</td>
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

        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Total:</th>
              <td class="text-right">Tk. {{$sales_info->sales_amount}}</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    
  </div>
  <!-- /.invoice -->