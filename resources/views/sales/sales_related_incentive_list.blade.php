@php
    use Carbon\Carbon;
@endphp

@extends('layouts.app')
<style>
    .td {}

    .text-red {
        color: rgb(145, 12, 12);
    }

    .text-green {
        color: rgb(19, 99, 8);
    }

    .text-blue {
        color: rgb(3, 47, 53);
    }

    .styled-hr {
        border: none;
        height: 5px;
        background-color: rgb(8, 71, 3);
        margin: 20px 0;
    }
</style>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printableArea, #printableArea * {
            visibility: visible;
        }
        #printableArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 8px;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<script>
    function printPage(itemId, companyName) {
        var printContents = document.getElementById('print_body_' + itemId).innerHTML;
        var today = new Date();
        var formattedDate = today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear();

        var printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: Arial, sans-serif; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
        printWindow.document.write('th, td { border: 1px solid black; padding: 8px; text-align: left; }');
        printWindow.document.write('.header { text-align: center; margin-bottom: 20px; }');
        printWindow.document.write('</style></head><body>');
        printWindow.document.write('<div class="header">');
        printWindow.document.write('<h1>' + companyName + '</h1>');
        printWindow.document.write('<p>Date: ' + formattedDate + '</p>');
        printWindow.document.write('</div>');
        printWindow.document.write(printContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>


@section('content')


    {{-- Monthly Incentive Report --}}
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Monthly Incentive Report
                        </h3>
                    </div>
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('sales_related_incentive') }}">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-3">
                                    <label>End Date</label>
                                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4 mt-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                    <a href="{{ route('sales_related_incentive') }}" class="btn btn-secondary">Reset</a>
                                    <button onclick="printTable()" class="btn btn-success" {{ !request('start_date') && !request('end_date') ? 'disabled' : '' }}>Print</button>
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#totalSheetModal" {{ !request('start_date') && !request('end_date') ? 'disabled' : '' }}>Top Sheet</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if(request('start_date') || request('end_date'))
        <div id="printableArea" class="p-4">
            <div class="report-header text-center">
                <h2 class="company-name text-uppercase font-weight-bold" style="color: #2c3e50; font-size: 28px;">{{ Session::get('company_name') }}</h2>
                <h4 class="report-title font-weight-bold" style="color: #3498db; border-bottom: 2px solid #3498db; display: inline-block;">Monthly Incentive Report</h4>
                <div class="period-info">
                    <p class="period bg-light p-2 rounded d-inline-block">
                        <strong>Period:</strong>
                        {{ request('start_date') ? date('d M, Y', strtotime(request('start_date'))) : 'Start' }}
                        to {{ request('end_date') ? date('d M, Y', strtotime(request('end_date'))) : 'End' }}
                    </p>
                    <p class="generated-on text-muted small">
                        Generated on: {{ date('d M, Y h:i A') }}
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="font-size: 14px;">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 3%;" class="text-center">SL</th>
                            <th style="width: 3%;" class="text-center no-print">Action</th>
                            <th style="width: 12%;">Customer Name</th>
                            <th style="width: 8%;">TDC</th>
                            <th style="width: 8%;">Voucher</th>
                            <th style="width: 10%;">Group</th>
                            <th style="width: 12%;">Name</th>
                            <th style="width: 8%;" class="text-center">Incentive (%)</th>
                            <th style="width: 12%;" class="text-right">Amount (Tk.)</th>
                            <th style="width: 10%;">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $allTotals = [
                                'directors' => [],
                                'coordinators' => [],
                                'shareholders' => [],
                                'outsiders' => []
                            ];
                            $visibleRows = 0;
                            $hiddenCustomers = [];
                        @endphp

                        @foreach($incentive_details as $key => $inc)
                            @php
                                $grouped = collect(['directors', 'coordinators', 'shareholders', 'outsiders'])->flatMap(function($group) use ($inc) {
                                    return collect($inc[$group] ?? [])->map(function($item) use ($group) {
                                        $item['group'] = $group;
                                        return $item;
                                    });
                                })->filter(fn($item) => $item['amount'] > 0);

                                $rows = $grouped->count();
                                $printed = false;

                                $groupMemberTotals = $grouped->groupBy(['group', 'name'])->map(function($groupItems, $groupName) {
                                    return $groupItems->map(function($memberItems, $memberName) {
                                        return [
                                            'name' => $memberName,
                                            'total' => $memberItems->sum('amount')
                                        ];
                                    });
                                });

                                foreach($groupMemberTotals as $groupName => $members) {
                                    foreach($members as $member) {
                                        if(!isset($allTotals[$groupName][$member['name']])) {
                                            $allTotals[$groupName][$member['name']] = 0;
                                        }
                                        $allTotals[$groupName][$member['name']] += $member['total'];
                                    }
                                }

                                $totalRows = $rows + $groupMemberTotals->flatten(1)->count();
                                $rowId = 'row-'.($key+1);
                                $customerId = 'customer-'.($key+1);

                                if($rows > 0) {
                                    $visibleRows++;
                                }
                            @endphp

                            @if($rows > 0)
                                <tbody id="{{ $rowId }}" class="data-row" data-customer="{{ $customerId }}">
                                    @foreach($grouped as $item)
                                        <tr data-group="{{ $item['group'] }}" data-name="{{ $item['name'] }}" data-amount="{{ $item['amount'] }}">
                                            @if(!$printed)
                                                <td rowspan="{{ $totalRows }}" class="text-center align-middle sl-number">{{ $visibleRows }}</td>
                                                <td rowspan="{{ $totalRows }}" class="text-center align-middle no-print">
                                                    <button class="btn btn-sm btn-danger btn-toggle-visibility" data-target="{{ $rowId }}" data-customer="{{ $customerId }}">
                                                        <i class="fas fa-eye-slash"></i> Hide
                                                    </button>
                                                </td>
                                                <td rowspan="{{ $totalRows }}" class="align-middle">{{ $inc['customer_name'] }}</td>
                                                <td rowspan="{{ $totalRows }}" class="align-middle">{{ $inc['customer_code'] }}</td>
                                                <td rowspan="{{ $totalRows }}" class="align-middle">{{ $inc['invoice_no'] ?? '' }}</td>
                                                @php $printed = true; @endphp
                                            @endif

                                            <td class="text-capitalize">
                                                @switch($item['group'])
                                                    @case('directors') Director @break
                                                    @case('coordinators') Co-Ordinator @break
                                                    @case('shareholders') Shareholder @break
                                                    @case('outsiders') Outsider @break
                                                @endswitch
                                            </td>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-center">{{ $item['percent'] }}%</td>
                                            <td class="text-right">{{ number_format($item['amount'], 2) }}</td>
                                            <td class="text-muted">
                                                @if($item['is_per_sales'])
                                                    <small><i>{{ $item['created_at'] }}</i></small>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach

                                    @foreach($groupMemberTotals as $groupName => $members)
                                        @foreach($members as $member)
                                            <tr style="background-color: #f8f9fa;">
                                                <td colspan="2" class="text-right font-weight-bold" style="border-right: none;">
                                                    <span class="text-capitalize">
                                                        @switch($groupName)
                                                            @case('directors') Director @break
                                                            @case('coordinators') Co-Ordinator @break
                                                            @case('shareholders') Shareholder @break
                                                            @case('outsiders') Outsider @break
                                                        @endswitch
                                                        Total – {{ $member['name'] }}
                                                    </span>
                                                </td>
                                                <td colspan="2" class="text-right font-weight-bold" style="border-left: none;">
                                                    {{ number_format($member['total'], 2) }} Tk.
                                                </td>
                                                <td colspan="2"></td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Sheet Modal -->
        <div class="modal fade" id="totalSheetModal" tabindex="-1" role="dialog" aria-labelledby="totalSheetModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info">
                        <h5 class="modal-title" id="totalSheetModalLabel">Top Sheet</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="printableTotalSheet">
                            <div class="report-header text-center">
                                <h2 class="company-name text-uppercase font-weight-bold" style="color: #2c3e50; font-size: 28px;">{{ Session::get('company_name') }}</h2>
                                <h4 class="report-title font-weight-bold" style="color: #3498db; border-bottom: 2px solid #3498db; display: inline-block;">Monthly Top Sheet Report</h4>
                                <div class="period-info">
                                    <p class="period bg-light p-2 rounded d-inline-block">
                                        <strong>Period:</strong>
                                        {{ request('start_date') ? date('d M, Y', strtotime(request('start_date'))) : 'Start' }}
                                        to {{ request('end_date') ? date('d M, Y', strtotime(request('end_date'))) : 'End' }}
                                    </p>
                                    <p class="generated-on text-muted small">
                                        Generated on: {{ date('d M, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover text-center">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Group</th>
                                            <th>Name</th>
                                            <th class="text-right">Amount (Tk.)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="topSheetTableBody">
                                        @foreach(['directors', 'coordinators', 'shareholders', 'outsiders'] as $group)
                                            @if(isset($allTotals[$group]) && !empty($allTotals[$group]))
                                                @foreach($allTotals[$group] as $name => $total)
                                                    <tr data-group="{{ $group }}" data-name="{{ $name }}" data-total="{{ $total }}">
                                                        <td class="text-capitalize">
                                                            @switch($group)
                                                                @case('directors') Director @break
                                                                @case('coordinators') Co-Ordinator @break
                                                                @case('shareholders') Shareholder @break
                                                                @case('outsiders') Outsider @break
                                                            @endswitch
                                                        </td>
                                                        <td>{{ $name }}</td>
                                                        <td class="text-right">{{ number_format($total, 2) }} Tk.</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold bg-light">
                                            <td colspan="2" class="text-right">Total Amount:</td>
                                            <td class="text-right" id="grandTotal">
                                                @php
                                                    $grandTotal = 0;
                                                    foreach($allTotals as $group => $members) {
                                                        foreach($members as $total) {
                                                            $grandTotal += $total;
                                                        }
                                                    }
                                                @endphp
                                                {{ number_format($grandTotal, 2) }} Tk.
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="printTotalSheet()">Print</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Track hidden members and their amounts
            let hiddenMembers = {};

            // Toggle row visibility
            document.querySelectorAll('.btn-toggle-visibility').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const customerId = this.getAttribute('data-customer');
                    const target = document.getElementById(targetId);

                    if (target.style.display === 'none') {
                        // Show row
                        target.style.display = '';
                        this.innerHTML = '<i class="fas fa-eye-slash"></i> Hide';

                        // Remove from hidden members
                        const memberRows = target.querySelectorAll('tr[data-group][data-name]');
                        memberRows.forEach(row => {
                            const group = row.getAttribute('data-group');
                            const name = row.getAttribute('data-name');
                            const amount = parseFloat(row.getAttribute('data-amount'));

                            if (hiddenMembers[group] && hiddenMembers[group][name]) {
                                hiddenMembers[group][name] -= amount;
                                if (hiddenMembers[group][name] <= 0) {
                                    delete hiddenMembers[group][name];
                                }
                            }
                        });
                    } else {
                        // Hide row
                        target.style.display = 'none';
                        this.innerHTML = '<i class="fas fa-eye"></i> Show';

                        // Add to hidden members
                        const memberRows = target.querySelectorAll('tr[data-group][data-name]');
                        memberRows.forEach(row => {
                            const group = row.getAttribute('data-group');
                            const name = row.getAttribute('data-name');
                            const amount = parseFloat(row.getAttribute('data-amount'));

                            if (!hiddenMembers[group]) {
                                hiddenMembers[group] = {};
                            }

                            if (!hiddenMembers[group][name]) {
                                hiddenMembers[group][name] = 0;
                            }

                            hiddenMembers[group][name] += amount;
                        });
                    }

                    // Update SL numbers
                    updateSerialNumbers();

                    // Update Top Sheet Report
                    updateTopSheetReport();
                });
            });

            function updateSerialNumbers() {
                const visibleRows = document.querySelectorAll('tbody.data-row:not([style*="display: none"])');
                let slNumber = 1;

                visibleRows.forEach(row => {
                    const slCell = row.querySelector('.sl-number');
                    if (slCell) {
                        slCell.textContent = slNumber++;
                    }
                });
            }

            function updateTopSheetReport() {
                // Get all rows in Top Sheet Report
                const topSheetRows = document.querySelectorAll('#topSheetTableBody tr');

                // Calculate new totals
                let newTotals = {};
                let grandTotal = 0;

                // Initialize new totals with original values
                topSheetRows.forEach(row => {
                    const group = row.getAttribute('data-group');
                    const name = row.getAttribute('data-name');
                    const originalTotal = parseFloat(row.getAttribute('data-total'));

                    if (!newTotals[group]) {
                        newTotals[group] = {};
                    }

                    newTotals[group][name] = originalTotal;
                });

                // Subtract hidden members' amounts
                for (const group in hiddenMembers) {
                    for (const name in hiddenMembers[group]) {
                        if (newTotals[group] && newTotals[group][name]) {
                            newTotals[group][name] -= hiddenMembers[group][name];
                        }
                    }
                }

                // Update Top Sheet Report
                topSheetRows.forEach(row => {
                    const group = row.getAttribute('data-group');
                    const name = row.getAttribute('data-name');

                    if (newTotals[group] && newTotals[group][name] !== undefined) {
                        const newAmount = newTotals[group][name];

                        if (newAmount <= 0) {
                            row.style.display = 'none';
                        } else {
                            row.style.display = '';
                            row.querySelector('td:nth-child(3)').textContent = newAmount.toFixed(2) + ' Tk.';
                            grandTotal += newAmount;
                        }
                    } else {
                        row.style.display = '';
                        const originalTotal = parseFloat(row.getAttribute('data-total'));
                        row.querySelector('td:nth-child(3)').textContent = originalTotal.toFixed(2) + ' Tk.';
                        grandTotal += originalTotal;
                    }
                });

                // Update grand total
                document.getElementById('grandTotal').textContent = grandTotal.toFixed(2) + ' Tk.';
            }

            function printTable() {
                const printContents = document.getElementById('printableArea').innerHTML;
                const originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
                window.location.reload();
            }

            function printTotalSheet() {
                const printContents = document.getElementById('printableTotalSheet').innerHTML;
                const originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
    @else
    <!-- Empty state when no date range selected -->
    @endif








    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Sale Related Incentive List</h3>
                        <!-- Add search input fields -->
                        <div class="card-tools">
                            <div class="input-group input-group-sm mt-1" style="width: 650px;">
                                <input type="text" id="tdcSearch" class="form-control float-right" placeholder="Search by TDC" style="width: 120px;">
                                <input type="text" id="customerSearch" class="form-control float-right ml-2" placeholder="Search by Customer" style="width: 150px;">
                                <input type="text" id="directorSearch" class="form-control float-right ml-2" placeholder="Search by Director" style="width: 150px;">
                                <input type="text" id="typeSearch" class="form-control float-right ml-2" placeholder="Search by Type" style="width: 120px;">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-default" id="searchBtn">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button type="button" class="btn btn-default ml-1" id="resetSearch">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <table id="salerelatedTable" class="table table-bordered table-striped" id="incentiveTable">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th width="10%" class="text-center">Voucher</th>
                                    <th width="20%" class="text-center">Project</th>
                                    <th width="10%" class="text-center">TDC</th>
                                    <th width="20%" class="text-center">Customer Name</th>
                                    <th width="10%" class="text-center">Type</th>
                                    <th width="20%" class="text-center">Director</th>
                                    <th width="10%" class="text-center">Incentive(%)</th>
                                    <th width="10%" class="text-center">Incentive Amount</th>
                                    <th width="10%" class="text-center">Incentive Approve</th>
                                    <th width="10%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sales_related_incentive as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->invoice_no ?? '' }}</td>
                                        <td class="text-center">{{ $item->customer->project->name ?? '' }}</td>
                                        <td class="text-center tdc-column">{{ $item->customer->customer_code ?? '' }}</td>
                                        <td class="text-center customer-column">{{ $item->customer->customer_name ?? '' }}</td>
                                        <td class="text-center type-column">{{ $item->type ?? '' }}</td>
                                        <td class="text-center director-column">{{ $item->director->employee_name ?? '' }}</td>
                                        <td class="text-center">{{ $item->directors_incentive ?? '' }}</td>
                                        <td class="text-center">{{ $item->directors_incentive_amount ?? 0 }}</td>
                                        <td class="text-center">
                                            @php
                                                $incentive = $item->salesIncentives()->latest()->first();
                                            @endphp

                                            @if($incentive && $incentive->status === 'pending')
                                                <form action="{{ route('approve.incentives', $item->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to approve this incentive?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill shadow-sm">
                                                        <i class="fas fa-check-circle me-1"></i> Approve
                                                    </button>
                                                </form>
                                            @elseif($incentive && $incentive->status === 'approved')
                                                <span class="badge bg-success text-white px-3 py-2 rounded-pill shadow-sm">
                                                    <i class="fas fa-check-double me-1"></i> Approved
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-details-modal-{{ $item->id }}"
                                                style="padding:2px; color:white" class="btn btn-xs btn-success  mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Sale Details View Modal -->
    @foreach ($sales_related_incentive as $item)
        <div class="modal fade view-details-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center d-flex justify-content-between align-items-center">
                        <h5>View Sales Incentive Details</h5>
                        <div>
                            <button type="button" class="btn btn-secondary" onclick="printPage({{ $item->id }}, '{{ Session::get('company_name') }}')">
                                <i class="fa fa-print"></i> Print
                            </button>
                            <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="modal-body" id="print_body_{{ $item->id }}">
                        <div class="row">

                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th colspan="10" style="text-align: center; color: green;">Sale Related Incentive Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="20%">Project: </th>
                                        <td style="color: green; font-weight: bold;">{{ $item->customer->project->name ?? '' }}</td>
                                        <th width="20%">Type: </th>
                                        <td>{{ $item->type ?? '' }}</td>
                                        <th width="20%">Customer Name: </th>
                                        <td>{{ $item->customer->customer_name ?? '' }}</td>
                                        <th width="20%">Code: </th>
                                        <td>{{ $item->customer->customer_code ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="20%">Voucher: </th>
                                        <td>{{ $item->invoice_no ?? '' }}</td>
                                        <th width="20%">Applicant's Date: </th>
                                        <td>{{ $item->application_date ?? '' }}</td>
                                        @php
                                            $installment = \App\Models\Installment::where('land_sale_id', $item->id)
                                                ->where(['company_id' => Session::get('company_id')])
                                                ->with('landSale')
                                                ->first();

                                            $paymentsMade = \App\Models\LandPayment::where(
                                                'land_sale_id',
                                                $installment->land_sale_id ?? 0,
                                            )
                                                ->where('payment_option', '!=', 'initial')
                                                ->count();

                                            $withoutInstallmentPaymentsMade = \App\Models\LandPayment::where(
                                                'land_sale_id',
                                                $item->id ?? 0,
                                            )
                                                ->where(['company_id' => Session::get('company_id')])
                                                ->where('payment_option', 'initial')
                                                ->sum('amount');

                                            $totalInstallmentAmountPaid = \App\Models\LandPayment::where(
                                                'land_sale_id',
                                                $installment->land_sale_id ?? 0,
                                            )->sum('amount');
                                        @endphp

                                        @if ($item->payment_option == 'notMade')
                                            <th width="20%">Monthly Installment: </th>
                                            <td>{{ $installment->monthly_installment ?? '' }}</td>
                                            <th width="20%">Total Amount Paid: </th>
                                            <td>{{ $totalInstallmentAmountPaid }} Tk.</td>
                                        @else
                                            <th width="20%">Total Amount Paid: </th>
                                            <td>{{ $withoutInstallmentPaymentsMade }} Tk.</td>
                                            <th width="20%">Remaining Amount: </th>
                                            <td>{{ $item->payment_option == 'initial' ? $item->remaining_amount ?? 0 : 0 }}</td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-sm table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th colspan="10" style="text-align: center; color: green;">
                                            {{ $item->type }} Details
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($item->type == 'Flat')
                                        <tr class="bg-light">
                                            <th>Flat No</th>
                                            <th>Floor</th>
                                            <th>Size</th>
                                        </tr>
                                        @foreach($item->flats as $flat)
                                            <tr>
                                                <td>{{ $flat->flat_no ?? 'N/A' }}</td>
                                                <td>{{ $flat->flat_floor->floor_no ?? 'N/A' }}</td>
                                                <td>{{ $flat->flat_size ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-light">
                                            <td colspan="2" class="text-right font-weight-bold">Total Price:</td>
                                            <td class="font-weight-bold text-success">{{ number_format($item->flat_total_price, 2) }} Tk.</td>
                                        </tr>
                                    @elseif($item->type == 'Plot')
                                        <tr class="bg-light">
                                            <th>Plot No</th>
                                            <th>Sector</th>
                                            <th>Road</th>
                                            <th>Size</th>
                                            <th>Rate Per Katha</th>
                                        </tr>
                                        @foreach($item->plots as $plot)
                                            <tr>
                                                <td>{{ $plot->plot_no ?? 'N/A' }}</td>
                                                <td>{{ $plot->road->sector->sector_name ?? 'N/A' }}</td>
                                                <td>{{ $plot->road->road_name ?? 'N/A' }}</td>
                                                <td>
                                                    {{ $plot->plotType ? $plot->plotType->plot_type . ' (' . $plot->plotType->percentage . ' শতাংশ)' : 'N/A' }}
                                                </td>
                                                <td>{{ number_format($item->rate_per_katha, 2) }} Tk.</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-light">
                                            <td colspan="4" class="text-right font-weight-bold">Total Price:</td>
                                            <td class="font-weight-bold text-success">{{ number_format($item->total_price, 2) }} Tk.</td>
                                        </tr>
                                    @elseif($item->type == 'Land')
                                        <tr class="bg-light">
                                            <th>Project</th>
                                            <th>Share Qty</th>
                                            <th>Percentage (শতাংশ)</th>
                                            <th>Size</th>
                                        </tr>
                                        @foreach($item->landshares as $landshare)
                                            <tr>
                                                <td>{{ $landshare->project->name ?? 'N/A' }}</td>
                                                <td>{{ $landshare->shareqty ?? 'N/A' }}</td>
                                                <td>{{ $landshare->sotangsho ?? 'N/A' }}</td>
                                                <td>{{ $landshare->size ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="bg-light">
                                            <td colspan="3" class="text-right font-weight-bold">Total Price:</td>
                                            <td class="font-weight-bold text-success">{{ number_format($item->land_total_price, 2) }} Tk.</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>

                                    @php
                                        $installment = \App\Models\Installment::where('land_sale_id', $item->id)
                                            ->where(['company_id' => Session::get('company_id')])
                                            ->first();

                                        $paymentsMade = $installment ? \App\Models\LandPayment::where('land_sale_id', $installment->land_sale_id ?? 0)
                                            ->where('payment_option', '!=', 'initial')
                                            ->count() : 0;

                                        $withoutInstallmentPaymentsMade = \App\Models\LandPayment::where('land_sale_id', $item->id ?? 0)
                                            ->where(['company_id' => Session::get('company_id')])
                                            ->where('payment_option', 'initial')
                                            ->sum('amount');

                                        $totalInstallmentAmountPaid = $installment ? \App\Models\LandPayment::where('land_sale_id', $installment->land_sale_id ?? 0)
                                            ->sum('amount') : 0;

                                        $totalPaid = $item->payment_option == 'notMade' ? $totalInstallmentAmountPaid : $withoutInstallmentPaymentsMade;
                                        $remainingAmount = $item->payment_option == 'notMade'
                                            ? $item->total_price - $totalInstallmentAmountPaid
                                            : ($item->payment_option == 'initial' ? $item->remaining_amount : 0);
                                    @endphp

                                    <div class="table-responsive">

                                        <!-- Payment Transactions Table -->
                                        <h6 class=""><i class="fas fa-list-alt mr-2 mt-2"></i> Payment Transactions</h6>
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Amount</th>
                                                    <th>Method</th>
                                                    <th>Fund</th>
                                                    <th>Bank</th>
                                                    <th>Account</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($item->land_payments as $key => $payment)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $payment->pay_date ?? 'N/A' }}</td>
                                                        <td class="font-weight-bold">{{ number_format($payment->amount, 2) }} Tk.</td>
                                                        <td>{{ $payment->payment_type->name ?? 'N/A' }}</td>
                                                        <td>{{ $payment->fund->name ?? 'N/A' }}</td>
                                                        <td>{{ $payment->bank->name ?? 'N/A' }}</td>
                                                        <td>{{ $payment->account->account_no ?? 'N/A' }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">No payment records found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>

                                        <!-- Payment Summary Table -->
                                        <table class="table table-sm table-bordered mb-4 mt-2">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Total Amount</th>
                                                    <th>Amount Paid</th>
                                                    <th>Remaining Amount</th>
                                                    @if($item->payment_option == 'notMade' && $installment)
                                                    <th>Installment Details</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    @if ($item->type == 'Flat')
                                                        <td class="font-weight-bold">{{ number_format($item->flat_total_price, 2) }} Tk.</td>
                                                    @elseif($item->type == 'Land')
                                                        <td class="font-weight-bold">{{ number_format($item->land_total_price, 2) }} Tk.</td>
                                                    @else
                                                        <td class="font-weight-bold">{{ number_format($item->total_price, 2) }} Tk.</td>
                                                    @endif
                                                    <td class="text-success font-weight-bold">{{ number_format($totalPaid, 2) }} Tk.</td>
                                                    <td class="text-danger font-weight-bold">{{ number_format($remainingAmount, 2) }} Tk.</td>
                                                    @if($item->payment_option == 'notMade' && $installment)
                                                        <td>
                                                            <strong>Installments:</strong> {{ $paymentsMade }}/{{ $installment->total_installment_number }}<br>
                                                            <strong>Monthly:</strong> {{ number_format($installment->monthly_installment, 2) }} Tk.
                                                        </td>
                                                    @endif
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                            <hr style="border: 1px solid #000;">

                            {{-- @if ($item->incentive)
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="15" style="text-align: center; color: green;">Incentive Details</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="3">Payment Reference</th>
                                            <th class="text-center" colspan="3">Director</th>
                                            <th class="text-center" colspan="3">Co-Ordinator</th>
                                            <th class="text-center" colspan="3">Shareholder</th>
                                            <th class="text-center" colspan="3">Outsider</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Payment Ref</th>
                                            <th class="text-center">Payment Date</th>
                                            <th class="text-center">Amount</th>

                                            <th class="text-center">Director</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                            <th class="text-center">Co-Ordinator</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                            <th class="text-center">Shareholder</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                            <th class="text-center">Outsider</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Initialize totals
                                            $directorTotal = 0;
                                            $coordinatorTotals = [];
                                            $shareholderTotals = [];
                                            $outsiderTotals = [];
                                        @endphp

                                        @foreach ($item->incentive as $incentive)
                                            @php
                                                // Calculate director amount
                                                $directorAmount = $incentive->directors_incentive_amount ?? $incentive->director_per_sales_incentive ?? 0;
                                                $directorTotal += $directorAmount;

                                                // Calculate coordinator amounts by name
                                                $coordinatorName = $incentive->co_ordinator->employee_name ?? '';
                                                $coordinatorAmount = $incentive->coordinators_incentive_amount ?? $incentive->coordinator_per_sales_incentive ?? 0;
                                                if ($coordinatorName) {
                                                    $coordinatorTotals[$coordinatorName] = ($coordinatorTotals[$coordinatorName] ?? 0) + $coordinatorAmount;
                                                }

                                                // Calculate shareholder amounts by name
                                                $shareholderName = $incentive->shareholder->employee_name ?? '';
                                                $shareholderAmount = $incentive->shareholders_incentive_amount ?? $incentive->shareholder_per_sales_incentive ?? 0;
                                                if ($shareholderName) {
                                                    $shareholderTotals[$shareholderName] = ($shareholderTotals[$shareholderName] ?? 0) + $shareholderAmount;
                                                }

                                                // Calculate outsider amounts by name
                                                $outsiderName = $incentive->outsider->employee_name ?? '';
                                                $outsiderAmount = $incentive->outsiders_incentive_amount ?? $incentive->outsider_per_sales_incentive ?? 0;
                                                if ($outsiderName) {
                                                    $outsiderTotals[$outsiderName] = ($outsiderTotals[$outsiderName] ?? 0) + $outsiderAmount;
                                                }
                                            @endphp

                                            <tr>
                                                <td class="text-center">Payment #{{ $loop->iteration }}</td>
                                                <td class="text-center">{{ $payment->pay_date ?? 'N/A' }}</td>
                                                <td class="font-weight-bold text-center">{{ number_format($payment->amount ?? 0, 2) }} Tk.</td>

                                                <td class="text-center">{{ $incentive->director->employee_name ?? '' }}</td>
                                                <td class="text-center">{{ $incentive->directors_incentive ?? '' }}</td>
                                                <td class="text-center">
                                                    @if($incentive->directors_incentive_amount)
                                                        {{ $incentive->directors_incentive_amount }}
                                                    @else
                                                        {{ $incentive->director_per_sales_incentive ?? '' }}
                                                    @endif
                                                </td>

                                                <td class="text-center">{{ $incentive->co_ordinator->employee_name ?? '' }}</td>
                                                <td class="text-center">{{ $incentive->coordinators_incentive ?? '' }}</td>
                                                <td class="text-center">
                                                    @if($incentive->coordinators_incentive_amount)
                                                        {{ $incentive->coordinators_incentive_amount }}
                                                    @else
                                                        {{ $incentive->coordinator_per_sales_incentive ?? '' }}
                                                    @endif
                                                </td>

                                                <td class="text-center">{{ $incentive->shareholder->employee_name ?? '' }}</td>
                                                <td class="text-center">{{ $incentive->shareholders_incentive ?? '' }}</td>
                                                <td class="text-center">
                                                    @if($incentive->shareholders_incentive_amount)
                                                        {{ $incentive->shareholders_incentive_amount }}
                                                    @else
                                                        {{ $incentive->shareholder_per_sales_incentive ?? '' }}
                                                    @endif
                                                </td>

                                                <td class="text-center">{{ $incentive->outsider->employee_name ?? '' }}</td>
                                                <td class="text-center">{{ $incentive->outsiders_incentive ?? '' }}</td>
                                                <td class="text-center">
                                                    @if($incentive->outsiders_incentive_amount)
                                                        {{ $incentive->outsiders_incentive_amount }}
                                                    @else
                                                        {{ $incentive->outsider_per_sales_incentive ?? '' }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <!-- Total Incentives Row -->
                                        <tr class="incentive-totals-row">
                                            <!-- Director Total -->
                                            <td colspan="3" class="bg-light-blue">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-primary">Director Total</span>
                                                    <span class="h5 mb-0">{{ number_format($directorTotal, 2) }} <small>Tk.</small></span>
                                                </div>
                                            </td>

                                            <!-- Coordinators Total -->
                                            <td colspan="3" class="bg-light-green">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-success">Coordinators Total</span>
                                                    <div class="d-flex flex-wrap justify-content-center">
                                                        @foreach($coordinatorTotals as $name => $total)
                                                        <div class="mx-2 my-1">
                                                            <div class="text-muted small">{{ $name }}</div>
                                                            <div class="font-weight-bold">{{ number_format($total, 2) }} <small>Tk.</small></div>
                                                        </div>
                                                        @endforeach
                                                        @if(count($coordinatorTotals) === 0)
                                                        <div class="text-muted small">N/A</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Shareholders Total -->
                                            <td colspan="3" class="bg-light-yellow">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-warning">Shareholders Total</span>
                                                    <div class="d-flex flex-wrap justify-content-center">
                                                        @foreach($shareholderTotals as $name => $total)
                                                        <div class="mx-2 my-1">
                                                            <div class="text-muted small">{{ $name }}</div>
                                                            <div class="font-weight-bold">{{ number_format($total, 2) }} <small>Tk.</small></div>
                                                        </div>
                                                        @endforeach
                                                        @if(count($shareholderTotals) === 0)
                                                        <div class="text-muted small">N/A</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Outsiders Total -->
                                            <td colspan="3" class="bg-light-pink">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-danger">Outsiders Total</span>
                                                    <div class="d-flex flex-wrap justify-content-center">
                                                        @foreach($outsiderTotals as $name => $total)
                                                        <div class="mx-2 my-1">
                                                            <div class="text-muted small">{{ $name }}</div>
                                                            <div class="font-weight-bold">{{ number_format($total, 2) }} <small>Tk.</small></div>
                                                        </div>
                                                        @endforeach
                                                        @if(count($outsiderTotals) === 0)
                                                        <div class="text-muted small">N/A</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            @endif --}}


                            @if ($item->incentive)
                                @php
                                    // Group incentives by payment_id
                                    $groupedIncentives = $item->incentive->groupBy('payment_id');

                                    // Get the first payment
                                    $firstPayment = $item->land_payments->sortBy('pay_date')->first();


                                    // Initialize totals
                                    $directorTotal = 0;
                                    $coordinatorTotals = [];
                                    $shareholderTotals = [];
                                    $outsiderTotals = [];
                                @endphp

                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th colspan="15" style="text-align: center; color: green;">Incentive Details</th>
                                        </tr>
                                    </thead>
                                    <thead>
                                        <tr>
                                            <th class="text-center" colspan="3">Payment Reference</th>
                                            <th class="text-center" colspan="3">Director</th>
                                            <th class="text-center" colspan="3">Co-Ordinator</th>
                                            <th class="text-center" colspan="3">Shareholder</th>
                                            <th class="text-center" colspan="3">Outsider</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">Payment Ref</th>
                                            <th class="text-center">Payment Date</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Director</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                            <th class="text-center">Co-Ordinator</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                            <th class="text-center">Shareholder</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                            <th class="text-center">Outsider</th>
                                            <th class="text-center">Incentive (%)</th>
                                            <th class="text-center">Incentive Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groupedIncentives as $paymentId => $incentives)
                                            @php
                                                $payment = $item->land_payments->where('id', $paymentId)->first();
                                            @endphp


                                            @foreach($incentives as $incentive)
                                                @php
                                                    // Calculate amounts for totals
                                                    $directorAmount = $incentive->directors_incentive_amount ?? $incentive->director_per_sales_incentive ?? 0;
                                                    $directorTotal += $directorAmount;

                                                    $coordinatorName = $incentive->co_ordinator->employee_name ?? '';
                                                    $coordinatorAmount = $incentive->coordinators_incentive_amount ?? $incentive->coordinator_per_sales_incentive ?? 0;
                                                    if ($coordinatorName) {
                                                        $coordinatorTotals[$coordinatorName] = ($coordinatorTotals[$coordinatorName] ?? 0) + $coordinatorAmount;
                                                    }

                                                    $shareholderName = $incentive->shareholder->employee_name ?? '';
                                                    $shareholderAmount = $incentive->shareholders_incentive_amount ?? $incentive->shareholder_per_sales_incentive ?? 0;
                                                    if ($shareholderName) {
                                                        $shareholderTotals[$shareholderName] = ($shareholderTotals[$shareholderName] ?? 0) + $shareholderAmount;
                                                    }

                                                    $outsiderName = $incentive->outsider->employee_name ?? '';
                                                    $outsiderAmount = $incentive->outsiders_incentive_amount ?? $incentive->outsider_per_sales_incentive ?? 0;
                                                    if ($outsiderName) {
                                                        $outsiderTotals[$outsiderName] = ($outsiderTotals[$outsiderName] ?? 0) + $outsiderAmount;
                                                    }
                                                @endphp

                                                <tr>
                                                    <td class="text-center">Payment #{{ $loop->parent->iteration }}</td>
                                                    <td class="text-center">{{ $payment->pay_date ?? 'N/A' }}</td>
                                                    <td class="font-weight-bold text-center">{{ number_format($payment->amount ?? 0, 2) }} Tk.</td>

                                                    <td class="text-center">{{ $incentive->director->employee_name ?? '' }}</td>
                                                    <td class="text-center">{{ $incentive->directors_incentive ?? '' }}</td>
                                                    <td class="text-center">{{ number_format($directorAmount, 2) }} Tk.</td>
                                                    <td class="text-center">{{ $incentive->co_ordinator->employee_name ?? '' }}</td>
                                                    <td class="text-center">{{ $incentive->coordinators_incentive ?? '' }}</td>
                                                    <td class="text-center">{{ number_format($coordinatorAmount, 2) }} Tk.</td>
                                                    <td class="text-center">{{ $incentive->shareholder->employee_name ?? '' }}</td>
                                                    <td class="text-center">{{ $incentive->shareholders_incentive ?? '' }}</td>
                                                    <td class="text-center">{{ number_format($shareholderAmount, 2) }} Tk.</td>
                                                    <td class="text-center">{{ $incentive->outsider->employee_name ?? '' }}</td>
                                                    <td class="text-center">{{ $incentive->outsiders_incentive ?? '' }}</td>
                                                    <td class="text-center">{{ number_format($outsiderAmount, 2) }} Tk.</td>
                                                </tr>
                                            @endforeach
                                        @endforeach

                                        <!-- Total Incentives Row -->
                                        <tr class="incentive-totals-row">
                                            <td colspan="3" class="bg-light-blue">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-pink">Amount Paid</span>
                                                    <span class="h5 mb-0">{{ number_format($totalPaid, 2) }} <small>Tk.</small></span>
                                                </div>
                                            </td>
                                            <td colspan="3" class="bg-light-blue">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-primary">Director Total</span>
                                                    <span class="h5 mb-0">{{ number_format($directorTotal, 2) }} <small>Tk.</small></span>
                                                </div>
                                            </td>
                                            <td colspan="3" class="bg-light-green">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-success">Coordinators Total</span>
                                                    <div class="d-flex flex-wrap justify-content-center">
                                                        @foreach($coordinatorTotals as $name => $total)
                                                        <div class="mx-2 my-1">
                                                            <div class="text-muted small">{{ $name }}</div>
                                                            <div class="font-weight-bold">{{ number_format($total, 2) }} <small>Tk.</small></div>
                                                        </div>
                                                        @endforeach
                                                        @if(count($coordinatorTotals) === 0)
                                                        <div class="text-muted small">N/A</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td colspan="3" class="bg-light-yellow">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-warning">Shareholders Total</span>
                                                    <div class="d-flex flex-wrap justify-content-center">
                                                        @foreach($shareholderTotals as $name => $total)
                                                        <div class="mx-2 my-1">
                                                            <div class="text-muted small">{{ $name }}</div>
                                                            <div class="font-weight-bold">{{ number_format($total, 2) }} <small>Tk.</small></div>
                                                        </div>
                                                        @endforeach
                                                        @if(count($shareholderTotals) === 0)
                                                        <div class="text-muted small">N/A</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td colspan="3" class="bg-light-pink">
                                                <div class="d-flex flex-column text-center p-2">
                                                    <span class="font-weight-bold text-danger">Outsiders Total</span>
                                                    <div class="d-flex flex-wrap justify-content-center">
                                                        @foreach($outsiderTotals as $name => $total)
                                                        <div class="mx-2 my-1">
                                                            <div class="text-muted small">{{ $name }}</div>
                                                            <div class="font-weight-bold">{{ number_format($total, 2) }} <small>Tk.</small></div>
                                                        </div>
                                                        @endforeach
                                                        @if(count($outsiderTotals) === 0)
                                                        <div class="text-muted small">N/A</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endif

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="printPage({{ $item->id }}, '{{ Session::get('company_name') }}')">Print</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
@push('script_js')

    <!-- Add jQuery script for search functionality -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchBtn').click(function() {
                filterTable();
            });

            // Reset search functionality
            $('#resetSearch').click(function() {
                $('#tdcSearch').val('');
                $('#customerSearch').val('');
                $('#directorSearch').val('');
                $('#typeSearch').val('');
                filterTable();
            });

            // Function to filter table rows
            function filterTable() {
                var tdcValue = $('#tdcSearch').val().toLowerCase();
                var customerValue = $('#customerSearch').val().toLowerCase();
                var directorValue = $('#directorSearch').val().toLowerCase();
                var typeValue = $('#typeSearch').val().toLowerCase();

                $('#salerelatedTable tbody tr').each(function() {
                    var tdcText = $(this).find('.tdc-column').text().toLowerCase();
                    var customerText = $(this).find('.customer-column').text().toLowerCase();
                    var directorText = $(this).find('.director-column').text().toLowerCase();
                    var typeText = $(this).find('.type-column').text().toLowerCase();

                    var tdcMatch = tdcText.includes(tdcValue);
                    var customerMatch = customerText.includes(customerValue);
                    var directorMatch = directorText.includes(directorValue);
                    var typeMatch = typeText.includes(typeValue);

                    if (tdcMatch && customerMatch && directorMatch && typeMatch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }

            // Trigger search when pressing Enter in any search field
            $('#tdcSearch, #customerSearch, #directorSearch, #typeSearch').keypress(function(e) {
                if (e.which === 13) {
                    filterTable();
                }
            });
        });
    </script>

    <script>
        function printTable() {
            window.print();
        }

        function printTotalSheet() {
            var printContents = document.getElementById('printableTotalSheet').innerHTML;
            var originalContents = document.body.innerHTML;

            // Create a new window for printing
            var printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Top Sheet Report</title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            table { width: 100%; border-collapse: collapse; }
                            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                            th { background-color: #f2f2f2; }
                            .text-right { text-align: right; }
                            .text-center { text-align: center; }
                            .report-header { margin-bottom: 20px; }
                            .company-name { color: #2c3e50; font-size: 28px; }
                            .report-title { color: #3498db; border-bottom: 2px solid #3498db; }
                        </style>
                    </head>
                    <body>
                        ${printContents}
                        <script>
                            window.onload = function() {
                                window.print();
                                window.close();
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }
    </script>

    <script>
        $(document).ready(function() {
            $('.bank').hide();
        });

        function showType(id) {
            const type = $('#type-' + id).val();
            const directorData = $('#type-' + id).data('director');
            const salesExecutivesData = $('#type-' + id).data('sales-executives');

            updateSalesIncentiveOptions(id, type, directorData, salesExecutivesData);
        }


        function updateSalesIncentiveOptions(id, type, directorData, salesExecutivesData) {
            const incentiveSelect = $('#sales_incentive_id-' + id);
            incentiveSelect.empty().append('<option value="">--Select One--</option>');

            if (type === 'director') {
                if (directorData) {
                    console.log(directorData);
                    incentiveSelect.append(
                        `<option value="${directorData.id}">${directorData.employee_name} - ${directorData.designation}</option>`
                    );
                }
            } else if (type === 'sales_executive') {
                salesExecutivesData.forEach(function(executive) {
                    incentiveSelect.append(
                        `<option value="${executive.employee_name}">${executive.employee_name} - ${executive.designation}</option>`
                    );
                });
            }
        }



        function showBankInfo(id) {
            const fundId = $('#fund-' + id).val();
            console.log(fundId);
            if (fundId == 1) {
                $('.bank-' + id).show();
                $('#bank_id-' + id).prop('required', true);
                $('#account_id-' + id).prop('required', true);
            } else {
                $('.bank-' + id).hide();
                $('#bank_id-' + id).prop('required', false);
                $('#account_id-' + id).prop('required', false);
            }
        }

        function filterAccount(id) {
            const bankId = $('#bank_id-' + id).val();
            const url = "{{ route('filter-bank-fund') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id: bankId
                },
                success: function(data) {
                    $('#account_id-' + id).html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account_id-' + id).append(
                            `<option value="${value.id}">${value.account_no}</option>`);
                    });
                },
            });
        }

        function showAccountBranch(id) {
            const accountId = $('#account_id-' + id).val();
            const url = "{{ route('getAccountBranch') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    account_id: accountId
                },
                dataType: "json",
                success: function(response) {
                    if (response) {
                        $('#branch-' + id).val(response.branch);
                    } else {
                        alert('No data found for the selected account.');
                    }
                },
                error: function() {
                    alert('An error occurred while fetching the account data.');
                }
            });
        }
    </script>
@endpush
