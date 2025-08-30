<!-- Main content -->
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>
                <img height="50px" src="{{ asset('upload_images/company_logo/' . $purchase_asset->company->logo) }}"
                    alt=""> {{ $purchase_asset->company->name }}
                <small class="float-right">Date: {{ date('d/m/Y', strtotime($purchase_asset->purchase_date)) }}</small>
            </h4>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">

            <address>
                <strong>{{ $purchase_asset->company->name }}</strong><br />
                {{ $purchase_asset->company->address }} <br />
                {{ $purchase_asset->company->email }} <br />
                {{ $purchase_asset->company->phone }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            Purchase Details
            <address>
                <strong>{{ $purchase_asset->p_code }}</strong><br>
                Payment Type: {{ $purchase_asset->payment_type }}<br>
                Cash/Bank: {{ $purchase_asset->fund->name ?? ''}}<br>
                Purchase Note: {{ $purchase_asset->purchase_note }}
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Invoice No #{{ $purchase_asset->invoice_no }}</b><br>
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
                        <th class="text-center">Asset</th>
                        <th class="text-center">Asset Group</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Unit Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase_asset->asset_details as $asset_detail)
                    <tr>
                        @if (!empty($asset_detail->asset->image))
                            <td class="text-center">
                                <img src="{{ asset('upload_images/asset/' . $asset_detail->asset->image) }}" height="80px" width="100px" />
                            </td>
                        @else
                            <td class="text-center">
                                <img src="{{ asset('upload_images/asset/default_image.jpg') }}" alt="Asset Image" style="height: 80px; width: 100px;">
                            </td>
                        @endif
                        <td class="text-center">{{ $asset_detail->asset->asset_group->name }}</td>
                        <td class="text-center">{{ $asset_detail->asset->name }}</td>
                        <td class="text-center">{{ $asset_detail->quantity }}</td>
                        <td class="text-center">{{ $asset_detail->unit_price }} Tk.</td>
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
                        <th style="width:40%">Total:</th>
                        <td class="text-right">{{ $purchase_asset->total_amount }} Tk.</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


</div>
<!-- /.invoice -->
