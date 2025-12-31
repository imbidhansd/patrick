@php
$company_insurance = $company->company_insurance;
    $trade_word = 'General';
    if ($company_item->trade_id == 2){
        $trade_word = 'Professional';
    }

@endphp

<table class="table table-bordered" border="0">
    <tr>
        <th colspan="2" class="text-center"><h3>Insurance Information</h3></th>
    </tr>
    <tr>
        <th>Do you carry both {{ $trade_word }} Liability Insurance and Worker Compensation Insurance?</th>
        <td>{{ $company_insurance->general_liability_insurance_and_worker_compensation_insurance }}</td>
    </tr>

    @if ($company_insurance->general_liability_insurance_and_worker_compensation_insurance == 'Yes')
        <tr>
            <th>Is your {{ $trade_word }} Liability Insurance and Workers Compensation Insurance both provided by the same insurance agent / agency ?</th>
            <td>{{ ucfirst($company_insurance->same_insurance_agent_agency) }}</td>
        </tr>

        @if($company_insurance->same_insurance_agent_agency == 'yes')
        <tr>
            <th colspan="2">{{ $trade_word }} Liability Insurance and Workers Compensation Insurance Agent/Agency</th>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $company_insurance->general_liability_insurance_agent_agency_name }}</td>
        </tr>
        <tr>
            <th>Phone Number</th>
            <td>{{ $company_insurance->general_liability_insurance_agent_agency_phone_number }}</td>
        </tr>
        @else
            <tr>
                <th colspan="2">{{ $trade_word }} Liability Insurance Agent/Agency</th>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $company_insurance->general_liability_insurance_agent_agency_name }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $company_insurance->general_liability_insurance_agent_agency_phone_number }}</td>
            </tr>
        
            @if ($company_item->trade_id == 1)
            <tr>
                <th colspan="2">
                    Workers Compensation Insurance Agent/Agency
                </th>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ $company_insurance->workers_compensation_insurance_agent_agency_name }}</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td>{{ $company_insurance->workers_compensation_insurance_agent_agency_phone_number }}</td>
            </tr>
            @endif
        @endif
    @else
    <tr>
        <th colspan="2">Insurance Agent/Agency</th>
    </tr>
    <tr>
        <th>Name</th>
        <td>{{ $company_insurance->general_liability_insurance_agent_agency_name }}</td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td>{{ $company_insurance->general_liability_insurance_agent_agency_phone_number }}</td>
    </tr>
    @endif

</table>
