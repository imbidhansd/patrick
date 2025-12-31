<div class="row">
    @php
    $profile_cls = "col-lg-12";
    $profile_cls2 = "col-lg-4";
    @endphp

    @if (isset($company_gallery) && count($company_gallery) > 0)
    @php
    $profile_cls = "col-lg-4";
    $profile_cls2 = "col-lg-12";
    @endphp

    @include('company.profile_page._company_gallery')
    @endif

    <div class="{{ $profile_cls }}">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <div class="row">
                        <div class="{{ $profile_cls2 }}">
                            @if (!is_null($companyObj->company_logo) && !is_null($companyObj->company_approval_status) && $companyObj->company_approval_status->company_logo == 'in process')
                            <div class="company_logo p-xl-5 profile_logo_border font-bold font-20">
                                {{ $companyObj->company_name }}
                            </div>
                            @elseif (!is_null($companyObj->company_logo))
                            <div class="company_logo">
                                <img src="{{ asset('/uploads/media/'.$companyObj->company_logo->file_name) }}" />
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            @else
                            <div class="company_logo p-xl-5 profile_logo_border font-bold font-20">
                                {{ $companyObj->company_name }}
                            </div>
                            @endif
                        </div>
                        <div class="{{ $profile_cls2 }}">
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
                                @elseif ($companyObj->id != 'Active')
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
                            
                            <div class="company_address" id="company_address_display_btn" style="{{ $hide }}">
                                <a href="javascript:;" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#displayContactInfoModal">View Contact Information</a>
                            </div>
                            
                            <div class="company_address" id="company_address_display" style="{{ $show }}">
                                <h4>
                                    {{ $companyObj->main_company_telephone }}
                                    @if (!is_null($companyObj->secondary_telephone))
                                    <br />
                                    <small>{{ $companyObj->secondary_telephone }}</small>
                                    @endif
                                </h4>
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
                            <div class="clearfix">&nbsp;</div>
                        </div>

                        <div class="{{ $profile_cls2 }}">
                            <div class="rattings">
                                <h5>Average Rating ({{ ((!is_null($average_ratings)) ? $average_ratings->total_reviews : 0) }})</h5>
                                <div class="starHalf"></div>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="official_recommend">
                                @if ($companyObj->status == 'Active')
                                <span>Official Recommended</span>
                                <br />
                                @endif
                                <span>Company Since: {{ $companyObj->registered_date }} </span>
                                <br />
                                <br />
                                <span>Most Recent Background Check: {{ $companyObj->registered_date }} </span>
                            </div>
                            <div class="clearfix">&nbsp;</div>
                            <div class="awards">
                                <h5>Award Area</h5>
                                <div class="clearfix">&nbsp;</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        $(".display_contact_info_btn").on("click", function (){
            $.ajax({
                url: '{{ url("company-profile/view-contact-information-session") }}',
                type: 'POST',
                data: {'company_id': '{{ $companyObj->id }}', '_token': '{{ csrf_token() }}'},
                success: function (data){
                    $("#displayContactInfoModal").modal("hide");
                    
                    $("#company_address_display_btn").hide();
                    $("#company_address_display").show();
                }
            });
        });
    });
</script>
@endpush