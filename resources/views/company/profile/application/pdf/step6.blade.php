@php
$company_listing_agreement = $company->company_listing_agreement;
@endphp
<table class="table table-bordered" border="0">
    <tr>
        <th class="text-center">
            <h3>Listing Agreement</h3>
        </th>
    </tr>
    <tr>
        <td class="text-left">
            <p>This agreement is made and entered into by and between Patrick's Pro Network, LLC, a Wyoming company (referred to in this agreement as "TrustPatrick.com") and for the purpose of
                listing <b>{{ $company->company_name }}</b> (referred to in this agreement as “Company”) on any of the
                websites
                owned and operated by TrustPatrick.com, and/or any additional products and or services
            purchased from TrustPatrick.com.</p>
            <p>Company understands they are purchasing an <strong>{{ $company_item->membership_level->title  }}</strong>
                for the main service area zip code
                of <b>{{ $company->main_zipcode }}</b> and all zip codes within a {{ $company->mile_range }} mile radius regardless of how those
                boundaries
                are
            identified. This is the service area that will be used on the internet.</p>
            <p><b>{{ $company->company_name }}</b> refers to the above company name and, if required, is licensed by a
                city,
                county or county in {{ $company_item->county }} or by the State of {{ $company_item->state->name }}</p>
                <p>and hereby certifies license and insurance is active and valid at the time of this agreement.
                    Company is established and holds all local and state permits and licenses required, if
                required, for the formal performance of:</p>
            </td>
        </tr>

        @if ($company_item->package_id > 0 && $company_item->package->addendum != '')
        <tr>
            <td class="text-left">
                <strong>Addendum!</strong> <br/>
                {!! $company_item->package->addendum !!}
            </td>
        </tr>
        @endif
    </table>

    @include('company.profile.application.pdf.service_categories')
    <table class="table table-bordered" border="0">
        <tr>
            <th>I hereby state that the information above is true, accurate and complete and relevant information has not been omitted</th>
            <td>{{ ucfirst($company_listing_agreement->true_information) }}</td>
        </tr>

        <tr>
            <th>I have read and agree to the Terms Of Use</th>
            <td>{{ ucfirst($company_listing_agreement->terms_of_use) }}</td>
        </tr>
    </table>
