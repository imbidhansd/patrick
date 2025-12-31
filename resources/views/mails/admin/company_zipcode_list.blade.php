Main zipcode: {{ $companyObj->main_zipcode }}, Radius: {{ $companyObj->mile_range }} Miles
<table width="100%" style="box-sizing: border-box;">
    @if (isset($company_zipcodes) && count($company_zipcodes) > 0)
        @php
            $zipcodes = array_chunk($company_zipcodes->toArray(), ceil(count($company_zipcodes) / 4));
        @endphp
        
        @foreach ($zipcodes AS $arr_item)
        <tr style="box-sizing: border-box;">
            @foreach ($arr_item AS $item)
            <td class="service_category_td">{{ $item['zip_code'] }}</td>
            @endforeach
        </tr>
        @endforeach
    @endif
</table>