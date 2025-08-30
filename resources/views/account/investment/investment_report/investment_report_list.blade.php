<div class="row">
    @php $date = date('d/m/Y'); @endphp
    <div class="col-md-12 text-right">
        <button class="mt-2 col-sm-1 btn btn-warning"
            onClick="document.title = '{{ $company_name }}-Investment Report-{{ $date }}'; printDiv('printableArea'); "
            style="margin-right:100px"> <i class="fa fa-print"></i> Print </button>
    </div>
</div>

<div id="printableArea">

    <div class="container" style="margin-top: 50px">
        <div class="row text-center">
            <div class="col-sm-12">
                <h2>{{ $company_name }}</h2>
                <h5><strong> Investment Report</strong></h5>
                <h6><strong>{{ $consumer_name->consumer_name }}</strong></h6>
                <h6>{{ date('d/m/Y') }}</h6>
                {{-- <h6>{{date('d/m/Y',strtotime($start_date))}} - {{date('d/m/Y',strtotime($end_date))}}</h6> --}}
            </div>
        </div>
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
