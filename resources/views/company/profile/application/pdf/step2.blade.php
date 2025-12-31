@php
$company_licensing = $company->company_licensing;
$step_2_page_title = 'Licensing And Warranties';
    if ($company->trade_id == 2){// Professional
        $step_2_page_title = 'Licensing And Registration';
    }
@endphp

<table class="table table-bordered" border="0">
    <tr>
        <th colspan="2" class="text-center"><h3>{{ $step_2_page_title }}</h3></th>
    </tr>
    <tr>
        <th colspan="2">Business Entity Information</th>
    </tr>
    <tr>
        <th>Is your company legally registered within the state you operate?</th>
        <td>{{ ucfirst($company_licensing->legally_registered_within_state) }}</td>
    </tr>
    @if ($company_licensing->legally_registered_within_state == 'yes')
    <tr>
        <th>Do you have a copy of your state business registration on your computer available to
            upload?</th>
        <td>
            @if ($company_licensing->state_business_registeration == 'yes')
            {{ ucfirst($company_licensing->state_business_registeration) }}
            @else
            No - I will email/upload a copy to the approval department after submitting the application.
            @endif
        </td>
    </tr>
    @else
    <tr>
        <th>Do you have a copy of proof of ownership on your computer available to upload?</th>
        <td>
            @if ($company_licensing->proof_of_ownership == 'yes')
            {{ ucfirst($company_licensing->proof_of_ownership) }}
            @else
            No - I will email/upload a copy to the approval department after submitting the application.
            @endif
        </td>
    </tr>
    
    <tr>
        <th colspan="2">Income Tax Filling</th>
    </tr>
    <tr>
        <th>Have you file your business income taxes?</th>
        <td>{{ $company_licensing->income_tax_filling }}</td>
    </tr>

    @if ($company_licensing->income_tax_filling != 'Sole Proprietor')
    <tr>
        <th>Do you have a copy of your business articles of incorporation on your computer available to upload? (LLC or Corporation)</th>
        <td>
            @if ($company_licensing->articles_of_incorporation == 'yes')
            {{ ucfirst($company_licensing->articles_of_incorporation) }}
            @else
            No - I will email/upload a copy to the approval department after submitting the application.
            @endif
        </td>
    </tr>
    @endif
    @endif

    

    @if (!is_null($company_licensing->licensing_required))
        @php
        $licensing_required = json_decode($company_licensing->licensing_required);
        @endphp

        @if (count($licensing_required) > 0)
            <tr>
                <th colspan="2">Licensing Requirements</th>
            </tr>

            <tr>
                <th>Is State, county or city licensing required to perform the services you provide ?</th>
                <td>{!! str_replace('Country', 'County', implode('<br />', $licensing_required)) !!}</td>
            </tr>

            @foreach ($licensing_required AS $licensing_required_item)
                @if ($licensing_required_item == 'State licensing is required')
                    <tr>
                        <th>Is your company state licensed?</th>
                        <td>{{ ucfirst($company_licensing->state_licensed) }}</td>
                    </tr>
                    @if ($company_licensing->state_licensed == 'yes')
                    <tr>
                        <th>Do you have a copy of your state license on your computer available to upload?</th>
                        <td>
                            @if ($company_licensing->copy_state_licensed == 'yes')
                            {{ ucfirst($company_licensing->copy_state_licensed) }}
                            @else
                            No - I will email/upload a copy to the approval department after submitting the application.
                            @endif
                        </td>
                    </tr>
                    @endif
                @elseif ($licensing_required_item == 'Country licensing is required')
                    <tr>
                        <th>Is your company county licensed?</th>
                        <td>{{ ucfirst($company_licensing->country_licensed) }}</td>
                    </tr>
                    @if ($company_licensing->country_licensed == 'yes')
                    <tr>
                        <th>Do you have a copy of your county license on your computer available to upload?</th>
                        <td>
                            @if ($company_licensing->copy_country_licensed == 'yes')
                            {{ ucfirst($company_licensing->copy_country_licensed) }}
                            @else
                            No - I will email/upload a copy to the approval department after submitting the application.
                            @endif
                        </td>
                    </tr>
                    @endif
                @elseif ($licensing_required_item == 'City licensing is required')
                    <tr>
                        <th>Is your company city licensed?</th>
                        <td>{{ ucfirst($company_licensing->city_licensed) }}</td>
                    </tr>
                    @if ($company_licensing->city_licensed == 'yes')
                    <tr>
                        <th>Do you have a copy of your city license on your computer available to upload?</th>
                        <td>
                            @if ($company_licensing->copy_city_licensed == 'yes')
                            {{ ucfirst($company_licensing->copy_city_licensed) }}
                            @else
                            No - I will email/upload a copy to the approval department after submitting the application.
                            @endif
                        </td>
                    </tr>
                    @endif
                @endif
            @endforeach
        @endif
    @endif

    @if ($company->trade_id == 1)
        <tr>
            <th colspan="2">Company Contracts / Work Agreements / Warranty</th>
        </tr>

        <tr>
            <th>Do you provide your customers with a written warranty/guaranty for the services you provide?</th>
            <td>{{ ucfirst($company_licensing->provide_written_warrenty) }}</td>
        </tr>
        <tr>
            <th>Do you have a copy of your contracts with warranty information on your computer available for upload ?</th>
            <td>
                @if ($company_licensing->written_warrenty == 'yes')
                {{ ucfirst($company_licensing->written_warrenty) }}
                @else
                No - I will email/upload a copy to the approval department after submitting the application.
                @endif
            </td>
        </tr>


        <tr>
            <th colspan="2">Company subcontractor Agreement</th>
        </tr>
        <tr>
            <th>Do you subcontract entire jobs to other companies?</th>
            <td>{{ ucfirst($company_licensing->subcontract_with_other_companies) }}</td>
        </tr>
        @if ($company_licensing->subcontract_with_other_companies == 'yes')
        <tr>
            <th>Do you have a copy of your subcontractor agreement available to upload now?</th>
            <td>
                @if ($company_licensing->copy_of_subcontractor_agreement == 'yes')
                {{ ucfirst($company_licensing->copy_of_subcontractor_agreement) }}
                @else
                No - I will email/upload a copy to the approval department after submitting the application.
                @endif
            </td>
        </tr>
        @endif
    @endif
</table>
