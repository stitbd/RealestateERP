@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
<style>
    .with-margin {
        margin-bottom: -4px !important;
    }

    .voucher-container {
        font-family: Arial, sans-serif;
        color: black;
        border: 1px solid black;
        padding: 20px;
        max-width: 800px;
        margin: 0 auto;
    }

    .voucher-no {
        font-size: 14px;
        font-weight: bold;
        margin: 0;
        padding-bottom: 5px;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        /* align-items: center; */
        text-align: center;
        border-bottom: 2px solid black;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .logo img {
        max-width: 80px;
    }

    .company-info h2,
    .company-info h1,
    .company-info h4 {
        margin: 0;
        line-height: 1.2;
    }

    .voucher-type {
        border: 1px solid black;
        padding: 10px;
        width: 150px;
        text-align: center;
    }

    .details-section {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .details-section div {
        width: 30%;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .details-table th,
    .details-table td {
        border: 1px solid black;
        padding: 8px;
        text-align: center;
        font-size: 14px;
    }

    .amount-words {
        text-align: left;
        padding-left: 10px;
        font-weight: bold;
    }

    .footer-section {
        display: flex;
        justify-content: space-around;
        border-top: 2px solid black;
        padding-top: 10px;
        margin-top: 20px;
    }

    .footer-box {
        text-align: center;
        font-size: 12px;
        padding: 10px;
    }
</style>
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Income List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('income-list') }}" method="get">
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="category">Income Category</label>
                                    <select name="category" class="form-control chosen-select" id="category"
                                        onchange="filterHead(this);">
                                        <option value="">Select One</option>
                                        @foreach ($categories as $v_category)
                                            @php $incomes = json_decode($v_category->category_type)  @endphp
                                            @if ($incomes && in_array('Income', $incomes))
                                                <option value="{{ $v_category->id }}">{{ $v_category->category_name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="head">Income Head</label>
                                    <select name="head" class="form-control chosen-select" id="head">
                                        <option value="">Select One</option>
                                        @foreach ($head as $v_head)
                                            <option value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-6">
                                    <label for="search">Search For</label>
                                    <input type="text" name="search" class="form-control">
                                </div>
                                <div class="col-lg-3">
                                    <label for="Project">Fund</label>
                                    <select name="fund_id" class="form-control">
                                        <option value="">Select One</option>
                                        @foreach ($fund_data as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="start_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('income-print?category=' . request()->get('category') . '&head=' . request()->get('head') . '&search=' . request()->get('search') . '&fund_id=' . request()->get('fund_id') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                            </div>
                        </div>
                        {{-- <div class="row">
                        <div class="col-12 text-right">
                            <a href="{{url('income-print?fund_id='.request()->get('fund_id').'&start_date='.request()->get('start_date').'&end_date='.request()->get('end_date'))}}" target="_blank" class="btn btn-warning float-end m-2">
                                <i class="fa fa-print" aria-hidden="true"></i> Print 
                            </a>
                        </div>
                    </div> --}}
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
                                    <th>View & Print</th>
                                    <th>Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $i = 0;
                                @endphp
                                @foreach ($income_data as $item)
                                    <tr>
                                        <td> @php
                                            $i =
                                                $income_data instanceof \Illuminate\Pagination\LengthAwarePaginator
                                                    ? $loop->iteration +
                                                        $income_data->perPage() * ($income_data->currentPage() - 1)
                                                    : ++$i;
                                        @endphp {{ $i }}
                                        </td>
                                        <td>{{ date('d/m/Y', strtotime($item->payment_date)) }}</td>
                                        <td>
                                            {{ $item->remarks ? $item->remarks : '' }}
                                        </td>
                                        <td>{{ $item->payment_type }}</td>
                                        <td>{{ $item->fund->name }}</td>
                                        <td class="text-right">Tk. {{ $item->amount }}</td>
                                        <td>
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
                                            <a href="{{ route('print-voucher', $item->id) }}" target="_blank"><i
                                                    class="fa fa-print" style="color: rgb(28, 145, 199)"></i></a>
                                        </td>
                                        <td class="text-center">
                                            {{-- @if ($item->created_at->format('Y-m-d') == date('Y-m-d')) --}}
                                            <a href="{{ route('edit-income', $item->id) }}"><i class="fa fa-edit"
                                                    style="color: rgb(28, 145,199)"></i></a>
                                            <a href="{{ route('statusUpdate', $item->id) }}"
                                                onclick="alert('Are You Sure Want to delete this?')"><i
                                                    class="fa fa-trash text-danger" style=""></i></a>

                                            {{-- @endif --}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            @if ($item->client_name != null)
                                                {!! $item->client_name != null ? 'Client Name:' . $item->client_name . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->client_id != null)
                                                {!! $item->client_id != null ? 'Client ID:' . $item->client_id . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->cheque_no)
                                                {!! $item->cheque_no != null ? 'Check Number:' . $item->cheque_no . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->cheque_issue_date)
                                                {!! $item->cheque_issue_date != null ? 'Check Issue Date:' . $item->cheque_issue_date . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->bank)
                                                {!! $item->bank->name != null ? 'Bank Name:' . $item->bank->name . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->account)
                                                {!! $item->account_id != null ? 'Bank Account Name:' . $item->account->account_no . '<br/>' : '' !!}
                                            @endif
                                            @if ($item->account)
                                                {!! $item->account_id != null ? 'Bank Holder Name:' . $item->account->account_holder_name . '<br/>' : '' !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $total += $item->amount;
                                    @endphp
                                @endforeach
                                <tr>
                                    <th colspan="5">Total</th>
                                    <th class="text-right">Tk. {{ $total }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row pt-3">
                            <div class="col-lg-12">
                                @if ($income_data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    {{ $income_data->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @foreach ($income_data as $v_data)
        @php
            $detail_array = App\Models\IncomeDetails::where('income_id', $v_data->id)->get();
            $company_info = App\Models\Company::where(
                'id',
                $v_data->other_company_id ? $v_data->other_company_id : $v_data->company_id,
            )->first();
        @endphp
        <div class="modal fade" id="view-modal-{{ $v_data['id'] }}" tabindex="-1" role="dialog"
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
                        <div class="container mt-5">
                            <!-- Header Section -->
                            <div class="header-section text-right">
                                <div class="logo">
                                    <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}"
                                        alt="Logo" height="100px" width="100px">
                                </div>
                                <div class="company-info">
                                    <h5 class="with-margin text-left">A sister concern of Unity Group</h4>
                                        <h1>{{ $company_info->name }}</h1>
                                        <h5>Unity, Honesty & Prosperity</h4>
                                            <p>{{ $company_info->address }}</p>
                                </div>

                                {{-- <p class="voucher-no">Voucher No.:</p>
                                <div class="voucher-type">
                                    <p>{{ $v_data->voucher_no }}</p>
                                </div>
                                <p>Date: {{ date('d/m/Y', strtotime($v_data->payment_date)) }}</p> --}}
                                {{-- <div class="voucher-container"> --}}
                                {{-- <div class="header-section"> --}}
                                <p class="voucher-no">Voucher No.:</p>
                                <div class="voucher-type">
                                    <p>{{ $v_data->voucher_no }}</p>
                                </div>
                                <div class="voucher-type">
                                    <p>Credit Voucher</p>
                                </div>
                                {{-- </div> --}}

                                {{-- </div> --}}
                            </div>

                            <!-- Details Section -->
                            <div class="details-section">
                                <div><strong>Head of Accounts:</strong>
                                    {{ $detail_array->pluck('category.category_name')->join(', ') }}</div> <br>
                                {{-- <div><strong>Code No:</strong> {{ $v_data->code_no }}</div> --}}
                                <div><strong>Received From:</strong> {{ $v_data->client_name ?? '' }}</div>
                            </div>

                            <!-- Table for Particulars and Amounts -->
                            <table class="details-table">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Description</th>
                                        <th>Amount (BDT)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detail_array as $index => $v_detail)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>Bring the amount Paid Against:<br>
                                                <strong>
                                                    @if ($v_data->remarks)
                                                        {{ $v_data->remarks }}
                                                    @endif
                                                </strong>
                                                <br>
                                            </td>
                                            <td>{{ number_format($v_detail->amount, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        @php
                                            $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                            $amount = getBangladeshCurrency($v_data->amount);
                                            //echo $f->format($v_data->amount);
                                        @endphp
                                        <td colspan="2" class="amount-words">In Words: {{ $amount }} TK
                                            Only</td>
                                        <td>{{ number_format($v_data->amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <!-- Footer Section -->
                            <div class="footer-section">
                                <div class="footer-box">Received By</div>
                                <div class="footer-box">Accountant</div>
                                <div class="footer-box">Director Admin</div>
                                <div class="footer-box">Director Finance</div>
                                <div class="footer-box">Managing Director / Chairman</div>
                            </div>
                        </div>

                        <div class="container mt-5" style="margin-top: 120px;">
                            <div class="row">
                                <div class="col-sm-5">
                                    <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}"
                                        alt="" height="100px" width="100px">
                                </div>
                                <div class="col-sm-7">
                                    <h2 class="h1">{{ $company_info->name }}</h2>
                                    <h6>{{ $company_info->address }}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4"></div>
                                <div class="col-sm-4 mt-3 text-center mb-3 col-sm-4 p-2 h3"
                                    style="background:black; color:white ">Credit Voucher</div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Received From</label>
                                    <label>:</label>
                                    <span>
                                        <!--    @if ($v_data->project_id != null)
    -->
                                        <!--    {{ $v_data->project->name }}-->
                                        <!--
@elseif($v_data->company_id && $v_data->other_company_id)
    -->
                                        <!--    {{ $v_data->other_company->name }}-->
                                    <!--    @else-->
                                        <!--    {{ $v_data->company->name }}-->
                                        <!--
    @endif-->
                                        @if ($v_data->client_name)
                                            {{ $v_data->client_name }}
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Account Head</label>
                                    <label>:</label>
                                    <span>
                                        @foreach ($detail_array as $v_detail)
                                            @if ($v_detail->category)
                                                {{ $v_detail->category->category_name }}
                                            @endif
                                        @endforeach
                                    </span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Code No</label>
                                    <label>:</label>
                                    @php $date = date('Y'); @endphp
                                    <span>{{ $v_data->code_no }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Receive By</label>
                                    <label>:</label>
                                    <span>
                                        @if ($item->receive_by)
                                            {{ $item->receive_by }}
                                        @endif
                                    </span>
                                </div>
                                <div class="col-sm-3">
                                    <label>Voucher No</label>
                                    <label>:</label>
                                    @php

                                    @endphp
                                    <span>{{ $v_data->voucher_no }}</span>
                                </div>
                                <div class="col-sm-2">
                                    <label>Date</label>
                                    <label>:</label>
                                    <span>{{ date('d/m/Y', strtotime($v_data->payment_date)) }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Amount</label>
                                    <label>:</label>
                                    <span>{{ number_format($v_data->amount) }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <label>Cash/Bank</label>
                                    <label>:</label>
                                    <span>{{ $v_data->payment_type }}</span>
                                </div>
                                <div class="col-sm-4">
                                    <label>Cheque No</label>
                                    <label>:</label>
                                    @if ($v_data->cheque_no)
                                        <span>{{ $v_data->cheque_no }}</span>
                                    @endif
                                </div>
                                <div class="col-sm-4">
                                    <label>Cheque Issue Date</label>
                                    <label>:</label>
                                    <span>{{ $v_data->cheque_issue_date }}</span>
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
                                                @php $detail_array = App\Models\IncomeDetails::where('income_id',$v_data->id)->get(); @endphp
                                                <td>Bring the amount Paid Against:<br>
                                                    <strong>
                                                        @if ($v_data->remarks)
                                                            {{ $v_data->remarks }}
                                                        @endif
                                                    </strong>
                                                    <br>
                                                </td>
                                                <td class="text-center">
                                                    @foreach ($detail_array as $v_detail)
                                                        <br>{{ number_format($v_detail->amount) }}
                                                    @endforeach
                                                </td>
                                                <td class="text-center"><br>{{ $v_data->amount }}</td>
                                            </tr>
                                            <!-- Add more rows as needed -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                @php
                                                    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                                                    $amount = getBangladeshCurrency($v_data->amount);
                                                    //echo $f->format($v_data->amount);
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
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
    <script>
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
    <script>
        $(".chosen-select").chosen();

        function printDiv(divId) {
            var printContents = document.getElementById(divId).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>
@endpush
