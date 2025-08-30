{{-- @extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        Income Edit
                    </h3>
                </div> <!-- /.card-body -->
                <div class="card-body p-3">
                    <form action="{{ route('update-income',$income->id) }}" method="post" enctype="multipart/form-data" id="myForm">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 ">

                                <div class="row border m-2 p-2" style="border-color: green !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                        Income Required Information
                                    </h6>
                                    <div class="col-lg-6">
                                        <label for="company">Company<i
                                    class="text-danger pt-1" style="font-size:16px">*</i> </label>
                                        <select name="company" id="company" class="form-control chosen-select" onchange="filterProject();">
                                            <option value="">Select Company..</option>
                                           @foreach ($company as $v_company)
                                           <option value="{{ $v_company->id }}" @if ($v_company->id == $income->company_id) selected @endif>{{ $v_company->name }}</option>
                                           @endforeach
                                        </select>

                                    </div>
                                    <div class="col-lg-6">
                                        <label for="project">Project</label>
                                        <select name="project" id="project" class="form-control chosen-select" >
                                            <option value="">Select Project..</option>
                                           @foreach ($project as $v_project)
                                           <option value="{{ $v_project->id }}" @if ($v_project->id == $income->project_id) selected @endif>{{ $v_project->name }}</option>
                                           @endforeach
                                        </select>
                                    </div>
                                    @php $income_details = App\Models\IncomeDetails::where('income_id',$income->id)->get(); @endphp
                                    @if ($income_details)
                                    @foreach ($income_details as $detail)
                                        <input type="hidden" name="detail_id[]" id="" value="{{$detail->id}}">
                                        <div class="col-lg-12">
                                            <label for="category">Income Category<i
                                        class="text-danger">*</i> </label>
                                            <select name="category[]" id="category-1" class="form-control category chosen-select"  onchange="filterHead(this); generateIncomeCode();newCategoryAdd(this);" required>
                                                <option value="">Select Category..</option>
                                                @foreach ($categories as $t_category)
                                                @php $incomes = json_decode($t_category->category_type)  @endphp
                                                @if ($incomes && in_array('Income', $incomes))
                                                    <option value="{{$t_category->id}}" @if ($t_category->id == $detail->category_id) selected @endif >{{$t_category->category_name}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <div id="categoryAdd-1">

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="head">Income Head<i
                                        class="text-danger">*</i></label>
                                            <select name="head[]" id="head-1" class="form-control chosen-select" onchange="newHeadAdd(this);">
                                                <option value="">Select Head</option>
                                                @foreach ($head as $v_head)
                                                <option value="{{ $v_head->id }}" @if ($v_head->id == $detail->head_id) selected @endif>{{ $v_head->head_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="date">Amount <i
                                        class="text-danger">*</i></label>
                                            <input type="number" name="amount[]" id="amount-1"  class="form-control amount" placeholder="Enter Amount" value="{{$detail->amount}}" onkeyup="totalAmount(this); ">
                                        </div>
                                        @endforeach
                                    @endif
                                    <div class="" id="wrapper">

                                    </div>
                                    <div class="col-lg-6">
                                        <label for="date">Date <i
                                    class="text-danger">*</i></label>
                                        <input type="date" name="payment_date" required class="form-control" value="{{$income->payment_date}}">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Code No">Code No <i
                                    class="text-danger">*</i></label>
                                        <input type="text" class="form-control" name="code_no" id="code_no" placeholder="Enter an Exp. Code No." value="{{$income->code_no}}">
                                        <small style="color:red">Code No. Should Be Unique</small>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Fund">Fund <i
                                    class="text-danger">*</i></label>
                                        <select name="fund_id" id="fund_id" class="form-control" required  onchange="checkPaymentType();checkType();">
                                            <option value="">Select One</option>
                                            @foreach ($fund_data as $v_fund)
                                            <option value="{{ $v_fund->id }}" @if ($v_fund->id == $income->fund_id) selected @endif >{{ $v_fund->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="Fund">Payment Type <i
                                        class="text-danger">*</i></label>
                                        <select name="payment_type" class="form-control" id="payment_type" required>
                                            <option value="Cash" @if ($income->payment_type == 'Cash') selected @endif>Cash</option>
                                            <option value="Cheque" @if ($income->payment_type == 'Cheque') selected @endif>Cheque</option>
                                            <option value="Bank" @if ($income->payment_type == 'Bank') selected @endif>Bank</option>
                                            <option value="Others" @if ($income->payment_type == 'Others') selected @endif>Others</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="total_amount">Total amount <i
                                    class="text-danger">*</i></label>
                                        <input type="text" id="total_amount" required name="total_amount" class="form-control" value="{{$income->amount}}" />
                                        <input type="hidden" id="hidden_amount" required  class="form-control" value="{{$income->amount}}" />
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="remarks">Remarks</label>
                                        <input type="text" name="remarks" class="form-control" value="{{$income->remarks}}" required/>
                                    </div>
                                    <div class="col-lg-12">
                                        <label for="adjustment_amount">Adjust Amount (If Payment Type Adjustment)</label>
                                        <input type="number" id="adjustment_amount" name="adjustment_amount" value="{{$income->adjustment_amount}}"
                                            class="form-control" />
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6 ">

                                <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                    <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                        Receiver Information
                                    </h6>

                                    <div class="col-lg-6">
                                        <input type="text" class="form-control mt-3 " name="client_name"
                                            placeholder="Type Client Name" required value="{{$income->client_name}} ">
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control mt-3 " name="client_id"
                                            placeholder="Type Client ID" value="{{$income->client_id}}">

                                    </div>
                                    @if ($income->bank_id != null)
                                    <div class="col-lg-12 ">
                                        <label for="mobile_no">Bank <i
                                        class="text-danger">*</i></label>
                                        <select name="bank_id" id="bank_id" class="form-control form-select chosen-select" onchange="filterAccount()">
                                            <option value="" selected>Select Bank</option>
                                            @foreach ($banks as $bank)
                                                <option value="{{$bank->id}}" @if ($bank->id == $income->bank_id) selected @endif>{{$bank->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-lg-12 ">
                                        <label for="mobile_no">Bank Account<i
                                        class="text-danger">*</i></label>
                                        <select name="account_id" id="account" class="form-control form-select chosen-select">
                                            <option value="" selected>Select a Bank Account</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{$account->id}}"  @if ($account->id == $income->account_id) selected @endif>{{$account->account_no}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif
                                    @if ($income->cheque_no)
                                    <div class="col-lg-6" id="">
                                        <label for="cheque_no">Cheque Number <i
                                    class="text-danger">*</i></label>
                                        <input type="text" name="cheque_no" class="form-control" value="{{$income->cheque_no}}" />
                                    </div>
                                    @endif
                                    @if ($income->cheque_issue_date)
                                    <div class="col-lg-6" id="">
                                        <label for="cheque_issue_date">Cheque Issue Date <i
                                    class="text-danger">*</i></label>
                                        <input type="date" name="cheque_issue_date" class="form-control" value="{{$income->cheque_issue_date}}"/>
                                    </div>
                                    @endif
                                    @if ($income->mobile_no)
                                    <div class="col-lg-12">
                                        <label for="mobile_no">Mobile No.<i
                                    class="text-danger">*</i></label>
                                        <input type="text" name="mobile_no" class="form-control" value="{{$income->mobile_no}}"/>
                                    </div>
                                    @endif

                                    <div class="col-lg-12 pt-3">
                                        <button class="btn btn-success btn-block"><i class="fa fa-check"></i> Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- @extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Income Entry
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('income-voucher') }}" method="post" enctype="multipart/form-data"
                            id="myForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 ">

                                    <div class="row border m-2 p-2" style="border-color: green !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                            Income Required Information
                                        </h6>
                                        <div class="col-lg-6">
                                            <label for="company">Company<i class="text-danger pt-1"
                                                    style="font-size:16px">*</i> </label>
                                            <select name="company" id="company" class="form-control chosen-select"
                                                onchange="filterProject();">
                                                <option value="">Select Company..</option>
                                                @foreach ($company as $v_company)
                                                    <option  @if ($v_company->id == $income->company_id) selected @endif value="{{ $v_company->id }}">{{ $v_company->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="project">Project</label>
                                            <select name="project" id="project" class="form-control chosen-select">
                                                <option value="">Select Project..</option>
                                                @foreach ($project as $v_project)
                                                    <option  @if ($v_project->id == $income->project_id) selected @endif value="{{ $v_project->id }}">{{ $v_project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="category">Income Category<i class="text-danger">*</i> </label>
                                            <select name="category" id="category-1"
                                                class="form-control category chosen-select"
                                                onchange="filterHead(this); generateIncomeCode();newCategoryAdd(this);"
                                                required>
                                                <option value="">Select Category..</option>
                                                <option value="new_category">New Category</option>
                                                @foreach ($categories as $category)
                                                    @php $incomes = json_decode($category->category_type)  @endphp
                                                    @if ($incomes && in_array('Income', $incomes))
                                                        <option  @if ($category->id == $income->category_id) selected @endif value="{{ $category->id }}">{{ $category->category_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                                <option value=""></option>
                                            </select>
                                            <div id="categoryAdd-1">

                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="head">Income Head</label>
                                            <select name="head" id="head-1" class="form-control chosen-select"
                                                onchange="newHeadAdd(this);">
                                                <option value="">Select Head...</option>
                                                <option value="new_head">New Head</option>
                                                @foreach ($head as $v_head)
                                                    <option  @if ($v_head->id == $income->head_id) selected @endif value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-12" id="headAdd-1">

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="date">Date <i class="text-danger">*</i></label>
                                            <input value="{{$income->payment_date}}" type="date" name="payment_date" required class="form-control">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Code No">Code No <i class="text-danger">*</i></label>
                                            <input type="text" class="form-control" name="code_no" id="code_no"
                                                placeholder="Enter an Inc. Code No." value="{{$income->code_no}}">
                                            <small style="color:red">Code No. Should Be Unique</small>
                                        </div>
                                        @foreach ($income->income_details as $key => $item)
                                        <div class="col-lg-6" id="row-{{$key+1}}">
                                            <label for="Fund">Fund <i class="text-danger">*</i></label>
                                            <select name="fund_id[]" id="fund_id-1" class="form-control" required
                                                onchange="checkPaymentType('1');checkType();">
                                                <option value="">Select One</option>
                                                @foreach ($fund_data as $v_fund)
                                                    <option  @if ($v_fund->id == $item->fund_id) selected @endif value="{{ $v_fund->id }}">{{ $v_fund->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Fund">Payment Type <i class="text-danger">*</i></label>
                                            <select name="payment_type[]" class="form-control" id="payment_type" required>
                                                <option value="">Select One</option>
                                                <option @if ($item->payment_type == 'Cash') selected @endif value="Cash">Cash</option>
                                                <option @if ($item->payment_type == 'Cheque') selected @endif  value="Cheque">Cheque</option>
                                                <option @if ($item->payment_type == 'Bank') selected @endif value="Bank">Bank</option>
                                                <option @if ($item->payment_type == 'Others') selected @endif value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="total_amount">Total amount <i class="text-danger">*</i></label>
                                            <input type="text" value="{{$item->amount}}" id="total_amount" required name="total_amount[]"
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-10">
                                            <label for="remarks">Remarks<i class="text-danger">*</i></label>
                                            <input type="text" value="{{$item->remarks}}" name="remarks[]" id="remarks" class="form-control"
                                                required />
                                                <button class="remove btn btn-md btn-danger text-center mt-4" onclick="removeRow({{$key}})">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                        </div>
                                        @endforeach
                                        <div class="col-lg-1 mt-4">
                                            <button type="button" class="btn btn-success btn-md add-btn"
                                                onclick="addMore()">+</button>
                                        </div>
                                        <div class="" id="wrapper">

                                        </div>

                                        <div class="col-lg-12">
                                            <label for="adjustment_amount">Adjust Amount (If Payment Type Adjustment)</label>
                                            <input type="number" id="adjustment_amount" name="adjustment_amount"
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="adjustment_remarks">Remarks (For Adjustment Amount)</label>
                                            <input type="text" id="adjustment_remarks" name="adjustment_remarks"
                                                class="form-control" />
                                        </div>


                                    </div>
                                </div>
                                <div class="col-lg-6 row-container">

                                    <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                            Receiver Information
                                        </h6>

                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 " name="client_name"
                                                placeholder="Type Client Name" required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 " name="client_id"
                                                placeholder="Type Client ID">

                                        </div>
                                        <div id="bankInformation-1" class="col-lg-12 mt-3"></div>
                                        <div class="col-lg-12 mobile">
                                            <label for="mobile_no">Mobile No.<i class="text-danger">*</i></label>
                                            <input type="text" name="mobile_no" class="form-control " />
                                        </div>

                                        <div class="col-lg-6">
                                            <label for="Supplier">Attachment</label>
                                            <input type="file" name="attachment" />
                                        </div>
                                        <div class="col-lg-12 pt-3">
                                            <button class="btn btn-success btn-block"><i class="fa fa-check"></i>
                                                Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Income Edit
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('update-income', $income->id) }}" method="post" enctype="multipart/form-data"
                            id="myForm">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 ">

                                    <div class="row border m-2 p-2" style="border-color: green !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-success text-center">
                                            Income Required Information
                                        </h6>
                                        <div class="col-lg-6">
                                            <label for="company">Company<i class="text-danger pt-1"
                                                    style="font-size:16px">*</i> </label>
                                            <select name="company" id="company" class="form-control chosen-select"
                                                required onchange="filterProject();">
                                                <option value="">Select Company..</option>
                                                @foreach ($company as $v_company)
                                                    <option @if ($v_company->id == $income->company_id) selected @endif
                                                        value="{{ $v_company->id }}">{{ $v_company->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="project">Project</label>
                                            <select name="project" id="project" class="form-control chosen-select">
                                                <option value="">Select Project..</option>
                                                @foreach ($project as $v_project)
                                                    <option @if ($v_project->id == $income->project_id) selected @endif
                                                        value="{{ $v_project->id }}">{{ $v_project->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="category">Income Category<i class="text-danger">*</i> </label>
                                            <select name="category" id="category-1"
                                                class="form-control category chosen-select"
                                                onchange="filterHead(this); newCategoryAdd(this);" required>
                                                <option value="">Select Category..</option>
                                                <option value="new_category">New Category</option>
                                                @foreach ($categories as $category)
                                                    @php $incomes = json_decode($category->category_type)  @endphp
                                                    @if ($incomes && in_array('Income', $incomes))
                                                        <option @if ($category->id == $income->category_id) selected @endif
                                                            value="{{ $category->id }}">{{ $category->category_name }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                                <option value=""></option>
                                            </select>
                                            <div id="categoryAdd-1">

                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <label for="head">Income Head</label>
                                            <select name="head" id="head-1" class="form-control chosen-select"
                                                required onchange="newHeadAdd(this);">
                                                <option value="">Select Head...</option>
                                                <option value="new_head">New Head</option>
                                                @foreach ($head as $v_head)
                                                    <option @if ($v_head->id == $income->head_id) selected @endif
                                                        value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-lg-12" id="headAdd-1">

                                        </div>
                                        <div class="col-lg-6">
                                            <label for="date">Date <i class="text-danger">*</i></label>
                                            <input value="{{ $income->payment_date }}" type="date" name="payment_date"
                                                required class="form-control">
                                        </div>
                                        <div class="col-lg-6">
                                            <label for="Code No">Code No <i class="text-danger">*</i></label>
                                            <input value="{{ $income->code_no }}" type="text" class="form-control"
                                                name="code_no" id="code_no" placeholder="Enter an Exp. Code No." readonly>
                                        </div>
                                        @if ($income->income_details->isNotEmpty())
                                            @php $firstItem = $income->income_details->first(); @endphp
                                            <div class="col-lg-6">
                                                <label for="Fund">Fund <i class="text-danger">*</i></label>
                                                <select name="fund_id[]" id="fund_id-0" class="form-control" required
                                                    onchange="checkPaymentType(0);checkType();">
                                                    <option value="">Select One</option>
                                                    @foreach ($fund_data as $v_fund)
                                                        <option @if ($v_fund->id == $firstItem->fund_id) selected @endif
                                                            value="{{ $v_fund->id }}">{{ $v_fund->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="Fund">Payment Type <i class="text-danger">*</i></label>
                                                <select name="payment_type[]" class="form-control" id="payment_type"
                                                    required>
                                                    <option value="">Select One</option>
                                                    <option @if ($firstItem->payment_type == 'Cash') selected @endif
                                                        value="Cash">Cash</option>
                                                    <option @if ($firstItem->payment_type == 'Cheque') selected @endif
                                                        value="Cheque">Cheque</option>
                                                    <option @if ($firstItem->payment_type == 'Bank') selected @endif
                                                        value="Bank">Bank</option>
                                                    <option @if ($firstItem->payment_type == 'Others') selected @endif
                                                        value="Others">Others</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-12">
                                                <label for="total_amount">Total amount <i class="text-danger">*</i></label>
                                                <input value="{{ $firstItem->amount }}" type="text" id="total_amount"
                                                    required name="total_amount[]" class="form-control" />
                                            </div>
                                            <div id="bank-info-0">
                                                @if ($firstItem->bank_id)
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <label for="bank_id">Bank <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="bank_id[]" id="bank_id-0"
                                                                class="form-control form-select chosen-select"
                                                                onchange="filterAccountF()">
                                                                <option value="" selected>Select Bank</option>
                                                                @foreach ($banks as $bank)
                                                                    <option
                                                                        @if ($bank->id == $firstItem->bank_id) selected @endif
                                                                        value="{{ $bank->id }}">{{ $bank->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>

                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="Fund">Bank Account <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="account_id[]" id="account-0"
                                                                class="form-control form-select chosen-select">
                                                                <option value="" selected>Select a Bank Account
                                                                </option>
                                                                @foreach ($accounts as $account)
                                                                    <option
                                                                        @if ($account->id == $firstItem->account_id) selected @endif
                                                                        value="{{ $account->id }}">
                                                                        {{ $account->account_no }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                        <div class="col-lg-6" id="cheque0">
                                                            <label for="cheque_no">Cheque Number <i
                                                                    class="text-danger">*</i></label>
                                                            <input type="text" name="cheque_no[]" class="form-control"
                                                                value="{{ $firstItem->cheque_no }}" />
                                                        </div>
                                                        <div class="col-lg-6" id="cheque0">
                                                            <label for="cheque_issue_date">Cheque Issue Date <i
                                                                    class="text-danger">*</i></label>
                                                            <input type="date" name="cheque_issue_date[]"
                                                                class="form-control"
                                                                value="{{ $firstItem->cheque_issue_date }}" />
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="col-lg-9">
                                                <label for="remarks">Remarks <i class="text-danger">*</i></label>
                                                <input value="{{ $firstItem->remarks }}" type="text" name="remarks[]"
                                                    id="remarks-0" class="form-control" placeholder="Remarks">
                                            </div>
                                        @endif
                                        <div class="col-lg-3 mt-4">
                                            <button type="button" class="btn btn-success btn-md add-btn"
                                                onclick="addMore()">+ More Fund</button>
                                        </div>
                                        <div class="col-lg-12 mt-4">
                                            <hr style="border: 1px solid green;">
                                            <button type="button" class="btn btn-success btn-md add-btn"
                                                onclick="adjustment()">+ Adjustment</button>
                                        </div>
                                        <div class="" id="wrapper-adjustment">
                                            @foreach (collect($income->income_details)->filter(function ($item) {
            return $item->adjust_head_id !== null;
        }) as $key => $item)
                                                {{-- @dd($item) --}}
                                                <hr id="hr-wrapper-adjustment-{{ $key }}"
                                                    style="border: 1px solid green;">
                                                <div class="col-md-12 mt-3" id="adjust-wrapper-{{ $key }}">
                                                    <div class="form-group row pb-3">
                                                        <input type="hidden" id="income-details-id-{{ $key }}" value="{{$item->id}}">
                                                        <div class="col-lg-6">
                                                            <label for="category">Category<i class="text-danger">*</i>
                                                            </label>
                                                            <select name="category_id[]"
                                                                id="category-adjust-{{ $key }}"
                                                                class="form-control category chosen-select"
                                                                onchange="filterAdjustHead_edit({{ $key }});"
                                                                required>
                                                                <option value="">Select Category..</option>
                                                                @foreach ($categories as $category)
                                                                    @php $incomes = json_decode($category->category_type) @endphp
                                                                    @if ($incomes && in_array('Income', $incomes))
                                                                        <option
                                                                            @if ($category->id == $item->adjust_category_id) selected @endif
                                                                            value="{{ $category->id }}">
                                                                            {{ $category->category_name }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                                <option value=""></option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-6">
                                                            <label for="head">Head<i class="text-danger">*</i></label>
                                                            <select name="head_id[]" id="head-adjust-{{ $key }}"
                                                                onchange="validateAdjustmentAmountEdit({{ $key }});"
                                                                class="form-control chosen-select">
                                                                <option value="">Select Head...</option>\
                                                                @foreach ($head as $v_head)
                                                                    <option
                                                                        @if ($v_head->id == $item->adjust_head_id) selected @endif
                                                                        value="{{ $v_head->id }}">
                                                                        {{ $v_head->head_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-12 mt-2">
                                                            <label for="adjustment_amount">Adjust Amount (If Payment Type
                                                                Adjustment)</label>
                                                            <input type="number"
                                                                id="adjustment_amount-{{ $key }}"
                                                                value="{{ $item->amount }}" name="adjustment_amount[]"
                                                                oninput="validateAdjustmentAmountEdit({{ $key }});"
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-lg-10">
                                                            <label for="adjustment_remarks">Remarks (For Adjustment
                                                                Amount)</label>
                                                            <input type="text"
                                                                id="adjustment_remarks-{{ $key }}"
                                                                value="{{ $item->remarks }}" name="adjustment_remarks[]"
                                                                class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 mt-4">
                                                            <button type="button"
                                                                class="btn btn-danger btn-md remove-btn"
                                                                onclick="removeAdjust({{ $key }})">-</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        {{-- <div class="col-lg-12 mt-3">
                                            <hr style="border: 1px solid green;">
                                            <label for="adjustment_amount">Adjust Amount (If Payment Type
                                                Adjustment)</label>
                                            <input type="number" id="adjustment_amount" name="adjustment_amount" value="{{$expense->amount ?? ''}}"
                                                class="form-control" />
                                        </div>
                                        <div class="col-lg-12">
                                            <label for="adjustment_remarks">Remarks (For Adjustment Amount)</label>
                                            <input type="text" id="adjustment_remarks" name="adjustment_remarks" value="{{$expense->remarks ?? ''}}"
                                                class="form-control" />
                                        </div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6 ">

                                    <div class="row border m-2 p-2" style="border-color: rgb(9, 155, 212) !important">
                                        <h6 class="col-lg-12 p-2 text-bold bg-info text-center">
                                            Receiver Information
                                        </h6>

                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 " name="client_name"
                                                placeholder="Type Client Name" value="{{ $income->client_name }}"
                                                required>
                                        </div>
                                        <div class="col-lg-6">
                                            <input type="text" class="form-control mt-3 " name="client_id"
                                                placeholder="Type Client ID" value="{{ $income->client_id }}">

                                        </div>
                                        <div class="col-lg-12">
                                            <label for="attachment">Attachment</label>
                                            <input type="file" name="attachment" class="form-control" />
                                            <div id="preview_file" style="margin-top: 10px;">
                                                @if ($income->attachment != null)
                                                    @php
                                                        $extension = pathinfo($income->attachment, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                                        <img src="{{ asset('attachment/' . $income->attachment) }}" class="img-fluid img-thumbnail" style="height: 100px" alt="User">
                                                    @elseif (strtolower($extension) === 'pdf')
                                                        <iframe src="{{ asset('attachment/' . $income->attachment) }}" style="width:100%; height:100px;" frameborder="0"></iframe>
                                                        <!-- Optionally, add a download link -->
                                                        <a href="{{ asset('attachment/' . $income->attachment) }}" target="_blank">View PDF</a>
                                                    @else
                                                        <p>Unsupported file type</p>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="" id="wrapper">
                                            @foreach (collect($income->income_details->skip(1))->filter(function ($item) {
            return $item->adjust_head_id == null;
        }) as $key => $item)
                                                <hr id="hr-wrapper-{{ $key }}" style="border: 1px solid green;">
                                                <div class="col-md-12 mt-3" id="fund-wrapper-{{ $key }}">
                                                    <div class="form-group row pb-3">
                                                        <div class="col-lg-12">
                                                            <label for="fund_id">Fund <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="fund_id[]" id="fund_id-{{ $key }}"
                                                                class="form-control chosen-select"
                                                                onchange="checkPaymentType({{ $key }});">
                                                                <option value="">Select One...</option>
                                                                @foreach ($fund_data as $v_fund)
                                                                    <option
                                                                        @if ($v_fund->id == $item->fund_id) selected @endif
                                                                        value="{{ $v_fund->id }}">{{ $v_fund->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="payment_type">Payment Type <i
                                                                    class="text-danger">*</i></label>
                                                            <select name="payment_type[]"
                                                                id="payment_type-{{ $key }}}"
                                                                class="form-control">
                                                                <option value="">Select One</option>
                                                                <option @if ($item->payment_type == 'Cash') selected @endif
                                                                    value="Cash">Cash</option>
                                                                <option @if ($item->payment_type == 'Cheque') selected @endif
                                                                    value="Cheque">Cheque</option>
                                                                <option @if ($item->payment_type == 'Bank') selected @endif
                                                                    value="Bank">Bank</option>
                                                                <option @if ($item->payment_type == 'Others') selected @endif
                                                                    value="Others">Others</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <label for="total_amount">Total Amount <i
                                                                    class="text-danger">*</i></label>
                                                            <input type="number" value="{{ $item->amount }}"
                                                                name="total_amount[]"
                                                                id="total_amount-{{ $key }}"
                                                                class="form-control amount" placeholder="Enter Amount">
                                                        </div>
                                                        <div id="bank-info-{{ $key }}">
                                                            @if ($item->bank_id)
                                                                <hr id="bank-hr-{{ $key }}">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <label for="Fund">Bank <i
                                                                                class="text-danger">*</i></label>
                                                                        <select name="bank_id[]"
                                                                            id="bank_id-{{ $key }}"
                                                                            class="form-control form-select chosen-select"
                                                                            onchange="filterAccount_edit({{ $key }})">
                                                                            <option value="" selected>Select Bank
                                                                            </option>
                                                                            @foreach ($banks as $bank)
                                                                                <option
                                                                                    @if ($bank->id == $item->bank_id) selected @endif
                                                                                    value="{{ $bank->id }}">
                                                                                    {{ $bank->name }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>

                                                                    <div class="col-lg-6">
                                                                        <label for="Fund">Bank Account <i
                                                                                class="text-danger">*</i></label>
                                                                        <select name="account_id[]"
                                                                            id="account-{{ $key }}"
                                                                            class="form-control form-select chosen-select">
                                                                            <option value="" selected>Select a Bank
                                                                                Account</option>
                                                                            @foreach ($accounts as $account)
                                                                                <option
                                                                                    @if ($account->id == $item->account_id) selected @endif
                                                                                    value="{{ $account->id }}">
                                                                                    {{ $account->account_no }}</option>
                                                                            @endforeach
                                                                        </select>

                                                                    </div>
                                                                    <div class="col-lg-6" id="cheque{{ $key }}">
                                                                        <label for="cheque_no">Cheque Number <i
                                                                                class="text-danger">*</i></label>
                                                                        <input type="text" name="cheque_no[]"
                                                                            class="form-control"
                                                                            value="{{ $item->cheque_no }}" />
                                                                    </div>
                                                                    <div class="col-lg-6" id="cheque{{ $key }}">
                                                                        <label for="cheque_issue_date">Cheque Issue Date <i
                                                                                class="text-danger">*</i></label>
                                                                        <input type="date" name="cheque_issue_date[]"
                                                                            class="form-control"
                                                                            value="{{ $item->cheque_issue_date }}" />
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="col-lg-10">
                                                            <label for="remarks">Remarks <i
                                                                    class="text-danger">*</i></label>
                                                            <input value="{{ $item->remarks }}" type="text"
                                                                name="remarks[]" id="remarks-{{ $key }}"
                                                                class="form-control" placeholder="Remarks">
                                                        </div>
                                                        <div class="col-lg-2 mt-4">
                                                            <button type="button"
                                                                class="btn btn-danger btn-md remove-btn"
                                                                onclick="removeSection({{ $key }})">-</button>
                                                        </div>
                                                    </div>
                                                    <!-- Placeholder for dynamic bank info -->
                                                </div>
                                            @endforeach

                                        </div>
                                        <div class="col-lg-12 pt-3">
                                            <button class="btn btn-success btn-block"><i class="fa fa-check"></i>
                                                Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

    <script>
        $(".chosen-select").chosen();
        $(document).ready(function() {
            $('.bank').hide();
            $('.cheque').hide();
            $('.mobile').hide();
            console.log("ready!");

            $('[id^="adjust-wrapper-"]').each(function() {
                const index = $(this).attr('id').split('-')[2];
                validateAdjustmentAmountEdit(index);
            });
        });
        var i = {{ count($income->income_details) }} - 1;


        function addMore() {
            ++i;
            $('#wrapper').append(`
             <hr id="hr-wrapper-${i}" style="border: 1px solid green;">
                <div class="col-md-12 mt-3" id="fund-wrapper-${i}">
                    <div class="form-group row pb-3">
                        <div class="col-lg-12">
                            <label for="fund_id">Fund <i class="text-danger">*</i></label>
                            <select name="fund_id[]" id="fund_id-${i}" class="form-control chosen-select" onchange="checkPaymentType(${i});">
                                <option value="">Select One...</option>
                                @foreach ($fund_data as $v_fund)
                                    <option value="{{ $v_fund->id }}">{{ $v_fund->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="payment_type">Payment Type <i class="text-danger">*</i></label>
                            <select name="payment_type[]" id="payment_type-${i}" class="form-control">
                                <option value="">Select One</option>
                                <option value="Cash">Cash</option>
                                <option value="Cheque">Cheque</option>
                                <option value="Bank">Bank</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-lg-6">
                            <label for="total_amount">Total Amount <i class="text-danger">*</i></label>
                            <input type="number" name="total_amount[]" id="total_amount-${i}" class="form-control amount" placeholder="Enter Amount">
                        </div>
                        <div class="col-lg-10">
                            <label for="remarks">Remarks <i class="text-danger">*</i></label>
                            <input type="text" name="remarks[]" id="remarks-${i}" class="form-control" placeholder="Remarks">
                        </div>
                        <div class="col-lg-2 mt-4">
                            <button type="button" class="btn btn-danger btn-md remove-btn" onclick="removeSection(${i})">-</button>
                        </div>
                    </div>
                    <!-- Placeholder for dynamic bank info -->
                    <div id="bank-info-${i}"></div>
                </div>
            `);
            $(".chosen-select").chosen();
        }

        function adjustment() {
            ++i;
            $('#wrapper-adjustment').append(`
                <hr id="hr-wrapper-adjustment-${i}" style="border: 1px solid green;">
                <div class="col-md-12 mt-3" id="adjust-wrapper-${i}">
                    <div class="form-group row pb-3">
                        <div class="col-lg-6">
                            <label for="category">Category<i class="text-danger">*</i> </label>
                            <select name="category_id[]" id="category-adjust-${i}" 
                                class="form-control category chosen-select"
                                onchange="filterAdjustHead(${i});" 
                                required>
                                <option value="">Select Category..</option>
                                @foreach ($categories as $category)
                                    @php $incomes = json_decode($category->category_type) @endphp
                                    @if ($incomes && in_array('Income', $incomes))
                                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                    @endif
                                @endforeach
                                <option value=""></option>
                            </select>
                        </div>

                        <div class="col-lg-6">
                            <label for="head">Head<i class="text-danger">*</i></label>
                            <select name="head_id[]" id="head-adjust-${i}" 
                                class="form-control chosen-select"
                                onchange="validateAdjustmentAmount(${i});">
                                <option value="">Select Head...</option>
                                @foreach ($head as $v_head)
                                    <option value="{{ $v_head->id }}">{{ $v_head->head_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 mt-2">
                            <label for="adjustment_amount">Adjust Amount (If Payment Type Adjustment)</label>
                            <input type="number" id="adjustment_amount-${i}" name="adjustment_amount[]"
                                class="form-control" 
                                oninput="validateAdjustmentAmount(${i});" />
                        </div>
                        <div class="col-lg-10">
                            <label for="adjustment_remarks">Remarks (For Adjustment Amount)</label>
                            <input type="text" id="adjustment_remarks-${i}" name="adjustment_remarks[]"
                                class="form-control" />
                        </div>
                        <div class="col-lg-2 mt-4">
                            <button type="button" class="btn btn-danger btn-md remove-btn" 
                                onclick="removeAdjust(${i})">-</button>
                        </div>
                    </div>
                </div>
            `);
            $(".chosen-select").chosen();
        }


        function removeSection(id) {
            $(`#fund-wrapper-${id}`).remove();
            $(`#hr-wrapper-${id}`).remove();
        }

        function removeAdjust(id) {
            $(`#adjust-wrapper-${id}`).remove();
            $(`#hr-wrapper-adjustment-${id}`).remove();
        }

        function validateAdjustmentAmount(index) {
            const headId = $(`#head-adjust-${index}`).val();
            console.log(headId);

            const adjustmentAmount = parseFloat($(`#adjustment_amount-${index}`).val()) || 0;
            console.log(adjustmentAmount);

            if (headId) {
                $.ajax({
                    url: '/get-current-balance',
                    method: 'GET',
                    data: {
                        head_id: headId
                    },
                    success: function(response) {
                        const prevBalance = parseFloat(response.prev_balance) || 0;
                        // console.log(prevBalance);

                        const balanceInfoDiv = $(`#adjustment_balance_info-${index}`);
                        const balanceText = `<strong>Available Balance: ${prevBalance} Tk.</strong>`;
                        if (balanceInfoDiv.length) {
                            balanceInfoDiv.html(balanceText);
                        } else {
                            $(`#adjustment_amount-${index}`)
                                .after(`<div id="adjustment_balance_info-${index}" class="mt-2 text-success">
                                    Available Balance: ${prevBalance} Tk.
                                </div>`);
                        }

                        if (adjustmentAmount > prevBalance) {
                            alert('Adjustment amount exceeds available balance!');
                            $(`#adjustment_amount-${index}`).val('');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch balance. Please try again.');
                    }
                });
            }
        }

        function validateAdjustmentAmountEdit(index) {
            const IncomeDetailsId = $(`#income-details-id-${index}`).val();
            const categoryId = $(`#category-adjust-${index}`).val();
            const headId = $(`#head-adjust-${index}`).val();
            const adjustmentAmount = parseFloat($(`#adjustment_amount-${index}`).val()) || 0;

            console.log(`Category ID: ${categoryId}`);
            console.log(`Head ID: ${headId}`);
            console.log(`Adjustment Amount: ${adjustmentAmount}`);

            if (headId) {
                $.ajax({
                    url: '/get-current-balance-edit',
                    method: 'GET',
                    data: {
                        IncomeDetailsId: IncomeDetailsId,
                        head_id: headId,
                        categoryId: categoryId,
                        adjustmentAmount: adjustmentAmount
                    },
                    success: function(response) {
                        const prevBalance = parseFloat(response.prev_balance) || 0;
                        // console.log(prevBalance);
                        const oldAdjustmentBalance = parseFloat(response.adjustment_amount) || 0;
                        const ultimateBalance = oldAdjustmentBalance + prevBalance;

                        const balanceInfoDiv = $(`#adjustment_balance_info-${index}`);
                        const balanceText = `<strong>Available Balance: ${ultimateBalance} Tk.</strong>`;
                        if (balanceInfoDiv.length) {
                            balanceInfoDiv.html(balanceText);
                        } else {
                            $(`#adjustment_amount-${index}`)
                                .after(`<div id="adjustment_balance_info-${index}" class="mt-2 text-success">
                                    ${balanceText}
                                </div>`);
                        }

                        if (adjustmentAmount > ultimateBalance) {
                            alert('Adjustment amount exceeds available balance!');
                            $(`#adjustment_amount-${index}`).val('');
                        }
                    },
                    error: function() {
                        alert('Failed to fetch balance. Please try again.');
                    }
                });
            }
        }



        function checkType() {
            var fundSelect = document.getElementById("fund_id");
            var paymentTypeSelect = document.getElementById("payment_type");
            var selectedFundId = fundSelect.value;
            var paymentTypeOption = paymentTypeSelect.querySelector("option[value='Bank']");

            if (selectedFundId === "1") {
                paymentTypeOption.selected = true;
            }

            $(".chosen-select").chosen();
        }

        function checkPaymentType(id) {
            const fundId = $(`#fund_id-${id}`).val();
            const bankInfoContainer = $(`#bank-info-${id}`);

            bankInfoContainer.empty();

            if (fundId == 1) {
                bankInfoContainer.append(`
            <hr id="bank-hr-${id}">
            <div class="row">
                <div class="col-lg-12 bank" id="bank-container-${id}">
                    <label for="bank_id-${id}">Bank <i class="text-danger">*</i></label>
                    <select name="bank_id[]" id="bank_id-${id}" class="form-control form-select chosen-select" onchange="filterAccount(${id})">
                        <option value="" selected>Select Bank</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-12 bank" id="account-container-${id}">
                    <label for="account-${id}">Bank Account <i class="text-danger">*</i></label>
                    <select name="account_id[]" id="account-${id}" class="form-control form-select chosen-select">
                        <option value="" selected>Select a Bank Account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 cheque" id="cheque1-${id}">
                    <label for="cheque_no-${id}">Cheque Number <i class="text-danger">*</i></label>
                    <input type="text" name="cheque_no[]" id="cheque_no-${id}" class="form-control" placeholder="Enter Cheque Number" />
                </div>

                <div class="col-lg-6 cheque" id="cheque2-${id}">
                    <label for="cheque_issue_date-${id}">Cheque Issue Date <i class="text-danger">*</i></label>
                    <input type="date" name="cheque_issue_date[]" id="cheque_issue_date-${id}" class="form-control" />
                </div>
            </div>
        `);

                $(`#bank_id-${id}`).attr('required', true);
                $(`#account-${id}`).attr('required', true);
                $(`#cheque_no-${id}`).attr('required', true);
                $(`#cheque_issue_date-${id}`).attr('required', true);

                $(".chosen-select").chosen();
            } else if (fundId == 3) {
                bankInfoContainer.append(`
            <hr id="mobile-hr-${id}">
            <div class="col-lg-12 mobile" id="mobile-container-${id}">
                <label for="mobile_no-${id}">Mobile No. <i class="text-danger">*</i></label>
                <input type="text" name="mobile_no[]" id="mobile_no-${id}" class="form-control" placeholder="Enter Mobile No." />
            </div>

        `);

                $(`#bank_id-${id}`).removeAttr('required');
                $(`#account-${id}`).removeAttr('required');
                $(`#cheque_no-${id}`).removeAttr('required');
                $(`#cheque_issue_date-${id}`).removeAttr('required');
                $(`#mobile_no-${id}`).attr('required', true);

            } else {
                $(`#bank_id-${id}`).removeAttr('required');
                $(`#account-${id}`).removeAttr('required');
                $(`#cheque_no-${id}`).removeAttr('required');
                $(`#cheque_issue_date-${id}`).removeAttr('required');
                $(`#mobile_no-${id}`).removeAttr('required');
            }
        }

        function filterHead(e) {
            var text = e.id;
            var id = text.replace('category-', '');
            var category_id = document.getElementById('category-' + id).value;
            if (category_id == 'new_category') {
                console.log(category_id);
                $('#head-' + id).empty().append(
                        '<option value="" disabled selected>Choose Head</option><option value="new_head">New Head</option>')
                    .trigger("chosen:updated");
            } else {
                var url = "{{ route('filter-head-data') }}";
                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id
                    },
                    success: function(data) {
                        $('#head-' + id).find('option').remove();
                        $('#head-' + id).html('');
                        $('#head-' + id).append('<option value="new_head">Choose Head</option>');
                        $('#head-' + id).append('<option value="new_head">New Head</option>');
                        $.each(data, function(key, value) {
                            $('#head-' + id).append('<option value="' + value.id + '">' + value
                                .head_name +
                                '</option>');
                        });

                        $('#head-' + id).trigger("chosen:updated");
                    },
                });
            }
        }

        function filterAdjustHead(id) {
            var category_id = document.getElementById('category-adjust-' + id).value;

            if (category_id === 'new_category') {
                console.log(category_id);
                $('#head-adjust-' + id)
                    .empty()
                    .trigger("chosen:updated");
            } else {
                var url = "{{ route('filter-head-data') }}";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id: category_id
                    },
                    success: function(data) {
                        var $headDropdown = $('#head-adjust-' + id);
                        $headDropdown.empty();
                        $headDropdown.append('<option value="">Choose Head</option>');

                        $.each(data, function(key, value) {
                            $headDropdown.append('<option value="' + value.id + '">' + value.head_name +
                                '</option>');
                        });

                        $headDropdown.trigger("chosen:updated");
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred:", status, error);
                    }
                });
            }
        }

        function filterAdjustHead_edit(id) {
            var category_id = document.getElementById('category-adjust-' + id).value;

            if (category_id === 'new_category') {
                console.log(category_id);
                $('#head-adjust-' + id)
                    .empty()
                    .trigger("chosen:updated");
            } else {
                var url = "{{ route('filter-head-data') }}";

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        category_id: category_id
                    },
                    success: function(data) {
                        var $headDropdown = $('#head-adjust-' + id);
                        $headDropdown.empty();
                        $headDropdown.append('<option value="">Choose Head</option>');

                        $.each(data, function(key, value) {
                            $headDropdown.append('<option value="' + value.id + '">' + value.head_name +
                                '</option>');
                        });

                        $headDropdown.trigger("chosen:updated");
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred:", status, error);
                    }
                });
            }
        }

        function filterProject() {
            var company_id = document.getElementById('company').value;
            var url = "{{ route('filter-project') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    company_id
                },
                success: function(data) {
                    $('#project').find('option').remove();
                    $('#project').html('<option value="" selected >Select Project..</option>');
                    $.each(data, function(key, value) {
                        $('#project').append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                    $('#project').trigger("chosen:updated");
                },
            });
        }

        function filterAccount(id) {
            var bank_id = document.getElementById(`bank_id-${id}`).value;
            var url = "{{ route('filter-account') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id: bank_id
                },
                success: function(data) {
                    var accountDropdown = $(`#account-${id}`);
                    accountDropdown.find('option').remove();
                    accountDropdown.html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        accountDropdown.append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                    accountDropdown.trigger("chosen:updated");
                },
                error: function(error) {
                    console.error(`Error fetching accounts for bank_id-${id}:`, error);
                }
            });
        }

        function filterAccount_edit(id) {
            var bank_id = document.getElementById(`bank_id-${id}`).value;
            var url = "{{ route('filter-account') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id: bank_id
                },
                success: function(data) {
                    var accountDropdown = $(`#account-${id}`);
                    accountDropdown.find('option').remove();
                    accountDropdown.html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        accountDropdown.append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                    accountDropdown.trigger("chosen:updated");
                },
                error: function(error) {
                    console.error(`Error fetching accounts for bank_id-${id}:`, error);
                }
            });
        }

        function totalAmount(e) {
            var text = e.id;
            var total = 0;
            var id = text.replace('amount-', '');
            var amount = document.getElementById('amount-' + id).value;
            let total_amount = 0;
            let ajaxPromises = [];
            $('[id^="amount-"]').each(function() {
                const currentAmount = parseFloat($(this).val());
                if (currentAmount) {
                    total += currentAmount
                }
            });
            document.getElementById('total_amount').value = total;
            document.getElementById('hidden_amount').value = total;
        }

        function newCategoryAdd(e) {
            var text = e.id;
            var id = text.replace('category-', '');
            var category = document.getElementById('category-' + id).value;
            if (category == 'new_category') {
                $('#categoryAdd-' + id).append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="category_name[]" class="form-control" id="" placeholder="Enter Category Name"/>
                                        <input type="hidden" name="category_type[][]" class="form-control" id="" value="Income"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('#categoryAdd-' + id).on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('#categoryAdd-' + id).hide();
            }

        }

        function newHeadAdd(e) {
            var text = e.id;
            var id = text.replace('head-', '');
            var head = document.getElementById('head-' + id).value;
            if (head == 'new_head') {
                $('#headAdd-' + id).empty().show().append(`
                    <div class="col-lg-12">
                            <div class="form-group row pb-3 mt-3">
                                    <div class="col-lg-11">
                                        <input type="text" name="head_name[]" class="form-control" id="" placeholder="Enter Head Name"/>
                                    </div>
                                <button class="remove btn btn-danger btn-sm  " style=""><i class="fa fa-times"></i></button>
                            </div>
                    </div>
            `);

                $('#headAdd-' + id).on('click', '.remove', function() {
                    $(this).parent().remove();
                });
            } else {
                $('#headAdd-' + id).hide();
            }

        }

        function checkPaymentTypeF() {
            const fundId = $('#fund_id').val();
            const wrapper = $('#wrapper');
            wrapper.empty();

            $('#bank_id').removeAttr('required');
            $('#account').removeAttr('required');
            $('#cheque_no').removeAttr('required');
            $('#cheque_issue_date').removeAttr('required');
            $('#mobile_no').removeAttr('required');

            if (fundId == 1) {
                wrapper.append(`
            <hr id="bank-hr">
            <div class="row" id="bank-info">
                <div class="col-lg-12 bank">
                    <label for="bank_id">Bank <i class="text-danger">*</i></label>
                    <select name="bank_id[]" id="bank_id" class="form-control form-select chosen-select" onchange="filterAccountF()">
                        <option value="" selected>Select Bank</option>
                        @foreach ($banks as $bank)
                            <option value="{{ $bank->id }}">{{ $bank->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-12 bank">
                    <label for="account">Bank Account <i class="text-danger">*</i></label>
                    <select name="account_id[]" id="account" class="form-control form-select chosen-select">
                        <option value="" selected>Select a Bank Account</option>
                        @foreach ($accounts as $account)
                            <option value="{{ $account->id }}">{{ $account->account_no }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 cheque">
                    <label for="cheque_no">Cheque Number <i class="text-danger">*</i></label>
                    <input type="text" name="cheque_no[]" id="cheque_no" class="form-control" placeholder="Enter Cheque Number" />
                </div>
                <div class="col-lg-6 cheque">
                    <label for="cheque_issue_date">Cheque Issue Date <i class="text-danger">*</i></label>
                    <input type="date" name="cheque_issue_date[]" id="cheque_issue_date" class="form-control" />
                </div>
            </div>
        `);

                $('#bank_id').prop('required', true);
                $('#account').prop('required', true);
                $('#cheque_no').prop('required', true);
                $('#cheque_issue_date').prop('required', true);
                $(".chosen-select").chosen();
            } else if (fundId == 3) {
                wrapper.append(`
            <hr id="mobile-hr">
            <div id="mobile-info">
                <div class="col-lg-12 mobile">
                    <label for="mobile_no">Mobile No. <i class="text-danger">*</i></label>
                    <input type="text" name="mobile_no[]" id="mobile_no" class="form-control" placeholder="Enter Mobile No." />
                </div>
            </div>
        `);

                $('#mobile_no').prop('required', true);
            }
        }
    </script>
@endpush
