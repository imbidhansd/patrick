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
    </style>
</head>
	<body>
		<table class="table" border="0">
			<tr>
				<td colspan="2" class="text-center">
					<img src="{{ asset('/') }}images/header-logo.png" alt="{{ env('SITE_TITLE') }}" />
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<th class="text-center" colspan="2"><h1>INVOICE</h1></th>
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
					<span class="bold_font">Invoice : </span> #{{ $company_invoice->invoice_id }}
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="text-left">
					<span class="bold_font">To:</span> <br />
					{{ $company_invoice->company->company_name }} <br />
					{{ $company_invoice->company->company_mailing_address }} <br />
					{{ $company_invoice->company->company_mailing_address }} <br />
					{{ $company_invoice->company->city }}, {{ $company_invoice->company->state->name }} - {{ $company_invoice->company->zipcode }}
				</td>
				<td class="text-right valigntop">
					<span class="bold_font">For:</span> {{ $company_invoice->invoice_for }}
				</td>
			</tr>
		</table>
		

		<table class="table invoice_item_table" border="0">
			<thead>
				<tr>
					<th class="table_head">Item</th>
					<th class="table_head">Description</th>
					<th class="table_head text-right">Quantity</th>
					<th class="table_head text-right">Amount</th>
				</tr>
			</thead>
			<tbody>
				@php $final_total = 0; @endphp
				@forelse($company_invoice->company_invoice_item AS $company_invoice_item)
				@php $final_total += $company_invoice_item->total; @endphp
				<tr>
					<td class="text-left valigntop">{{ $company_invoice_item->title }}</td>
					<td class="text-left">{!! $company_invoice_item->description !!}</td>
					<td class="text-right valigntop">{{ $company_invoice_item->qty }}</td>
					<td class="text-right valigntop">£{{ number_format($company_invoice_item->total, 2) }}</td>
				</tr>
				@empty
				@endforelse
			</tbody>
			<tfoot>
				<tr>
					<td class="text-right" colspan="3">Total Due</td>
					<td class="text-right">£{{ number_format($final_total, 2) }}</td>
				</tr>
			</tfoot>
		</table>

		<table class="table" border="0">
			<tr>
				<td>Please make all checks payable to TrustPatrick.com</td>
			</tr>
			<tr>
				<th>Thank you for your business!</th>
			</tr>
		</table>
	</body>
</html>
