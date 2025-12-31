@if (!is_null($company_customer_references))
<h3>{{ $company_customer_references->ref_type }}</h3>
@if ($company_customer_references->ref_type == 'Professional Affiliations')
@php
$references = json_decode($company_customer_references->professional_affiliations);
@endphp
<table class="table table-bordered">
    @foreach ($references AS $reference_item)
    
    @if ($reference_item != 'Other: (Please List)')
    <tr>
        <td>{{ $reference_item }}</td>
    </tr>
    @else
    <tr>
        <td>
            {!! $company_customer_references->other_professional_affiliations !!}
        </td>
    </tr>
    @endif
    @endforeach
</table>
@elseif ($company_customer_references->ref_type == 'Customer References')
@php
$references = json_decode($company_customer_references->customers);
@endphp
<table class="table table-bordered text-center">
    <thead>
        <tr>
            <th>Customer Name</th>
            <th>Customer Phone</th>
            <th>Date Work Performed</th>
        </tr>
    </thead>
    @foreach ($references AS $reference_item)
    <tr>
        <td>{{ $reference_item->name }}</td>
        <td>{{ $reference_item->phone }}</td>
        <td>{{ $reference_item->date }}</td>
    </tr>
    @endforeach
</table>
@endif
@endif