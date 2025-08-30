@extends('layouts.app')

@php
    use App\Models\Income;
    use App\Models\Expense;
    use App\Models\SiteOpeningBalance;
    use App\Models\SiteExpense;
    use App\Models\MoneyRequisition;
    use App\Models\MaterialRequisition;
@endphp


@section('content')
<style>
    .custom-card {
        background-color: #f72585;
        color: white;
    }

    .custom-card .icon {
        font-size: 2.5rem;
    }

    .custom-card .project-title {
        font-size: 1.2rem;
    }

    .custom-card .project-number {
        font-size: 1.8rem;
        font-weight: bold;
    }
</style>
<style>
    .scrollable-list {
        display: block;
        max-height: 300px;
        overflow-y: auto;
        width: 100%;
    }

    .scrollable-list thead,
    .scrollable-list tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    /* .scrollable-list thead {
        width: calc( 100% - 1em );
    } */

    .scrollable-list tbody {
        display: block;
        overflow-y: auto;
        height: 100%;
        /* Control height based on content */
    }

    .scrollable-list th,
    .scrollable-list td {
        /* width: 100px; */
        /* padding: 8px; */
        text-align: center;
    }
</style>

    <div class="row  p-4">
       <div class="col-lg-3">
                <div class="card mb-3" style="height: 100px; border: 1px solid #e0e0e0;  overflow: hidden;">
                    <div class="row no-gutters">
                        <div class="col-md-4"
                            style="background-color: #1691c2; color: white; border-right: 1px solid #e0e0e0;">
                            <i class="fas fa-balance-scale" style="font-size: 2.5rem; padding:29%"></i>
                        </div>
                        <div class="col-md-8 p-2">
                            <div class="card-body">
                                <span
                                    style="margin-bottom: 0.5rem; font-size: 1rem; font-weight: bold; color:#656564">Today's
                                    Income</span>
                                <p class="card-text" style="font-size: 0.9rem;"><b>{{ $todayIncome }}</b></p>
                                {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card mb-3" style="height: 100px; border: 1px solid #e0e0e0;  overflow: hidden;">
                    <div class="row no-gutters">
                        <div class="col-md-4"
                            style="background-color: #6f42c1; color: white; border-right: 1px solid #e0e0e0;">
                            <i class="fas fa-hand-holding-usd" style="font-size: 2.5rem; padding:29%"></i>
                        </div>
                        <div class="col-md-8 p-2">
                            <div class="card-body">
                                <span
                                    style="margin-bottom: 0.5rem; font-size: 1rem; font-weight: bold; color:#656564">Today's
                                    Expense</span>
                                <p class="card-text" style="font-size: 0.9rem;"><b>{{ $todayExpense }}</b></p>
                                {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card mb-3" style="height: 100px; border: 1px solid #e0e0e0;  overflow: hidden;">
                    <div class="row no-gutters">
                        <div class="col-md-4"
                            style="background-color: #1691c2; color: white; border-right: 1px solid #e0e0e0;">
                            <i class="fas fa-balance-scale" style="font-size: 2.5rem; padding:29%"></i>
                        </div>
                        <div class="col-md-8 p-2">
                            <div class="card-body">
                                <span
                                    style="margin-bottom: 0.5rem; font-size: 1rem; font-weight: bold; color:#656564">Today's
                                    Project Received</span>
                                <p class="card-text" style="font-size: 0.9rem;"><b>{{ $todayProjectReceived }}</b></p>
                                {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-3">
                <div class="card mb-3" style="height: 100px; border: 1px solid #e0e0e0;  overflow: hidden;">
                    <div class="row no-gutters">
                        <div class="col-md-4"
                            style="background-color: #6f42c1; color: white; border-right: 1px solid #e0e0e0;">
                            <i class="fas fa-hand-holding-usd" style="font-size: 2.5rem; padding:29%"></i>
                        </div>
                        <div class="col-md-8 p-2">
                            <div class="card-body">
                                <span style="margin-bottom: 0.5rem; font-size: 1rem; font-weight: bold; color:#656564">
                                    Today's Project Payment</span>
                                <p class="card-text" style="font-size: 0.9rem;"><b>{{ $todayProjectPayment }}</b></p>
                                {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="col-lg-3">
                <div class="card mb-3" style="height: 100px; border: 1px solid #e0e0e0;  overflow: hidden;">
                  <div class="row no-gutters">
                    <div class="col-md-4" style="background-color: #20c997; color: white; border-right: 1px solid #e0e0e0;">
                      <i class="fas fa-boxes" style="font-size: 2.5rem; padding:29%"></i>
                    </div>
                    <div class="col-md-8 p-2">
                      <div class="card-body">
                        <p style="margin-bottom: 0.5rem; font-size: 1rem; font-weight: bold; color:#656564">Total Project Received</p>
                        <p class="card-text" style="font-size: 0.9rem;"><b></b></p>
                        {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> --
                      </div>
                    </div>
                  </div>
                </div>
              </div> --}}
            {{-- <div class="col-lg-3">
                <div class="card mb-3" style="height: 100px; border: 1px solid #e0e0e0;  overflow: hidden;">
                  <div class="row no-gutters">
                    <div class="col-md-4" style="background-color: #20c997; color: white; border-right: 1px solid #e0e0e0;">
                      <i class="fas fa-boxes" style="font-size: 2.5rem; padding:29%"></i>
                    </div>
                    <div class="col-md-8 p-2">
                      <div class="card-body">
                        <p style="margin-bottom: 0.5rem; font-size: 1rem; font-weight: bold; color:#656564">Total Project Payment</p>
                        <p class="card-text" style="font-size: 0.9rem;"><b></b></p>
                        {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> 
                      </div>
                    </div>
                  </div>
                </div>
              </div> --}}
            {{-- <div class="col-lg-3">
                <div class="card mb-3" style="height: 100px; border: 1px solid #e0e0e0;  overflow: hidden;">
                  <div class="row no-gutters">
                    <div class="col-md-4" style="background-color: #20c997; color: white; border-right: 1px solid #e0e0e0;">
                      <i class="fas fa-boxes" style="font-size: 2.5rem; padding:29%"></i>
                    </div>
                    <div class="col-md-8 p-2">
                      <div class="card-body">
                        <p  style="margin-bottom: 0.5rem; font-size: 1rem; font-weight: bold; color:#656564">Total Supplier Payment</p>
                        <p class="card-text" style="font-size: 0.9rem;"><b></b></p>
                        {{-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> 
                      </div>
                    </div>
                  </div>
                </div>
              </div> --}}
            <div class="col-12 mt-4">
                <div class="card">
                    <div class="card-header border-1 bg-info">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Project List</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped scrollable-list" style="font-size:15px">
                            <thead>
                                <tr class="bg-secondary text-center">
                                    <th width="4%">ID</th>
                                    {{-- <th>Company</th> --}}
                                    <th width="10%">Name</th>
                                    <th width="10%">Location</th>
                                    <th width="10%">Tender ID</th>
                                    <th width="10%">Start Date</th>
                                    <th width="10%">End Date</th>
                                    <th width="15%">Contact Amount</th>
                                    <th width="15%">Project In</th>
                                    <th width="15%">Project Out</th>
                                    <th width="15%">Profit/Loss</th>
                                    {{-- <th>Status</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @if ($project_data)
                                    @foreach ($project_data as $item)
                                        @php
                                            $income = Income::where('project_id', $item->id)
                                                ->where('status', 1)
                                                ->sum('amount');
                                            $expense = Expense::where('project_id', $item->id)
                                                ->where('status', 1)
                                                ->sum('amount');
                                            $profit = $income - $expense;
                                        @endphp
                                        <tr>
                                            <td width="4%">{{ $item->id }}</td>
                                            {{-- <td>{{ $item->company->name }}</td> --}}
                                            <td width="10%">{{ $item->name }}</td>
                                            <td width="10%">{{ $item->location }}</td>
                                            <td width="10%">{{ $item->description }}</td>
                                            {{-- <td width="15%">{{ $item->authority }}</td> --}}
                                            <td width="10%">
                                                {{ $item->start_date ? date('d/m/Y', strtotime($item->start_date)) : '' }}
                                            </td>
                                            <td width="10%">
                                                {{ $item->end_date ? date('d/m/Y', strtotime($item->end_date)) : '' }}</td>
                                            <td width="15%">{{ $item->project_amount }}</td>
                                            <td width="15%">{{ $income > 0 ? $income : '' }}</td>
                                            <td width="15%">{{ $expense > 0 ? $expense : '' }}</td>
                                            <td width="15%">{{ $profit }}</td>
                                            {{-- <td>
                                            @if ($item->status == '1')
                                                <span class="btn btn-block btn-outline-info">Not Started</span>
                                            @elseif ($item->status == '2')
                                                <span class="btn btn-block btn-outline-primary">In Progress</span>
                                            @elseif ($item->status == '3')
                                                <span class="btn btn-block btn-outline-warning">On Hold</span>
                                            @elseif ($item->status == '4')
                                                <span class="btn btn-block btn-outline-danger">Canceled</span>
                                            @elseif ($item->status == '5')
                                                <span class="btn btn-block btn-outline-success">Completed</span>
                                            @endif
                                        </td> --}}

                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-4">
                <div class="card">
                    <div class="card-header border-1" style="background:  rgb(238, 74, 74); color:white">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Recent Debit </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" style="font-size:15px">
                            <tbody>
                                @if ($recent_debit)
                                    @php
                                        $debitNo = 0;
                                    @endphp
                                    @foreach ($recent_debit as $debit)
                                        <tr class="text-center">
                                            <td>{{ ++$debitNo }}</td>
                                            <td>{{ date('d/m/Y', strtotime($debit->payment_date)) }}</td>
                                            <td>{{ $debit->voucher_no }}</td>
                                            <td>{{ $debit->amount }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6  mt-4">
                <div class="card">
                    <div class="card-header border-1 text-white" style="background: rgb(38, 110, 38)">
                        <div class="d-flex justify-content-between ">
                            <h3 class="card-title">Recent Credit </h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped" style="font-size:15px">
                            <tbody>
                                @if ($recent_credit)
                                    @php
                                        $creditNo = 0;
                                    @endphp
                                    @foreach ($recent_credit as $credit)
                                        <tr class="text-center">
                                            <td>{{ ++$creditNo }}</td>
                                            <td>{{ date('d/m/Y', strtotime($credit->payment_date)) }}</td>
                                            <td>{{ $credit->voucher_no }}</td>
                                            <td>{{ $credit->amount }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mt-3">
                <div class="card">
                    <div class="card-header border-1 bg-info">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Expense Report(Current Month)</h3>
                        </div>
                    </div>
                    <div class="card-body pt-3">
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
                        <canvas id="debitChart" style="width:100%;max-width:600px"></canvas>

                        @php
                            $payment_data = '';
                            $payment_label = '';
                            foreach ($allIncome as $v_payment) {
                                $payment_data .= $v_payment->amount . ',';
                                $payment_label .= date('d', strtotime($v_payment->payment_date)) . ',';
                            }

                        @endphp
                        <script>
                            var xValues = '{{ $payment_label }}';
                            var yValues = '{{ $payment_data }}';
                            var xValues = xValues.split(",");
                            var yValues = yValues.split(",");
                            //alert(yValues);
                            let [min_value, max_value] = yValues.reduce(([prevMin, prevMax], curr) => [Math.min(prevMin, curr), Math.max(
                                prevMax, curr)], [Infinity, -Infinity]);
                            //alert(min);

                            new Chart("debitChart", {
                                type: "line",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        fill: false,
                                        lineTension: 0,
                                        backgroundColor: "rgba(0,0,255,1.0)",
                                        borderColor: "rgba(0,0,255,0.1)",
                                        data: yValues
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                min: min_value,
                                                max: max_value
                                            }
                                        }],
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mt-3">
                <div class="card">
                    <div class="card-header border-1 bg-success">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Income Report (Current Month)</h3>
                        </div>
                    </div>
                    <div class="card-body pt-3">

                        <canvas id="creditChart" style="width:100%;max-width:600px"></canvas>

                        @php
                            $receive_data = '';
                            $receive_label = '';
                            foreach ($allExpense as $v_receive) {
                                $receive_data .= $v_receive->amount . ',';
                                $receive_label .= date('d', strtotime($v_receive->payment_date)) . ',';
                            }

                        @endphp
                        <script>
                            var xValues = '{{ $receive_label }}';
                            var yValues = '{{ $receive_data }}';
                            var xValues = xValues.split(",");
                            var yValues = yValues.split(",");
                            //alert(yValues);
                            let [Vendor_min_value, Vendor_max_value] = yValues.reduce(([prevMin, prevMax], curr) => [Math.min(prevMin,
                                curr), Math.max(prevMax, curr)], [Infinity, -Infinity]);
                            //alert(min);

                            new Chart("creditChart", {
                                type: "line",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        fill: false,
                                        lineTension: 0,
                                        backgroundColor: "rgba(0,0,255,1.0)",
                                        borderColor: "rgba(0,0,255,0.1)",
                                        data: yValues
                                    }]
                                },
                                options: {
                                    legend: {
                                        display: false
                                    },
                                    scales: {
                                        yAxes: [{
                                            ticks: {
                                                min: Vendor_min_value,
                                                max: Vendor_max_value
                                            }
                                        }],
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-1 bg-success">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Fund Balance In (Current Month)</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="daily_cash" style="width:100%;max-width:600px"></canvas>
                        @php
                            $amount = '';
                            $payment_type = '';
                            foreach ($daily_cash_in as $item) {
                                $amount .= $item->amount . ',';
                                $payment_type .= $item->payment_type . ',';
                            }

                        @endphp
                        <script>
                            var VendorxValues = '{{ $payment_type }}';
                            var VendoryValues = '{{ $amount }}';
                            var xValues = VendorxValues.split(",");
                            var yValues = VendoryValues.split(",");

                            //var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
                            //var yValues = [55, 49, 44, 24, 15];
                            var barColors = [
                                "#b91d47",
                                "#00aba9",
                                "#2b5797",
                                "#e8c3b9",
                                "#1e7145"
                            ];

                            new Chart("daily_cash", {
                                type: "pie",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        backgroundColor: barColors,
                                        data: yValues
                                    }]
                                },
                                options: {
                                    title: {
                                        display: true,
                                        text: ""
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header border-1 bg-info">
                        <div class="d-flex justify-content-between">
                            <h3 class="card-title">Fund Balance Out (Current Month)</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="daily_cash_out" style="width:100%;max-width:600px"></canvas>
                        @php
                            $amount = '';
                            $payment_type = '';
                            foreach ($daily_cash_out as $item) {
                                $amount .= $item->amount . ',';
                                $payment_type .= $item->payment_type . ',';
                            }

                        @endphp
                        <script>
                            var VendorxValues = '{{ $payment_type }}';
                            var VendoryValues = '{{ $amount }}';
                            var xValues = VendorxValues.split(",");
                            var yValues = VendoryValues.split(",");

                            //var xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
                            //var yValues = [55, 49, 44, 24, 15];
                            var barColors = [
                                "#b91d47",
                                "#00aba9",
                                "#2b5797",
                                "#e8c3b9",
                                "#1e7145"
                            ];

                            new Chart("daily_cash_out", {
                                type: "pie",
                                data: {
                                    labels: xValues,
                                    datasets: [{
                                        backgroundColor: barColors,
                                        data: yValues
                                    }]
                                },
                                options: {
                                    title: {
                                        display: true,
                                        text: ""
                                    }
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>

@endsection
