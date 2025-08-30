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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

 <body>
   <div class="row">
    <div class="col-sm-8">
    </div>
    <div class="col-sm-2">
        <button class="mt-5 btn btn-info float-right" onclick="printDiv('printableArea')"><i class="fa-solid fa-print"></i>Print</button>
    </div>
    <div class="col-sm-2">
        <form action="{{route('save-investment')}}" method="POST">
            @csrf
            <button type="submit" class="mt-5 btn btn-success"><i class="fa-solid fa-download"></i>Confirm</button>
        </form>
    </div>
</div>
    <div id="printableArea">
          
    <div class="container mt-5" >
        <div class="row">
            <div class="col-sm-5">
                <img src="{{ asset('image/logo.png') }}" alt="" height="auto" width="300px">
            </div>
            <div class="col-sm-7">
                <h1 class="h1">e-Learning & Earning Ltd</h1>
                <h6>Khaja IT Park(2nd Floor), 7 South Kallyanpur,Mirpur Road, Dhaka-1207, Bangladesh</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 mt-3 text-center mb-3 col-sm-4 p-2 h3"  style="background:black; color:white ">Debit Voucher</div>
            <div class="col-sm-4"></div>
        </div>
        {{-- @dd($model) --}}
        <div class="row">
            <div class="col-sm-4">
                <label>Account Main Head</label>
                <label>:</label>
                <span>
                       @if($model->category){{$model->category->category_name}} @else {{$category->category_name}} @endif
                </span>
            </div>
            <div class="col-sm-4">
                <label>Account Sub Head</label>
                <label>:</label>
                <span> @if($model->head) {{$model->head->head_name}} @else {{$head->head_name}} @endif</span>
            </div>
            <div class="col-sm-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>{{$model->invest_code}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Client / Company</label>
                <label>:</label>
                <span>{{$model->client_name}}</span>
            </div>
            <div class="col-sm-4">
                <label>Address</label>
                <label>:</label>
                <span>{{$model->address}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Date</label>
                <label>:</label>
                <span>{{date('d/m/Y',strtotime($model->invest_date))}}</span>
            </div>
            {{-- <div class="col-sm-3">
                <label>Date:</label>
                <span>.................................</span>
            </div> --}}
            <div class="col-sm-4">
                <label>Voucher No</label>
                <label>:</label>
                @php
                  
                @endphp
                <span>{{ $model->voucher_no }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Cash/Bank</label>
                <label>:</label>
                <span>{{$model->fund->name}}</span>
            </div>
            <div class="col-sm-4">
                <label>Investor</label>
                <label>:</label>
                    <span>{{$model->investor}}</span>
            </div>
            <div class="col-sm-4">
                <label>Tentative Receivable Date</label>
                <label>:</label>
                <span>{{date('d/m/Y',strtotime($model->tentative_receivable_date))}}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Particulars</th>
                            <th scope="col" colspan="2" class="text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>          
                                    <strong>{{$model->purpose}}</strong><br>    
                            </td>
                            <td class="text-center" >
                                    <br>{{$model->amount}}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $amount =  $f->format($model->amount);
                                // $amount = getBangladeshCurrency($model->amount);
                                // echo $f->format($model->amount); 
                            @endphp
                            <th>Amount In Words :{{$amount}} TK Only</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <table  class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Received By</th>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Accounts</th>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Acknowledge By</th>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Approved By</th>
                          </tr>
                    </thead>
                   
                </table>
            </div>
        </div> 
    </div>
    <div class="container " style="margin-top:150px">
        <div class="row">
            <div class="col-sm-5">
                <img src="{{ asset('image/logo.png') }}" alt="" height="auto" width="300px">
            </div>
            <div class="col-sm-7">
                <h1 class="h1">e-Learning & Earning Ltd</h1>
                <h6>Khaja IT Park(2nd Floor), 7 South Kallyanpur,Mirpur Road, Dhaka-1207, Bangladesh</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4 mt-3 text-center mb-3 col-sm-4 p-2 h3"  style="background:black; color:white ">Debit Voucher</div>
            <div class="col-sm-4"></div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Account Main Head</label>
                <label>:</label>
                <span>
                       @if($model->category){{$model->category->category_name}} @else {{$category->category_name}} @endif
                </span>
            </div>
            <div class="col-sm-4">
                <label>Account Sub Head</label>
                <label>:</label>
                <span> @if($model->head) {{$model->head->head_name}} @else {{$head->head_name}} @endif</span>
            </div>
            <div class="col-sm-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>{{$model->invest_code}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Client / Company</label>
                <label>:</label>
                <span>{{$model->client_name}}</span>
            </div>
            <div class="col-sm-4">
                <label>Address</label>
                <label>:</label>
                <span>{{$model->address}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Date</label>
                <label>:</label>
                <span>{{date('d/m/Y',strtotime($model->invest_date))}}</span>
            </div>
            {{-- <div class="col-sm-3">
                <label>Date:</label>
                <span>.................................</span>
            </div> --}}
            <div class="col-sm-4">
                <label>Voucher No</label>
                <label>:</label>
                @php
                  
                @endphp
                <span>{{ $model->voucher_no }}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Cash/Bank</label>
                <label>:</label>
                <span>{{$model->fund->name}}</span>
            </div>
            <div class="col-sm-4">
                <label>Investor</label>
                <label>:</label>
                    <span>{{$model->investor}}</span>
            </div>
            <div class="col-sm-4">
                <label>Tentative Receivable Date</label>
                <label>:</label>
                <span>{{date('d/m/Y',strtotime($model->tentative_receivable_date))}}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Particulars</th>
                            <th scope="col" colspan="2" class="text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>          
                                    <strong>{{$model->purpose}}</strong><br>    
                            </td>
                            <td class="text-center" >
                                    <br>{{$model->amount}}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $amount =  $f->format($model->amount);
                                // $amount = getBangladeshCurrency($model->amount);
                                // echo $f->format($model->amount); 
                            @endphp
                            <th>Amount In Words :{{$amount}} TK Only</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <table  class="table table-bordered mt-2">
                    <thead>
                        <tr>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Received By</th>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Accounts</th>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Acknowledge By</th>
                            <th class="text-center" rowspan="3"  style="padding-bottom: 40px">Approved By</th>
                          </tr>
                    </thead>
                   
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
