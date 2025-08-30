@extends('layouts.app')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Consumer Investor Statement
                        </h3>
                    </div> <!-- /.card-body -->
                    @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
                        <div class="card-body p-3">
                            {{-- <form action="{{ route('Report-list') }}" method="get"> --}}
                            <div class="row pb-3">
                                <div class="col-lg-3">
                                    <label for="consumer_name">Consumer Investor</label>
                                    <select name="consumer_name" id="consumer_name" class="form-control chosen-select">
                                        <option value="">Select One</option>
                                        @foreach ($invests as $invest)
                                            <option value="{{ $invest->id }}">{{ $invest->consumer_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-lg-3">
                                    <label for="start_date">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="col-lg-3">
                                    <label for="end_date">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date"
                                        value="{{ date('Y-m-d') }}" />
                                </div> --}}

                                <div class="col-lg-3">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block" onclick="viewInvestmentReport();">
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
    {{-- <div class="row">
        <div class="col-md-12 text-right">
            @php $date = date('d/m/Y'); @endphp
            <button
                class="mt-2 col-sm-1 btn btn-warning "onClick="document.title = '{{ $company_name }}-Investment Report-{{ $date }}'; printArea('printableArea');"
                style="margin-right:100px"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        </div>
    </div>
    <div id="printableArea">
        <div class="container" style="margin-top: 80px" id="main-table">
            <div class="row text-center">
                <div class="col-sm-12">
                    <h2>{{ $company_name }}</h2>
                    <h5><strong> Consumer Investor Statement </strong></h5>
                    <h6>{{ $date }}</h6>
                </div>
            </div>
            @if (auth()->user()->role == 'SuperAdmin' || auth()->user()->role == 'Admin')
                <div class="row" style="margin-top: 20px">
                    <div class="col-sm-12">
                        <table class="table table-bordered">
                            <thead class="">
                                <tr>
                                    <th class="text-center">Consumer Investor</th>
                                    <th class="text-center">Monthly Invest Amount</th>
                                    <th class="text-center">Invest Collection Date</th>
                                    <th class="text-center">Collection Month</th>
                                    <th class="text-center">Remarks</th>
                                    <th class="text-center">Collected</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total_collected = 0;
                                @endphp
                                @foreach ($invests as $invest)
                                @if ($invest->collect_invest->isNotEmpty())
                                    @php
                                        $groupedCollections = $invest->collect_invest
                                            ->flatMap(fn($r) => $r->collected_missed_months)
                                            ->where('type', 'collect')
                                            ->groupBy('consumer_investor_id');
                                    @endphp
                            
                                    @foreach ($groupedCollections as $consumer_investor_id => $collections)
                                        @php
                                            $firstIteration = true;
                                            $total_collected = $collections->sum('collect_amount');
                                        @endphp
                                        @foreach ($collections as $c)
                                            <tr>
                                                @if ($firstIteration)
                                                    <td rowspan="{{ $collections->count() }}" class="text-center">
                                                        {{ $invest->consumer_name }}
                                                    </td>
                                                    <td rowspan="{{ $collections->count() }}" class="text-center">
                                                        {{ $invest->invest_amount }}
                                                    </td>
                                                    @php
                                                        $firstIteration = false;
                                                    @endphp
                                                @endif
                                                <td class="text-center">{{ date('d/m/Y', strtotime($c->collection->date)) }}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($c->collect_month)->format('F Y') }}</td>
                                                <td class="text-center">{{ $c->remarks }}</td>
                                                <td class="text-center">{{ $c->collect_amount }} Tk.</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6" class="text-right">
                                                <strong>Total Collected: {{ $total_collected }} Tk.</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif


        </div>
    </div> --}}


@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script>
    $(".chosen-select").chosen();

    function viewInvestmentReport() {
        $('#main-table').hide();
        $('.btn-warning').hide();
        // var start_date = document.getElementById('start_date').value;
        // var end_date = document.getElementById('end_date').value;
        var consumer_name = document.getElementById('consumer_name').value;
        var url = "{{ route('investment_report_list') }}"

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                consumer_name
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
