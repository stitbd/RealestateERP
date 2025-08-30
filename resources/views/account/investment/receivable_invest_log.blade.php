@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Receivable Invest Log
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="row">
                        <div class="col-md-12 text-right">
                            @php $date = date('d/m/Y'); @endphp
                            <button
                                class="mt-2 col-sm-1 btn btn-warning "onClick="document.title = '{{ $company_name }}-Investment Report-{{ $date }}'; printArea('printableArea');"
                                style="margin-right:100px"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
                        </div>
                    </div>
                    <div id="printableArea">
                        <div class="card-body p-3">
                            <h4 class="bg-info text-center p-2 text-bold">
                                Client / Company: {{ $invest != null ? $invest->invest->client_name : '' }}
                                ({{ $invest != null ? $invest->invest->invest_code : '' }})
                            </h4>
                            {{-- <h5 class="bg-success text-center p-2 text-bold">
                        Supplier: {{($due_amount != null)?$due_amount->supplier->name:''}}
                     </h5> --}}
                            <table class="table mb-5">
                                <tr>
                                    <th class="bg-light">Investor : </th>
                                    <th class="bg-light">{{ $invest != null ? $invest->company->name : '' }}</th>
                                    <th class="bg-light">Due Amount : </th>
                                    <th class="bg-light text-right">Tk. {{ $invest->current_amount }}</th>
                                </tr>
                                <tr>
                                    {{-- <th class="bg-dark">Employee : </th>
                            <th class="bg-light">{{($loan != null)?$loan->loan->loanee_name:''}} ({{ $loan->loan->designation }})</th> --}}
                                    <th class="bg-light">Fund Type : </th>
                                    <th class="bg-light">{{ $invest->fund->name }}</th>
                                </tr>
                                @if ($invest->bank)
                                    <tr>
                                        <th class="bg-light">Bank:</th>
                                        <th class="bg-light">
                                            {{ $invest != null && $invest->bank != null ? $invest->bank->name : '' }}</th>
                                        <th class="bg-light">Account:</th>
                                        <th class="bg-light text-right">
                                            {{ $invest != null && $invest->invest != null ? $invest->invest->account->account_no : '' }}
                                        </th>
                                    </tr>
                                @endif

                            </table>

                            <table class="table table-bordered">
                                <thead style="background-color: rgb(23, 162, 184);">
                                    <tr>
                                        <th colspan="8">
                                            Receivable Invest List
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Date</th>
                                        <th class="text-center">Returned Amount</th>
                                        {{-- <th>Last Modified Date</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($invest->invest->return_invest) --}}
                                    @foreach ($invest->invest->return_invest as $item)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ date('d/m/Y', strtotime($item->date)) }}</td>
                                            <td class="text-center">{{ $item->return_amount }} Tk.</td>
                                            {{-- <td class="text-center">{{ date('d/m/Y',strtotime($item->created_at)) }}</td> --}}

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
@endsection
<script>
    function printArea(divId) {
        var printContents = document.getElementById(divId).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
