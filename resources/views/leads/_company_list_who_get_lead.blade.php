@if (isset($company_list) && count($company_list) > 0)
<table width="100%" style="box-sizing: border-box;">
    <tr style="box-sizing: border-box;">
        <th align="left" style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            Company Name
        </th>
        <th align="left" style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            Level
        </th>
        <th align="left" style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            Status
        </th>
    </tr>
    @foreach ($company_list AS $company_item)
    @if (!is_null($company_item->company_name_admin_list))
    <tr style="box-sizing: border-box;">
        <td style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            {{ $company_item->company_name_admin_list->company_name }}
        </td>
        <td style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            {{ $company_item->company_name_admin_list->membership_level->title }}
        </td>
        <td style="border-top-width: 1px; border-top-color: #eee; border-top-style: solid; padding: 5px 0px;">
            {{ $company_item->company_name_admin_list->status }}
        </td>
    </tr>
    @endif
    @endforeach
</table>
@endif