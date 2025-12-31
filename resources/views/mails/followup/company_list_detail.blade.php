@php
    $company_leads = \App\Models\Company::leftJoin('company_leads', 'companies.id', 'company_leads.company_id')->where([['companies.status', 'Active'], ['company_leads.lead_id', $lead_id]])->get();
@endphp

@if (count($company_leads) > 0)
<table align="center" border="0" width="100%" cellpadding="0" cellspacing="0">
    @foreach ($company_leads AS $company_item)
    <tr>
        <td align="center" width="30%" style="border-top: 1px solid #003E74; border-bottom: 1px solid #003E74; border-left: 1px solid #003E74; border-top-left-radius: 10px; border-bottom-left-radius: 10px;">
            @if (!is_null($company_item->company_logo))
            <a href="{{ route('company-page', ['company_slug' => $company_item->slug]) }}">
                <img src="{{ asset('/uploads/media/'.$company_item->company_logo->file_name) }}" style="width:100; max-width: 130px;" />
            </a>
            @else
            <h4><a href="{{ route('company-page', ['company_slug' => $company_item->slug]) }}">{{ $company_item->company_name }}</a></h4>
            @endif
        </td>
        <td align="center" width="30%" style="border-top: 1px solid #003E74; border-bottom: 1px solid #003E74;">
            <a href="{{ route('company-page', ['company_slug' => $company_item->slug]) }}">{{ $company_item->company_name }}</a> <br />

            @php
                $address_arr = [];
                $address_arr_line_1 = [];
                $address_arr_line_2 = [];

                if ($company_item->company_mailing_address != '') {
                    $address_arr[] = $company_item->company_mailing_address;
                    $address_arr_line_1[] = $company_item->company_mailing_address;
                }

                if ($company_item->suite != '') {
                    $address_arr[] = $company_item->suite;
                    $address_arr_line_1[] = $company_item->suite;
                }

                if ($company_item->city != '') {
                    $address_arr[] = $company_item->city;
                    $address_arr_line_2[] = $company_item->city;
                }

                if (!is_null($company_item->state) && $company_item->state->name != '') {
                    $address_arr[] = $company_item->state->name;
                    $address_arr_line_2[] = $company_item->state->name;
                }

                if ($company_item->zipcode != '') {
                    $address_arr[] = $company_item->zipcode;
                    $address_arr_line_2[] = $company_item->zipcode;
                }



                /*
                $tmp_add_arr = array_chunk($address_arr, 2);
                if (isset($tmp_add_arr[0])) {
                    echo implode(', ', $tmp_add_arr[0]);
                }
                if (isset($tmp_add_arr[1])) {
                    echo ',<br/>';
                    echo implode(', ', $tmp_add_arr[1]);
                }*/


                echo implode(', ',$address_arr_line_1);
                echo '<br/>';
                echo implode(', ',$address_arr_line_2);
            @endphp
        </td>
        <td align="center" width="30%" style="border-top: 1px solid #003E74; border-bottom: 1px solid #003E74; border-right: 1px solid #003E74; border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
            @php
                $average_ratings = \App\Models\Feedback::select(DB::raw('AVG(ratings) AS average_ratings'), DB::raw('COUNT(id) AS total_reviews'))->where('company_id', $company_item->id)->first();
                
                $ratings = ((!is_null($average_ratings)) ? ceil($average_ratings->average_ratings) : 0);
                $ceil_ratings = ceil($ratings);
                $floor_ratings = $ratings - floor($ratings);
            @endphp
            
            Average Rating<br />
            <p>
                @for ($i=1;$i<$ceil_ratings;$i++)
                <img src="{{ asset('images/star/full.png') }}" />
                @endfor
                
                @if ($floor_ratings != 0)
                <img src="{{ asset('images/star/half.png') }}" />
                @endif
                
                @for ($i=1;$i<= (5 - $ceil_ratings);$i++)
                <img src="{{ asset('images/star/blank.png') }}" />
                @endfor
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="3">&nbsp;</td>
    </tr>
    @endforeach
</table>
@endif