@php
    use Carbon\Carbon;
    use App\Models\Project;

    $today = Carbon::today();

    $yesterday = $today->subDay();

    $formattedDate = $yesterday->format('Y-m-d');

    $project = Project::where('id', auth()->user()->project_id)->first();

@endphp

@extends('layouts.app')
@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Daily Ledger List
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        {{-- <form action="{{ route('ledger-list') }}" method="get"> --}}
                        <div class="row pb-3">
                            <div class="col-lg-3">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="start_date"
                                    value="@php echo date('Y-m-d') @endphp" />
                            </div>
                            <div class="col-lg-3">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="end_date"
                                    value="@php echo date('Y-m-d') @endphp" />
                            </div>

                            <div class="col-lg-3">
                                <label for="action">Action</label> <br />
                                <button class="btn btn-success btn-block" onclick="viewLedger();">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        {{-- </form> --}}
                    </div>

                </div>

                <div class="container" style="margin-top: 70px;" id="main-table">
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
                                                <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
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
                                                <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
                                                <td>Opening Balance</td>
                                                <td></td>
                                                <td>{{$total += $opening_balance->amount;}}</td>
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
                                                <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
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
                                                <td>{{ date('d/m/Y', strtotime(date('Y-m-d'))) }}</td>
                                                <td>{{$v_expense->remarks}}</td>
                                                <td></td>
                                                <td>{{$v_expense->amount}}</td>
                                                <td></td>
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
            </div>
        </div>
    </div>
    <div id="wrapper">

    </div>
@endsection

<script>
    function viewLedger() {
        $('#main-table').hide();
        $('.btn-warning').hide();
        var start_date = document.getElementById('start_date').value;
        var end_date = document.getElementById('end_date').value;
        var url = "{{ route('daily-ledger-after-search') }}"

        $.ajax({
            type: 'GET',
            url: url,
            data: {
                start_date,
                end_date,
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
