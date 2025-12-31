@php
$form_url = "update-company-profile";
if (isset($admin_form) && $admin_form){
$form_url = "admin/companies/update-company-profile";

$company_approval_status = $company_item->company_approval_status;
}
@endphp

<div class="col-lg-12 col-md-12">
    <div class="card b-0">
        <div class="card-header bg-secondary">
            <div class="card-widgets">
                <span class="badge badge-warning float-right">Public</span>
            </div>
            <h3 class="card-title text-white mb-0">Company Information</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive111">
                <table class="table m-0">
                    <tr>
                        <th class="b-0 w-50">Company Name:</th>
                        <td class="b-0">{{ $company_item->company_name }}</td>
                    </tr>

                    @if ($company_item->membership_level->paid_members == 'yes')
                    <tr>
                        <th class="b-0">Public Company Name:</th>
                        <td class="b-0">{{ $company_item->public_company_name }}</td>
                    </tr>
                    @endif
                    
                    <tr>
                        <th class="b-0">Company Page (For Short Name on company page):</th>
                        <td class="b-0">{{ $company_item->short_company_name }}</td>
                    </tr>

                    @if ($company_item->membership_level->paid_members == 'yes')
                    <tr>
                        <th class="b-0">Company Start Date:</th>
                        <td class="b-0">
                            @if(!is_null($company_item->company_information) &&
                            !is_null($company_item->company_information->company_start_date))
                            <span
                                class="badge badge-primary">{{ $company_item->company_information->company_start_date }}</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    
                    
                    <tr>
                        <th class="b-0">Approval Date:</th>
                        <td class="b-0">
                            @if(!is_null($company_item->approval_date))
                            <span class="badge badge-primary">{{ $company_item->approval_date }}</span>
                            @else
                            <span class="badge badge-danger">Not An Approved Company</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="b-0">Renewal Date:</th>
                        <td class="b-0">
                            @if(!is_null($company_item->renewal_date))
                            <span class="badge badge-primary">{{ $company_item->renewal_date }}</span>
                            @else
                            <span class="badge badge-danger">Not An Approved Company</span>
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <th class="b-0 w-50">Email Address:</th>
                        <td class="b-0">{{ ((!is_null($user_item)) ? $user_item->email : '') }}</td>
                    </tr>
                    <tr>
                        <th class="b-0">Main Company Phone:</th>
                        <td class="b-0">{{ $company_item->main_company_telephone }}</td>
                    </tr>
                    <tr>
                        <th class="b-0">Secondary Company Phone:</th>
                        <td class="b-0">{{ $company_item->secondary_telephone }}</td>
                    </tr>
                    <tr>
                        <th class="b-0">Company Website:</th>
                        <td class="b-0">
                            @if (!is_null($company_item->company_website))
                            @php
                            $url = $company_item->company_website;
                            if (substr($url, 0, '7') === 'http://'){
                            $url = str_replace('http://', '', $url);
                            } else if (substr($url, 0, '8') === 'https://'){
                            $url = str_replace('https://', '', $url);
                            }

                            if (substr($url, 0, '4') !== 'www.'){
                            $url = 'www.'.$url;
                            }
                            @endphp

                            {{ rtrim($url, '/') }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="b-0">Company Mailing Address:</th>
                        <td class="b-0">
                            <?php
                            $address_arr = [];

                            if ($company_item->company_mailing_address != '') {
                                $address_arr[] = $company_item->company_mailing_address;
                            }

                            if ($company_item->suite != '') {
                                $address_arr[] = $company_item->suite;
                            }

                            if ($company_item->city != '') {
                                $address_arr[] = $company_item->city;
                            }

                            if (!is_null($company_item->state) && $company_item->state->short_name != '') {
                                $address_arr[] = $company_item->state->short_name;
                            }

                            /* if (!is_null($company_item->state) && $company_item->state->name != '') {
                              $address_arr[] = $company_item->state->name;
                              } */

                            if ($company_item->zipcode != '') {
                                $address_arr[] = $company_item->zipcode;
                            }

                            echo implode("<br />", $address_arr);
                            ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer text-right b-0 bc-none">
            <a href="javascript:;" title="Edit Company Information" data-toggle="modal"
                data-target="#udpateCompanyInfoModal" class="btn btn-sm btn-primary">Edit</a>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12">
    <div class="card b-0">
        <div class="card-header bg-secondary text-white">
            <div class="card-widgets">
                <b class="badge badge-warning">Private</b>
            </div>
            <h3 class="card-title text-white mb-0">Company Owner Information</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive111">
                <table class="table m-0">
                    @for($i=1;$i<=$company_item->number_of_owners;$i++)

                        @php
                        $full_name = 'company_owner_'.$i.'_full_name';
                        $email = 'company_owner_'.$i.'_email';
                        $phone = 'company_owner_'.$i.'_phone';
                        @endphp

                        <tr>
                            <th class="b-0 w-50">Owner #{{ $i }} Full Name:</th>
                            <td class="b-0">
                                {{ !is_null($company_item->company_information) ? $company_item->company_information->$full_name : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="b-0 w-50">Owner #{{ $i }} Email:</th>
                            <td class="b-0">
                                {{ !is_null($company_item->company_information) ? $company_item->company_information->$email : '' }}
                            </td>
                        </tr>
                        <tr>
                            <th class="b-0 w-50">Owner #{{ $i }} Phone:</th>
                            <td class="b-0">
                                {{ !is_null($company_item->company_information) ? $company_item->company_information->$phone : '' }}
                            </td>
                        </tr>
                        @endfor
                </table>
            </div>
        </div>
        
        @if (isset($admin_form) && $admin_form && $company_item->status == 'Active')
        <div class="card-footer text-right b-0 bc-none">
            <a href="javascript:;" title="Edit Company Owner Information" data-toggle="modal" data-target="#udpateCompanyOwnerModal" class="btn btn-sm btn-primary">Edit</a>
        </div>
        @endif
    </div>
</div>

<div class="col-lg-12 col-md-12">
    <div class="card b-0">

        <div class="card-header bg-secondary text-white">
            <div class="card-widgets">
                <b class="badge badge-warning">Private</b>
            </div>
            <h3 class="card-title text-white mb-0">Internal Contact Information</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive111">
                <table class="table m-0">
                    <tr>
                        <th class="b-0 w-50">Internal Contact Name:</th>
                        <td class="b-0">{{ $company_item->internal_contact_name }}</td>
                    </tr>
                    <tr>
                        <th class="b-0">Internal Contact Email:</th>
                        <td class="b-0">{{ $company_item->internal_contact_email }}</td>
                    </tr>
                    <tr>
                        <th class="b-0">Internal Contact Phone:</th>
                        <td class="b-0">{{ $company_item->internal_contact_phone }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card-footer text-right b-0 bc-none">
            <a href="javascript:;" title="Edit Internal Contact Information" data-toggle="modal"
                data-target="#udpateInternalContactModal" class="btn btn-sm btn-primary">Edit</a>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12">
    <div class="card b-0" id="company_bio_update">

        <div class="card-header bg-secondary text-white">
            <div class="card-widgets">
                <b class="badge badge-dark">Public</b>
            </div>
            <h3 class="card-title text-white mb-0">Company Bio</h3>
        </div>

        <div class="card-body">
            <div class="text-left">
                {!! \App\Models\Custom::cleanHtml($company_item->company_bio) !!}
            </div>

            @if (!is_null($company_approval_status) && $company_approval_status->company_bio == 'pending' &&
            !is_null($company_approval_status->company_bio_reject_note))
            <div class="clearfix"></div>
            <div class="text-left mt-4">
                <p class="text-danger font-bold mb-1">Your company Bio has been rejected.</p>
                <p class="text-dark_grey"><b class="text-danger">Reason: </b> {!! $company_approval_status->company_bio_reject_note !!}</p>
                <p class="text-danger">Please update your company bio and resubmit. Thank you</p>
            </div>
            @endif
        </div>

        <div class="card-footer b-0 bc-none">
            <div class="row">
                <div class="col-md-6">

                    @if (isset($admin_form) && $admin_form)
                        @if (!is_null($company_approval_status))
                            @if ($company_approval_status->company_bio == 'completed')
                                <span class="badge badge-primary">Approved</span>
                            @elseif ($company_approval_status->company_bio == 'pending' && !is_null($company_approval_status->company_bio_reject_note))
                                <span class="badge badge-danger">Rejected</span>
                            @elseif ($company_approval_status->company_bio == 'in process')
                                <div class="btn-group btn-group-solid">
                                    <a href="javascript:;" class="btn btn-warning btn-sm accept_company_bio">Accept</a>
                                    <a href="javascript:;" class="btn btn-danger btn-sm reject_company_bio" data-toggle="modal"
                                        data-target="#rejectCompanyInfoModal">Reject</a>
                                </div>
                            @endif
                        @endif

                    @elseif (!is_null($company_approval_status) && $company_approval_status->company_bio == 'in process')
                        <a href="javascript:;" class="btn btn-danger btn-xs">Pending Approval</a>
                    @endif

                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group btn-group-solid">
                        @if (isset($admin_form) && $admin_form && !is_null($company_item->company_bio))
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_company_bio">Delete Company Bio</a>
                        @endif
                        <a href="javascript:;" title="Edit Company Bio Information" data-toggle="modal"
                            data-target="#udpateCompanyBioModal" class="btn btn-sm btn-primary">Edit</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 col-md-12">
    <div class="card b-0" id="company_logo_update">

        <div class="card-header bg-secondary text-white">
            <div class="card-widgets">
                <b class="badge badge-dark">Public</b>
            </div>
            <h3 class="card-title text-white mb-0">Company Logo</h3>
        </div>

        <div class="card-body">
            <p class="text-center text-danger pt-2 pb-0 mb-3">Please upload a logo minimum dimension of
                {{ env('MAX_LOGO_WIDTH') }}(w) x {{ env('MAX_LOGO_HEIGHT') }}(h)</p>
            <div class="row">
                <div class="col-sm-12">
                    @include('admin.companies._company_logo_upload')
                </div>
            </div>
        </div>

        @if (isset($admin_form) && $admin_form && !is_null($company_item->company_logo))
        <div class="card-footer b-0 bc-none">
            <div class="row">
                <div class="col-md-6">
                    @if (!is_null($company_approval_status) && $company_approval_status->company_logo == 'completed')
                    <span class="badge badge-primary">Approved</span>
                    @elseif (!is_null($company_approval_status) && $company_approval_status->company_logo == 'pending' && !is_null($company_approval_status->company_logo_reject_note))
                    <span class="badge badge-danger">Rejected</span>
                    @elseif (!is_null($company_approval_status) && $company_approval_status->company_logo == 'in process')
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-warning btn-sm accept_company_logo">Accept</a>

                        <a href="javascript:;" class="btn btn-danger btn-sm reject_company_logo" data-toggle="modal"
                            data-target="#rejectCompanyInfoModal">Reject</a>
                    </div>
                    @endif
                </div>
                <div class="col-md-6 text-right">
                    <div class="btn-group btn-group-solid">
                        <a href="javascript:;" class="btn btn-danger btn-sm remove_company_logo">Delete Company Logo</a>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@if ($company_item->membership_level->paid_members == 'yes')
<div class="col-lg-12 col-md-12">
    <div class="card b-0">
        <div class="card-header bg-secondary text-white">
            <div class="card-widgets">
                <b class="badge badge-dark">Public</b>
            </div>
            <h3 class="card-title text-white mb-0">Professional Affiliations</h3>
        </div>
        <div class="card-body p-0">
            @php
            $a_none = $a_boma = $a_napa = $a_aci = $a_interlocking = $a_bbb = $a_angies = $a_home_advisor = $a_networx =
            $a_others = false;
            @endphp
            @if (!is_null($company_customer_references) &&
            !is_null($company_customer_references->professional_affiliations))

            @php
            $professional_affiliations_arr = json_decode($company_customer_references->professional_affiliations);

            if (is_array($professional_affiliations_arr)) {
            if (in_array('None', $professional_affiliations_arr)) {
            $a_none = true;
            }

            if (in_array('Other: (Please List)', $professional_affiliations_arr)) {
            $a_others = true;
            }
            }
            @endphp
            <div class="table-responsive111">
                <table class="table table-bordered">
                    @foreach ($professional_affiliations_arr AS $reference_item)

                    @if ($reference_item != 'Other: (Please List)')
                    <tr>
                        <td>{{ $reference_item }}</td>
                    </tr>
                    @else
                    <tr>
                        <td>
                            {!! $company_customer_references->other_professional_affiliations !!}
                        </td>
                    </tr>
                    @endif
                    @endforeach
                </table>
            </div>
            @endif
        </div>
        <div class="card-footer text-right b-0 bc-none">
            <a href="javascript:;" title="Edit Company Professional Affiliations" data-toggle="modal"
                data-target="#udpateCompanyProfessionalAffiliationsModal" class="btn btn-sm btn-primary">Edit</a>
        </div>
    </div>
</div>
@endif

<div class="col-lg-12 col-md-12">
    <div class="card b-0">

        <div class="card-header bg-secondary text-white">
            <div class="card-widgets">
                <b class="badge badge-dark">Private</b>
            </div>
            <h3 class="card-title text-white mb-0">Social Media Links</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive111">
                <table class="table m-0">
                    <tr>
                        <th class="b-0 w-50">Facebook:</th>
                        <td class="b-0">{{ $company_item->facebook_url }}</td>
                    </tr>
                    <tr>
                        <th class="b-0">Linkedin:</th>
                        <td class="b-0">{{ $company_item->linkedin_url }}</td>
                    </tr>
                    <tr>
                        <th class="b-0">Twitter:</th>
                        <td class="b-0">{{ $company_item->twitter_url }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="card-footer text-right b-0 bc-none">
            <a href="javascript:;" title="Edit Company Social Information" data-toggle="modal"
                data-target="#udpateCompanySocialModal" class="btn btn-sm btn-primary">Edit</a>
        </div>
    </div>
</div>



<div class="modal fade" id="udpateCompanyInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Contact Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url($form_url), 'class' => 'module_form update_company_profile_form']) !!}

            {!! Form::hidden('update_type', 'company_contact_info') !!}

            @if (isset($admin_form) && $admin_form)
            {!! Form::hidden('company_id', $company_item->id) !!}
            @endif
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Company Name') !!}
                            {!! Form::text('company_name', $company_item->company_name, ['class' => 'form-control',
                            'maxlength' => 255, 'readonly' => isset($admin_form) && $admin_form ? false : true, 'required' => true]) !!}
                        </div>
                    </div>

                    @if ($company_item->membership_level->paid_members == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Public Company Name') !!}
                            {!! Form::text('public_company_name', $company_item->public_company_name, ['class' =>
                            'form-control', 'maxlength' => 255, 'readonly' => isset($admin_form) && $admin_form ? false : true, 'required' => false]) !!}
                        </div>
                    </div>
                    <?php /* @else
                    <div class="col-md-6"></div> */ ?>
                    @endif
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Company Short Name') !!}
                            {!! Form::text('short_company_name', $company_item->short_company_name, ['class' => 'form-control', 'maxlength' => 255, 'required' => false]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Main Company Telephone') !!}
                            {!! Form::text('main_company_telephone', $company_item->main_company_telephone,
                            ['class' => 'form-control', 'required' => true, 'maxlength' => 255, 'data-toggle' =>
                            'input-mask', 'data-mask-format' => '(000) 000-0000']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Secondary Telephone') !!}
                            {!! Form::text('secondary_telephone', $company_item->secondary_telephone, ['class' =>
                            'form-control', 'required' => false, 'maxlength' => 255, 'data-toggle' => 'input-mask',
                            'data-mask-format' => '(000) 000-0000']) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Company Website') !!}
                            {!! Form::text('company_website', $company_item->company_website, ['class' =>
                            'form-control', 'placeholder' => '', 'required' => false, 'maxlength' => 255]) !!}
                            <span class="help-block text-info"><small>Keep blank if no website</small></i></span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('H.No./Street') !!}
                            {!! Form::text('company_mailing_address', $company_item->company_mailing_address,
                            ['class' => 'form-control', 'required' => true, 'maxlength' => 255]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Suite') !!}
                            {!! Form::text('suite', $company_item->suite, ['class' => 'form-control', 'maxlength' =>
                            255, 'required' => false]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('City') !!}
                            {!! Form::text('city', $company_item->city, ['class' => 'form-control',
                            'required' => false, 'maxlength' => 255]) !!}
                        </div>
                    </div>


                    @if ($company_item->membership_level->paid_members == 'yes')
                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('County') !!}
                            {!! Form::text('county', $company_item->county, ['class' => 'form-control',
                            'required' => false, 'maxlength' => 255]) !!}
                        </div>
                    </div>
                    @endif

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('State') !!}
                            {!! Form::select('state_id', $states, $company_item->state_id, ['class' =>
                            'form-control custom-select', 'required' => true, 'maxlength' => 255]) !!}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('Zipcode') !!}
                            {!! Form::text('zipcode', $company_item->zipcode, ['class' => 'form-control',
                            'required' => true, 'maxlength' => 5, 'data-toggle' => 'input-mask', 'data-mask-format' =>
                            '00000']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light update_company_profile_btn">Save
                    changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="udpateCompanyOwnerModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Owner Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url($form_url), 'class' => 'module_form update_company_profile_form']) !!}

            {!! Form::hidden('update_type', 'company_owner_info') !!}

            @if (isset($admin_form) && $admin_form)
            {!! Form::hidden('company_id', $company_item->id) !!}
            @endif
            <div class="modal-body">
                @for($i=1;$i<=$company_item->number_of_owners;$i++)
                    <div class="row">
                        @php
                        $full_name = 'company_owner_'.$i.'_full_name';
                        $email = 'company_owner_'.$i.'_email';
                        $phone = 'company_owner_'.$i.'_phone';
                        @endphp

                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Owner '.$i.' Firstname Lastname') !!}
                                {!! Form::text('company_owner_'.$i.'_full_name',
                                !is_null($company_item->company_information) ?
                                $company_item->company_information->$full_name : null, ['class' => 'form-control',
                                'required' => true, 'maxlength' => 255]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Owner '.$i.' Email Address') !!}
                                {!! Form::email('company_owner_'.$i.'_email',
                                !is_null($company_item->company_information) ?
                                $company_item->company_information->$email : null, ['class' => 'form-control',
                                'required' => true, 'maxlength' => 255]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Owner '.$i.' Phone') !!}
                                {!! Form::text('company_owner_'.$i.'_phone', !is_null($company_item->company_information) ? $company_item->company_information->$phone : null, ['class' => 'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true, 'maxlength' => 255]) !!}
                            </div>
                        </div>
                    </div>
                    @endfor
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light update_company_profile_btn">Save changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="udpateInternalContactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Internal Contact Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url($form_url), 'class' => 'module_form update_company_profile_form']) !!}

            {!! Form::hidden('update_type', 'internal_contact_info') !!}

            @if (isset($admin_form) && $admin_form)
            {!! Form::hidden('company_id', $company_item->id) !!}
            @endif
            <div class="modal-body">


                <p>Please list an internal contact that can be reached at a moments notice.</p>

                <div class="form-group">
                    {!! Form::label('Internal Contact First & Last Name') !!}
                    {!! Form::text('internal_contact_name', $company_item->internal_contact_name, ['class' =>
                    'form-control', 'required' => true, 'maxlength' => 255]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Internal Contact Email Address') !!}
                    {!! Form::email('internal_contact_email', $company_item->internal_contact_email, ['class' =>
                    'form-control', 'required' => true, 'maxlength' => 255]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Internal Contact Phone') !!}
                    {!! Form::text('internal_contact_phone', $company_item->internal_contact_phone, ['class' =>
                    'form-control', 'required' => true, 'maxlength' => 255, 'data-toggle' => 'input-mask',
                    'data-mask-format' => '(000) 000-0000']) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light update_company_profile_btn">Save
                    changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="udpateCompanyBioModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Bio</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url($form_url), 'class' => 'module_form update_company_profile_form', 'id' =>
            'update_company_bio_form']) !!}

            {!! Form::hidden('update_type', 'company_bio') !!}

            @if (isset($admin_form) && $admin_form)
            {!! Form::hidden('company_id', $company_item->id) !!}
            @endif
            <div class="modal-body">
                <p class="text-center">Your company bio is the first exposure people visiting our websites have to your
                    company so make it stand out! Take the time to write a great bio or copy and paste from your company
                    website.</p>

                <div class="form-group">
                    {!! Form::label('Company Bio') !!}
                    {!! Form::textarea('company_bio', $company_item->company_bio, ['class' => 'form-control summernote',
                    'required' => false]) !!}
                </div>

                <p>Per our policy, company bio cannot contain phone numbers, website addresses, custom text numbers, or
                    any other contact information.</p>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light update_company_profile_btn">Save
                    changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@if ($company_item->membership_level->paid_members == 'yes')
<div class="modal fade" id="udpateCompanyProfessionalAffiliationsModal" tabindex="-1" role="dialog"
    aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Affiliations</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if (isset($admin_form) && $admin_form)
            @php $customer_reference_form_url = 'admin/companies/update-affiliations'; @endphp
            @else
            @php $customer_reference_form_url = 'update-affiliations'; @endphp
            @endif

            {!! Form::open(['url' => $customer_reference_form_url, 'class' => 'module_form']) !!}

            @if (isset($admin_form) && $admin_form)
            {!! Form::hidden('company_id', $company_item->id) !!}
            @endif
            <div class="modal-body">
                <div class="form-group mb-0">
                    <div class="checkbox checkbox-primary">
                        {!! Form::checkbox('professional_affiliations[]', 'None', $a_none, ['id' => 'a_none',
                        'class'
                        =>
                        'cust_ref_opt_none', 'data-parsley-errors-container' => '#professional_affiliations_error']) !!}
                        <label for="a_none">None</label>
                    </div>

                    @if (isset($professional_affiliations) && count($professional_affiliations) > 0)
                    @foreach ($professional_affiliations AS $professional_affiliation_item)
                    <div class="checkbox checkbox-primary">
                        @php $checked = false; @endphp
                        @if (isset($professional_affiliations_arr) && is_array($professional_affiliations_arr) &&
                        in_array($professional_affiliation_item, $professional_affiliations_arr))
                        @php $checked = true; @endphp
                        @endif
                        {!! Form::checkbox('professional_affiliations[]', $professional_affiliation_item, $checked,
                        ['id' => $professional_affiliation_item, 'class' => 'cust_ref_opt_other',
                        'data-parsley-errors-container' => '#professional_affiliations_error']) !!}
                        <label for="{{ $professional_affiliation_item }}">{{ $professional_affiliation_item }}</label>
                    </div>
                    @endforeach
                    @endif

                    <div class="checkbox checkbox-primary">
                        {!! Form::checkbox('professional_affiliations[]',
                        'Other: (Please List)', $a_others,
                        ['id' => 'a_others', 'class' =>
                        'cust_ref_opt_other', 'data-parsley-errors-container' => '#professional_affiliations_error'])
                        !!}
                        <label for="a_others">Other: (Please List)</label>
                    </div>
                </div>
                <div id="professional_affiliations_error"></div>

                <div class="form-group {{ $a_others ? '' : 'hide' }} other_professional_affiliations">
                    {!! Form::textarea('other_professional_affiliations', null, ['placeholder' => 'Explain Other
                    Professional Affiliations Here', 'class' => 'form-control', 'id' =>
                    'other_professional_affiliations', 'required' => $a_others ? true : false]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light update_company_profile_btn">Save
                    changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="udpateCompanySocialModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Company Social Media Info</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url($form_url), 'class' => 'module_form update_company_profile_form']) !!}

            {!! Form::hidden('update_type', 'company_social_info') !!}

            @if (isset($admin_form) && $admin_form)
            {!! Form::hidden('company_id', $company_item->id) !!}
            @endif
            <div class="modal-body">

                <div class="form-group">
                    {!! Form::label('Facebook Business Page Link') !!}
                    {!! Form::text('facebook_url', $company_item->facebook_url, ['class' => 'form-control',
                    'required'
                    => false]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Linkedin Business Page Link') !!}
                    {!! Form::text('linkedin_url', $company_item->linkedin_url, ['class' => 'form-control',
                    'required'
                    => false]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Twitter Business Page Link') !!}
                    {!! Form::text('twitter_url', $company_item->twitter_url, ['class' => 'form-control',
                    'required'
                    => false]) !!}
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light update_company_profile_btn">Save
                    changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<div class="modal fade" id="rejectCompanyInfoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => '', 'class' => 'module_form', 'id' => 'company_bio_reject_form']) !!}
            {!! Form::hidden('approval_status', 'pending') !!}
            {!! Form::hidden('approval_status_type', null, ['id' => 'approval_status_type']) !!}
            {!! Form::hidden('company_id', $company_item->id) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Note') !!}
                    {!! Form::textarea('reject_note', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@push('_edit_company_profile_js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

@include('admin.companies._company_logo_bio_request_js')
<script type="text/javascript">
    $(function (){
    $(".update_company_profile_form").on("submit", function (){
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(this).find(".update_company_profile_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(this).find(".update_company_profile_btn").attr('disabled', true);
        } else {
            $(this).find(".update_company_profile_btn").html('Save changes');
            $(this).find(".update_company_profile_btn").attr('disabled', false);
        }
    });

    $("#company_bio_update_form").on("submit", function (){
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(".company_bio_update_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".company_bio_update_btn").attr('disabled', true);
        } else {
            $(".company_bio_update_btn").html('Save changes');
            $(".company_bio_update_btn").attr('disabled', false);
        }
    });

    $('.cust_ref_opt_other').click(function () {
        $('.cust_ref_opt_none').prop('checked', false);
    });

    $('.cust_ref_opt_none').click(function () {
        $('.cust_ref_opt_other').prop('checked', false);
    });
    $('#a_others').change(function () {
        if ($(this).is(':checked') == true) {
            $('.other_professional_affiliations').show();
            $('#other_professional_affiliations').attr('required', true);
        } else {
            $('.other_professional_affiliations').hide();
            $('#other_professional_affiliations').removeAttr('required');
        }
    });
});
</script>
@endpush
