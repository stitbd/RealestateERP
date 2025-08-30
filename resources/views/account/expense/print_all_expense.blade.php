@extends('layouts.print')
@section('content')

<table class="table table-bordered table-striped" width="100%">
    <thead class="bg-info">
        <tr>
            <th>ID</th>
            <th>Expense Date</th>
            <th>Remarks</th>
            <th>Voucher No.</th>
            <th>Payment Type</th>
            <th>Fund </th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; $i=0; @endphp
        @foreach ($expense_data as $v_expense)
        <tr>
            <td>{{ ++$i }}</td>
            <td>{{ date('d/m/Y',strtotime($v_expense->payment_date)) }}</td>
            <td>{{$v_expense->remarks }}</td>
            <td>{{$v_expense->voucher_no }}</td>
            <td>{{ $v_expense->payment_type }}</td>
            <td>{{ $v_expense->fund->name }}</td>
            <td>{{ $v_expense->amount }}</td>
            @php  $total +=$v_expense->amount; @endphp
        </tr>
        <tr>
            <td colspan="6" class="text-center" style="padding-x: 10%; text-align:center;">
                @if(auth()->user()->role == 'SuperAdmin') {!!($v_expense->user->name !=null)?'Created By:'.$v_expense->user->name .'<br/>':''!!} @endif
                @if($v_expense->expense_by){!!($v_expense->employee->name !=null)?'Expenser Name:'.$v_expense->employee->name .'<br/>':''!!} @else Expenser Name: {{$v_expense->expenser_name}} <br> @endif
                @if($v_expense->expense_by){!!($v_expense->employee->mobile_no!=null)?'Expenser Mobile No:'.$v_expense->employee->mobile_no.'<br/>':''!!} @else Expenser Mobile No: {{$v_expense->expenser_mobile_no}} <br> @endif
                @if($v_expense->expense_by){!!($v_expense->employee->nid!=null)?'Expenser NID:'.$v_expense->employee->nid.'<br/>':''!!}@else Expenser Department: {{$v_expense->department}} <br> @endif
                @if($v_expense->expense_by){!!($v_expense->employee->address !=null)?'Expenser Address:'.$v_expense->employee->address.'<br/>':''!!}@else Expenser Designation: {{$v_expense->designation}} <br> @endif
                {!!($v_expense->remarks!=null)?'Remarks:'.$v_expense->remarks.'<br/>':''!!}
                @if($v_expense->fund_id==1)
                <span style="color: green; border-bottom: 1px solid rgb(156, 154, 154)">Bank Information</span><br>
                <table class="table table-bordered table-striped" style="margin-bottom:16px;">
                    <thead>
                        <tr>
                            <th>Bank Name</th>
                            <th>Acc. No</th>
                            <th>Acc. Holder Name</th>
                            <th>Check No.</th>
                            <th>Check Issue Date</th>
                            <th>Payment Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $bankInfo = App\Models\ExpenseBankInfo::where('master_id',$v_expense->id)->get(); @endphp
                        @if($bankInfo)
                            @foreach($bankInfo as $bank)
                             <tr>
                                <td>{!!($bank->bank_id !=null)? $bank->bank->name:''!!}</td>
                                <td>{!!($bank->account_id !=null)? $bank->bank_account->account_no:''!!}</td>
                                <td>{!!($bank->account_holder!=null)? $bank->account_holder:''!!}</td>
                                <td>{!!($bank->payment_note!=null)? $bank->payment_note:''!!}</td>
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

        @endforeach
        <tr>
            <th colspan="6">Total</th>
            <th class="text-right">Tk. {{ number_format($total)}}</th>
        </tr>
    </tbody>
</table>
@endsection