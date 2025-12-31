<html>
    <head>
        <!-- Title -->
        <title>Company Invoice | {{ env('SITE_TITLE') }}</title>
        <link href="{{ asset('themes/pdf-bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
              id="bootstrap-stylesheet" />

        <style type="text/css">
            body{
                font-size: 13px;
            }
            .bold_font {
                font-weight: bold;
            }
            .table tr td,
            .table tr th {
                padding: 5px;
            }

            .text-right{
                text-align: right;
            }
            .text-left{
                text-align: left;
            }
            .text-center{
                text-align: center;
            }

            .valigntop{
                vertical-align: text-top;
            }

            .invoice_item_table td,
            .invoice_item_table th{
                border: 1px solid #ccc;
            }
            .invoice_item_table .table_head{
                background: #0D3E73;
                color: #fff;
                padding: 12px;
                border: 0px;
            }

            .list_style{
                list-style: none;
            }
            .category_type{
                color: #0D3E73;
            }

            @page {
                header: page-header;
                footer: page-footer;
            }
        </style>
    </head>
    <body>
    <htmlpageheader name="page-header">
        <p style="font-size: 12px; text-align: right;">Page No:{PAGENO}</p>
        <table class="table" border="0">
            <tr>
                <td colspan="2" class="text-center">
                    <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt="{{ env('SITE_TITLE') }}" />
                </td>
            </tr>
        </table>
    </htmlpageheader>


    <h3><i>FAX/EMAIL</i></h3>
    <br />
    <p>
        @php
        $trade_word = 'General';
        if ($company->trade_id == 2){
        $trade_word = 'Professional';
        }

        @endphp

        @if (isset($insurance_type) && $insurance_type == 'worker_compensation')
        Subject: Proof of Workers Compensation Insurance
        @elseif ((isset($insurance_type) && $insurance_type == 'general_liability') || ($company->trade_id == 2))
        Subject: Proof of {{ $trade_word }} Liability Insurance
        @else
        Subject: Proof of {{ $trade_word }} Liability/Workers Compensation Insurance
        @endif

        <br />
        <br />
        Date: <i>{{ now()->format(env('DB_DATE_FORMAT')) }}</i>
        <br />
        <br />
        @if (isset($insurance_type) && $insurance_type == 'worker_compensation')
        To: {{ $company->company_insurance->workers_compensation_insurance_agent_agency_name }}
        @else
        To: {{ $company->company_insurance->general_liability_insurance_agent_agency_name }}
        @endif
        <br />
        <br />
        From: {{ $company->company_name }}
        <br />
        <br />
        <br />
        @if (isset($insurance_type) && $insurance_type == 'worker_compensation')
        Please add “TrustPatrick.com as a certificate holder to the Workers Compensation Insurance policy for {{ $company->company_name }}
        @elseif ((isset($insurance_type) && $insurance_type == 'general_liability') || ($company->trade_id == 2))
        Please add “TrustPatrick.com as a certificate holder to the {{ $trade_word }} Liability Insurance policy for {{ $company->company_name }}
        @else
        Please add “TrustPatrick.com as a certificate holder to the {{ $trade_word }} Liability/Workers Compensation Insurance policy for {{ $company->company_name }}
        @endif

        <br />
        <br />
        <span class="bold_font">Certificate Holder Info:</span> <br />
        Patrick's Pro Network, LLC<br />
        325 Adams Drive<br />
        Ste 325 PMB 523<br />
        Weatherford, TX 76086<br />
        <br />
        <br />
        Once completed, please email certificate to insurance@trustpatrick.com as soon as
        possible.
        <br />
        <br />
        Thank you!
        <br />
        <br />

        <span class="bold_font">Phase One Digital Marketing</span> <br />
        <?php /* 9233 Park Meadows Dr Suite 209 <br />
          Lone Tree, CO 80214 <br />
          Phone – 720-445-4400 */ ?>
    </p>
</body>
</html>
