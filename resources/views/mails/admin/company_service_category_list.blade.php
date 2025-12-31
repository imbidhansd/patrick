@if (isset($service_categories) && count($service_categories) > 0)
    @php $service_category_list = ''; @endphp
    @foreach ($service_categories AS $service_category_item)
        @php $service_category_list .= $service_category_item->service_category->title.', '; @endphp
    @endforeach
    
    {{ rtrim($service_category_list, ', ') }}

    <?php /* @php
        $service_category_list = array_chunk($service_categories->toArray(), ceil(count($service_categories) / 4));
    @endphp
    
    <table width="100%" style="box-sizing: border-box;">
        @foreach ($service_category_list AS $arr_item)
        <tr style="box-sizing: border-box;">
            @foreach ($arr_item AS $item)
            <td class="service_category_td">{{ $item['service_category']['title'] }}</td>
            @endforeach
        </tr>
        @endforeach
    </table> */ ?>
@endif