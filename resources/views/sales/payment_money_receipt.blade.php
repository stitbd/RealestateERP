<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/admin_css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin_css/style.css') }}">

</head>
<style>
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 5px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 0px solid #ddd;
        text-align: left !important;
    }

    table {
        page-break-inside: auto
    }

    tr {
        page-break-inside: avoid;
        page-break-after: auto
    }

    thead {
        display: table-header-group
    }

    tfoot {
        display: table-footer-group
    }




    .modal-body-receipt {
        background-color: #ffffff !important;
    }

    .money-receipt {
        font-family: Arial, sans-serif;
        /* max-width: 800px; */
        margin: auto;
        padding: 20px;
        /* border: 1px solid #000; */
    }

    .header-section-receipt {
        display: flex;
        align-items: center;
        justify-content: left;
    }

    .logo img {
        width: 60px;
        margin-right: 10px;
    }

    .company-info-receipt h1 {
        font-family: 'Times New Roman', Times, serif;
        font-weight: bold;
    }

    .company-info-receipt p {
        font-size: 14px;
        color: #555;
        font-style: italic;
        text-align: right;
        margin: 0;
    }

    .text-center h4 {
        color: #928d8d;
        font-weight: bold;
        font-size: 20px;
    }

    .receipt-details {
        /* text-align: right; */
        margin-bottom: 10px;
    }

    /* .receipt-details p {
        margin: 0;
    } */

    .payment-type-section {
        border: 1px solid #000;
        padding: 5px;
        margin-bottom: 10px;
    }

    .payment-type-section label {
        display: inline-block;
        margin-right: 15px;
    }

    .payment-type-section p {
        margin: 5px 0;
    }

    .details-section-receipt p {
        display: flex;
        font-size: 14px;
        margin: 10px 0;
    }

    /* .details-section-receipt p::before {
        content: "";
        flex-grow: 1;
        border-bottom: 1px dotted #000;
        margin-right: 10px;
    } */

    .dotted-line {
        flex-grow: 2;
        border-bottom: 1px dotted #000;
        padding: 0 10px;
        margin-left: 10px;
    }

    .details-table-receipt {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .details-table-receipt th,
    .details-table-receipt td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
    }

    .details-table-receipt th {
        background-color: #f2f2f2;
    }

    .details-table-receipt tfoot td {
        font-weight: bold;
    }

    /* .footer-section-receipt {
        display: flex;
        justify-content: space-between;
        padding-top: 10px;
        margin-top: 60px;
    }

    .footer-box-receipt {
        text-align: center;
        flex: 1;
        border-top: 1px solid #000;
        padding: 5px;
        font-size: 12px;
    } */

    .footer-section-receipt {
        display: flex;
        justify-content: space-between;
        margin-top: 60px;
    }

    .footer-box-receipt {
        text-align: center;
        flex: 1;
        /* border-top: 1px solid #000; */
        padding: 5px;
        font-size: 12px;
    }

    .signature-box {
        position: relative;
        margin-bottom: -35px;
    }

    .signature-line {
        border-top: 1px solid #1b1b1b;
        position: absolute;
        top: 0;
        left: 50px;
        right: 50px;
    }

    .signature-box p {
        margin-top: 10px;
    }

    .company-info-footer {
        background-color: #4b4b4b45;
        text-align: center;
        font-size: 12px;
        margin-top: 20px;
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
        $detail_array = App\Models\IncomeDetails::where('income_id', $income->id)->get();
        $company_info = \App\Models\Company::first();
    @endphp
    <div class="money-receipt">
        <!-- Header Section with Logo and Company Info -->
        <div class="header-section-receipt">
            <div class="logo">
                <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}" alt="Company Logo" />
            </div>
            <div class="company-info-receipt" style="margin-left: 20%;">
                <h1 style="font-size: 55px; font-weight: bold; margin: 0;">{{ $company_info->name }}
                </h1>
                <div style="display: flex; align-items: right; margin-left:50px">
                    <hr style="flex: 1; border: 3px solid #4b4b4b73; margin: 5px 10px 5px 0;">
                    <p style="font-style: italic; font-size: 14px; margin: 0;">Unity, Honesty &
                        Prosperity</p>
                </div>

            </div>
        </div>

        <div class="text-center">
            <h4 style="font-style:initial; color: #7b7a7a; font-size: 20px; margin-top: 10px; -webkit-print-color-adjust: exact; print-color-adjust: exact;"><b>Money
                    Receipt</b></h4>
        </div>


        <!-- Date, MR No, and Total Amount Section -->
        <div class="receipt-details" style="display: flex; justify-content: space-between;">
            <!-- Payment Type Section -->
            <div class="payment-type-section">
                <label>Cash <input type="checkbox" {{ $income->payment_type == 'Cash' ? 'checked' : '' }} /></label>
                <hr style="margin: 10px 0; border: 1px solid #000000;">
                <p>Cheque/DD/PO No.:
                    {{-- {{ $income->payment_type == 'Bank' ? $income->cheque_no ?? '' : '' }}</p> --}}
                    {{ $income->cheque_no ?? '' }}</p>
                <p>Drawn on: {{ $income->bank->name ?? '' }}
                </p>
                <p>Date: {{ $income->payment_date ?? '' }}</p>
            </div>


            <div style="text-align: left; font-size: 14px; margin-bottom: 10px;">
                <p style="margin-bottom: 25px;">Date:
                    {{ date('d.m.Y', strtotime($income->payment_date)) }}</p>
                <p style="margin-bottom: 30px;">MR No.: <strong>{{ $income->voucher_no }}</strong></p>
                <p>Total Amount:
                    <span style="border: 1px solid black; padding: 5px 10px; display: inline-block;">
                        <strong>{{ number_format($income->amount, 2) }} Tk</strong>
                    </span>
                </p>

            </div>

        </div>

        <!-- Received From and Account Details -->
        <div class="details-section-receipt">
            <p>Received with thanks from
                <span class="dotted-line"><b>{{ $income->client_name ?? '' }} ({{ $income->client_id ?? '' }})</b></span>
            </p>

            <p>On account of
                <span
                    class="dotted-line"><b>{{ $income->head->head_name  ?? '' }}</b></span>
            </p>

            <p>A sum of amount:
                @php
                    $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                    $amount = getBangladeshCurrency($income->amount);
                    //echo $f->format($model->amount);
                @endphp
                <span class="dotted-line"><b>{{ $amount }} Only</b></span>
            </p>
        </div>



        <!-- Footer Section with Signatures -->
        <div class="footer-section-receipt">
            <div class="footer-box-receipt">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p>Payer/Ref. Signature</p>
                </div>
            </div>
            <div class="footer-box-receipt">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p>Accounts Department</p>
                </div>
            </div>
            <div class="footer-box-receipt">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <p>Authorized Authority</p>
                </div>
            </div>
        </div>

        <div class="company-info-footer" style="background-color: #4b4b4b45; text-align: center; font-size: 12px; margin-top: 20px; padding: 10px; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
            <p><strong>Head Office: {{ $company_info->address }}</strong></p>
            <p><strong>Email: {{ $company_info->email }}, Phone: {{ $company_info->phone }}</strong></p>
            <p><strong>www.unitylandmark.com</strong></p>
        </div>
        

    </div>

</body>

</html>
