@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Expense Requisition List
                    </h3> 
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <div class="row">
                        {{-- <div class="col-12 text-right">
                            <a href="{{url('expese-print?category='.request()->get('category').'&head='.request()->get('head').'&search='.request()->get('search').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                        </div> --}}
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead class="bg-info">
                            <tr>
                                <th>SL NO.</th>
                                <th>Date</th>
                                <th>Particulars</th>
                                <th>Type</th>
                                <th>Fund</th>
                                <th>Amount</th>
                                <th>Attachment</th>
                                <th>Approved By</th>
                                <th>Action</th>
                                <th>View & Print</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $i = 0
                            @endphp
                            {{-- @dd($expense) --}}
                            @foreach ($expense as $item)
                            @if (
                                (auth()->user()->id == $item->user->id && $item->forward_status == '')
                                || ((auth()->user()->role == 'User') && in_array($item->forward_status, [1, 2, 3]))
                                || (auth()->user()->role == 'Admin' && in_array($item->forward_status, [2, 1, 3]))
                                || (auth()->user()->role == 'SuperAdmin' && in_array($item->forward_status, [3, 2]))
                            )
                            <tr>
                                {{-- <td>{{ ++$i }}</td> --}}
                                <td> @php
                                    $i = ($expense instanceof \Illuminate\Pagination\LengthAwarePaginator) ? ($loop->iteration + ($expense->perPage() * ($expense->currentPage() - 1)))  : ++$i;
                                @endphp {{$i}}
                                </td>
                                <td>{{ date('d/m/Y',strtotime($item->payment_date)) }}</td>
                                <td>
                                   {{$item->remarks}}
                                </td>
                                <td>{{ $item->payment_type }}</td>
                                <td>{{ $item->fund->name }}</td>

                                <td class="text-right">Tk. {{ number_format($item->amount) }}</td>
                                <td>
                                    @if ($item->attachment != null)
                                        <a href="{{ asset('attachment/'.$item->attachment)}}" target="_blank" class="btn btn-info">
                                                <i class="fa fa-download"></i>
                                        </a>
                                    @else
                                    <a data-toggle="modal" data-target="#addAttachment-{{ $item['id'] }}" class="btn btn-primary" style="color:white;">
                                            <i class="fa fa-plus"></i>
                                       </a>
                                    @endif
                                </td>

                                <td>
                                    @if($item->forward_status == 1)
                                            <span class="text-success">User</span>
                                    @elseif($item->forward_status == 2)
                                        <span class="text-success">Admin</span>
                                    @elseif($item->forward_status == 3)
                                        <span class="text-success">SuperAdmin</span>
                                    @endif
                                </td>
                                
                                <td class="text-center">
                                    @if($item->forward_status == 3 && $item->user->id == auth()->user()->id)
                                        <a href="{{url("update-expense-status/$item->id/1")}}" class="btn btn-success btn-sm"><i class="fa fa-check"></i>Confirm</a>
                                    @endif

                                    @if(auth()->user()->role == 'User')
                                        @if( $item->forward_status != 1 && $item->forward_status != 2 && $item->forward_status != 3)
                                            <a href="{{url("update-forward-status/$item->id/1")}}" class="btn btn-info btn-sm"><i class="pl-2 fa fa-check"></i>Approve</a>
                                            <a href="{{url("update-expense-status/$item->id/2")}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>Reject</a>
                                            <a href="{{route('edit-expense',$item->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>Edit</a>
                                        @endif
                                    @elseif( auth()->user()->role == 'Admin')
                                       @if( $item->forward_status != 2 && $item->forward_status != 3|| $item->forward_status == 1 )
                                            <a href="{{url("update-forward-status/$item->id/2")}}" class="btn btn-info btn-sm"><i class="pl-2 fa fa-check"></i>Approve</a>
                                            <a href="{{url("update-expense-status/$item->id/2")}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>Reject</a>
                                            <a href="{{route('edit-expense',$item->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>Edit</a>

                                       @endif

                                    @elseif(auth()->user()->role == 'SuperAdmin')
                                        @if( $item->forward_status != 3 )
                                           <a href="{{url("update-forward-status/$item->id/3")}}" class="btn btn-info btn-sm"><i class="pl-2 fa fa-check"></i>Approve</a>
                                           <a href="{{url("update-expense-status/$item->id/2")}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i>Reject</a>
                                           <a href="{{route('edit-expense',$item->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i>Edit</a>

                                       @endif
                                    @endif
                                  
                                </td>
                              
                                <td>
                                    <a data-toggle="modal" data-target="#view-modal-{{ $item['id'] }}"><i class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                    <a href="{{route('print-debit-voucher',$item->id)}}" target="_blank"><i class="fa fa-print" style="color: rgb(28, 145, 199)"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-center">
                                    @if(auth()->user()->role == 'SuperAdmin'){!!($item->user->name !=null)?'<strong>Created By:</strong>:'.$item->user->name .'<br/>':''!!}@endif
                                    @if($item->updated_by){!!($item->update_by->name !=null)?'<strong>Edited By:</strong>'.$item->update_by->name .'<br/>':''!!}@endif
                                    @if($item->expenser_name){!!($item->expenser_name !=null)?'<strong>Expenser Name:</strong>:'. $item->expenser_name .'<br/>':''!!}@endif
                                    @if($item->project) {!!($item->project_id !=null)?'<strong>Project Name:</strong>:'.$item->project->name.'<br/>':''!!} @endif
                                    @if($item->fund_id==1)
                                    {{-- <span style="color: green; border-bottom: 1px solid rgb(156, 154, 154); padding-top:20px">Bank Information</span><br> --}}
                                    <table class="table table-bordered table-striped" style="margin-bottom:16px; margin-top:20px; ">
                                        <thead>
                                            <tr>
                                                <th>Bank Name</th>
                                                <th>Acc. No</th>
                                                <th>Amount</th>
                                                <th>Check No.</th>
                                                <th>Check Issue Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $bankInfo = App\Models\ExpenseBankInfo::where('master_id',$item->id)->get(); @endphp
                                            @if($bankInfo)
                                                @foreach($bankInfo as $bank)
                                                 <tr>
                                                    <td>{!!($bank->bank_id !=null)? $bank->bank->name:''!!}</td>
                                                    <td>{!!($bank->account_id !=null)? $bank->bank_account->account_no:''!!}</td>
                                                    <td>{!!($bank->amount!=null)? $bank->amount:''!!}</td>
                                                    <td> {!!($bank->cheque_no!=null)? $bank->cheque_no:''!!}</td>
                                                    <td>{!!($bank->cheque_issue_date!=null)? $bank->cheque_issue_date:''!!}</td>
                                                </tr>
                                            @endforeach
                                           
                                            @endif
                                        </tbody>
                                    </table>
                                    @endif
                                </td>
                            </tr>

                            @php
                                $total += $item->amount;
                            @endphp
                             @endif
                            @endforeach
                            {{-- <tr>
                                <th colspan="5">Total</th>
                                <th class="text-right">Tk. {{ number_format($total)}}</th>
                            </tr> --}}
                        </tbody>
                    </table>

                    <div class="row pt-3">
                        <div class="col-lg-12">
                            @if($expense instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $expense->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($expense as $model)
@php 
    // $detail_array = App\Models\IncomeDetails::where('income_id',$v_data->id)->get(); 
    $company_info = App\Models\Company::where('id',$model->other_company_id?$model->other_company_id : $model->company_id)->first(); 
@endphp
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
                        <img src="{{ asset('upload_images/company_logo/'.$company_info->logo) }}" alt="" height="auto" width="300px">
                    </div>
                    <div class="col-sm-7">
                        <h2 class="h1">{{$company_info->name}}</h2>
                        <h6>{{$company_info->address}}</h6>
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
                           {{$model->category->category_name}}
                        </span>
                    </div>
                    <div class="col-sm-4">
                        <label>Account Category</label>
                        <label>:</label>
                        <span> @if($model->head_id) {{$model->head->head_name}} @endif </span>
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
                        <span>@if($model->expense_by){{$model->employee->name}} @else {{$model->expenser_name}} @endif</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Dept</label>
                        <label>:</label>
                        <span>@if($model->expense_by){{$model->employee->department->name}} @else {{$model->department}} @endif</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Amount</label>
                        <label>:</label>
                        <span>{{ number_format($model->amount)}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Designation</label>
                        <label>:</label>
                        <span>@if($model->expense_by){{$model->employee->designation->name}} @else {{$model->designation}} @endif</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Date</label>
                        <label>:</label>
                        <span>{{ date('d/m/Y',strtotime($model->payment_date)) }}</span>
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
                        <span>{{$model->cheque_no}}</span>
                        @endif
                    </div>
                    <div class="col-sm-4">
                        <label>Cheque Issue Date</label>
                        <label>:</label>
                        <span>@if($model->cheque_issue_date){{date('d/m/Y',strtotime($model->cheque_issue_date))}}@endif</span>
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
                                    <td>Bring the amount Paid Against:<br>
                                        {{$model->remarks}}
                                    </td>
                                    <td class="text-center"><br>{{ number_format($model->amount)}}</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    @php
                                        $f = new NumberFormatter("BDT", NumberFormatter::SPELLOUT);
                                        // $amount =  $f->format($model->amount);
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
                        <img src="{{ asset('upload_images/company_logo/'.$company_info->logo) }}" alt="" height="auto" width="300px">
                    </div>
                    <div class="col-sm-7">
                        <h2 class="h1">{{$company_info->name}}</h2>
                        <h6>{{$company_info->address}}</h6>
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
                           {{$model->category->category_name}}
                        </span>
                    </div>
                    <div class="col-sm-4">
                        <label>Account Category</label>
                        <label>:</label>
                        <span> @if($model->head_id) {{$model->head->head_name}} @endif </span>
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
                        <span>@if($model->expense_by){{$model->employee->name}} @else {{$model->expenser_name}} @endif</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Dept</label>
                        <label>:</label>
                        <span>@if($model->expense_by){{$model->employee->department->name}} @else {{$model->department}} @endif</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Amount</label>
                        <label>:</label>
                        <span>{{ number_format($model->amount)}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <label>Designation</label>
                        <label>:</label>
                        <span>@if($model->expense_by){{$model->employee->designation->name}} @else {{$model->designation}} @endif</span>
                    </div>
                    <div class="col-sm-4">
                        <label>Date</label>
                        <label>:</label>
                        <span>{{ date('d/m/Y',strtotime($model->payment_date)) }}</span>
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
                        <span>{{$model->cheque_no}}</span>
                        @endif
                    </div>
                    <div class="col-sm-4">
                        <label>Cheque Issue Date</label>
                        <label>:</label>
                        <span>@if($model->cheque_issue_date){{date('d/m/Y',strtotime($model->cheque_issue_date))}}@endif</span>
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
                                    <td>Bring the amount Paid Against:<br>
                                        {{$model->remarks}}
                                    </td>
                                    <td class="text-center"><br>{{ number_format($model->amount)}}</td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                            <tfoot>
                                <tr>
                                    @php
                                        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
                                        // $amount =  $f->format($model->amount);
                                        $amount = getBangladeshCurrency($model->amount);
                                        // echo $f->format($model->amount); 
                                    @endphp
                                    <th>Amount In Words :{{$amount}} TK Only</th>
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
    </div>
</div>
</div>

<div class="modal fade" id="addAttachment-{{ $model['id'] }}" role="dialog"
aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content custom-modal ">
        <div class="d-flex justify-content-between p-3">
            <h5 class="modal-title" id="exampleModalLabel">Add Attachment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span class="fs-2 mr-3 mt-3" aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{route('add-attachment',$model->id)}}" method="POST" enctype="multipart/form-data">
            @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                    <input type="file" name="attachment" class="form-control">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    </div>
</div>
</div>
@endforeach

@endsection
@push('script_js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
    $(".chosen-select").chosen();

    function filterHead() {
            var category_id = document.getElementById('category').value;
                var url = "{{ route('filter-head') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id
                    },
                    success: function(data) {
                        $('#head').find('option').remove();
                        $('#head').html('');
                        $('#head').append(`<option value="" disabled selected>Select One</option>`);
                        $.each(data, function(key, value) {
                            $('#head').append(`
                            <option value="` + value.id + `">` + value.head_name +
                                `</option>`);
                        });
                        $('#head').trigger("chosen:updated");
                    },
                });
                $(".chosen-select").chosen();
            
           
        }
</script>
@endpush

