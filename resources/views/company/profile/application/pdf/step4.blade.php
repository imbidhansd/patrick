@php
$company_customer_references = $company->company_customer_references;
@endphp

<table class="table table-bordered" border="0">
    <tr>
        <th class="text-center"><h3>Reference And Professional Affiliations</h3></th>
    </tr>
    <tr>
        <th>{{ $company_customer_references->ref_type }}</th>
    </tr>

    @if ($company_customer_references->ref_type == 'Professional Affiliations')
        @php
            $professional_affiliations = json_decode($company_customer_references->professional_affiliations);
        @endphp

        @if (count($professional_affiliations) > 0)
            @foreach ($professional_affiliations AS $affiliation_item)
            <tr>
                <td>{{ $affiliation_item }}</td>
            </tr>
            @endforeach

        @elseif (!is_null($company_customer_references->other_professional_affiliations))
            <tr>
                <td>{{ $company_customer_references->other_professional_affiliations }}</td>
            </tr>
        @endif
    @elseif ($company_customer_references->ref_type == 'Customer References')
        @php
            $customers = json_decode($company_customer_references->customers, true);
        @endphp

        @if (count($customers) > 0)
            @foreach($customers AS $customer_item)
            <tr>
                <td>
                    <strong>Name: </strong> {{ $customer_item['name'] }} <br />
                    <strong>Phone: </strong> {{ $customer_item['phone'] }} <br />
                    <strong>Date: </strong> {{ $customer_item['date'] }} <br />
                </td>
            </tr>
            @endforeach
        @endif
    @endif
</table>
