@if (isset($company_list) && count($company_list) > 0)
<table width="100%" style="box-sizing: border-box;">
    <tr style="box-sizing: border-box;">
        <th align="left" style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            Company Name
        </th>
        <th align="left" style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            Level Status
        </th>
        <th align="left" style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            Reason
        </th>
    </tr>
    @foreach ($company_list AS $company_item)
    <tr style="box-sizing: border-box;">
        <td style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            {{ $company_item->company_name }}
        </td>
        <td style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            Level: {{ $company_item->membership_level_title }} <br />
            Status: {{ $company_item->membership_status }}
        </td>
        <td style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            @if ($company_item->leads_status == 'inactive')
            Lead status Paused
            @elseif ($company_item->membership_status != 'Active')
            Not active Company
            @elseif ($company_item->membership_status == 'Active' && $company_item->charge_type == 'ppl_price')
            Monthly budget exceed
            @endif
        </td>
    </tr>
    @endforeach
</table>
@endif