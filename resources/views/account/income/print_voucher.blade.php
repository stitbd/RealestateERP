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

    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }




    body {
        font-family: Arial, sans-serif;
        background-color: #e6f7ff;
        margin: 0;
        padding: 0;
    }


    .with-margin {
        margin-bottom: -8px !important;
    }

    .with-margin-address {
        margin-bottom: -1px !important;
    }

    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-section .logo img {
        height: 100px;
        width: auto;
    }

    .mr-6 {
        margin-right: 16.4rem !important;
    }

    .company-info {
        text-align: center;
    }

    .company-info h1 {
        font-size: 3rem !important;
        margin: 0;
    }

    .company-info h5 {
        font-size: 16px;
        margin: 0;
    }
    .company-info h6 {
        font-size: 14px;
        margin: 0;
    }

    .voucher-show-info {
        text-align: center;
    }

    .voucher-show-info h3 {
        font-size: 18px;
        margin: 0;
    }

    .voucher-show-info h5 {
        font-size: 14px;
        margin: 0;
    }

    .voucher-info {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }

    .details-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .dotted-container {
        display: flex;
        align-items: center;
        font-size: 14px;
        margin: 5px 0;
    }

    .dotted-line-voucher {
        flex-grow: 1;
        border-bottom: 1px dotted #000;
        margin-left: 10px;
        padding: 0 5px;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .details-table th,
    .details-table td {
        border: 1px solid #919191;
        padding: 8px;
        text-align: left;
    }

    .details-table th {
        /* background-color: #161616; */
        color: #080808;
    }

    .amount-words {
        margin-top: 10px;
        font-size: 14px;
        font-weight: bold;
    }

    .footer-section {
        display: flex;
        justify-content: space-between;
        margin-top: 21px;
    }

    .footer-box {
        text-align: center;
        font-size: 14px;
        padding-top: 20px;
        /* border-top: 1px solid #000; */
        width: 18%;
    }
</style>
<style>
    @media print {

      .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6,
      .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
           float: left;               
      }

      .col-sm-12 {
           width: 100%;
      }

      .col-sm-11 {
           width: 91.66666666666666%;
      }

      .col-sm-10 {
           width: 83.33333333333334%;
      }

      .col-sm-9 {
            width: 75%;
      }

      .col-sm-8 {
            width: 66.66666666666666%;
      }

       .col-sm-7 {
            width: 58.333333333333336%;
       }

       .col-sm-6 {
            width: 50%;
       }

       .col-sm-5 {
            width: 41.66666666666667%;
       }

       .col-sm-4 {
            width: 33.33333333333333%;
       }

       .col-sm-3 {
            width: 25%;
       }

       .col-sm-2 {
              width: 16.666666666666664%;
       }

       .col-sm-1 {
              width: 8.333333333333332%;
        }            
}
</style>
<script>
    window.print();
    window.onafterprint = function () {
        window.close();
    };
</script>

 <body>      
    <div class="container mt-5">
        <!-- Header Section -->
        <div class="header-section">
            <div class="logo ml-5">
                <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}"
                    alt="Logo">
            </div>
            <div class="company-info">
                <h5 class="mr-6 with-margin"><strong>A sister concern of Unity Group</strong></h5>
                <h1>{{ $company_info->name }}</h1>
                <h5><strong>Unity, Honesty & Prosperity</strong></h5>
                <h6 class="with-margin-address">{{ $company_info->address }}</h6>
                <h6 class="with-margin-address">E-Mail: {{ $company_info->email }}, Phone: {{ $company_info->phone }} </h6>
            </div>
            <div class="voucher-show-info">
                <h5>Voucher No.:</h5>
                <div
                    style="border: 1px solid rgb(238, 233, 233); padding: 5px; border-radius: 8px; width: 135px; text-align: center;">
                    <h3 style="margin: 0; font-size: 16px;"><strong>{{ $model->voucher_no }}</strong>
                    </h3>
                </div>
                <div class="mt-1"
                    style="border: 1px solid rgb(230, 222, 222); padding: 5px; border-radius: 8px; width: 135px; text-align: center; font-size: 15px;">
                    <p style="margin: 0;"><strong>Credit Voucher</strong></p>
                </div>
            </div>

        </div>

        <div class="voucher-info">
            <div><strong><i class="fas fa-globe"
                        style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
            <div><input type="checkbox"
                {{ $detail_array->where('fund_id', 2)->isNotEmpty() ? 'checked' : '' }}>
                <strong>Cash</strong>
            </div>
            <div><input type="checkbox"
                {{ $detail_array->where('fund_id', 1)->isNotEmpty() ? 'checked' : '' }}><strong> Bank</strong>
            </div>
            <div>
                <input type="checkbox" {{ $model->adjustment_amount != null ? 'checked' : '' }}>
                <strong>Adjustment</strong>
            </div>
            <div><strong>Date: {{ date('d/m/Y', strtotime($model->payment_date)) }}</strong></div>
        </div>

        <!-- Head of Accounts and Received From Section -->
        <div class="details-section">
            <div class="dotted-container"><strong>Head of Accounts:</strong>
                <span
                    class="dotted-line-voucher">{{ $model->head->head_name ?? ''}}</span>
            </div>
            <div class="dotted-container"><strong>Received From:</strong><span
                    class="dotted-line-voucher">{{ $model->client_name ?? '' }}</span></div>
        </div>

        <!-- Table for Particulars and Amounts -->
        <table class="details-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Description</th>
                    <th>Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail_array as $index => $v_detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $v_detail->remarks ?? '' }}</strong></td>
                        <td>{{ number_format($v_detail->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>                     
            <tfoot>
                <tr>
                    @php
                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        $amount = getBangladeshCurrency($model->amount);
                        //echo $f->format($model->amount);
                    @endphp
                    <td colspan="2" class="amount-words">
                        <strong>In Words:</strong> {{ $amount }} Only
                    </td>
                    <td>{{ number_format($model->amount, 2) }}</td>
                </tr>

            </tfoot>
        </table>



        <!-- Footer Section -->
        <div class="footer-section">
            <div class="footer-box">Received By</div>
            <div class="footer-box">Accountant</div>
            <div class="footer-box">Director Admin</div>
            <div class="footer-box">Director Finance</div>
            <div class="footer-box">Managing Director / Chairman</div>
        </div>
    </div>

    <div class="container mt-6" style="margin-top: 150px;">
        <!-- Header Section -->
        <div class="header-section">
            <div class="logo ml-5">
                <img src="{{ asset('upload_images/company_logo/' . $company_info->logo) }}"
                    alt="Logo">
            </div>
            <div class="company-info">
                <h5 class="mr-6 with-margin"><strong>A sister concern of Unity Group</strong></h5>
                <h1>{{ $company_info->name }}</h1>
                <h5><strong>Unity, Honesty & Prosperity</strong></h5>
                <h6 class="with-margin-address">{{ $company_info->address }}</h6>
                <h6 class="with-margin-address">E-Mail: {{ $company_info->email }}, Phone: {{ $company_info->phone }} </h6>
            </div>
            <div class="voucher-show-info">
                <h5>Voucher No.:</h5>
                <div
                    style="border: 1px solid rgb(230, 224, 224); padding: 5px; border-radius: 8px; width: 135px; text-align: center;">
                    <h3 style="margin: 0; font-size: 16px;"><strong>{{ $model->voucher_no }}</strong>
                    </h3>
                </div>
                <div class="mt-1"
                    style="border: 1px solid rgb(231, 227, 227); padding: 5px; border-radius: 8px; width: 135px; text-align: center; font-size: 15px;">
                    <p style="margin: 0;"><strong>Credit Voucher</strong></p>
                </div>
            </div>

        </div>

        <div class="voucher-info">
            <div><strong><i class="fas fa-globe"
                        style="margin-right: 5px;"></i>www.unitylandmark.com</strong></div>
            <div><input type="checkbox" {{ $detail_array->where('fund_id', 2)->isNotEmpty() ? 'checked' : '' }}>
                <strong>Cash</strong>
            </div>
            <div> <input type="checkbox" {{ $detail_array->where('fund_id', 1)->isNotEmpty() ? 'checked' : '' }}>
                <strong>Bank</strong>
            </div>
            <div>
                <input type="checkbox" {{ $model->adjustment_amount != null ? 'checked' : '' }}>
                <strong>Adjustment</strong>
            </div>
            <div><strong>Date: {{ date('d/m/Y', strtotime($model->payment_date)) }}</strong></div>
        </div>

        <!-- Head of Accounts and Received From Section -->
        <div class="details-section">
            <div class="dotted-container"><strong>Head of Accounts:</strong>
                <span
                    class="dotted-line-voucher">{{ $model->head->head_name ?? '' }}</span>
            </div>
            <div class="dotted-container"><strong>Received From:</strong><span
                    class="dotted-line-voucher">{{ $model->client_name ?? '' }}</span></div>
        </div>

        <!-- Table for Particulars and Amounts -->
        <table class="details-table">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Description</th>
                    <th>Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($detail_array as $index => $v_detail)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $v_detail->remarks ?? '' }}</strong></td>
                        <td>{{ number_format($v_detail->amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>               
            <tfoot>
                <tr>
                    @php
                        $f = new NumberFormatter('en', NumberFormatter::SPELLOUT);
                        $amount = getBangladeshCurrency($model->amount);
                        //echo $f->format($model->amount);
                    @endphp
                    <td colspan="2" class="amount-words">
                        <strong>In Words:</strong> {{ $amount }} Only
                    </td>
                    <td>{{ number_format($model->amount, 2) }}</td>
                </tr>

            </tfoot>
        </table>



        <!-- Footer Section -->
        <div class="footer-section">
            <div class="footer-box">Received By</div>
            <div class="footer-box">Accountant</div>
            <div class="footer-box">Director Admin</div>
            <div class="footer-box">Director Finance</div>
            <div class="footer-box">Managing Director / Chairman</div>
        </div>
    </div>
 
</body>
</html>
