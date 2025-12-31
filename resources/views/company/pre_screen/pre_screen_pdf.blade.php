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
                    <img src="{{ asset('/images/header-logo.png') }}" alt="{{ env('SITE_TITLE') }}" />
                </td>
            </tr>
        </table>
        </htmlpageheader>
        

        <table class="table" border="0">
            <tr>
                <th class="text-center"><h1>Pre Screening Questions</h1></th>
            </tr>
            <tr>
                <th class="text-center"><h4>{{ $item->first_name }} {{ $item->last_name }}</h4></th>
            </tr>
            
        </table>
        <hr/>

        <table class="table" border="0">
            <tr>
                <td>
                    <strong>Q. Have you ever been convicted of fraud?</strong>
                    <br/>
                    <strong>A. </strong>{{ $item->convicted_in_fraud }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Q. Have you ever been convicted of a felony?</strong>
                    <br/>
                    <strong>A. </strong>{{ $item->convicted_in_felony }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Q. Have you filed for bankruptcy in the last 7 years?</strong>
                    <br/>
                    <strong>A. </strong>{{ $item->bankruptcy }}
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Q. Have you operated this business or a similar business under any other business name?</strong>
                    <br/>
                    <strong>A. </strong>{{ $item->other_business_name }} 
                    @if ($item->business_name_list != '')
                    <br/>
                    {{ $item->business_name_list }}
                    @endif
                </td>
            </tr>
        </table>





    </body>
    </html>
