{!! Form::model($company_listing_agreement, ['url' => 'account/application/listing-agreement','id' =>
    'company_listing_agreement_form', 'class' => 'module_form', 'files' => true])
    !!}

    <h4 class="mb-3">Listing Agreement</h4>

<p>This agreement is made and entered into by and between Patrick's Pro Network, LLC, a Wyoming company (referred to in this agreement as "TrustPatrick.com") and for the purpose of listing <b>{{ $company->company_name }}</b> (referred to in this agreement as “Company”) on any of the websites owned and operated by TrustPatrick.com, and/or any additional products and or services purchased from TrustPatrick.com.</p>
<p>Company understands they are purchasing an <strong>{{ $company_item->membership_level->title  }}</strong> for the main service area ZIP code of <strong>{{ $company->main_zipcode }}</strong> and a <strong>{{ $company->mile_range }} mile</strong> radius regardless of how those boundaries are identified. This is the service area that will be used on the internet.</p>
<p><b>{{ $company->company_name }}</b> refers to the above company name and, if required, is licensed by a city or county in <strong class="step_6_county">{{ $company_item->county }}</strong> or by the State of <b>{{ $company_item->state->name }}</b> and hereby certifies license and insurance is active and valid at the time of this agreement. Company is established and holds all local and state permits and licenses required, if required, for the formal performance of:</p>



@if ($company_item->package_id > 0 && $company_item->package->addendum != '')
<h5>Addendum</h5>
{!! $company_item->package->addendum !!}
<div class="clearfix">&nbsp;</div>
@endif


<div class="row text-center">

    <div class="col-sm-12">
        <div class="row">
            @if (isset($company_service_category_list) && count($company_service_category_list) > 0)
            @include('company.profile._service_categories_display')
            @endif
        </div>
    </div>

</div>

<p>IN WITNESS WHEREOF, for adequate consideration and intending to be legally bound, the parties
    hereto have caused this Agreement to be executed by their duly authorized representatives. I
    UNDERSTAND ANY MARKETING AND/OR ADVERTISING IS NOT A SCIENCE; THAT IT IS A CREATIVE PROCESS
    WITH ASSOCIATED RISKS, AND THAT THERE ARE NO GUARANTEES OR WARRANTIES (EXPRESSED OR IMPLIED
    BY TrustPatrick.com OR ITS AFFILIATES AS TO THE NUMBER OF CALLS, WEB HITS, LEADS OR INCOME
TO THIS PROGRAM AND/OR ROI, NOR WILL THIS BE A REASON TO REQUEST A REFUND.</p>

<div class="card card-border card-primary mt-3">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">
            Terms and Conditions <span class="required">*</span>
        </h3>
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="checkbox checkbox-primary">
                <input value="yes" type="checkbox" name="true_information" id="terms1" required="true" />
                <label for="terms1">I hereby state that the information submitted throughout this application is true, accurate and complete.</label>
        </div>

        <div class="checkbox checkbox-primary">
            <input type="checkbox" name="terms_of_use" value="yes" id="terms2" required="true"
            data-parsley-group="step_6" />
            <label for="terms2">
                I have read and agree to the
                <a href="javascript:;" data-toggle="modal" data-target="#termsModal">Terms Of Use</a>
            </label>
        </div>
    </div>
</div>
</div>

<button type="button" class="btn btn-dark back_btn">Back</button>
<button type="submit" class="btn btn-info submit_btn float-md-right last_input step1_submit">Save & Submit</button>


{!! Form::close() !!}


@push('page_scripts')
<style type="text/css">
    .dd-list .dd-item .dd-handle .update_item {
        display: none;
    }
</style>
<!-- Plugins css -->
<link href="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.css') }}" rel="stylesheet"
type="text/css" />
<!-- Plugins js-->
<script src="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.js') }}"></script>
<!-- Nestable init-->
<script src="{{ asset('/themes/admin/assets/js/pages/nestable.init.js') }}"></script>

<script type="text/javascript">
    $(function(){
        $('#company_listing_agreement_form').submit(function(){
            var instance = $(this).parsley();
            if (instance.isValid()){
                $(this).find('.submit_btn').html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(this).find('.submit_btn').attr("disabled", true);
            } else {
                $(this).find('.submit_btn').html('Save & Submit');
                $(this).find('.submit_btn').attr("disabled", false);
            }
        })

        $('#submittin_profile_employee').click(function(){
            $('.employee_of_company_div').show();
            $('.employee_of_company_div').find('input').attr('required', true).prop('required', true);
            refresh_slick_content();
        });
        $('#submittin_profile_owner').click(function(){
            $('.employee_of_company_div').hide();
            $('.employee_of_company_div').find('input').removeAttr('required').prop('required', false);
            refresh_slick_content();
        });
            // Submit Step
        });
    </script>

    @endpush
