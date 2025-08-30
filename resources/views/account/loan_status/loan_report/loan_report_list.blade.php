<div class="row">
    @php $date = date('d/m/Y'); @endphp
    <div class="col-md-12 text-right">
        <button class="mt-2 col-sm-1 btn btn-warning" onClick="document.title = '{{$company_name}}-Loan Report-{{$date}}'; printDiv('printableArea'); " style="margin-right:100px"> <i class="fa fa-print"></i> Print </button>
    </div>
</div>

<div id="printableArea">

<div class="container" style="margin-top: 50px">
    <div class="row text-center">
        <div class="col-sm-12">
            <h2>{{$company_name}}</h2>
            <h5><strong>  Loan Report </strong></h5>
            <h6>{{date('d/m/Y',strtotime($start_date))}} - {{date('d/m/Y',strtotime($end_date))}}</h6>
        </div>
    </div>
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
                    @foreach ($data['loans'] as $loan)
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
