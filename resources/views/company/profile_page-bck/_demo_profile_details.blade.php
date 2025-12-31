
<div class="text-center">
    <div class="row">
        <div class="col-md-4">
            @if (!is_null($companyObj->company_logo) && !is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_logo == 'in process')
            <div class="company_logo p-xl-5 profile_logo_border font-bold font-20">
                {{ $companyObj->company_name }}
            </div>
            @elseif (!is_null($companyObj->company_logo))
            <div class="company_logo">
                <img src="{{ asset('/uploads/media/'.$companyObj->company_logo->file_name) }}" />
            </div>

            @else
            <div class="company_logo p-xl-5 profile_logo_border font-bold font-20">
                {{ $companyObj->company_name }}
            </div>
            @endif
        </div>
        <div class="col-md-4">
            @if (!Auth::guard('company_user')->check() || (Auth::guard('company_user')->check() && Auth::guard('company_user')->user()->company_id != $companyObj->id))
            @php
            $session_id = Session::getId();
            $get_views = \App\Models\CompanyContactView::where([
            ['company_id', $companyObj->id],
            ['session_id', $session_id]
            ])->latest()->first();
            @endphp

            @if (!is_null($get_views))
            @php 
            $show = "";
            $hide = "display:none;";
            @endphp
            @elseif ($companyObj->status != 'Active')
            @php
            $show = "display:none;";
            $hide = "";
            @endphp
            @else
            @php
            $show = "display:none;";
            $hide = "";
            @endphp
            @endif
            @else
            @php
            $show = "";
            $hide = "display:none;";
            @endphp
            @endif


            @php
            if ($companyObj->status == 'Active'):
            $show = "";
            $hide = "display:none;";
            endif;
            @endphp

            <div class="company_address" id="company_address_display_btn" style="{{ $hide }}">
                <a href="javascript:;" class="btn btn-primary btn-sm mt-5 mb-5" data-toggle="modal" data-target="#displayContactInfoModal">View Contact Information</a>
            </div>

            <div class="company_address" id="company_address_display" style="{{ $show }}">
                <p class="font-16 text-theme_color font-bold">
                    {{ $companyObj->main_company_telephone }}
                    @if (!is_null($companyObj->secondary_telephone))
                    <br />
                    <small>{{ $companyObj->secondary_telephone }}</small>
                    @endif
                </p>
                <span>
                    @php

                    $address_arr = [];

                    if ($companyObj->company_mailing_address != '') {
                    $address_arr[] = $companyObj->company_mailing_address;
                    }

                    if ($companyObj->suite != '') {
                    $address_arr[] = $companyObj->suite;
                    }

                    if ($companyObj->city != '') {
                    $address_arr[] = $companyObj->city;
                    }

                    if (!is_null($companyObj->state) && $companyObj->state->name != '') {
                    $address_arr[] = $companyObj->state->name;
                    }

                    if ($companyObj->zipcode != '') {
                    $address_arr[] = $companyObj->zipcode;
                    }

                    $tmp_add_arr = array_chunk($address_arr, 2);
                    if (isset($tmp_add_arr[0])) {
                    echo implode(', ', $tmp_add_arr[0]);
                    }
                    if (isset($tmp_add_arr[1])) {
                    echo ',<br/>';
                    echo implode(', ', $tmp_add_arr[1]);
                    }
                    @endphp

                    @if (!is_null($companyObj->company_website))
                    @php
                    $website_link = $url = $companyObj->company_website;
                    if (substr($url, 0, '7') === 'http://'){
                    $url = str_replace('http://', '', $url);
                    } else if (substr($url, 0, '8') === 'https://'){
                    $url = str_replace('https://', '', $url);
                    }

                    if (substr($url, 0, '4') !== 'www.'){
                    $url = 'www.'.$url;
                    }


                    if ($str = parse_url($companyObj->company_website)){
                    if (!isset($str['scheme'])){
                    $website_link = 'http://'.$companyObj->company_website;
                    }
                    }
                    @endphp


                    <br />
                    @if ($companyObj->membership_level->paid_members == 'yes')
                    <a href="{{ $website_link }}" target="_blank">{{ $url }}</a>
                    @else
                    {{ $url }}
                    @endif
                    @endif

                    <?php /* <br />
                      {{ ((!is_null($company_super_admin)) ? $company_super_admin->email : '') }} */ ?>
                </span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="rattings">
                <h5>Average Rating <a href="javascript:;" data-href="#customer_reviews" class="scroll_btn review_btn font-weight-normal">{{ ((!is_null($average_ratings)) ? $average_ratings->total_reviews : 0) }}</a></h5>
                <div class="starHalf"></div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="official_recommend">
                <?php /* @if ($companyObj->status == 'Active')
                <span>Official Recommended</span>
                <br />
                @endif */ ?>

                @if (!is_null($companyObj->registered_date))
                <span>Company Since: {{ $companyObj->registered_date }} </span>
                <br />
                <br />
                @endif

                @if (!is_null($companyObj->registered_date))
                <span>Most Recent Background Check: {{ $companyObj->registered_date }} </span>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>

<div class="profile_sub_menu">

    <ul class="nav justify-content-center">
        <li class="nav-item">
            <a href="javascript:;" data-href="#customer_reviews" class="nav-link scroll_btn review_btn">Customer Reviews</a>
        </li>
        <li class="nav-item">
            <a href="javascript:;" data-href="#customer_reviews" class="nav-link scroll_btn complaint_btn">Customer Complaints</a>
        </li>
        <li class="nav-item">
            <a href="javascript:;" data-href="#service_provided" class="nav-link scroll_btn">Service Provided</a>
        </li>
        <li class="nav-item">
            <a href="javascript:;" data-href="#service_areas" class="nav-link scroll_btn">Service Areas</a>
        </li>
        <li class="nav-item">
            <a href="javascript:;" data-href="javascript:;" class="nav-link scroll_btn" id="create_request">Contact {{ $companyObj->showCompanyName() }}'s</a>
        </li>
    </ul>




    <?php /* <ul class="pl-0 mb-0 list-unstyled">
      <li class="float-md-left mr-md-4 float-sm-none font-15">
      <a href="#customer_reviews" class="scroll_btn review_btn">Customer Reviews ({{ ((!is_null($average_ratings)) ? $average_ratings->total_reviews : 0) }})</a>
      </li>
      <li class="float-md-left mr-md-4 float-sm-none font-15">
      <a href="#customer_reviews" class="scroll_btn complaint_btn">Customer Complaints ({{ $total_complaints }})</a>
      </li>
      <li class="float-md-left mr-md-4 float-sm-none font-15">
      <a href="#service_provided" class="scroll_btn">Service Provided</a>
      </li>
      <li class="float-md-left mr-md-4 float-sm-none font-15">
      <a href="#service_areas" class="scroll_btn">Service Areas</a>
      </li>
      <li class="float-md-left mr-md-4 float-sm-none font-15">
      <a href="javascript:;" id="create_request">Contact {{ $companyObj->company_name }}'s</a>
      </li>
      </ul> */ ?>
</div>


<div class="modal fade" id="displayContactInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">
                    {{ $companyObj->company_name }} is not an Official TrustPatrick.com recommended company and is not endorsed by TrustPatrick.com in any way. Our Privacy Policy and Terms of Service do not apply. By continuing, you understand and agree.
                </p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light display_contact_info_btn">I Understand And Agree</button>
            </div>
        </div>
    </div>
</div>



@push('page_script')
<script type="text/javascript">
    $(function () {
        $(".display_contact_info_btn").on("click", function () {
            $.ajax({
                url: '{{ url("company-profile/view-contact-information-session") }}',
                type: 'POST',
                data: {'company_id': '{{ $companyObj->id }}', '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    $("#displayContactInfoModal").modal("hide");

                    $("#company_address_display_btn").hide();
                    $("#company_address_display").show();
                }
            });
        });


        $('.complaint_btn').click(function () {
            $('.complaint_btn_link').trigger('click');
        });

        $('.review_btn').click(function () {
            $('.review_btn_link').trigger('click');
        });
    });
</script>
@endpush