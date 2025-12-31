@php
$company_information = $company->company_information;
@endphp

<table class="table" border="0">
    <tr>
        <th class="text-center" colspan="2"><h1>Company Application</h1></th>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
    <tr>
        <td class="text-left">
            <span class="bold_font">Patrick's Pro Network, LLC<br />
            30 N Gould St<br />
            Sheridan, WY 82801<br />
        </td>
        <td class="text-right valigntop">
            <span class="bold_font">Company Name: </span> {{ $company->company_name }} <br />
            <span class="bold_font">Company Legal Name: </span> {{ $company_information->legal_company_name }} <br />
            <span class="bold_font">Company Start Date: </span> {{ $company_information->company_start_date }} <br />
        </td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
    </tr>
</table>