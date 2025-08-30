@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Expense List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('site-expense-list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="category">Expense Category</label>
                                    <select name="category" class="form-control chosen-select" id="category"
                                        onchange="filterHead(this);">
                                        <option value="">Select One</option>
                                        @foreach ($categories as $v_category)
                                            @php $expenses = json_decode($v_category->category_type)  @endphp
                                            @if ($expenses && in_array('Expense', $expenses))
                                                <option value="{{ $v_category->id }}">{{ $v_category->category_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="head">Expense Head</label>
                                    <select name="head" class="form-control chosen-select" id="head">
                                        <option value="">Select One</option>
                                        @foreach ($head as $v_head)
                                            <option value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-lg-6">
                                <label for="search">Search For</label>
                                <input type="text" name="search" class="form-control"  value="{{$serachText? $serachText : ''}}">
                            </div> --}}
                                <div class="col-lg-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-3" id="search">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('expese-print?category='.request()->get('category').'&head='.request()->get('head').'&search='.request()->get('search').'&fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                        </div>
                    </div> --}}
                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>SL NO.</th>
                                    <th>Expense Code</th>
                                    <th>Date</th>
                                    <th>Account Main Head</th>
                                    <th>Account Sub Head</th>
                                    <th>Particulars</th>
                                    <th>Amount</th>
                                    <th>Attachment</th>
                                    <th>View & Print</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $i = 0;
                                @endphp

                                {{-- @dd($expense) --}}
                                @foreach ($expense as $item)
                                    {{-- @dd($item) --}}
                                    <tr>
                                        {{-- <td>{{ ++$i }}</td> --}}
                                        <td>
                                            @php
                                                $i =
                                                    $expense instanceof \Illuminate\Pagination\LengthAwarePaginator
                                                        ? $loop->iteration +
                                                            $expense->perPage() * ($expense->currentPage() - 1)
                                                        : ++$i;

                                            @endphp {{ $i }}
                                        </td>
                                        <td>{{ $item->code_no }}</td>
                                        <td>{{ date('d/m/Y', strtotime($item->payment_date)) }}</td>
                                        <td>
                                            {{ $item->head ? $item->head->category_name : '' }}
                                        </td>
                                        <td>
                                            {{ $item->sub_head ? $item->sub_head->head_name : '' }}
                                        </td>
                                        <td>
                                            {{ $item->remarks }}
                                        </td>
                                        <td class="text-right">Tk. {{ number_format($item->amount) }}</td>
                                        <td class="text-center">
                                            @if (!empty($item->attachment))
                                                <a href="{{ asset('attachment/' . $item->attachment) }}" target="_blank"
                                                    class="btn btn-info">
                                                    <i class="fa fa-download"></i>
                                                </a>
                                            @endif
                                            {{-- @else
                                    <a data-toggle="modal" data-target="#addAttachment-{{ $item['id'] }}" class="btn btn-primary" style="color:white;">
                                            <i class="fa fa-plus"></i>
                                       </a>
                                    @endif --}}
                                        </td>

                                        <td>
                                            <a data-toggle="modal" data-target="#view-modal-{{ $item['id'] }}"><i
                                                    class="fa fa-eye pr-2 pl-2" style="color: rgb(78, 151, 78)"></i></a>
                                            <a href="{{ route('print-site-expense', $item->id) }}" target="_blank"><i
                                                    class="fa fa-print" style="color: rgb(28, 145, 199)"></i></a>
                                        </td>
                                        <td class="text-center">
                                            {{-- @if ($item->created_at->format('Y-m-d') == date('Y-m-d')) --}}
                                            <a href="{{ route('site-expense-edit', $item->id) }}"><i class="fa fa-edit"
                                                    style="color: rgb(28, 145,199)"></i></a>
                                            <a href="{{ route('site-expense-delete', $item->id) }}"
                                                onclick="alert('Are You Sure Want to delete this?')"><i
                                                    class="fa fa-trash text-danger" style=""></i></a>

                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            @if ($item->expenser_name)
                                                Expenser Name: {{ $item->expenser_name }} <br>
                                            @endif
                                            @if ($item->expenser_designation)
                                                Expenser Designation: {{ $item->expenser_designation }} <br>
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $total += $item->amount;
                                    @endphp
                                @endforeach
                                <tr>
                                    <th colspan="6">Total</th>
                                    <th class="text-right">Tk. {{ number_format($total) }}</th>
                                </tr>
                            </tbody>
                        </table>

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                @if ($expense instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $expense->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- @dd($expense) --}}
    @foreach ($expense as $model)
        @php
            // $detail_array = App\Models\IncomeDetails::where('income_id',$v_data->id)->get();
            $company_info = App\Models\Company::where(
                'id',
                auth()->user()->company_id
            )->first();
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
                        <div class="container mt-5" style="padding: 20px">
                           
                            <div class="row">
                                <div class="col-sm-6">
                                    <img src="@if(!empty($company_info->logo)){{ asset('upload_images/company_logo/' . $company_info->logo)}}  @endif"
                                        alt="" height="auto" width="250px">
                                </div>
                                <div class="col-sm-6">
                                    
                                    <h2 class="h3">{{ $company_info->name }}</h2>
                                    <h6>{{ $company_info->address }}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4 mt-3 text-center mb-3 col-sm-4 p-2 h3"
                                    style="background:black; color:white ">Debit Voucher</div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Account Category</label>
                                    <label>:</label>
                                    <span>
                                        {{ $model->head->category_name }}
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Account Head</label>
                                    <label>:</label>
                                    <span>
                                        @if ($model->sub_head_id)
                                            {{ $model->sub_head->head_name }}
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Code No</label>
                                    <label>:</label>
                                    @php $date = date('Y'); @endphp
                                    <span>{{ $model->code_no }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <label>:</label>
                                    <span>{{ date('d/m/Y', strtotime($model->payment_date)) }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Amount</label>
                                    <label>:</label>
                                    <span>{{ number_format($model->amount) }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Voucher No</label>
                                    <label>:</label>
                                    @php

                                    @endphp
                                    <span>{{ $model->voucher_no }}</span>
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
                                                    {{ $model->remarks }}
                                                </td>
                                                <td class="text-center"><br>{{ number_format($model->amount) }}</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                @php
                                                    $f = new NumberFormatter('BDT', NumberFormatter::SPELLOUT);
                                                    // $amount =  $f->format($model->amount);
                                                    $amount = getBangladeshCurrency($model->amount);
                                                    // echo $f->format($model->amount);
                                                @endphp
                                                <th>Amount In Words :{{ $amount }} TK Only</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <table class="table table-bordered mt-2">
                                        <thead>
                                            <tr>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Received By</th>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Accounts</th>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Acknowledge By</th>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Approved By</th>
                                            </tr>
                                        </thead>

                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="container"  style="margin-top:150px;padding:20px">
                           
                            <div class="row">
                                <div class="col-sm-6">
                                    <img src="@if(!empty($company_info->logo)){{ asset('upload_images/company_logo/' . $company_info->logo)}}  @endif"
                                        alt="" height="auto" width="250px">
                                </div>
                                <div class="col-sm-6">
                                    
                                    <h2 class="h3">{{ $company_info->name }}</h2>
                                    <h6>{{ $company_info->address }}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4 mt-3 text-center mb-3 col-sm-4 p-2 h3"
                                    style="background:black; color:white ">Debit Voucher</div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Account Category</label>
                                    <label>:</label>
                                    <span>
                                        {{ $model->head->category_name }}
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Account Head</label>
                                    <label>:</label>
                                    <span>
                                        @if ($model->sub_head_id)
                                            {{ $model->sub_head->head_name }}
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Code No</label>
                                    <label>:</label>
                                    @php $date = date('Y'); @endphp
                                    <span>{{ $model->code_no }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Date</label>
                                    <label>:</label>
                                    <span>{{ date('d/m/Y', strtotime($model->payment_date)) }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Amount</label>
                                    <label>:</label>
                                    <span>{{ number_format($model->amount) }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Voucher No</label>
                                    <label>:</label>
                                    @php

                                    @endphp
                                    <span>{{ $model->voucher_no }}</span>
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
                                                    {{ $model->remarks }}
                                                </td>
                                                <td class="text-center"><br>{{ number_format($model->amount) }}</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                @php
                                                    $f = new NumberFormatter('BDT', NumberFormatter::SPELLOUT);
                                                    // $amount =  $f->format($model->amount);
                                                    $amount = getBangladeshCurrency($model->amount);
                                                    // echo $f->format($model->amount);
                                                @endphp
                                                <th>Amount In Words :{{ $amount }} TK Only</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <table class="table table-bordered mt-2">
                                        <thead>
                                            <tr>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Received By</th>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Accounts</th>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Acknowledge By</th>
                                                <th class="text-center" rowspan="3" style="padding-bottom: 40px">
                                                    Approved By</th>
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

<script></script>
