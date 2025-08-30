<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
 		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}"> 
        <link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}"> 
	
</head>
<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 5px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 0px solid #ddd;
        text-align: left !important;
    }

    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
</style>
<style>
    @media print {

      .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
      .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
           float: left;               
      }

      .col-sm-12 {
           width: 100%;
      }

      .col-sm-11 {
           width: 91.66666666666666%;
      }

      .col-sm-10 {
           width: 83.33333333333334%;
      }

      .col-sm-9 {
            width: 75%;
      }

      .col-sm-8 {
            width: 66.66666666666666%;
      }

       .col-sm-7 {
            width: 58.333333333333336%;
       }

       .col-sm-6 {
            width: 50%;
       }

       .col-sm-5 {
            width: 41.66666666666667%;
       }

       .col-sm-4 {
            width: 33.33333333333333%;
       }

       .col-sm-3 {
            width: 25%;
       }

       .col-sm-2 {
              width: 16.666666666666664%;
       }

       .col-sm-1 {
              width: 8.333333333333332%;
        }            
}
</style>
<script>
    window.print();
    window.onafterprint = function () {
        window.close();
    };
</script>
 <body>
          
    <div class="container mt-5">
        <div class="row">
            <!-- Logo and Company Info -->
            <div class="col-sm-5">
                <img src="{{ asset('upload_images/company_logo/' . $model->company->logo) }}"
                    alt="Company Logo" height="auto" width="300px">
            </div>
            <div class="col-sm-7 text-end">
                <h1>{{ $model->company->name }}</h1>
                <p>
                    {{ $model->company->phone }}<br>
                    {{ $model->company->email }}<br>
                    {{ $model->company->address }}
                </p>
            </div>
        </div>
        <div class="row mt-4">
            <!-- Invoice To Information -->
            <div class="col-sm-6">
                <h5>INVOICE TO:</h5>
                <p><strong>{{ $model->renter->name }}</strong></p>
                <p>{{ $model->renter->phone }}<br>{{ $model->renter->present_address }}</p>
            </div>
            <!-- Invoice Details -->
            <div class="col-sm-6 text-end">
                <p>Status:
                    <span
                        class="{{ $model->status == '2' ? 'badge badge-success' : ($model->status == '1' ? 'badge badge-danger' : ($model->status == '3' ? 'badge badge-warning' : 'badge badge-warning')) }}">
                        {{ $model->status == '2' ? 'Paid' : ($model->status == '1' ? 'Unpaid' : ($model->status == '3' ? 'Partial Paid' : 'Unknown')) }}
                    </span>
                </p>
                <p>Invoice No: #{{ $model->invoice_no }}</p>
                <p>Invoice Month: {{ \Carbon\Carbon::parse($model->month)->format('F Y') }}</p>
                {{-- <p>End Date: {{ \Carbon\Carbon::parse($model->end_date)->format('M d, Y') }}</p> --}}
            </div>
        </div>
        <!-- Table for invoice items -->
        <div class="row mt-4">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        @if ($model->type == 'Electricity Bill')
                            <th>Meter No. </th>
                        @endif
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($model->type == 'Monthly Rent')
                        <tr>
                            <td>Monthly Rent</td>
                            {{-- <td>{{ $model->note }}</td> --}}
                            <td>{{ number_format($model->unit->rent_amount, 2) }} Tk</td>
                        </tr>
                        <tr>
                            <td>Gas Bill</td>
                            {{-- <td>{{ $model->note }}</td> --}}
                            <td>{{ number_format($model->unit->gas_bill, 2) }} Tk</td>
                        </tr>
                        <tr>
                            <td>Water Bill</td>
                            {{-- <td>{{ $model->note }}</td> --}}
                            <td>{{ number_format($model->unit->water_bill, 2) }} Tk</td>
                        </tr>
                        <tr>
                            <td>Trash Bill</td>
                            {{-- <td>{{ $model->note }}</td> --}}
                            <td>{{ number_format($model->unit->trash_bill, 2) }} Tk</td>
                        </tr>
                        <tr>
                            <td>Security Bill</td>
                            {{-- <td>{{ $model->note }}</td> --}}
                            <td>{{ number_format($model->unit->security_bill, 2) }} Tk</td>
                        </tr>
                        <tr>
                            <td>Service Charge</td>
                            {{-- <td>{{ $model->note }}</td> --}}
                            <td>{{ number_format($model->unit->service_charge, 2) }} Tk</td>
                        </tr>
                    @else
                        <tr>
                            <td>Electricity Bill</td>
                            <td>{{ $model->unit->meter->meter_number }}</td>
                            <td>{{ number_format($model->total_amount, 2) }} Tk</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Total and Due Amount -->
        <div class="row mt-4">
            <div class="col-sm-6">
                <h5>Total: {{ number_format($model->total_amount, 2) }} Tk</h5>
            </div>
            <div class="col-sm-6 text-end">
                <h5>Due Amount: {{ number_format($model->due_amount, 2) }} Tk</h5>
            </div>
        </div>
    </div>
 
</body>
</html>
