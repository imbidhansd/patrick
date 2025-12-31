<html>
    <head>
        <!-- Title -->
        <title>Company Invoice | {{ env('SITE_TITLE') }}</title>
        <link href="{{ asset('themes/pdf-bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css"
              id="bootstrap-stylesheet" />

        <style type="text/css">
            body{
                font-size: 12px;
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
                border-bottom: 1px solid #ccc;
                font-size: 11px !important;
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
        <p style="font-size: 11px; text-align: right;">Page No:{PAGENO}</p>
        <table class="table" border="0">
            <tr>
                <td colspan="2" class="text-center">
                    <img src="https://s3.us-west-2.amazonaws.com/images.trustpatrick.com/trust_patrick_rn_logo_v1.png"  alt="{{ env('SITE_TITLE') }}" />
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <table class="table" border="0">
        <tr>
            <th class="text-center" colspan="2"><h2>INVOICE</h2></th>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
            <td class="text-left">
                <span class="bold_font">Phase One Digital Marketing</span> <br />
                DBA Trust Patrick Referral Network<br />
                325 Adams Drive<br />
                Ste 325 PMB 523<br />
                Weatherford, TX 76086<br />
            </td>
            <td class="text-right valigntop">
                <span class="bold_font">Invoice Date: </span> {{ $company_invoice->invoice_date }} <br />
                <span class="bold_font">Invoice : </span> #{{ $company_invoice->invoice_id }}<br/>
                @if ($company_invoice->transaction_id != '')
                <span class="bold_font">Payment Status : </span> Paid<br/>
                <span class="bold_font">Trasaction ID : </span> {{ $company_invoice->transaction_id }}<br/>
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        @if (!is_null($company_invoice->ship_address) || !is_null($company_invoice->bill_address))
        <tr>
            <td class="text-left">
                @if (!is_null($company_invoice->ship_address))
                <span class="bold_font">Shipping Address:</span> <br />
                {{ $company_invoice->ship_address->company_name }} <br />
                {{ $company_invoice->ship_address->first_name }} {{ $company_invoice->ship_address->last_name }} <br />
                {{ $company_invoice->ship_address->mailing_address }} <br />
                {{ $company_invoice->ship_address->city }}, {{ $company_invoice->ship_address->state->name }} - {{ $company_invoice->ship_address->zipcode }}<br/>
                {{ $company_invoice->ship_address->phone }}
                @endif
            </td>
            <td class="text-left">
                @if (!is_null($company_invoice->bill_address))
                <span class="bold_font">Billing Address:</span> <br />
                {{ $company_invoice->bill_address->company_name }} <br />
                {{ $company_invoice->bill_address->first_name }} {{ $company_invoice->bill_address->last_name }} <br />
                {{ $company_invoice->bill_address->mailing_address }} <br />
                {{ $company_invoice->bill_address->city }}, {{ $company_invoice->bill_address->state->name }} - {{ $company_invoice->bill_address->zipcode }}<br/>
                {{ $company_invoice->bill_address->phone }}
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        @endif
        <tr>
            <td colspan="2" style="text-align: center"><strong>Invoice For:</strong> {{ $company_invoice->invoice_for }}</td>
        </tr>
    </table>


    <table style="margin:0px" class="table invoice_item_table" border="0">
        <thead>
            <tr>
                <th style="width: 25% !important; border-right: none !important" class="table_head">Item</th>
                <th style="width: 50% !important; border-right: none !important" class="table_head">Description</th>
                <th style="width: 10% !important; border-right: none !important" class="table_head text-right">Quantity</th>
                <th style="width: 15% !important;" class="table_head text-right">Amount</th>
            </tr>
        </thead>
    </table>
    @php $final_total = 0; @endphp
    @forelse($company_invoice->company_invoice_item AS $company_invoice_item)
    @php $final_total += $company_invoice_item->total; @endphp
    <table  style="margin:0px" class="table invoice_item_table" border="0">
        <tbody>
            <tr>
                <td style="width: 25% !important; border-right: none !important" class="text-left valigntop">{{ $company_invoice_item->title }}</td>
                <td style="width: 50% !important; border-right: none !important" class="text-left">
                    {!! $company_invoice_item->description !!}
                </td>


                @if ($company_invoice->company->membership_level->charge_type == 'ppl_price' && is_null($company_invoice_item->total))
                    @if ($company_invoice->invoice_type == 'PPL Lead Invoice')
                    <td style="width: 10% !important; border-right: none !important" class="text-right valigntop">{{ $company_invoice_item->qty }}</td>

                    <td style="width: 15% !important;" class="text-right valigntop">$0.00</td>
                    @else
                    <td style="width: 25% !important;" class="text-right valigntop" colspan="2">Pay Per Lead Listing <br /> (Monthly Ongoing)</td>
                    @endif
                @else
                <td style="width: 10% !important; border-right: none !important" class="text-right valigntop">{{ $company_invoice_item->qty }}</td>

                <td style="width: 15% !important;" class="text-right valigntop">${{ number_format($company_invoice_item->total, 2) }}</td>
                @endif
            </tr>
        </tbody>
    </table>
    @empty
    @endforelse
    <table  style="margin:0px" class="table invoice_item_table" border="0">
        <tr>
            <td class="text-right" style="width: 85% !important; border-right: none !important" colspan="3"><strong>Total Due</strong></td>
            <td class="text-right" style="width: 15% !important;"><strong>{{ '$'.number_format($final_total, 2) }}</strong></td>
        </tr>
    </table>



    <table class="table" border="0">
        <tr>
            <th>Thank you for your business!</th>
        </tr>
    </table>
</body>
</html>
