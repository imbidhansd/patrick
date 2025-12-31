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
        @include('company.profile.application.pdf.header')

        @include('company.profile.application.pdf.step1')
        <?php /* <pagebreak></pagebreak> */ ?>
        @include('company.profile.application.pdf.step2')
        <?php /* <pagebreak></pagebreak> */ ?>
        @include('company.profile.application.pdf.step3')
        <?php /* <pagebreak></pagebreak> */ ?>
        @include('company.profile.application.pdf.step4')
        <?php /* <pagebreak></pagebreak> */ ?>
        @include('company.profile.application.pdf.step5')
        <?php /* <pagebreak></pagebreak> */ ?>
        @include('company.profile.application.pdf.step6')

        <?php /* <table class="table" border="0">
            <tr>
                <th>Thank you for your business!</th>
            </tr>
        </table> */ ?>
        
        <pagebreak></pagebreak>
        <h4>{{ $terms_page->title }}</h4>
        {!! $terms_page->content !!}
        <?php /* <table class="table" border="0">
            <tr>
                <th>{{ $terms_page->title }}</th>
            </tr>
            <tr>
                <td>
                    {!! $terms_page->content !!}
                </td>
            </tr>
        </table> */ ?>
    </body>
</html>
