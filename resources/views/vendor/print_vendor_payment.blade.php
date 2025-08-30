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
    @media print {
        .h3{
            background-color: black !important;
            color: white !important;
            print-color-adjust: exact; 
        }
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

 <body>
    <button class="col-sm-1 mt-2 btn btn-success pull-right" onclick="printDiv('printableArea')">Print</button>
    <div id="printableArea">
          
    <div class="container mt-5">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{ asset('image/logo.png') }}" alt="" height="auto" width="300px">
            </div>
            <div class="col-sm-7">
                <h1 class="h1">e-Learning & Earning Ltd</h1>
                <h6 style="font-size:12px;">Khaja IT Park(2nd Floor), 7 South Kallyanpur,Mirpur Road, Dhaka-1207, Bangladesh</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 mt-3 text-center mb-3 p-2 h3 voucher" style="background:black; color:white ">Credit Voucher</div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Receive From</label>
                <label>:</label>
                <span>{{$vendor_name}}</span>
            </div>
            <div class="col-sm-4">
                <label>Account Head</label>
                <label>:</label>
                <span>Income</span>
            </div>
            <div class="col-sm-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>SPC{{$date }}-{{substr($details->nid, -3)}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <label>Received By</label>
                <label>:</label>
                <span>{{$details->receiver_name}}</span>
            </div>
            <div class="col-sm-3">
                <label>Voucher No</label>
                <label>:</label>
                <span>VHR-{{$date}}-{{substr($details->nid, -3)}}</span>
            </div>
            <div class="col-sm-3">
                <label>Date:</label>
                <span>{{$model->payment_date}}</span>
            </div>
            <div class="col-sm-3">
                <label>Amount</label>
                <label>:</label>
                <span>{{$model->amount}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Cash/Bank</label>
                <label>:</label>
                <span>{{$model->payment_type }}</span>
            </div>
            <div class="col-sm-4">
                <label>Cheque No</label>
                <label>:</label>
                <span>{{ $details->check_number}}</span>
            </div>
            <div class="col-sm-4">
                <label>Cheque Date</label>
                <label>:</label>
                <span>{{$details->check_issue_date}}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="col-sm-8 text-center">Particulars</th>
                            <th scope="col"  class="col-sm-4 text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @php  $particular = App\Models\Project::where('id',$model->project_id)->first() @endphp
                            <td rowspan="4" class="col-sm-8" >Bring the amount Paid Against : {{$particular->category_name}}</td>
                            <td rowspan="4" class="col-sm-4 text-center">{{$model->amount}}</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                    <tfoot>
                        <tr>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $amount =  $f->format($model->amount);
                                $amount = ucfirst($amount);
                                // echo $f->format($model->amount); 
                            @endphp
                            <th>Amount In Words TK : {{$amount}}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <table  class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th class="text-center">Receive By</th>
                            <th class="text-center">Accounts</th>
                            <th class="text-center">Acknowledge By</th>
                            <th class="text-center">Approved By</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$details->receiver_name}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="container" style="margin-top: 150px">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{ asset('image/logo.png') }}" alt="" height="auto" width="300px">
            </div>
            <div class="col-sm-7">
                <h1 class="h1">e-Learning & Earning Ltd</h1>
                <h6 style="font-size:12px;">Khaja IT Park(2nd Floor), 7 South Kallyanpur,Mirpur Road, Dhaka-1207, Bangladesh</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 mt-3 text-center mb-3 p-2 h3 voucher" style="background:black; color:white ">Credit Voucher</div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Receive From</label>
                <label>:</label>
                <span>{{$vendor_name}}</span>
            </div>
            <div class="col-sm-4">
                <label>Account Head</label>
                <label>:</label>
                <span>Income</span>
            </div>
            <div class="col-sm-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>SPC{{$date }}-{{substr($details->nid, -3)}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <label>Received By</label>
                <label>:</label>
                <span>{{$details->receiver_name}}</span>
            </div>
            <div class="col-sm-3">
                <label>Voucher No</label>
                <label>:</label>
                <span>VHR-{{$date}}-{{substr($details->nid, -3)}}</span>
            </div>
            <div class="col-sm-3">
                <label>Date:</label>
                <span>{{$model->payment_date}}</span>
            </div>
            <div class="col-sm-3">
                <label>Amount</label>
                <label>:</label>
                <span>{{$model->amount}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Cash/Bank</label>
                <label>:</label>
                <span>{{$model->payment_type }}</span>
            </div>
            <div class="col-sm-4">
                <label>Cheque No</label>
                <label>:</label>
                <span>{{ $details->check_number}}</span>
            </div>
            <div class="col-sm-4">
                <label>Cheque Date</label>
                <label>:</label>
                <span>{{$details->check_issue_date}}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="col-sm-8 text-center">Particulars</th>
                            <th scope="col"  class="col-sm-4 text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @php  $particular = App\Models\Project::where('id',$model->project_id)->first() @endphp
                            <td rowspan="4" class="col-sm-8" >Bring the amount Paid Against : {{$particular->category_name}}</td>
                            <td rowspan="4" class="col-sm-4 text-center">{{$model->amount}}</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                    <tfoot>
                        <tr>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $amount =  $f->format($model->amount);
                                $amount = ucfirst($amount);
                                // echo $f->format($model->amount); 
                            @endphp
                            <th>Amount In Words TK : {{$amount}}</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <table  class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th class="text-center">Receive By</th>
                            <th class="text-center">Accounts</th>
                            <th class="text-center">Acknowledge By</th>
                            <th class="text-center">Approved By</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$details->receiver_name}}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
    <script>
        function printDiv(divId) {
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
    </script>
 </body>

</html>
