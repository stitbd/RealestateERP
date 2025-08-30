@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div style="text-align: right;" class="mb-3">
                    <input type="button" class="btn btn-success" onclick="printDiv('printableArea')" value="Print Log" />
                </div>
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Receivable Loan Log
                        </h3>
                    </div>
                     <!-- /.card-body -->
                     <div id="printableArea">
                    <div class="card-body p-3">
                        <h4 class="bg-info text-center p-2 text-bold">
                            Employee: {{ $loan != null ? $loan->loan->loanee_name : '' }}
                        </h4>

                        <table class="table mb-5">
                            <tr>
                                <th class="">Loan Provider : </th>
                                <th class="">{{ $loan != null ? $loan->company->name : '' }}</th>
                                <th class="">Due Amount : </th>
                                <th class="">Tk. {{ $loan->current_amount }}</th>
                            </tr>
                            <tr>

                                <th class="">Fund Type : </th>
                                <th class="">{{ $loan->fund->name }}</th>
                            </tr>
                            @if ($loan->bank)
                                <tr>
                                    <th class="">Bank:</th>
                                    <th class="">
                                        {{ $loan != null && $loan->bank != null ? $loan->bank->name : '' }}</th>
                                    <th class="">Account:</th>
                                    <th class="">
                                        {{ $loan != null && $loan->account != null ? $loan->account->account_no : '' }}
                                    </th>
                                </tr>
                            @endif


                        </table>

                        <table class="table table-bordered">
                            <thead class="">
                                <tr>
                                    <th colspan="8">
                                        Loan Information
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center">Loan Provide Date</th>
                                    <th class="text-center">Loan Amount</th>
                                    <th class="text-center">Fund</th>
                                    <th class="text-center">Bank</th>
                                    <th class="text-center">Account</th>
                                    <th class="text-center">Maturity Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="text-center">
                                    <td>{{$loan->loan->loan_date}}</td>
                                    <td>{{$loan->loan->amount}}</td>
                                    <td>{{$loan->loan->fund->name}}</td>
                                    <td>{{$loan->bank_id ? $loan->loan->bank->name : ''}}</td>
                                    <td>{{$loan->loan->account_id ? $loan->loan->account->account_no : ''}}</td>
                                    <td>{{$loan->loan->valid_date ? $loan->loan->valid_date : ''}}</td>
                                </tr>
                            </tbody>
                        </table>


                        <table class="table table-bordered mt-4">
                            <thead class="">
                                <tr>
                                    <th colspan="8">
                                        Loan Collection List
                                    </th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Collected Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($loan->loan->loan_collection as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                        <td class="text-center">{{ $item->collect_amount }} Tk.</td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
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
@endsection


