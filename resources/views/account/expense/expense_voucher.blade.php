@extends('layouts.app')
<style>
    .h1 {
        font-size: 3.5rem !important;
        font-weight: 600;
    }
</style>
@section('content')
    <div class="col-md-12">
        <a class="btn btn-success text-right" target="_blank"
        href="{{route('credit-voucher')}}">Print</a></div>
        
    <div class="container mt-5" >
        <div class="row">
            <div class="col-md-5">
                <img src="{{ asset('image/logo.png') }}" alt="" height="auto" width="300px">
            </div>
            <div class="col-md-7">
                <h1 class="h1">e-Learning & Earning Ltd</h1>
                <h6>Khaja IT Park(2nd Floor), 7 South Kallyanpur,Mirpur Road, Dhaka-1207, Bangladesh</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 mt-3 text-center mb-3 col-md-4 bg-dark text-white p-2 h3">Credit Voucher</div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Expense For</label>
                <label>:</label>
                <span>{{$details->receiver_name}}</span>
            </div>
            <div class="col-md-4">
                <label>Account Head</label>
                <label>:</label>
                <span> {{$particular_name}} </span>
            </div>
            <div class="col-md-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>EXP-{{$date }}-{{substr($details->nid, -3)}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Expense By</label>
                <label>:</label>
                <span>{{$expense_by}}</span>
            </div>
            <div class="col-md-4">
                <label>Dept</label>
                <label>:</label>
                <span>.................................</span>
            </div>
            {{-- <div class="col-md-3">
                <label>Date:</label>
                <span>.................................</span>
            </div> --}}
            <div class="col-md-4">
                <label>Amount</label>
                <label>:</label>
                <span>{{$model->amount}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Designation</label>
                <label>:</label>
                <span>.................................</span>
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <label>:</label>
                <span>{{$model->payment_date}}</span>
            </div>
            {{-- <div class="col-md-3">
                <label>Date:</label>
                <span>.................................</span>
            </div> --}}
            <div class="col-md-4">
                <label>Voucher No</label>
                <label>:</label>
                @php
                    $date = Date("Y");
                @endphp
                <span>VHR-{{$date}}-{{substr($details->nid, -3)}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Cash/Bank</label>
                <label>:</label>
                @if($model->payment_type == 'Cash')
                <span>Cash</span>
                @else
                <span>Bank</span>
                {{-- @else
                <span>Cheque</span> --}}
                @endif
            </div>
            <div class="col-md-4">
                @if($details->check_number)
                <label>Cheque No</label>
                <label>:</label>
                <span>{{$details->check_number}}</span>
                {{-- @else
                <label>Account No.</label>
                <label>:</label>
                <span>{{$details->bank_account_no}}</span> --}}
                @endif
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <label>:</label>
                <span>{{$details->check_issue_date}}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="col-sm-8 text-center">Particulars</th>
                            <th scope="col"  class="col-sm-4 text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @php  $particular = App\Models\AccountCategory::where('id',$model->particulars)->first() @endphp
                            <td rowspan="4" class="col-sm-8" >Bring the amount Paid Against : {{$particular->category_name}}</td>
                            <td rowspan="4" class="col-sm-4">{{$model->amount}}</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                    <tfoot>
                        <tr>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $amount =  $f->format($model->amount);
                                // echo $f->format($model->amount); 
                            @endphp
                            <th>Amount In Words TK:{{$amount}}</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$details->receiver_name}}</td>
                            <td>{{$expense_by}}</td>
                            <td>{{$expense_by}}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
    <div class="container " style="margin-top:150px">
        <div class="row">
            <div class="col-md-5">
                <img src="{{ asset('image/logo.png') }}" alt="" height="auto" width="300px">
            </div>
            <div class="col-md-7">
                <h1 class="h1">e-Learning & Earning Ltd</h1>
                <h6>Khaja IT Park(2nd Floor), 7 South Kallyanpur,Mirpur Road, Dhaka-1207, Bangladesh</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4 mt-3 text-center mb-3 col-md-4 bg-dark text-white p-2 h3">Credit Voucher</div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Expense For</label>
                <label>:</label>
                <span>{{$details->receiver_name}}</span>
            </div>
            <div class="col-md-4">
                <label>Account Head</label>
                <label>:</label>
                <span> {{$particular_name}} </span>
            </div>
            <div class="col-md-4">
                <label>Code No</label>
                <label>:</label>
                @php $date = date('Y'); @endphp
                <span>EXP-{{$date }}-{{substr($details->nid, -3)}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Expense By</label>
                <label>:</label>
                <span>{{$expense_by}}</span>
            </div>
            <div class="col-md-4">
                <label>Dept</label>
                <label>:</label>
                <span>.................................</span>
            </div>
            {{-- <div class="col-md-3">
                <label>Date:</label>
                <span>.................................</span>
            </div> --}}
            <div class="col-md-4">
                <label>Amount</label>
                <label>:</label>
                <span>{{$model->amount}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Designation</label>
                <label>:</label>
                <span>.................................</span>
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <label>:</label>
                <span>{{$model->payment_date}}</span>
            </div>
            {{-- <div class="col-md-3">
                <label>Date:</label>
                <span>.................................</span>
            </div> --}}
            <div class="col-md-4">
                <label>Voucher No</label>
                <label>:</label>
                @php
                    $date = Date("Y");
                @endphp
                <span>VHR-{{$date}}-{{substr($details->nid, -3)}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <label>Cash/Bank</label>
                <label>:</label>
                @if($model->payment_type == 'Cash')
                <span>Cash</span>
                @else
                <span>Bank</span>
                {{-- @else
                <span>Cheque</span> --}}
                @endif
            </div>
            <div class="col-md-4">
                @if($details->check_number)
                <label>Cheque No</label>
                <label>:</label>
                <span>{{$details->check_number}}</span>
                {{-- @else
                <label>Account No.</label>
                <label>:</label>
                <span>{{$details->bank_account_no}}</span> --}}
                @endif
            </div>
            <div class="col-md-4">
                <label>Date</label>
                <label>:</label>
                <span>{{$details->check_issue_date}}</span>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col" class="col-sm-8 text-center">Particulars</th>
                            <th scope="col"  class="col-sm-4 text-center">Amount (TK)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @php  $particular = App\Models\AccountCategory::where('id',$model->particulars)->first() @endphp
                            <td rowspan="4" class="col-sm-8" >Bring the amount Paid Against : {{$particular->category_name}}</td>
                            <td rowspan="4" class="col-sm-4">{{$model->amount}}</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                    <tfoot>
                        <tr>
                            @php
                                $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                $amount =  $f->format($model->amount);
                                // echo $f->format($model->amount); 
                            @endphp
                            <th>Amount In Words TK:{{$amount}}</th>
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{$details->receiver_name}}</td>
                            <td>{{$expense_by}}</td>
                            <td>{{$expense_by}}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> 
    </div>
@endsection
<script>
    function printDiv(divId) {
     var printContents = document.getElementById(divId).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
