<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/customer.css') }}">
</head>

<body>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12 d-flex justify-content-between align-items-center">
                                    <h2 class="content-header-title text-green">{{ $customerdata->customer_name }}</h2>
                                    <form method="GET" action="{{ route('submitLogout') }}">
                                         @csrf
                                        <button type="submit" class="btn btn-danger">Logout</button>
                                    </form>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <section id="dashboard-ecommerce">
                    <div class="row match-height">
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="card card-statistics">
                                    <div class="card-header">
                                        <h4 class="card-title text-green">Customer Details</h4>
                                    </div>
                                    @php
                                        $customerPhoto = $customerdata
                                            ? asset('upload_images/customer_photo/' . $customerdata->customer_photo)
                                            : '';
                                    @endphp
                                    <div class="card-body statistics-body">
                                        <div class="columns">
                                            <div class="column">
                                                <div class="contact-info">
                                                    <p><b>Customer Name:</b> {{ $customerdata->customer_name }}</p>
                                                    <p><b>Father Name:</b> {{ $customerdata->father_name }}</p>
                                                    <p><b>Mother Name:</b> {{ $customerdata->mother_name }}</p>
                                                    <p><b>Spouse Name:</b> {{ $customerdata->spouse_name }}</p>
                                                    <p><b>Email:</b> {{ $customerdata->email }}</p>
                                                    <p><b>Phone:</b> {{ $customerdata->phone_no }}</p>
                                                    <p><b>Mobile:</b> {{ $customerdata->mobile_no }}</p>
                                                    <p><b>Permanent Address:</b> {{ $customerdata->permanent_address }}
                                                    </p>
                                                    <p><b>Present Address:</b>
                                                        {{ $customerdata->present_mailing_address }}</p>
                                                    <p><b>Nationality:</b> {{ $customerdata->nationality }}</p>
                                                    <p><b>Religion:</b> {{ $customerdata->religion }}</p>
                                                    <p><b>Date of Birth:</b> {{ $customerdata->date_of_birth }}</p>
                                                    <p><b>National ID:</b> {{ $customerdata->national_id }}</p>
                                                    @if ($customerdata->passport_no)
                                                        <p><b>Passport No:</b> {{ $customerdata->passport_no }}</p>
                                                    @endif
                                                    <p><b>TIN No:</b> {{ $customerdata->tin_no }}</p>
                                                    <p><b>Office Name:</b> {{ $customerdata->office_name }}</p>
                                                    <p><b>Office Address:</b> {{ $customerdata->office_address }}</p>
                                                    <p><b>Designation:</b> {{ $customerdata->designation }}</p>
                                                    <p><b>Cell:</b> {{ $customerdata->customer_cell }}</p>
                    
                                                </div>
                                            </div>
                                            <div class="column">
                                                <div class="media">
                                                    <img src="{{ $customerPhoto }}" alt="Customer Image"
                                                        class="customer-image">
                                                </div>

                                                <div class="contact-info">
                                                    <p><b>Nominee Name:</b> {{ $customerdata->nominee_name }}</p>
                                                    <p><b>Nominee Address:</b> {{ $customerdata->nominee_address }}</p>
                                                    <p><b>Relation With the Applicant:</b>
                                                        {{ $customerdata->relation }}</p>
                                                    <p><b>Nominee's Cell:</b> {{ $customerdata->nominee_cell }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row match-height">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title text-green">Booking Info</h4>
                                    </div>
                                    <div class="card-body statistics-body">
                                        <div class="contact-info">
                                            <table style="width:100%; table-layout:fixed;">
                                                <tr>
                                                    {{-- @dd($landSaledata) --}}
                                                    <td style="vertical-align: top;">
                                                        <p><b>Booking No:</b> {{ $landSaledata->land_sale_code }}</p>
                                                        <p><b>Plot No:</b> {{ $landSaledata->plot->plot_no }}</p>
                                                        @if ($landSaledata->payment_option == 'initial')
                                                            <p><b>Booking Date:</b> {{ $landSaledata->booking_date }}
                                                            </p>
                                                            <p><b>Booking Money:</b> {{ $landSaledata->booking_money }}
                                                            </p>
                                                            <p><b>Down Payment:</b> {{ $landSaledata->down_payment }}
                                                            </p>
                                                        @endif
                                                        <p><b>Rate Per Katha:</b> {{ $landSaledata->rate_per_katha }}
                                                        </p>
                                                    </td>
                                                    <td style="vertical-align: top;">
                                                        <p><b>Plot Price:</b> {{ $landSaledata->plot_price }}</p>
                                                        <p><b>Documentation Cost:</b>
                                                            {{ $landSaledata->documentation_cost }}</p>
                                                        <p><b>Processing Fee:</b> {{ $landSaledata->processing_fee }}
                                                        </p>
                                                        <p><b>Total Price:</b> {{ $landSaledata->total_price }}</p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row match-height">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title text-green">Installment Statement</h4><br>
                                        <h4 class="card-title">Total Installment : <span
                                                style="color: red;">{{ $installmentdata->total_installment_number }}</span>
                                        </h4>
                                    </div>
                                    {{-- @dd($installmentdata); --}}
                                    <div class="card-body">
                                        @php
                                            $initial_payment = \App\Models\LandPayment::with('landSale')
                                                ->where('land_sale_id', $installmentdata->land_sale_id)
                                                ->first();
                                            // dd($initial_payment);
                                        @endphp
                                        @if ($initial_payment->payment_option == 'initial')
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>DOWN PAYMENT DATE</th>
                                                        <th>DOWN PAYMENT</th>
                                                        <th>INITIAL PAYMENT</th>
                                                        <th>INITIAL PAYMENT DATE</th>
                                                        <th>BALANCE AMOUNT</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>{{ $initial_payment->landSale->down_payment_date }}</td>
                                                        <td>{{ $initial_payment->landSale->down_payment }}</td>
                                                        <td>{{ $initial_payment->landSale->total_initial_payment }} Tk.
                                                        </td>
                                                        <td>{{ $initial_payment->landSale->initial_payment_made_date }}
                                                        </td>
                                                        <td>{{ $initial_payment->landSale->balance_amount }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        @endif
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>INSTALLMENT NUMBER</th>
                                                    <th>MONTH</th>
                                                    <th>AMOUNT</th>
                                                    <th>STATUS</th>
                                                    <th>PAID</th>
                                                    {{-- <th>UNPAID</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    use Carbon\Carbon;
                                                    $installmentDate = Carbon::parse(
                                                        $installmentdata->installment_date,
                                                    );

                                                    // Initialize the total paid amount
                                                    $totalPaid = 0;
                                                @endphp

                                                @for ($i = 1; $i <= $installmentdata->total_installment_number; $i++)
                                                    @php
                                                        $currentInstallmentMonth = $installmentDate
                                                            ->copy()
                                                            ->addMonths($i - 1)
                                                            ->format('Y-m');

                                                        $status = 'Unpaid';
                                                        $amountPaid = 0;

                                                        foreach (
                                                            $installmentdata->landSale->land_payments
                                                            as $payment
                                                        ) {
                                                            $paymentDateMonth = Carbon::parse(
                                                                $payment->payment_month,
                                                            )->format('Y-m');
                                                            if ($currentInstallmentMonth === $paymentDateMonth) {
                                                                $status = 'Paid';
                                                                $amountPaid = $payment->amount;
                                                                break;
                                                            }
                                                        }

                                                        $color = $status === 'Paid' ? 'green' : 'red';

                                                        if ($status === 'Paid') {
                                                            $totalPaid += $amountPaid;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>{{ $installmentDate->copy()->addMonths($i - 1)->format('F Y') }}
                                                        </td>
                                                        <td>{{ $installmentdata->monthly_installment }}</td>
                                                        <td style="color: {{ $color }}">{{ $status }}
                                                        </td>
                                                        <td>{{ $status === 'Paid' ? $amountPaid : 0 }}</td>
                                                    </tr>
                                                @endfor
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" style="text-align: right; font-weight: bold;">
                                                        Total Paid</td>
                                                    <td>{{ $totalPaid }}</td>
                                                </tr>
                                            </tfoot>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </div>
    </div>
    <script src="path/to/feather-icons.js"></script>
</body>

</html>
