@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Rental Bill List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">
                        <form action="{{ route('rental_bill_list') }}" method="get">
                            <div class="row pb-3">

                                <div class="col-lg-2">
                                    <label for="property_id">Property</label>
                                    <select class="form-control" name="property_id">
                                        <option value="">Select property</option>
                                        @foreach ($properties as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="unit_id">Unit</label>
                                    <select class="form-control" name="unit_id">
                                        <option value="">Select unit</option>
                                        @foreach ($units as $item)
                                            <option value="{{ $item->id }}">{{ $item->unit_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="floor_id">Floor</label>
                                    <select class="form-control" name="floor_id">
                                        <option value="">Select floor</option>
                                        @foreach ($floors as $item)
                                            <option value="{{ $item->id }}">{{ $item->floor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="renter_id">Renter</label>
                                    <select class="form-control" name="renter_id">
                                        <option value="">Select renter</option>
                                        @foreach ($renters as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="type">Bill type</label>
                                    <select class="form-control" name="type">
                                        <option value="">Select Bill Type</option>
                                        <option value="Monthly Rent">Monthly Rent</option>
                                        <option value="Electricity Bill">Electricity Bill</option>
                                    </select>
                                </div>
                                <div class="col-lg-3">
                                    <label for="status">Payment Status</label>
                                    <select class="form-control" name="status">
                                        <option value="">Select Payment Status</option>
                                        <option value="1">Unpaid</option>
                                        <option value="2">Paid</option>
                                        <option value="3">Partial Paid</option>
                                    </select>
                                </div>
                                <div class="col-lg-2">
                                    <label for="start_date">From</label>
                                    <input type="date" class="form-control" name="start_date" />
                                </div>
                                <div class="col-lg-2">
                                    <label for="end_date">To</label>
                                    <input type="date" class="form-control" name="end_date" />
                                </div>

                                <div class="col-lg-2">
                                    <label for="action">Action</label> <br />
                                    <button class="btn btn-success btn-block">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="{{ url('rental-print?property_id=' . request()->get('property_id') . '&unit_id=' . request()->get('unit_id') . '&floor_id=' . request()->get('floor_id') . '&renter_id=' . request()->get('renter_id') . '&type=' . request()->get('type') . '&status=' . request()->get('status') . '&start_date=' . request()->get('start_date') . '&end_date=' . request()->get('end_date')) }}"
                                    target="_blank" class="btn btn-warning float-end m-2">
                                    <i class="fa fa-print" aria-hidden="true"></i> Print
                                </a>
                            </div>
                        </div>

                        <table class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Bill Type</th>
                                    <th class="text-center">Property</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Renter</th>
                                    <th class="text-center">Due Amount</th>
                                    <th class="text-center">Total Amount</th>
                                    <th class="text-center">Payment Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill_data as $item)
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($item->due_date);
                                        $today = \Carbon\Carbon::today();
                                        $isOverdue = $dueDate->lessThan($today);
                                        // dd($isOverdue);
                                    @endphp
                        
                                    <tr @if ($isOverdue && $item->status != 2) style="background-color: #f8d7da;" @endif>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $item->type }}</td>
                                        <td class="text-center">{{ $item->unit->property->name ?? '' }}</td>
                                        <td class="text-center">{{ $item->unit->unit_name ?? '' }}, {{ $item->unit->floor->floor_name ?? '' }}</td>
                                        <td class="text-center">{{ $item->renter->name ?? '-' }}</td>
                                        <td class="text-center">{{ $item->due_amount ?? '-' }} Tk</td>
                                        <td class="text-center">{{ $item->total_amount ?? '-' }} Tk</td>
                                        <td class="text-center">
                                            @if ($item->status == 1)
                                                <span style="background-color: #ea5455; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Unpaid</span>
                                            @elseif ($item->status == 2)
                                                <span style="background-color: #28c76f; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Paid</span>
                                            @else
                                                <span style="background-color: #ffc107; border-radius: 3px; padding: 3px; color: white; font-weight: bold;">Partial Paid</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-modal-{{ $item->id }}" style="padding:2px; color:white" class="btn btn-xs btn-success mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('print_rental_invoice', $item->id) }}" style="padding:2px; color:white" class="btn btn-xs btn-primary mr-1" target="_blank">
                                                <i class="fa fa-print"></i>
                                            </a>
                                            <a data-toggle="modal" data-target=".payment-details-modal-{{ $item->id }}" style="padding:2px; color:rgb(255, 255, 255)" class="btn btn-xs btn-dark mr-1">
                                                <i class="fa fa-credit-card"></i> <i class="fa fa-info"></i>
                                            </a>
                                            @if ($item->status == 1 || $item->status == 3)
                                                <a data-toggle="modal" data-target=".payment-modal-{{ $item->id }}" style="padding:2px; color:rgb(255, 255, 255)" class="btn btn-xs btn-info mr-1">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        

                        <div class="row pt-3">
                            <div class="col-lg-12">
                                {{ $bill_data->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Payment modal start --}}
    @foreach ($bill_data as $item)
        <div class="modal fade payment-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5>Make Payment</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="container mt-2">
                            <div class="col-lg-12">
                                <div class="mb-2">
                                    <label class="form-label">Bill Type: <span
                                            style="color: red;">{{ $item->type ?? '' }}</span></label>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Renter:
                                        {{ $item->renter->name ?? '' }}
                                        ({{ $item->unit->unit_name ?? '' }}, {{ $item->unit->floor->floor_name ?? '' }},
                                        {{ $item->unit->property->name ?? '' }})
                                    </label>
                                </div>
                                @php
                                    $payments = App\Models\RentalPayment::where('bill_id', $item->id)->get();
                                    $total_paid = $payments->sum('amount');
                                    $payable_amount = $item->total_amount - $total_paid;
                                @endphp
                                <div class="mb-2">
                                    <label class="form-label">Total Amount: <span
                                            style="color: red;">{{ $item->total_amount ?? '' }} Tk.</span></label>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Payable Amount: <span
                                            style="color: red;">{{ $payable_amount ?? '' }} Tk.</span></label>
                                </div>

                                {{-- <div class="mb-2">
                                    <label class="form-label">Due Amount: <span style="color: red;">{{ $item->due_amount ?? '' }} Tk.</span></label>
                                </div> --}}
                            </div>
                            <hr>
                            <form action="{{ route('save_bill_payment') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="bill_id" value="{{ $item->id }}">
                                    <input type="hidden" name="renter_id" value="{{ $item->renter_id }}">
                                    <input type="hidden" name="unit_id" value="{{ $item->unit_id }}">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Month<i class="text-danger">*</i></label>
                                            <input type="month" class="form-control" name="month"
                                                value="{{ $item->month }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Pay Amount<i class="text-danger">*</i></label>
                                            <input type="number" step="0.01" class="form-control" name="amount"
                                                placeholder="Pay Amount" required max="{{ $item->due_amount }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Payment Date<i class="text-danger">*</i></label>
                                            <input type="date" class="form-control" name="date" required>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success  text-center col-md-12 mb-3"><i
                                        class="fa fa-check"></i>
                                    Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    {{-- Payment modal end --}}


    {{-- Payment Details modal start --}}
    @foreach ($bill_data as $item)
        <div class="modal fade payment-details-modal-{{ $item->id }}" id="exampleModal" tabindex="-1"
            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center">
                        <h5> Payment Details</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div id="print_body">
                            <style>
                                @media print {
                                    @page {
                                        margin: 20px;
                                    }
                                    body {
                                        margin: 20px;
                                        padding: 20px;
                                    }
                                    header,
                                    footer,
                                    #print_btn {
                                        display: none !important;
                                    }
                                    .table {
                                        border-collapse: collapse;
                                        width: 100%;
                                    }
                                    .table th,
                                    .table td {
                                        border: 1px solid #000;
                                        padding: 8px;
                                        text-align: center;
                                    }
                                }
                            </style>
                            <div class="container mt-2">
                                <div class="col-lg-12">
                                    <div class="mb-2">
                                        <label class="form-label">Bill Type: <span style="color: red;">{{ $item->type ?? '' }}</span></label>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Renter: {{ $item->renter->name ?? '' }} ({{ $item->unit->unit_name ?? '' }},
                                            {{ $item->unit->floor->floor_name ?? '' }}, {{ $item->unit->property->name ?? '' }})
                                        </label>
                                    </div>
                                    @if ($item->type == 'Electricity Bill')
                                        <div class="mb-2">
                                            <label class="form-label">Meter No: <span style="color: red;">{{ $item->unit->meter->meter_number ?? '' }}</span></label>
                                        </div>
                                    @endif
                                    <div class="mb-2">
                                        <label class="form-label">Month: {{ \Carbon\Carbon::parse($item->month)->format('F Y') }}</label>
                                    </div>
                                    @if($item->advance_deduction)
                                    <div class="mb-2">
                                        <label class="form-label" style="color: #c41212">Deducted From Advance Amount!</label>
                                    </div>
                                    @endif
                                    
                                </div>
                                <hr>
                                <div class="row">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Payment Date</th>
                                                <th class="text-center">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $payment_details = App\Models\RentalPayment::where('bill_id', $item->id)->get();
                                                $total_amount = $payment_details->sum('amount');
                                            @endphp
                                            @foreach ($payment_details as $p)
                                                <tr>
                                                    <td class="text-center">{{ \Carbon\Carbon::parse($p->date)->format('d F Y') }}</td>
                                                    <td class="text-center">{{ $p->amount }} Tk.</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-center"><strong>Total</strong></td>
                                                <td class="text-center"><strong>{{ $total_amount }} Tk.</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <br>
                            <div align="right">
                                <button id="print_btn"
                                    style="height:30px; border-radius:10px; background-color:rgb(0, 0, 0); color:#fff; margin-bottom:10px;"
                                    onclick="printShow()">Print</button>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        function printShow() {
                            document.getElementById('print_btn').style.display = 'none';
                    
                            // Get today's date
                            var today = new Date();
                            var options = { year: 'numeric', month: 'long', day: 'numeric' };
                            var formattedDate = today.toLocaleDateString('en-US', options);
                    
                            // Get the print contents and include today's date
                            var printContents = document.getElementById('print_body').innerHTML;
                            var dateHtml = '<div style="text-align: right; margin-bottom: 20px;"><strong>Date: ' + formattedDate + '</strong></div>';
                            var printWindow = window.open('', '_blank');
                    
                            printWindow.document.write('<html><head><title>Print</title>');
                            printWindow.document.write('<style>@media print { @page { margin: 20px; } body { margin: 20px; padding: 20px; } header, footer, #print_btn { display: none !important; } .table { border-collapse: collapse; width: 100%; } .table th, .table td { border: 1px solid #000; padding: 8px; text-align: center; } }</style>');
                            printWindow.document.write('</head><body>');
                            
                            // Include the date in the print contents
                            printWindow.document.write(dateHtml);
                            printWindow.document.write(printContents);
                            printWindow.document.write('</body></html>');
                    
                            printWindow.document.close();
                            printWindow.print();
                    
                            printWindow.onafterprint = function () {
                                printWindow.close();
                            };
                    
                            document.getElementById('print_btn').style.display = 'block';
                        }
                    </script>                
                    
                </div>
            </div>
        </div>
    @endforeach
    {{-- Payment Details modal end --}}


    {{-- View modal start --}}
    @foreach ($bill_data as $item)
        <div class="modal fade view-modal-{{ $item->id }}" id="exampleModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content custom-modal">
                    <div class="d-flex justify-content-end">
                        <h5 class="modal-title" id="exampleModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span class="fs-2 mr-3 mt-3" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container mt-5">
                            <div class="row">
                                <!-- Logo and Company Info -->
                                <div class="col-sm-5">
                                    <img src="{{ asset('upload_images/company_logo/' . $item->company->logo) }}"
                                        alt="Company Logo" height="auto" width="300px">
                                </div>
                                <div class="col-sm-7 text-end">
                                    <h1>{{ $item->company->name }}</h1>
                                    <p>
                                        {{ $item->company->phone }}<br>
                                        {{ $item->company->email }}<br>
                                        {{ $item->company->address }}
                                    </p>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <!-- Invoice To Information -->
                                <div class="col-sm-6">
                                    <h5>INVOICE TO:</h5>
                                    <p><strong>{{ $item->renter->name }}</strong></p>
                                    <p>{{ $item->renter->phone }}<br>{{ $item->renter->present_address }}</p>
                                </div>
                                <!-- Invoice Details -->
                                <div class="col-sm-6 text-end">
                                    <p>Status:
                                        <span
                                            class="{{ $item->status == '2' ? 'badge badge-success' : ($item->status == '1' ? 'badge badge-danger' : ($item->status == '3' ? 'badge badge-warning' : 'badge badge-warning')) }}">
                                            {{ $item->status == '2' ? 'Paid' : ($item->status == '1' ? 'Unpaid' : ($item->status == '3' ? 'Partial Paid' : 'Unknown')) }}
                                        </span>
                                    </p>
                                    <p>Invoice No: #{{ $item->invoice_no }}</p>
                                    <p>Invoice Month: {{ \Carbon\Carbon::parse($item->month)->format('F Y') }}</p>
                                    {{-- <p>End Date: {{ \Carbon\Carbon::parse($item->end_date)->format('M d, Y') }}</p> --}}
                                </div>
                            </div>
                            <!-- Table for invoice items -->
                            <div class="row mt-4">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Type</th>
                                            @if ($item->type == 'Electricity Bill')
                                                <th>Meter No. </th>
                                            @endif
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($item->type == 'Monthly Rent')
                                            <tr>
                                                <td>Monthly Rent</td>
                                                {{-- <td>{{ $item->note }}</td> --}}
                                                <td>{{ number_format($item->unit->rent_amount, 2) }} Tk</td>
                                            </tr>
                                            <tr>
                                                <td>Gas Bill</td>
                                                {{-- <td>{{ $item->note }}</td> --}}
                                                <td>{{ number_format($item->unit->gas_bill, 2) }} Tk</td>
                                            </tr>
                                            <tr>
                                                <td>Water Bill</td>
                                                {{-- <td>{{ $item->note }}</td> --}}
                                                <td>{{ number_format($item->unit->water_bill, 2) }} Tk</td>
                                            </tr>
                                            <tr>
                                                <td>Trash Bill</td>
                                                {{-- <td>{{ $item->note }}</td> --}}
                                                <td>{{ number_format($item->unit->trash_bill, 2) }} Tk</td>
                                            </tr>
                                            <tr>
                                                <td>Security Bill</td>
                                                {{-- <td>{{ $item->note }}</td> --}}
                                                <td>{{ number_format($item->unit->security_bill, 2) }} Tk</td>
                                            </tr>
                                            <tr>
                                                <td>Service Charge</td>
                                                {{-- <td>{{ $item->note }}</td> --}}
                                                <td>{{ number_format($item->unit->service_charge, 2) }} Tk</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>Electricity Bill</td>
                                                <td>{{ $item->unit->meter->meter_number }}</td>
                                                <td>{{ number_format($item->total_amount, 2) }} Tk</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- Total and Due Amount -->
                            <div class="row mt-4">
                                <div class="col-sm-6">
                                    <h5>Total: {{ number_format($item->total_amount, 2) }} Tk</h5>
                                </div>
                                <div class="col-sm-6 text-end">
                                    <h5>Due Amount: {{ number_format($item->due_amount, 2) }} Tk</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{-- View modal end --}}
@endsection
