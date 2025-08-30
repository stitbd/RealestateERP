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

 <body>
    <button class="col-sm-1 mt-2 btn btn-success pull-right" onclick="printDiv('printableArea')">Print</button>
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
        <div class="row">
            <div class="col-sm-4">
                <label>Expense For</label>
                <label>:</label>
                <span>
                    @if($model->project_id != null)
                    {{$model->project->name}}
                @else
                    {{$model->company->name}}
                @endif
                </span>
            </div>
            <div class="col-sm-4">
                <label>Account category</label>
                <label>:</label>
                <span> @if($model->category_id) {{$model->category->category_name}} @endif</span>
            </div>
            <div class="col-sm-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>{{$model->code_no}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Expense By</label>
                <label>:</label>
                <span>@if($model->expense_by)
                    {{$model->employee->name}}
                    @else
                    {{$model->expenser_name}}
                    @endif
                </span>
            </div>
            <div class="col-sm-4">
                <label>Dept</label>
                <label>:</label>
                <span>@if($model->expense_by){{$model->employee->department->name}}
                @else {{$model->department}} @endif</span>
            </div>
            <div class="col-sm-4">
                <label>Amount</label>
                <label>:</label>
                <span>{{$model->amount}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Designation</label>
                <label>:</label>
                <span>@if($model->expense_by) {{$model->employee->designation->name}} @else {{$model->designation}} @endif</span>
            </div>
            <div class="col-sm-4">
                <label>Date</label>
                <label>:</label>
                <span>{{$model->payment_date}}</span>
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
                <span>{{$model->payment_type}}</span>
            </div>
            <div class="col-sm-4">
                <label>Cheque No</label>
                <label>:</label>
                @if($model->cheque_no)
                <span>{{$model->cheque_no}}, </span>
                @endif
                @if($bankArray != '' && $bankArray)
                    @foreach($bankArray as $info)
                    <span>{{$info->cheque_no}}</span>
                    @endforeach
                @endif
            </div>
            <div class="col-sm-4">
                <label>Cheque Issue Date</label>
                <label>:</label>
                <span>@if($model->cheque_issue_date){{date('d/m/Y',strtotime($model->cheque_issue_date)) }}, @endif</span>
                @if($bankArray != '' && $bankArray)
                @foreach($bankArray as $info)
                <span>{{date('d/m/Y',strtotime($info->cheque_issue_date))}}</span>
                @endforeach
            @endif
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Particulars</th>
                            <th scope="col" class="text-center">Remarks</th>
                            <th scope="col" colspan="2" class="text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Bring the amount Paid Against:<br>
                                @foreach($detail_array as $v_detail)
                                    <strong>{{$v_detail->head->head_name}}</strong><br>
                                @endforeach
                            </td>
                            <td><br>{{$model->remarks}}</td>
                            <td class="text-center" >
                                @foreach($detail_array as $v_detail)
                                    <br>{{$v_detail->amount}}
                                @endforeach
                            </td>
                            <td class="text-center"><br>{{$model->amount}}</td>
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
                <label>Expense For</label>
                <label>:</label>
                <span>
                    @if($model->project_id != null)
                        {{$model->project->name}}
                    @else
                        {{$model->company->name}}
                    @endif
                </span>
            </div>
            <div class="col-sm-4">
                <label>Account Category</label>
                <label>:</label>
                <span> @if($model->category_id) {{$model->category->category_name}} @endif </span>
            </div>
            <div class="col-sm-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>{{$model->code_no}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Expense By</label>
                <label>:</label>
                <span>@if($model->expense_by)
                    {{$model->employee->name}}
                    @else
                    {{$model->expenser_name}}
                    @endif
                </span>
            </div>
            <div class="col-sm-4">
                <label>Dept</label>
                <label>:</label>
                <span>@if($model->expense_by){{$model->employee->department->name}}
                @else {{$model->department}} @endif</span>
            </div>
            <div class="col-sm-4">
                <label>Amount</label>
                <label>:</label>
                <span>{{$model->amount}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label>Designation</label>
                <label>:</label>
                <span>@if($model->expense_by) {{$model->employee->designation->name}} @else {{$model->designation}} @endif</span>
            </div>
            <div class="col-sm-4">
                <label>Date</label>
                <label>:</label>
                <span>{{$model->payment_date}}</span>
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
                <span>{{$model->payment_type}}</span>
            </div>
            <div class="col-sm-4">
                <label>Cheque No</label>
                <label>:</label>
                @if($model->cheque_no)
                <span>{{$model->cheque_no}}, </span>
                @endif
                @if($bankArray != '' && $bankArray)
                    @foreach($bankArray as $info)
                    <span>{{$info->cheque_no}}</span>
                    @endforeach
                @endif
            </div>
            <div class="col-sm-4">
                <label>Cheque Issue Date</label>
                <label>:</label>
                <span>@if($model->cheque_issue_date){{date('d/m/Y',strtotime($model->cheque_issue_date)) }},  @endif</span>
                @if($bankArray != '' && $bankArray)
                @foreach($bankArray as $info)
                <span>{{date('d/m/Y',strtotime($info->cheque_issue_date))}}</span>
                @endforeach
            @endif
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-sm-12">
                <table class="table table-bordered" width="100%">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Particulars</th>
                            <th scope="col" class="text-center">Remarks</th>
                            <th scope="col" colspan="2" class="text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Bring the amount Paid Against:<br>
                                @foreach($detail_array as $v_detail)
                                    <strong>{{$v_detail->head->head_name}}</strong><br>
                                @endforeach
                            </td>
                            <td><br>{{$model->remarks}}</td>
                            <td class="text-center">
                                @foreach($detail_array as $v_detail)
                                <br>{{$v_detail->amount}}
                                @endforeach
                            </td>
                            <td class="text-center"><br>{{$model->amount}}</td>
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
                            <th>Amount In Words :{{$amount}} TK ONLY</th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
                <table  class="table table-bordered mt-2 mb-5">
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
