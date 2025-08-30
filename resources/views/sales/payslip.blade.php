<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}">

</head>
<style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f8f8;
    }

    .invoice-box {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #eee;
        background-color: #fff;
    }

    header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .company-details,
    .invoice-details {
        width: 45%;
    }

    h1,
    h2,
    h3 {
        margin: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table th,
    table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    table thead th {
        background-color: #f0f0f0;
    }

    tfoot td {
        font-weight: bold;
        background-color: #f9f9f9;
    }

    tfoot tr:first-child td {
        border-top: 2px solid #ddd;
    }

    footer {
        text-align: center;
        font-size: 12px;
        color: #888;
    }
</style>
<script>
    window.print();
    window.onafterprint = function() {
        window.close();
    };
</script>


<body>
    @php
        $company = \App\Models\Company::first();
        $companyLogo = $company ? asset('upload_images/company_logo/' . $company->logo) : '';
    @endphp
    <div class="container mt-3">

        <div class="row">
            <div class="col-12 text-center">
                <h4>
                    <img height="50px" src="{{ $companyLogo }}" alt="">
                </h4>
                <address>
                    {{ $company->address ?? '' }} <br />
                    {{ $company->email ?? '' }} <br />
                    {{ $company->phone_no ?? '' }}
                </address>
            </div>
        </div>

        <div class="row invoice-info">
            {{-- <div class="col-sm-4 invoice-col">

        
         </div> --}}
            <!-- /.col -->
            <div class="col-sm-6 invoice-col">
                <address>
                    <strong>{{ $land_sale->customer->customer_name }}</strong><br>
                    Address: {{ $land_sale->customer->present_mailing_address }}<br>
                    Phone: {{ $land_sale->customer->mobile_no }}<br>
                    Email: {{ $land_sale->customer->email }}<br>
                </address>
            </div>
            <!-- /.col -->
            <div class="col-sm-6 invoice-col">
                <b>Invoice No : {{ $land_sale->invoice_no }}</b><br>
                Date Issued: {{ date('F j, Y') }}
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Initial Payment date</th>
                            <th>Project</th>
                            @if ($land_sale->plot_id)
                                <th>Plot No.</th>
                                <th>Road No.</th>
                                <th>Sector No.</th>
                                <th>Block No.</th>
                            @else
                                <th>Flat No.</th>
                                <th>Floor No.</th>
                            @endif
                            <th>Booking Money</th>
                            <th>Booking Date</th>
                            <th>Down Payment</th>
                            <th>Remaining Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach ($purchase_details as $item)
                @dd($item) --}}
                        <tr>
                            <td>{{ $land_sale->initial_payment_made_date ?? '' }}</td>
                            <td>{{ $land_sale->customer->project->name ?? '' }}</td>
                            @if ($land_sale->plot_id)
                                <td>{{ $land_sale->plot->plot_no ?? '' }}</td>
                                <td>{{ $land_sale->plot->road->road_name ?? '' }}</td>
                                <td>{{ $land_sale->plot->sector->sector_name ?? '' }}</td>
                                <td>{{ $land_sale->plot->block_no ?? '' }}</td>
                            @else
                                <td>{{ $land_sale->flat->flat_no ?? '' }}</td>
                                <td>{{ $land_sale->flat->flat_floor->floor_no ?? '' }}</td>
                            @endif
                            <td>{{ $land_sale->booking_money ?? '' }} Tk.</td>
                            <td>{{ $land_sale->booking_date ?? '' }}</td>
                            <td>{{ $land_sale->down_payment ?? '' }} Tk.</td>
                            <td>{{ $land_sale->remaining_amount ?? '' }} Tk.</td>
                        </tr>
                        {{-- @endforeach --}}
                    </tbody>
                    {{-- <tfoot>
                <tr>
                    <th colspan="6" class="text-right">Total Price:</th>
                    <td>{{ number_format($purchase_info->total_amount,2) }} Tk.</td>
                </tr>
                <tr>
                    <th colspan="6" class="text-right">Due Amount:</th>
                    <td>{{ number_format($purchase_info->due_amount,2) }} Tk.</td>
                </tr>
            </tfoot> --}}

                </table>
            </div>
            <!-- /.col -->
        </div>
</body>

</html>
