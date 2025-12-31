<html>
    <head>
        <!-- Title -->
        <title>Company Invoice | {{ env('SITE_TITLE') }}</title>
        <link href="{{ asset('themes/pdf-bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />

        <!-- Plugins js -->
        <script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
        <script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
        <script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

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

            .customer_reference_tbl td{
                margin: 10px;
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

    <h4 class="text-right">{{ $companyObj->company_name }}</h4>
    <h3 class="text-center"><i>REFERENCES</i></h3>

    <p>
        Please list 5 past references. 1 or 2 for each year for the past 3 to 5 years. <br />
        Please include customer name, phone number, and date work was performed.
    </p>

    <br />
    <br />
    
    
    <table class="table table-bordered text-center" border="0" cellpadding="15" cellspacing="15">
        <tr>
            <td>&nbsp;</td>
            <th class="text-center" style="padding: 15px;">Customer Name</th>
            <th class="text-center" style="padding: 15px;">Phone Number</th>
            <th class="text-center" style="padding: 15px;">Date work performed</th>
        </tr>
        @if (!is_null($companyObj->company_customer_references) && $companyObj->company_customer_references->ref_type == 'Customer References' && !is_null($companyObj->company_customer_references->customers))
            @php 
                $customer_reference_list = json_decode($companyObj->company_customer_references->customers);
            @endphp
            
            @foreach ($customer_reference_list AS $j => $customer_reference_item)
            <tr>
                <td>{{ $j }}</td>
                <td style="padding: 15px;">{{ $customer_reference_item->name }}</td>
                <td style="padding: 15px;">{{ $customer_reference_item->phone }}</td>
                <td style="padding: 15px;">{{ $customer_reference_item->date }}</td>
            </tr>
            @endforeach
        @else
            @for ($i=1;$i<=5;$i++)
            <tr>
                <td>{{ $i }}</td>
                <td style="padding: 15px;">&nbsp;</td>
                <td style="padding: 15px;">&nbsp;</td>
                <td style="padding: 15px;">&nbsp;</td>
            </tr>
            @endfor
        @endif
    </table>
</body>
</html>
