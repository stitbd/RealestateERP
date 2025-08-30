@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Loan Report List
                        </h3>
                    </div> <!-- /.card-body -->
                    @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
                        <div class="card-body p-3">
                            {{-- <form action="{{ route('report-list') }}" method="get"> --}}
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>

                                <div class="col-lg-3">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block" onclick="viewLoanReport();">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                            {{-- </form> --}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div id="wrapper">

    </div>
    <div class="row">
        <div class="col-md-12 text-right">
            @php $date = date('d/m/Y'); @endphp
            <button
                class="mt-2 col-sm-1 btn btn-warning "onClick="document.title = '{{ $company_name }}-Loan Report-{{ $date }}'; printArea('printableArea');"
                style="margin-right:100px"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        </div>
    </div>
    <div id="printableArea">
        <div class="container" style="margin-top: 80px" id="main-table">
            <div class="row text-center">
                <div class="col-sm-12">
                    <h2>{{ $company_name }}</h2>
                    <h5><strong> Loan Report</strong></h5>
                    <h6>{{ $date }}</h6>
                </div>
            </div>
            @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <thead class="">
                                <tr>
                                    <th class="text-center">Employee</th>
                                    <th class="text-center">Loan Provider</th>
                                    <th class="text-center">Provided Loan</th>
                                    <th class="text-center">Collection Date</th>
                                    <th class="text-center">Note</th>
                                    <th class="text-center">Collected</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_collected = 0;
                                    $amount_to_collect = 0;
                                @endphp
                                @foreach ($loans as $loan)
                                @php
                                    $firstIteration = true;
                                @endphp
                                @foreach ($loan->loan_collection as $l)
                                    <tr>
                                        @if ($firstIteration)
                                            <td rowspan="{{ count($loan->loan_collection) }}" class="text-center">{{ $loan->loanee_name }}
                                            </td>
                                            <td rowspan="{{ count($loan->loan_collection) }}" class="text-center">{{ $loan->loan_provider }}
                                            </td>
                                            <td rowspan="{{ count($loan->loan_collection) }}" class="text-center">
                                                {{ $loan->amount }}</td>
                                            @php
                                                $firstIteration = false;
                                            @endphp
                                        @endif
        
                                        <td class="text-center">{{ date('d/m/Y', strtotime($l->date)) }}</td>
                                        <td class="text-center"class="text-center">{{ $l->note }}</td>
                                        <td class="text-center">{{ $l->collect_amount }}</td>
                                    </tr>
                                    @php
                                        $total_collected += $l->collect_amount;
                                        $amount_to_collect = $loan->amount - $total_collected;
                                    @endphp
                                @endforeach
        
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Total Collected:
                                            {{ $total_collected }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-right"><strong>Remaining Collection:
                                            {{ $amount_to_collect }}</strong>
                                    </td>
                                </tr>
                                @php
                                    $total_collected = 0;
                                    $amount_to_collect = 0;
                                @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

        </div>
    </div>


@endsection

<script>
    function viewLoanReport() {
        $('#main-table').hide();
        $('.btn-warning').hide();
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var url = "{{ route('loan_report_list') }}"

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                start_date,
                end_date
            },
            success: function(data) {
                $('#wrapper').html(data);
            }
        });
    }
</script>

<script>
    function printArea(divId) {
    var printContents = document.getElementById(divId).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
    }
</script>
    