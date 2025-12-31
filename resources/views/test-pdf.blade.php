<html>
<head>
    <!-- Title -->
    <title>Company Invoice | {{ env('SITE_TITLE') }}</title>
    <link href="{{ asset('themes/pdf-bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
    id="bootstrap-stylesheet" />

    <style type="text/css">
        body{
            font-size: 13px;
            font-family: 'signature_font';
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
        .signature {
            font-family: 'signature_font';
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
                    <img src="{{ asset('/images/logo.png') }}" alt="{{ env('SITE_TITLE') }}" />
                </td>
            </tr>
        </table>
        </htmlpageheader>
        
        <p class="signature">Ajay D Makwanan</p>
        <h1 class="signature">Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <h1>Hello How are you?</h1>
        <table class="table" border="0">
            <tr>
                <th>Thank you for your business!</th>
            </tr>
        </table>
    </body>
    </html>
