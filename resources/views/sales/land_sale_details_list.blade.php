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

        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }
</script>

@section('content')
    <!-- Session Message Modal বা Alert -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border border-success border-2 rounded-3 px-4 py-3 mt-3"
            role="alert" style="background: #03ad33;">
            <div class="d-flex align-items-center">
                <i class="bi bi-check-circle-fill me-2 fs-4 text-success"></i>
                <div class="flex-grow-1">
                    <strong>সফল!</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif


    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- @if ($landSaleCount > 0)
                <h5 class="bg-warning p-3 text-center"> <strong>Need to pay {{ $landSaleCount }} installment in this
                        week!</strong> </h5>
            @endif --}}
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            Land Sale Details List
                        </h3>
                    </div> <!-- /.card-body -->
                    <div class="card-body p-3">

                        {{-- search / Filter options --}}

                        <table id="landSaleTable" class="table table-bordered table-striped">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    {{-- <th class="text-center">Application Date</th> --}}
                                    <th width="10%" class="text-center">Voucher No</th>
                                    <th width="10%" class="text-center">Project</th>
                                    <th width="8%" class="text-center">Type</th>
                                    {{-- <th width="10%" class="text-center">Plot No</th>
                                <th width="10%" class="text-center">Flat No</th> --}}
                                    <th width="10%" class="text-center">Customer's Name</th>
                                    <th width="8%" class="text-center">TDC</th>
                                    <th width="11%" class="text-center">Installment Amount</th>
                                    <th width="11%" class="text-center">Due Amount (Full Payment)</th>
                                    <th width="5%" class="text-center">Installment Number</th>
                                    <th width="11%" class="text-center">Total Paid</th>
                                    {{-- <th class="text-center">Status</th> --}}
                                    {{-- <th>Change Status</th> --}}
                                    <th width="25%" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @dd($land_sale_list) --}}
                                @foreach ($land_sale_list as $item)
                                    {{-- @dd($item) --}}
                                    <tr>
                                        @php
                                            $installment = \App\Models\Installment::where('land_sale_id', $item->id)
                                                ->where(['company_id' => Session::get('company_id')])
                                                ->with('landSale')
                                                ->first();
                                            // dd($installment);

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
                                            // dd($withoutInstallmentPaymentsMade);
                                            $totalInstallmentAmountPaid = \App\Models\LandPayment::where(
                                                'land_sale_id',
                                                $installment->land_sale_id ?? 0,
                                            )->sum('amount');

                                            $remainingInstallments =
                                                ($installment->total_installment_number ?? 0) - $paymentsMade;
                                        @endphp
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        {{-- <td class="text-center">{{ $item->application_date ?? '' }}</td> --}}
                                        <td class="text-center">{{ $item->invoice_no ?? '' }}</td>
                                        <td class="text-center">{{ $item->customer->project->name ?? '' }}</td>
                                        <td class="text-center">{{ $item->type ?? '' }}</td>
                                        {{-- <td class="text-center">{{ $item->plot->plot_no ?? '' }}</td>
                                    <td class="text-center">{{ $item->flat->flat_no ?? '' }}</td> --}}
                                        <td class="text-center">{{ $item->customer->customer_name ?? '' }}</td>
                                        <td class="text-center">{{ $item->customer->customer_code ?? '' }}</td>
                                        <td class="text-center">{{ $installment->monthly_installment ?? 0 }}</td>
                                        {{-- <td class="text-center">
                                        {{ optional($item->land_payments->first())->due_amount ? $item->land_payments->first()->due_amount ?? '' : '' }}
                                    </td> --}}
                                        <td class="text-center">
                                            {{ $item->payment_option == 'initial' ? $item->remaining_amount ?? 0 : 0 }}
                                        </td>



                                        <td class="text-center">{{ $installment->total_installment_number ?? 0 }}</td>
                                        <td class="text-center">
                                            {{ $item->payment_option == 'initial' ? $withoutInstallmentPaymentsMade : $totalInstallmentAmountPaid }}
                                        </td>
                                        {{-- <td class="text-center">
                                        @if ($item->status == 'Pending')
                                            <span class="label label-danger text-red">Pending</span>
                                        @elseif ($item->status == 'Approve')
                                            <span class="label label-success text-green">Approved</span>
                                        @else
                                            <span class="label label-success text-blue">Processing</span>
                                        @endif
                                    </td> --}}
                                        <td class="text-center">
                                            <a data-toggle="modal" data-target=".view-details-modal-{{ $item->id }}"
                                                style="padding:2px; color:rgb(255, 255, 255)"
                                                class="btn btn-xs btn-success mr-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- <a href="{{ route('edit_application_form',$item->id) }}"><i
                                                class="fa fa-edit text-primary" style=""></i>
                                            </a> --}}

                                            {{-- <a href="{{ route('land_sale_bill_generate', $item->id) }}" target="_blank"><i
                                                    class="fa fa-print" style="color: rgb(219, 65, 34)"></i>
                                            </a> --}}


                                            @if ($item->remaining_amount == 0 || ($installment && $installment->total_installment_number == 0))
                                                <span class="label label-success text-green"><b>Paid</b></span>
                                            @else
                                                <a data-toggle="modal"
                                                    data-target=".installment-payment-modal-{{ $item->id }}"
                                                    style="padding:2px; color:white" class="btn btn-xs btn-primary  mr-1">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>
                                            @endif

                                            <a data-toggle="modal"
                                                data-target=".development-payment-modal-{{ $item->id }}"
                                                style="padding:2px; color:rgb(0, 0, 0)"
                                                class="btn btn-xs btn-warning  mr-1">
                                                <i class="fas fa-plus-circle"></i>
                                            </a>


                                            <a href="#"
                                                onclick="event.preventDefault(); if(confirm('আপনি কি নিশ্চিত এই ল্যান্ড অ্যাপ্লিকেশন রিজেক্ট করতে চান?')) { document.getElementById('delete-form-{{ $item->id }}').submit(); }"
                                                class="btn btn-sm btn-outline-danger" title="অ্যাপ্লিকেশন রিজেক্ট করুন">
                                                <i class="fas fa-times-circle"></i>
                                            </a>

                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('delete_application_form', $item->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>

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



    <!-- Land Sale Details View Modal -->
    @foreach ($land_sale_list as $item)
        <div class="modal fade view-details-modal-{{ $item->id }}" id="saleDetailsModal-{{ $item->id }}"
            tabindex="-1" role="dialog" aria-labelledby="saleDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-file-invoice mr-2"></i> Sale Details - {{ $item->land_sale_code }}
                        </h5>
                        <div>
                            <button type="button" class="btn btn-light btn-sm mr-2"
                                onclick="printPage({{ $item->id }}, '{{ Session::get('company_name') }}')">
                                <i class="fas fa-print"></i> Print
                            </button>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-4" id="print_body_{{ $item->id }}">
                        <!-- Company Header -->
                        <div class="text-center mb-4">
                            <h4 class="mb-1">{{ Session::get('company_name') }}</h4>
                            <p class="mb-1"><i class="fas fa-map-marker-alt"></i> {{ Session::get('company_address') }}
                            </p>
                            <h5 class="mt-3 text-primary">SALE DETAILS</h5>
                        </div>

                        <!-- Applicant Details Card -->
                        <div class="card mb-4 border-primary">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0"><i class="fas fa-user-tie mr-2"></i> Applicant Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-bordered table-striped shadow-sm">
                                            <thead class="table-success text-center">
                                                <tr>
                                                    <th colspan="4">Applicant Details</th>
                                                </tr>
                                            </thead>

                                            <tr>
                                                <th width="20%">Applicant Code:</th>
                                                <td>{{ $item->customer->customer_code ?? '' }}</td>
                                                <th width="20%">Applicant Name:</th>
                                                <td>{{ $item->customer->customer_name ?? '' }}</td>
                                            </tr>

                                            <tr>
                                                <th>Father Name:</th>
                                                <td>{{ $item->customer->father_name ?? '' }}</td>
                                                <th>Mother Name:</th>
                                                <td>{{ $item->customer->mother_name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Spouse Name:</th>
                                                <td>{{ $item->customer->spouse_name ?? '' }}</td>
                                                <th>Present Address:</th>
                                                <td>{{ $item->customer->present_mailing_address ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Permanent Address:</th>
                                                <td>{{ $item->customer->permanent_address ?? '' }}</td>
                                                <th>Mobile No:</th>
                                                <td>{{ $item->customer->mobile_no ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Phone No:</th>
                                                <td>{{ $item->customer->phone_no ?? '' }}</td>
                                                <th>Email:</th>
                                                <td>{{ $item->customer->email ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nationality:</th>
                                                <td>{{ $item->customer->nationality ?? '' }}</td>
                                                <th>Religion:</th>
                                                <td>{{ $item->customer->religion ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Date of Birth:</th>
                                                <td>{{ $item->customer->date_of_birth ?? '' }}</td>
                                                <th>Applicant's Photo:</th>
                                                <td class="text-center">
                                                    @if (!empty($item->customer->customer_photo))
                                                        <img src="{{ asset('/upload_images/customer_photo/' . $item->customer->customer_photo) }}"
                                                            class="img-thumbnail" style="height: 80px; width: 100px;" />
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>National ID:</th>
                                                <td>{{ $item->customer->national_id ?? '' }}</td>
                                                @if ($item->passport_no)
                                                    <th>Passport No:</th>
                                                    <td>{{ $item->customer->passport_no ?? '' }}</td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th>TIN No:</th>
                                                <td colspan="3">{{ $item->customer->tin_no ?? '' }}</td>
                                            </tr>
                                            <thead class="table-primary text-center">
                                                <tr>
                                                    <th colspan="4">Profession / Occupation</th>
                                                </tr>
                                            </thead>
                                            <tr>
                                                <th>Office Name:</th>
                                                <td>{{ $item->customer->office_name ?? '' }}</td>
                                                <th>Office Address:</th>
                                                <td>{{ $item->customer->office_address ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Designation:</th>
                                                <td>{{ $item->customer->designation ?? '' }}</td>
                                                <th>Cell:</th>
                                                <td>{{ $item->customer->customer_cell ?? '' }}</td>
                                            </tr>
                                        </table>
                                        <!-- Additional Customers -->
                                        @if ($item->customers->count() > 0)
                                            <div class="mt-4">
                                                <div class="card-header bg-dark text-white">
                                                    <h6 class="mb-0"><i class="fas fa-users mr-2"></i> Co-Applicants
                                                        Information</h6>
                                                </div>
                                                @foreach ($item->customers as $customer)
                                                    @if ($customer->id != $item->customer_id)
                                                        <!-- Skip main customer -->
                                                        <div class="mb-3 border-bottom pb-3">
                                                            <table class="table table-bordered table-striped shadow-sm">
                                                                <thead class="table-success text-center">
                                                                    <tr>
                                                                        <th colspan="4">Co-Applicants Details</th>
                                                                    </tr>
                                                                </thead>
                                                                <tr>
                                                                    <th width="20%">Applicant Code:</th>
                                                                    <td>{{ $customer->customer_code ?? '' }}</td>
                                                                    <th width="20%">Applicant Name:</th>
                                                                    <td>{{ $customer->customer_name ?? '' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Father Name:</th>
                                                                    <td>{{ $customer->father_name ?? '' }}</td>
                                                                    <th>Mother Name:</th>
                                                                    <td>{{ $customer->mother_name ?? '' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Spouse Name:</th>
                                                                    <td>{{ $customer->spouse_name ?? '' }}</td>
                                                                    <th>Mobile No.:</th>
                                                                    <td>{{ $customer->mobile_no ?? '' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Present Address:</th>
                                                                    <td colspan="3">
                                                                        {{ $customer->present_mailing_address ?? '' }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Photo:</th>
                                                                    <td colspan="3">
                                                                        @if (!empty($customer->customer_photo))
                                                                            <img src="{{ asset('/upload_images/customer_photo/' . $customer->customer_photo) }}"
                                                                                class="img-thumbnail"
                                                                                style="height: 80px; width: 100px;" />
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- Nominee Details Card (Conditional) -->
                        @if ($item->customer->nominee_name || $item->customer->inheritor)
                            <div class="card mb-4 border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0"><i class="fas fa-users mr-2"></i> Nominee Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th width="40%">Nominee Name:</th>
                                                    <td>{{ $item->customer->nominee_name ?? ($item->customer->inheritor ?? 'N/A') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Relation:</th>
                                                    <td>{{ $item->customer->relation ?? ($item->customer->inheritor_relation ?? 'N/A') }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Mobile No:</th>
                                                    <td>{{ $item->customer->nominee_cell ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                <tr>
                                                    <th width="40%">Address:</th>
                                                    <td>{{ $item->customer->nominee_address ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Photo:</th>
                                                    <td>
                                                        @if (!empty($item->customer->nominee_photo))
                                                            <img src="{{ asset('/upload_images/nominee_photo/' . $item->customer->nominee_photo) }}"
                                                                class="img-thumbnail" width="80">
                                                        @else
                                                            <span class="text-muted">No Photo</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Property Details Card -->
                        <div class="card mb-4 border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-{{ $item->type == 'Flat' ? 'building' : ($item->type == 'Plot' ? 'map-marked-alt' : 'landmark') }} mr-2"></i>
                                    {{ $item->type }} Details
                                </h6>
                            </div>
                            <div class="card-body">
                                @if ($item->type == 'Flat')
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Flat No</th>
                                                    <th>Floor</th>
                                                    <th>Size</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->flats as $flat)
                                                    <tr>
                                                        <td>{{ $flat->flat_no ?? 'N/A' }}</td>
                                                        <td>{{ $flat->flat_floor->floor_no ?? 'N/A' }}</td>
                                                        <td>{{ $flat->flat_size ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-light">
                                                    <td colspan="2" class="text-right font-weight-bold">Total Price:</td>
                                                    <td class="font-weight-bold text-success">
                                                        {{ number_format($item->flat_total_price, 2) }} Tk.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif($item->type == 'Plot')
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Plot No</th>
                                                    <th>Sector</th>
                                                    <th>Road</th>
                                                    <th>Size</th>
                                                    <th>Rate Per Katha</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->plots as $plot)
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
                                                    <td class="font-weight-bold text-success">
                                                        {{ number_format($item->total_price, 2) }} Tk.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @elseif($item->type == 'Land')
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th>Project</th>
                                                    <th>Share Qty</th>
                                                    <th>Percentage (শতাংশ)</th>
                                                    <th>Size</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->landshares as $landshare)
                                                    <tr>
                                                        <td>{{ $landshare->project->name ?? 'N/A' }}</td>
                                                        <td>{{ $landshare->shareqty ?? 'N/A' }}</td>
                                                        <td>{{ $landshare->sotangsho ?? 'N/A' }}</td>
                                                        <td>{{ $landshare->size ?? 'N/A' }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-light">
                                                    <td colspan="3" class="text-right font-weight-bold">Total Price:</td>
                                                    <td class="font-weight-bold text-success">
                                                        {{ number_format($item->land_total_price, 2) }} Tk.</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>


                        <!-- Payment Details Card -->
                        <div class="card mb-4 border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0"><i class="fas fa-money-bill-wave mr-2"></i> Payment Information</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $installment = \App\Models\Installment::where('land_sale_id', $item->id)
                                        ->where(['company_id' => Session::get('company_id')])
                                        ->first();
                                @endphp

                                @if ($installment)
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <div class="alert alert-info p-2">
                                                <h6 class="mb-2"><i class="fas fa-calendar-alt mr-2"></i> Installment
                                                    Plan</h6>
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tr>
                                                        <th width="50%">Monthly Installment:</th>
                                                        <td class="font-weight-bold">
                                                            {{ number_format($installment->monthly_installment, 2) }} Tk.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>Total Installments:</th>
                                                        <td>{{ $installment->total_installment_number }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Payment Date</th>
                                                <th>Amount</th>
                                                <th>Payment Method</th>
                                                <th>Fund</th>
                                                <th>Bank</th>
                                                <th>Account</th>
                                                <th>Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($item->land_payments as $key => $payment)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $payment->pay_date ?? 'N/A' }}</td>
                                                    <td class="font-weight-bold">{{ number_format($payment->amount, 2) }}
                                                        Tk.</td>
                                                    <td>{{ $payment->payment_type->name ?? 'N/A' }}</td>
                                                    <td>{{ $payment->fund->name ?? 'N/A' }}</td>
                                                    <td>{{ $payment->bank->name ?? 'N/A' }}</td>
                                                    <td>{{ $payment->account->account_no ?? 'N/A' }}</td>
                                                    <td>{{ $payment->remarks ?? 'N/A' }}</td>
                                                </tr>
                                            @endforeach
                                            @if (count($item->land_payments) == 0)
                                                <tr>
                                                    <td colspan="7" class="text-center text-muted">No payment records
                                                        found</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details Card -->
                        <div class="card mb-4 border-warning">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Total Amount Paid</th>
                                                <th>Remaining Amount</th>
                                                <th>Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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

                                            <tr>
                                                @if ($item->payment_option == 'notMade')
                                                    <td>{{ $totalInstallmentAmountPaid }} Tk.</td>
                                                    <td>{{ number_format($item->total_price - $totalInstallmentAmountPaid, 2) }} Tk.</td>
                                                @else
                                                    <td>{{ $withoutInstallmentPaymentsMade }} Tk.</td>
                                                    <td>{{ $item->payment_option == 'initial' ? number_format($item->remaining_amount ?? 0, 2) : 0 }} Tk.</td>
                                                @endif

                                                @if ($item->type == 'Flat')
                                                    <td>{{ number_format($item->flat_total_price, 2) }} Tk.</td>
                                                @elseif($item->type == 'Land')
                                                    <td>{{ number_format($item->land_total_price, 2) }} Tk.</td>
                                                @else
                                                    <td>{{ number_format($item->total_price, 2) }} Tk.</td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>

                                    @if ($item->payment_option == 'notMade')
                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label class="form-label">Total Installment Number:<span style="color: red;"> {{ $installment->total_installment_number ?? '' }}</span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Monthly Installment: <span style="color: red;">{{ $installment->monthly_installment ?? '' }} Tk.</span></label>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Payments Made: <span style="color: red;">{{ $paymentsMade ?? '0' }}</span></label>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>



                        <!-- Summary Section - Fixed Position -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="sticky-top" style="top: 20px; z-index: 100;">
                                    <div class=" alert-light border shadow-sm">
                                        <h6 class="mb-2"><i class="fas fa-info-circle mr-2"></i> Sale Summary</h6>
                                        <table class="table table-sm table-borderless mb-0">
                                            <tr>
                                                <th width="50%">Invoice No:</th>
                                                <td>{{ $item->invoice_no }}</td>
                                            </tr>
                                            <tr>
                                                <th>Application Date:</th>
                                                <td>{{ $item->application_date }}</td>
                                            </tr>
                                            <tr>
                                                <th>Payment Option:</th>
                                                <td class="font-weight-bold">{{ ucfirst($item->payment_option) }}</td>
                                            </tr>
                                            @if ($item->type == 'Flat')
                                                <tr>
                                                    <th>Total Flat Price:</th>
                                                    <td class="text-success font-weight-bold">
                                                        {{ number_format($item->flat_total_price, 2) }} Tk.</td>
                                                </tr>
                                            @elseif ($item->type == 'Land')
                                                <tr>
                                                    <th>Total Land Price:</th>
                                                    <td class="text-success font-weight-bold">
                                                        {{ number_format($item->land_total_price, 2) }} Tk.</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <th>Total Plot Price:</th>
                                                    <td class="text-success font-weight-bold">
                                                        {{ number_format($item->total_price, 2) }} Tk.</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="sticky-top" style="top: 20px; z-index: 100;">
                                    <div class=" alert-light border shadow-sm">
                                        <h6 class="mb-2"><i class="fas fa-file-signature mr-2"></i> Authorized By</h6>
                                        <div class="text-center mt-3">
                                            <p class="mb-1">_________________________</p>
                                            <p class="mb-0 font-weight-bold">Authorized Signature</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach



    <!-- Installment Payment Modal -->
    @foreach ($land_sale_list as $item)
        {{-- @dd($item) --}}
        <div class="modal fade installment-payment-modal-{{ $item->id }}" id="exampleModal-{{ $item->id }}"
            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-info text-center d-flex justify-content-between align-items-center">
                        @if ($item->payment_option == 'initial')
                            <h5>Pay Remaining Amount</h5>
                        @else
                            <h5>Pay Installment</h5>
                        @endif
                        <div>
                            <button type="button" class="close ml-2" data-dismiss="modal">&times;</button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <div class="row">
                                <!-- Existing form content -->
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
                                    // dd($withoutInstallmentPaymentsMade);
                                    $totalInstallmentAmountPaid = \App\Models\LandPayment::where(
                                        'land_sale_id',
                                        $installment->land_sale_id ?? 0,
                                    )->sum('amount');

                                    // $remainingInstallments =
                                    //     ($installment->total_installment_number ?? 0) - $paymentsMade;

                                @endphp
                                @if ($item->payment_option == 'notMade')
                                    <div class="col-md-4">
                                        <label class="form-label">Total Installment Number:<span style="color: red;">
                                                {{ $installment->total_installment_number ?? '' }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Monthly Installment: <span
                                                style="color: red;">{{ $installment->monthly_installment ?? '' }}
                                                Tk.</span>
                                        </label>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <label class="form-label">Remaining Installment Number: <span
                                                style="color: red;">{{ $remainingInstallments ?? '' }}</span>
                                        </label>
                                    </div> --}}
                                    <div class="col-md-4">
                                        <label class="form-label">Total Amount Paid:
                                            <span style="color: #0c5a1d;">{{ $totalInstallmentAmountPaid }} Tk.</span>
                                        </label>
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        <label class="form-label">Total Amount Paid:
                                            <span style="color: #0c5a1d;">{{ $withoutInstallmentPaymentsMade }}
                                                Tk.</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Remaining Amount: <span
                                                style="color: red;">{{ $item->payment_option == 'initial' ? $item->remaining_amount ?? 0 : 0 }}
                                                Tk</span>
                                        </label>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr>
                    </div>
                    <form action="{{ route('store_land_sale_payment') }}" method="post" enctype="multipart/form-data"
                        target="_blank">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" value="{{ $item->id }}" name="land_sale_id">
                                <input type="hidden" value="{{ $item->payment_option }}" name="payment_option">
                                @if ($item->payment_option == 'notMade')
                                    <input type="hidden" id="monthly_installment-{{ $item->id }}"
                                        value="{{ $installment->monthly_installment }}">
                                    <div class="col-md-6">
                                        <label for="amount">Installment Amount(Tk.)</label>
                                        <input type="number" id="monthly_installment_amount-{{ $item->id }}"
                                            name="amount" class="form-control" placeholder="" required readonly>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <label for="amount">Remaining Amount(Tk.)</label>
                                        <input type="number" id="remaining_amount-{{ $item->id }}" name="amount"
                                            class="form-control" placeholder="" required
                                            max="{{ $item->remaining_amount }}"
                                            oninput="convertNumberToWords({{ $item->id }})">
                                    </div>
                                @endif
                                <div class="form-group col-md-6">
                                    <label for="remaininginwords-{{ $item->id }}"><strong>In Words</strong></label>
                                    <input type="text" name="remaining_amount"
                                        id="remaininginwords-{{ $item->id }}" class="form-control" readonly>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <label for="fund">Fund<i class="text-danger">*</i></label>
                                            <select name="fund_id" id="fund-{{ $item->id }}" class="form-control"
                                                required onchange="showBankInfo({{ $item->id }})">
                                                <option value="">Select a Fund </option>
                                                @foreach ($fund_types as $fund)
                                                    <option value="{{ $fund->id }}">{{ $fund->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-6 bank-{{ $item->id }}" style="display: none;">
                                            <label for="">Bank <i class="text-danger">*</i></label>
                                            <select name="bank_id" id="bank_id-{{ $item->id }}"
                                                class="form-control" onchange="filterAccount({{ $item->id }})">
                                                <option value="">Select a Bank</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->id }}">{{ $bank->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 bank-{{ $item->id }}" style="display: none;">
                                            <label for="account">Account <i class="text-danger">*</i></label>
                                            <select name="account_id" id="account_id-{{ $item->id }}"
                                                class="form-control" onchange="showAccountBranch({{ $item->id }})">
                                                <option value="">Select An Account</option>
                                                @foreach ($accounts as $account)
                                                    <option value="{{ $account->id }}">
                                                        {{ $account->account_no }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 bank-{{ $item->id }}" style="display: none;">
                                            <label for="branch">Branch <i class="text-danger">*</i></label>
                                            <input type="text" id="branch-{{ $item->id }}" class="form-control">
                                        </div>

                                        <div class="col-md-12">
                                            <label for="payment_type_id">Payment Method<i
                                                    class="text-danger">*</i></label>
                                            <select name="payment_type_id" id="payment_type_id-{{ $item->id }}"
                                                required class="form-control">
                                                <option value="">Select a Method</option>
                                                @foreach ($payment_types as $payment_type)
                                                    <option value="{{ $payment_type->id }}">
                                                        {{ $payment_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="pay_date">Pay Date<i class="text-danger">*</i></label>
                                            <input type="date" name="pay_date" value="{{ date('Y-m-d') }}"
                                                class="form-control" placeholder="" required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="remaining_amount_cheque_no">Cash/ Cheque/ Pay Order No.
                                                <i class="text-danger">*</i></label>
                                            <input type="text" name="remaining_amount_cheque_no" class="form-control"
                                                id="remaining_amount_cheque_no-{{ $item->id }}" placeholder=""
                                                required>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="note">Note
                                                <i class="text-danger">*</i></label>
                                            <textarea name="remarks" class="form-control" id="note" rows="3" placeholder=""></textarea>
                                        </div>

                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-12">
                                            <label for="payment_month">Payment Month<i class="text-danger">*</i></label>
                                            <input type="month" value="{{ date('Y-m') }}" name="payment_month"
                                                class="form-control" placeholder="" required>
                                        </div>
                                    </div> --}}

                                    {{-- @if ($installment->adjustment_number - $left_adjustment != 0)
                                        <hr class="styled-hr">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="adjustment_amount">Adjustment Amount <span
                                                        style="color: #ff0000;">(</span>if any<span
                                                        style="color: #ff0000;">)</span></label>
                                                <input type="number" name="adjustment_amount" class="form-control"
                                                    max="{{ $installment->adjustment_amount - $adjustment }}"
                                                    placeholder="Adjustment Amount">
                                            </div>
                                        </div>
                                    @endif --}}
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    @endforeach
    <!-- /.modal -->



    <!-- Development Payment Modals -->
    @foreach ($land_sale_list as $item)
        <div class="modal fade development-payment-modal-{{ $item->id }}"
            id="developmentModal-{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="developmentModalLabel-{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content shadow-lg rounded">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="developmentModalLabel-{{ $item->id }}">
                            <i class="fas fa-tools mr-2"></i> Development Payment
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped table-bordered text-center shadow-sm">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>Total Amount Paid</th>
                                            <th>Remaining Amount</th>
                                            <th>Total Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white">
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
                                                    <td>{{ $totalInstallmentAmountPaid }} Tk.</td>
                                                    <td>{{ number_format($item->total_price - $totalInstallmentAmountPaid, 2) }} Tk.</td>
                                                @else
                                                    <td>{{ $withoutInstallmentPaymentsMade }} Tk.</td>
                                                    <td>{{ $item->payment_option == 'initial' ? number_format($item->remaining_amount ?? 0, 2) : 0 }} Tk.</td>
                                                @endif

                                                @if ($item->type == 'Flat')
                                                    <td>{{ number_format($item->flat_total_price, 2) }} Tk.</td>
                                                @elseif($item->type == 'Land')
                                                    <td>{{ number_format($item->land_total_price, 2) }} Tk.</td>
                                                @else
                                                    <td>{{ number_format($item->total_price, 2) }} Tk.</td>
                                                @endif
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <form action="{{ route('development_payment') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $item->id }}" name="land_sale_id">
                            <input type="hidden" value="{{ $item->payment_option }}" name="payment_option">

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="amount-{{ $item->id }}"><strong>Development Amount (Tk.)</strong></label>
                                    <input type="number" id="amount-{{ $item->id }}" name="amount"
                                        class="form-control amount-input" data-target="{{ $item->id }}"
                                        placeholder="Enter amount" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="inwords-{{ $item->id }}"><strong>In Words</strong></label>
                                    <input type="text" name="remaining_amount" id="inwords-{{ $item->id }}"
                                        class="form-control" readonly>
                                </div>
                            </div>

                            <!-- New Fields Start -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="start_date-{{ $item->id }}"><strong>Start Date</strong></label>
                                    <input type="date" name="start_date" id="start_date-{{ $item->id }}" class="form-control" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="end_date-{{ $item->id }}"><strong>End Date</strong></label>
                                    <input type="date" name="end_date" id="end_date-{{ $item->id }}" class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="image-{{ $item->id }}"><strong>Upload File</strong></label>
                                <input type="file" name="image" id="image-{{ $item->id }}" class="form-control">
                            </div>


                            <div class="form-group">
                                <label for="note-{{ $item->id }}"><strong>Note</strong></label>
                                <textarea name="note" id="note-{{ $item->id }}" class="form-control summernote" rows="4"></textarea>
                            </div>
                            <!-- New Fields End -->

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i> Close
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save mr-1"></i> Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- /.modal -->









@endsection
@push('script_js')
    <script>
        $(document).ready(function() {
            $('.bank').hide();

            @foreach ($land_sale_list as $item)
                var installment = parseFloat($('#monthly_installment-{{ $item->id }}').val());
                $('#monthly_installment_amount-{{ $item->id }}').val(installment);
            @endforeach
        });

        function showBankInfo(id) {
            var fund_id = document.getElementById('fund-' + id).value;
            console.log(fund_id);
            if (fund_id == 1) {
                $('.bank-' + id).show();
                $('#bank_id-' + id).prop('required', true);
                $('#account_id-' + id).prop('required', true);
            } else {
                $('.bank-' + id).hide();
            }

        }

        function filterAccount(id) {
            var bank_id = document.getElementById('bank_id-' + id).value;
            var url = "{{ route('filter-bank-fund') }}";
            $.ajax({
                type: "GET",
                url: url,
                data: {
                    bank_id
                },
                success: function(data) {
                    $('#account_id-' + id).html('<option value="">Select One</option>');
                    $.each(data, function(key, value) {
                        $('#account_id-' + id).append('<option value="' + value.id + '">' + value
                            .account_no +
                            '</option>');
                    });
                },
            });
        }

        function showAccountBranch(id) {
            var account_id = document.getElementById('account_id-' + id).value;
            console.log(account_id);
            var url = "{{ route('getAccountBranch') }}";

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    account_id: account_id
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

    {{-- In Words --}}
    <script>
        function numberToWords(n) {
            const a = [
                '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten',
                'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen',
                'Seventeen', 'Eighteen', 'Nineteen'
            ];
            const b = [
                '', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'
            ];

            if (!n || isNaN(n)) return '';

            n = parseInt(n);
            if (n === 0) return 'Zero Taka only';

            const helper = (num) => {
                if (num < 20) return a[num];
                if (num < 100) return b[Math.floor(num / 10)] + (num % 10 ? ' ' + a[num % 10] : '');
                if (num < 1000) return a[Math.floor(num / 100)] + ' Hundred' + (num % 100 ? ' and ' + helper(num %
                    100) : '');
                if (num < 100000) return helper(Math.floor(num / 1000)) + ' Thousand' + (num % 1000 ? ' ' + helper(num %
                    1000) : '');
                if (num < 10000000) return helper(Math.floor(num / 100000)) + ' Lakh' + (num % 100000 ? ' ' + helper(
                    num % 100000) : '');
                return helper(Math.floor(num / 10000000)) + ' Crore' + (num % 10000000 ? ' ' + helper(num % 10000000) :
                    '');
            };

            return helper(n).trim() + ' Taka only';
        }

        function updateInWordsById(inputId, targetId) {
            const input = document.getElementById(inputId);
            const target = document.getElementById(targetId);
            if (!input || !target) return;

            input.addEventListener('input', function() {
                const value = parseInt(this.value);
                target.value = (!isNaN(value) && value > 0) ? numberToWords(value) : '';
            });
        }

        // ✅ For dynamic fields with dataset
        document.querySelectorAll('.amount-input').forEach(function(input) {
            input.addEventListener('input', function() {
                const id = this.dataset.target;
                const wordsField = document.getElementById(`inwords-${id}`);
                const value = parseInt(this.value);
                if (!isNaN(value) && value > 0) {
                    wordsField.value = numberToWords(value);
                } else {
                    wordsField.value = '';
                }
            });
        });

        // ✅ For Installment fields (use this in script init if needed)
        function convertNumberToWords(id) {
            const amount = document.getElementById(`remaining_amount-${id}`).value;
            const inWordsField = document.getElementById(`remaininginwords-${id}`);
            const value = parseInt(amount);
            inWordsField.value = (!isNaN(value) && value > 0) ? numberToWords(value) : '';
        }
    </script>

    <!-- jQuery এবং DataTables স্ক্রিপ্ট যোগ করুন -->
    <script>
        $(document).ready(function() {
            var table = $('#landSaleTable').DataTable({
                "paging": true,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "searching": true,
                "responsive": true,
                "order": [
                    [5, "asc"]
                ], // TDC কলাম সর্টিং
                "columnDefs": [{
                        "orderable": false,
                        "targets": 0
                    }, // SL কলাম সর্ট অফ
                    {
                        "orderable": true,
                        "targets": 5
                    } // TDC কলাম সর্ট অন
                ]
            });

            // পেজিনেশন বা সর্ট করলে সিরিয়াল নম্বর আপডেট হবে না
            table.on('order.dt search.dt', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>

<!-- Summernote CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" rel="stylesheet">

<!-- Summernote JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>

<script>
    $(document).ready(function () {
        $('.summernote').summernote({
            height: 150,
            placeholder: 'Write your note here...'
        });
    });
</script>



@endpush
