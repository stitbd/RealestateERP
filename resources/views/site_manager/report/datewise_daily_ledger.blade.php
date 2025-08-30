
@php
    use Carbon\Carbon;
    use App\Models\Project;

    $today = Carbon::today();

    $yesterday = $today->subDay();

    $formattedDate = $yesterday->format('Y-m-d');

    $project = Project::where('id', auth()->user()->project_id)->first();

@endphp
<div class="container" style="margin-top: 70px;">
    <div class="row">
        <div class="col-md-12 text-right">
            @php $date = date('d/m/Y'); @endphp
            <button class="mt-2 col-sm-1 btn btn-warning"
                onClick="document.title = '{{ $project->name }}-Daily Ledger-{{ $date }}'; printArea('printableArea');"
                style="margin-right:100px"><i class="fa fa-print" aria-hidden="true"></i> Print</button>
        </div>
    </div>
    <div id="printableArea">

        <div class="row text-center">
            <div class="col-sm-12">
                <h3>{{ $project->name }}</h3>
                {{-- <h5><strong>{{ $project->location }}</strong></h5> --}}
                <h5><strong> Daily Ledger Report</strong></h5>
                <h6>{{ $date }}</h6>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead class="">
                        <tr>
                            <th>Date</th>
                            <th>Particulars</th>
                            <th>Sub Haed</th>
                            <th>Receive</th>
                            <th>Payment</th>
                            <th>V.NO.</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @if ($prev_balance)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($start_date)) }}</td>
                                <td>Cash From {{ date('d/m/Y', strtotime($formattedDate)) }}</td>
                                <td></td>
                                <td>{{$prev_balance}}</td>
                                <td></td>
                                <td></td>
                                @php
                                    $total += $prev_balance;
                                @endphp
                                <td>{{$total}}</td>
                            </tr>
                        @endif
                        @if ($opening_balance)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($opening_balance->date)) }}</td>
                                <td>Opening Balance</td>
                                <td></td>
                                <td>{{$opening_balance->amount}}</td>
                                <td></td>
                                <td></td>
                                @php
                                    $total += $opening_balance->amount;
                                @endphp
                                <td>{{$total}}</td>
                            </tr>
                        @endif
                        @if($site_pay)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($site_pay->date)) }}</td>
                                <td>Receive From Head Office</td>
                                <td></td>
                                <td>{{$site_pay->amount}}</td>
                                <td></td>
                                <td></td>
                                @php
                                    $total += $site_pay->amount;
                                @endphp
                                <td>{{$total}}</td>
                            </tr>
                        @endif
                        @if($site_expense)
                        @foreach($site_expense as $v_expense)
                            <tr>
                                <td>{{ date('d/m/Y', strtotime($v_expense->payment_date)) }}</td>
                                <td>{{$v_expense->remarks}}</td>
                                <td></td>
                                <td>{{$v_expense->amount}}</td>
                                <td>{{$v_expense->voucher_no}}</td>
                                <td></td>
                                @php
                                    $total -= $v_expense->amount;
                                @endphp
                                <td>{{$total}}</td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>