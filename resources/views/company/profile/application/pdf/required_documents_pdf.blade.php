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

    <h4 class="text-right">{{ $companyObj->company_name }}</h4>
    <h3 class="text-center"><i>REQUIRED DOCUMENTS LIST</i></h3>

    <br />
    <p>As indicated in your application, please gather these documents and upload them into the "Company Documents" Section of your dashboard.</p>

    @if (isset($required_document_arr) && count($required_document_arr) > 0)
    <ol style="padding-left: 20px;">
        @foreach ($required_document_arr AS $document_item)
        <li>{{ $document_item }}</li>
        @endforeach
    </ol>
    @endif

    <br />
    <p>Please also be sure to fax or email the certificate holder request form to your insurance as soon as possible to avoid approval delays.</p>
</body>
</html>
