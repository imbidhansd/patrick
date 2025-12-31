@php
$company_information = $company->company_information;
@endphp

<table class="table table-bordered" border="0">
    <tr>
        <th colspan="4" class="text-center"><h3>Company Information</h3></th>
    </tr>

    @for ($i=1;$i<=$company->number_of_owners;$i++)

    @php
    $full_name = "company_owner_".$i."_full_name";
    $email = "company_owner_".$i."_email";
    @endphp
    <tr>
        <th>Company Owner #{{ $i }} Full Name</th>
        <td>{{ $company_information->$full_name }}</td>
        <th>Company Owner #{{ $i }} Email</th>
        <td>{{ $company_information->$email }}</td>
    </tr>
    @endfor
    <tr>
        <th>Legal Company Name</th>
        <td>{{ $company_information->legal_company_name }}</td>
        <th>EIN (Employer Identification Number)</th>
        <td>{{ $company_information->ein }}</td>
    </tr>
    <tr>
        <th>Company Start date</th>
        <td>{{ $company_information->company_start_date }}</td>
        <th>Main Company Telephone</th>
        <td>{{ $company_information->main_company_telephone }}</td>
    </tr>
    <tr>
        <th>Company Website</th>
        <td>{{ $company_information->website }}</td>
        <th>Company Mailing Address</th>
        <td>{{ $company_information->mailing_address }}</td>
    </tr>
    <tr>
        <th>Suite</th>
        <td>{{ $company_information->suite }}</td>
        <th>City</th>
        <td>{{ $company_information->city }}</td>
    </tr>
    <tr>
        <th>State</th>
        <td>{{ $company_information->state->name }}</td>
        <th>County</th>
        <td>{{ $company_information->county }}</td>
    </tr>
    <tr>
        <th>Zipcode</th>
        <td>{{ $company_information->zipcode }}</td>
        <th>&nbsp;</th>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <th colspan="4">Internal Contact</th>
    </tr>
    <tr>
        <th>Full Name</th>
        <td>{{ $company_information->internal_contact_fullname }}</td>
        <th>
            Email <br />
            Phone
        </th>
        <td>
            {{ $company_information->internal_contact_email }} <br />
            {{ $company_information->internal_contact_phone }}
        </td>
    </tr>
</table>
