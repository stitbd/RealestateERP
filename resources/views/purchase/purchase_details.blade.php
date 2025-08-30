<!-- Main content -->
<div class="invoice p-3 mb-3">
    <div class="row">
      <div class="col-12">
        <h4>
          <img height="50px" src="{{asset('upload_images/company_logo/'.$purchase_info->company->logo)}}" alt=""> {{$purchase_info->company->name}}
          <small class="float-right">Date: {{date('d/m/Y',strtotime($purchase_info->purchase_date))}}</small>
        </h4>
      </div>
    </div>
    <div class="row invoice-info">
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
          @foreach ($purchase_details as $item)
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
              <td class="text-right">Tk. {{$purchase_info->invoice_amount}}</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    
  </div>
  <!-- /.invoice -->