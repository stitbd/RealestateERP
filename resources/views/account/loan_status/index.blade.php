@extends('layouts.app')
<style>
    .td{
     
    }
</style>
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Loan List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('loan-list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="loanee_name">Employee</label>
                                    <input type="text" name="loanee_name" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label for="company_id">Company</label>
                                    <select class="form-control" name="company_id">
                                        <option value="">Select One</option>
                                        @foreach ($companies as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="fund">Fund Type</label>
                                    <select name="fund_id" class="form-control">
                                        <option value="">Select a Fund </option>
                                        @foreach ($fund_types as $fund)
                                            <option value="{{ $fund->id }}">{{ $fund->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="bank">Bank</label>
                                    <select name="bank_id" class="form-control">
                                        <option value="">Select a Bank </option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">From</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_date">To</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('loan-print?loanee_name=' . request()->get('loanee_name') . '&company_id=' . request()->get('company_id') . '&fund_id=' . request()->get('fund_id') . '&start_date=' . request()->get('start_date') . '&bank_id=' . request()->get('bank_id') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                                <a href="{{ url('loan-pdf?loanee_name=' . request()->get('loanee_name') . '&company_id=' . request()->get('company_id') . '&fund_id=' . request()->get('fund_id') . '&start_date=' . request()->get('start_date') . '&bank_id=' . request()->get('bank_id') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn  btn-danger float-end m-2">
                                    <i class="fas fa-file-pdf" aria-hidden="true"></i> Pdf
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>Loan Provide Date</th>
                                    <th>Fund </th>
                                    <th>Payment Type</th>
                                    <th>Remarks </th>
                                    <th>Loan Amount</th>
                                    <th>Maturity Date</th>
                                    <th>Attachment</th>
                                    <th>View & Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($loans) --}}
                                @foreach ($loans as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($item->loan_date)) }}</td>
                                        {{-- <td class="text-center">{{ $item->loan_provider ?? '' }}</td> --}}
                                        <td class="text-center">{{ $item->fund->name ?? '' }}</td>
                                        <td class="text-center">{{ $item->payment_type ?? '' }}</td>
                                        <td class="text-center">{{ $item->remarks ?? '' }}</td>
                                        <td class="text-center">{{ $item->amount }} Tk.</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($item->valid_date)) }}</td>

                                        <td class="text-center">
                                            @if ($item->attachment != null)
                                                <a href="{{ asset('attachment/' . $item->attachment) }}" target="_blank"
                                                    class="btn btn-info">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            @endif
                                        </td>

                                        <td>
                                            <a data-toggle="modal" data-target="#view-modal-{{ $item['id'] }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a href="{{ route('print-loan-debit-voucher', $item->id) }}" target="_blank"><i
                                                    class="fa fa-print" style="color: rgb(28, 145, 199)"></i></a>
                                        </td>
                                    </tr>
                                    <tr class="text-center">
                                        <td colspan="10" class="text-center" style="justify-content: left">
                                            {!! $item->loanee_name != null ? '<strong>Name:  </strong>' . $item->loanee_name . '<br/>' : '' !!}
                                            {!! $item->department != null ? '<strong>Department:  </strong>' . $item->department . '<br/>' : '' !!}
                                            {!! $item->designation != null ? '<strong>Designation: </strong>' . $item->designation . '<br/>' : '' !!}
                                            {!! $item->company->name != null ? '<strong>Company:  </strong>' . $item->company->name . '<br/>' : '' !!}
                                            {!! $item->address != null ? '<strong>Address:  </strong>' . $item->address . '<br/>' : '' !!}
                                            {!! $item->phone != null ? '<strong>Mobile No: </strong>' . $item->phone . '<br/>' : '' !!}
                                            {!! $item->email != null ? '<strong>Email:</strong>' . $item->email . '<br/>' : '' !!}
                                            {!! $item->description != null ? '<strong>Loan Purpose:</strong>' . $item->description . '<br/>' : '' !!}
                                            {!! $item->nid != null
                                                ? '<strong>NID: </strong>' .
                                                    '<a href="' .
                                                    asset('nid/' . $item->nid) .
                                                    '" target="_blank">' .
                                                    $item->nid .
                                                    '</a>' .
                                                    '<br/>'
                                            : '' !!}

                                                @if($item->fund_id==1)
                                                    <span style="color: green; border-bottom: 1px solid rgb(156, 154, 154)">Bank Information</span><br>
                                                    <table class="table table-bordered table-striped" style="margin-bottom:16px;">
                                                    <thead>
                                                    <tr>
                                                        <th>Bank Name</th>
                                                        <th>Acc. No</th>
                                                        <th>Acc. Holder Name</th>
                                                        <th>Check No.</th>
                                                        <th>Check Issue Date</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                        <td>{!!($item->bank_id !=null)? $item->bank->name:''!!}</td>
                                                        <td>{!!($item->account_id !=null)? $item->account->account_no:''!!}</td>
                                                        <td>{!!($item->account->account_holder_name !=null)? $item->account->account_holder_name:''!!}</td>
                                                        <td> {!!($bank->cheque_no!=null)? $bank->cheque_no:''!!}</td>
                                                        <td>{!!($bank->cheque_issue_date!=null)? $bank->cheque_issue_date:''!!}</td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                                @endif
                                        
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $loans->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($loans as $model)
        <div class="modal fade" id="view-modal-{{ $model['id'] }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content custom-modal">
                    <div class="d-flex justify-content-end">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="fs-2 mr-3 mt-3" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
                                           @if($model->category){{$model->category->category_name}} @endif
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Account Sub Head</label>
                                    <label>:</label>
                                    <span> @if($model->head) {{$model->head->head_name}} @endif</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Code No</label>
                                    <label>:</label>
                                    @php $date = date('Y'); @endphp
                                    <span>{{$model->loan_code}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Employee</label>
                                    <label>:</label>
                                    <span>{{$model->loanee_name}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Dept.</label>
                                    <label>:</label>
                                    <span>{{$model->department}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Address</label>
                                    <label>:</label>
                                    <span>{{$model->address}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Designation</label>
                                    <label>:</label>
                                    <span>{{$model->designation}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <label>:</label>
                                    <span>{{date('d/m/Y',strtotime($model->loan_date))}}</span>
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
                                    <label>Loan Provider</label>
                                    <label>:</label>
                                        <span>{{$model->loan_provider}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Maturity Date</label>
                                    <label>:</label>
                                    <span>{{date('d/m/Y',strtotime($model->valid_date))}}</span>
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
                                                        <strong>{{$model->remarks}}</strong><br>    
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
                                                    $amount = getBangladeshCurrency($model->amount);
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
                                           @if($model->category){{$model->category->category_name}} @endif
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Account Sub Head</label>
                                    <label>:</label>
                                    <span> @if($model->head) {{$model->head->head_name}} @endif</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Code No</label>
                                    <label>:</label>
                                    @php $date = date('Y'); @endphp
                                    <span>{{$model->loan_code}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Employee</label>
                                    <label>:</label>
                                    <span>{{$model->loanee_name}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Dept.</label>
                                    <label>:</label>
                                    <span>{{$model->department}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Address</label>
                                    <label>:</label>
                                    <span>{{$model->address}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Designation</label>
                                    <label>:</label>
                                    <span>{{$model->designation}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <label>:</label>
                                    <span>{{date('d/m/Y',strtotime($model->loan_date))}}</span>
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
                                    <label>Cash/Bank/Bkash/Card</label>
                                    <label>:</label>
                                    <span>{{$model->fund->name}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Loan Provider</label>
                                    <label>:</label>
                                        <span>{{$model->loan_provider}}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Maturity Date</label>
                                    <label>:</label>
                                    <span>{{date('d/m/Y',strtotime($model->valid_date))}}</span>
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
                                                        <strong>{{$model->remarks}}</strong><br>    
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
                                                    $amount = getBangladeshCurrency($model->amount);
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
                </div>
            </div>
        </div>
    @endforeach
@endsection
