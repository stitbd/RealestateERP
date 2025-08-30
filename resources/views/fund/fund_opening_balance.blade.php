@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 mt-4">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h4 class="text-center">
                        Add Opening Balance
                    </h4>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <form action="{{route('fund-balance-store')}}" method="POST">
                        @csrf
                    <div class="row p-4">
                      
                        <div class="col-md-12">
                            <label for="fund_id">Fund</label>
                            <select name="fund_id" id="fund_id" class="form-control" onchange="checkfundType();">
                                <option value="">Select Fund..</option>
                                @foreach ($fund as $v_fund)
                                    <option value="{{ $v_fund->id }}">{{ $v_fund->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 bank">
                            <label for="bank_id">Bank</label>
                            <select name="bank_id" id="bank_id" class="form-control" onchange="filterAccount();">
                                <option value="">Select Bank..</option>
                                @foreach ($bank as $v_bank)
                                    <option value="{{ $v_bank->id }}">{{ $v_bank->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 bank">
                            <label for="account_id">Bank Account</label>
                            <select name="account_id" id="account_id" class="form-control">
                                <option value="">Select Bank..</option>
                                @foreach ($account as $v_account)
                                    <option value="{{ $v_account->id }}">{{ $v_account->account_no }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="project">Opening Balance</label>
                            <input type="number" name="amount" id="" class=" form-control" placeholder="Enter Amount" required>
                        </div>
                        <div class="col-md-12">
                            <label for="project">Date</label>
                            <input type="date" name="date" id="" class=" form-control" placeholder="" required>
                        </div>
                        <div class="col-lg-6 pt-3">
                            <button class="btn btn-success btn-block" id="submit"><i class="fa fa-check"></i> Save</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12 mt-4 p-5">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h4 class="text-center">
                        Opening Balance List
                    </h4>
                </div> <!-- /.card-body -->
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="bg-info text-center">
                                <th>Sl No.</th>
                                <th>Date</th>
                                <th>Fund Type</th>
                                <th>Fund Name</th>
                                <th>Account Number</th>
                                <th>Opening Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach ($income as $v_income)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{$v_income->payment_date}}</td>
                                <td>{{ $v_income->fund->name }}</td>
                                <td>@if($v_income->bank_id){{ $v_income->bank->name }} @else {{ $v_income->fund->name }} @endif</td>
                                <td>@if($v_income->account){{ $v_income->account->account_no }} @endif</td>
                                <td>{{ $v_income->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                      </table>
                      <div class="row pt-3">
                        <div class="col-lg-12">
                            {{$income->links();}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_js')
<script>
  $( document ).ready(function() {
    $('.bank').hide();
    });
function checkfundType(){
        var fund_id = document.getElementById('fund_id').value;
        if(fund_id == 2){
            $('.bank').hide();
        }
        if(fund_id == 1){
            $('.bank').show();
        }

        if(fund_id == 3){
            $('.bank').hide();
        }

        if(fund_id == 4){
            $('.bank').hide();
        }
       }

       function filterAccount(){

            var bank_id = document.getElementById('bank_id' ).value;
            var url = "{{route('filter-account')}}";
            $.ajax({
                type:"GET",
                url:url,
                data:{bank_id},
                success:function(data){
                    $('#account_id').html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account_id').append('<option value="' + value.id + '">' + value.account_no +
                            '</option>');
                    });
                },
            });
       }

</script>

@endpush