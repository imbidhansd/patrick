<div id="generate_request_form">
    {!! Form::open(['url' => 'company-profile-page/generate-lead', 'class' => 'module_form', 'id' => 'generate_lead_form']) !!}
    {!! Form::hidden('lead_generate_for', $companyObj->id, ['id' => 'lead_generate_for']) !!}
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="text-center">
                @if ($companyObj->trade_id == 1)
                <h2 class="text-uppercase blue-font">Request a free estimate from {{ $companyObj->showCompanyName() }}</h2>
                @else
                <h2 class="text-uppercase blue-font">Request to be contacted by {{ $companyObj->showCompanyName() }}</h2>
                @endif
            </div>
        </div>
    </div>

    
    <div class="row">
        
        @if ($companyObj->trade_id == 1)
        <div class="col-lg-12">
            <div class="form-group">
                {!! Form::label('Service Category Type') !!}
                {!! Form::select('service_category_type_id', $service_category_types, null, ['class' => 'form-control custom-select', 'id' => 'service_category_type_id', 'placeholder' => 'Select Service Category type', 'required' => 'true']) !!}
            </div>
        </div>
        @else
        @php $service_category_type_arr = array_keys($service_category_types->toArray()); @endphp
        {!! Form::hidden('service_category_type_id', $service_category_type_arr[0], ['id' => 'service_category_type_id']) !!}
        @endif
        
        
        @if (count($top_level_categories) > 1)
        <div class="col-lg-12 tlc_div">
            <div class="form-group">
                @php $disabled = true; @endphp
                @if ($companyObj->trade_id == 2)
                @php $disabled = false; @endphp
                @endif
                
                {!! Form::label('Top Level Category') !!}
                {!! Form::select('top_level_category_id', $top_level_categories, null, ['class' => 'form-control custom-select', 'id' => 'top_level_category_id', 'placeholder' => 'Select Top Level Category', 'disabled' => $disabled, 'required' => 'true']) !!}
            </div>
        </div>
        @else
        @php $top_level_category_arr = array_keys($top_level_categories->toArray()); @endphp
        {!! Form::hidden('top_level_category_id', $top_level_category_arr[0], ['id' => 'top_level_category_id']) !!}
        @endif

        <div class="col-lg-12 msc_div hide">
            <div class="form-group">
                {!! Form::label('Main Category') !!}
                {!! Form::select('main_category_id', [], null, ['class' => 'form-control custom-select', 'id' => 'main_category_id', 'placeholder' => 'Select Main Category', 'disabled' => true, 'required' => 'true']) !!}
            </div>
        </div>

        <div class="col-lg-12 sc_div hide">
            <div class="form-group">
                {!! Form::label('Service Category') !!}
                {!! Form::select('service_category_id', [], null, ['class' => 'form-control custom-select', 'id' => 'service_category_id', 'placeholder' => 'Select Service Category', 'disabled' => true, 'required' => 'true']) !!}
            </div>
        </div>
        
        
        
        
        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('Full Name') !!}
                {!! Form::text('full_name', null, ['class' => 'form-control', 'placeholder' => 'Full Name', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('Email') !!}
                {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('Phone') !!}
                {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('Project Address') !!}
                {!! Form::text('project_address', null, ['class' => 'form-control', 'placeholder' => 'Enter Project Address', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('Zipcode') !!}
                {!! Form::text('zipcode', null, ['class' => 'form-control', 'id' => 'zipcode', 'placeholder' => 'Enter Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
            </div>
        </div>

        <div class="col-lg-6">
            <div class="form-group">
                {!! Form::label('What is your timeframe for the work to be completed') !!}
                {!! Form::select('timeframe', $timeframe, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Timeframe', 'required' => true]) !!}
            </div>
        </div>

        <?php /* @if (isset($company_service_category_list) && count($company_service_category_list) > 0)
        @include('company.profile_page._service_category_selection')
        @endif
        <div class="clearfix">&nbsp;</div> */ ?>
        
        <div class="col-lg-12">
            <div class="form-group">
                {!! Form::label('Project Info') !!}
                {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Enter Project Info', 'required' => false]) !!}
            </div>
        </div>
    </div>

    <div class="row">
        <?php /* <div class="col-lg-12 text-center">
            <div class="checkbox checkbox-primary checkbox-circle">
                <input name="lead_terms" value="1" id="lead_terms" type="checkbox" data-parsley-errors-container="#lead_terms_error" required />
                <label for="lead_terms">
                    By submitting this request, you are agree to our <a href="javascript:;" data-toggle="modal" data-target="#termsModal">Terms Of Use</a>
                    <div id="lead_terms_error" style="position: absolute; width: 100%; float: left; text-align: left;"></div>
                </label>
            </div>
        </div>
        <div class="clearfix">&nbsp;</div> */ ?>
        
        <div class="col-lg-12 text-center">
            <div class="g-recaptcha" id="lead_generate-recaptcha" data-callback="imNotARobot" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY')  }}"></div>
        </div>
        
        <div class="col-lg-12 text-center">
            <button type="submit" class="btn btn-primary btn-md generate_lead_btn" disabled>Submit Request</button>
        </div>
        <div class="clearfix">&nbsp;</div>
        <div class="col-lg-12 text-center">
            <p>By clicking 'Submit Request' You agree to our <a href="javascript:;" data-toggle="modal" data-target="#termsModal">Terms Of Use</a> and <a href="javascript:;" class="tcpa_policy">TCPA Policy</a>.</p>
            <p>We respect your email privacy.</p>
            <p class="tcpa_policy_text" style="display: none;">Clicking the submit request button constitutes your express written consent, without obligation to purchase, to be contacted by prospective service providers (including with pre-recorded messages and through automated means, e.g. auto dialing and text messaging) via telephone, mobile device (including SMS and MMS), and/or email, even if your telephone number is on a corporate, state or the National Do Not Call Registry.</p>
        </div>
    </div>
    
    <?php /* {!! Form::hidden('service_category_type_id', null, ['id' => 'service_category_type_id']) !!}
    {!! Form::hidden('top_level_category_id', null, ['id' => 'top_level_category_id']) !!}
    {!! Form::hidden('main_category_id', null, ['id' => 'main_category_id']) !!}
    {!! Form::hidden('service_category_id', null, ['id' => 'service_category_id']) !!} */ ?>
    
    {!! Form::hidden('recaptcha', null, ['id' => 'recaptcha']) !!}
    {!! Form::close() !!}
</div>

<!-- Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $terms_page->title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                {!! $terms_page->content !!}
            </div>
        </div>
    </div>
</div>


@push('page_script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php /* @include ('admin.includes.captcha_js', ['action_field' => 'generate_lead']) */ ?>

<!-- Plugins js -->
<script src="{{ asset('themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('themes/admin/assets/js/pages/form-masks.init.js') }}"></script>
<script type="text/javascript">
var imNotARobot = function() {
    //console.info("Button was clicked");
    $(".generate_lead_btn").attr("disabled", false);
};

$(function () {
    var company_id = '{{ $companyObj->id }}';
    var valid_zipcode = false;
    
    /* Submit lead start */
    /*$("#create_request").on("click", function () {
        @if (Auth::guard('company_user')->check() && Auth::guard('company_user')->user()->company_id == $companyObj->id)
            Swal.fire({
                title: '',
                type: 'info',
                text: 'When consumers submit a request to be contacted by your company, you will be notified immediately via email. No other companies receive this.'
            }).then(function (t){
                $('html, body').animate({
                    scrollTop: $("#generate_request_form").offset().top - 100
                }, 2000);
            });
        @else
        $('html, body').animate({
            scrollTop: $("#generate_request_form").offset().top - 100
        }, 2000);
        @endif
    });

    $("#zipcode").on("blur", function () {
        var zipcode = $(this).val();

        if (typeof zipcode !== 'undefined' && zipcode != '') {
            $.ajax({
                url: '{{ url("company-profile-page/check-company-zipcode") }}',
                type: 'POST',
                data: {'zipcode': zipcode, 'company_id': '{{ $companyObj->id }}', '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    if (!data.success) {
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message,
                            showCancelButton: !0,
                            cancelButtonText: "Re-enter Zipcode",
                            cancelButtonColor: "#ff0000",
                            confirmButtonText: "Submit Request",
                            confirmButtonColor: "#003E74",
                        }).then(function (t) {
                            if (typeof t.value !== 'undefined') {
                                $("#lead_generate_for").val('');
                            } else {
                                $("#lead_generate_for").val(company_id);
                                $("#zipcode").val('');
                                $("#zipcode").focus();
                            }
                        });
                    }
                }
            });
        }
    });
    
    
    $(".category_selection").on("click", function (){
        var service_category_type_id = $(this).data("service_category_type");
        var top_level_category_id = $(this).data("top_level_category");
        var main_category_id = $(this).data("main_category");
        var service_category_id = $(this).data("service_category");
        
        if ($(this).hasClass("active")){
            $(this).removeClass("active");
            
            $("#service_category_type_id, #top_level_category_id, #main_category_id, #service_category_id").val('');
        } else {
            $("#service_category_type_id").val(service_category_type_id);
            $("#top_level_category_id").val(top_level_category_id);
            $("#main_category_id").val(main_category_id);
            $("#service_category_id").val(service_category_id);

            $(".category_selection").removeClass("active");
            $(this).addClass("active");
        }
    });*/

    $("#service_category_type_id").on("change", function () {
        if ($(this).val() == '') {
            $('.tlc_div, .msc_div, .sc_div').addClass('hide');
            $("#main_category_id, #service_category_id").val("");
            $("#main_category_id, #service_category_id").attr("disabled", true);
        } else {
            $("#top_level_category_id").attr("disabled", false);
            $('.tlc_div').removeClass('hide');
            
            if ($("#top_level_category_id").val() != ''){
                get_main_categories();
            }
        }
    });
    
    @if (count($top_level_categories) == 1 && $companyObj->trade_id == 2)
    get_main_categories();
    @endif

    $("#top_level_category_id").on("change", function () {
        get_main_categories();
    });

    $(document).on("change", "#main_category_id", function () {
        var service_category_type_id = $("#service_category_type_id").val();
        var top_level_category_id = $("#top_level_category_id").val();
        var main_category_id = $(this).val();

        if (main_category_id == '') {
            $('.sc_div').addClass('hide');
            $("#service_category_id").val("");
            $("#service_category_id").attr("disabled", true);
        } else {
            $('.sc_div').removeClass('hide');
            $("#service_category_id").attr("disabled", false);
        }

        if (typeof service_category_type_id !== 'undefined' && service_category_type_id != '' && typeof main_category_id !== 'undefined' && main_category_id != '' && typeof top_level_category_id !== 'undefined' && top_level_category_id != '') {
            $.ajax({
                url: '{{ url("company-profile-page/get-service-categories") }}',
                type: 'POST',
                data: {
                    'service_category_type_id': service_category_type_id,
                    'top_level_category_id': top_level_category_id,
                    'main_category_id': main_category_id,
                    'company_id': '{{ $companyObj->id }}',
                    '_token': '{{ csrf_token() }}'
                },
                success: function (data) {
                    if (typeof data.success !== 'undefined') {
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message
                        });
                    } else {
                        $("#service_category_id").html(data);
                    }
                }
            });
        }
    });
    
    $(".tcpa_policy").on("click", function (){
        $(".tcpa_policy_text").toggle();
    });
    
    $("#generate_lead_form").on("submit", function (){
        
        $(".generate_lead_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
        $(".generate_lead_btn").attr('disabled', true);

        if (valid_zipcode == false){
            var zipcode = $("#zipcode").val();

            $.ajax({
                url: '{{ url("company-profile-page/check-company-zipcode") }}',
                type: 'POST',
                data: {'zipcode': zipcode, 'company_id': '{{ $companyObj->id }}', '_token': '{{ csrf_token() }}'},
                success: function (data) {
                    
                    if (data.success == 0){
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message,
                            showCancelButton: !0,
                            cancelButtonText: "Re-enter Zipcode",
                            cancelButtonColor: "#ff0000",
                            confirmButtonText: "Submit Request",
                            confirmButtonColor: "#003E74",
                        }).then(function(t){
                            
                            if (typeof t.value != 'undefined'){
                                // Submit for Find A Pro
                                $("#lead_generate_for").val('');
                                valid_zipcode = true;
                                $("#generate_lead_form").submit();
                            }else{
                                // Reenter zipcode
                                $("#lead_generate_for").val(company_id);
                                $("#zipcode").val('').focus();

                                $(".generate_lead_btn").html('Submit Request');
                                $(".generate_lead_btn").attr('disabled', false);
                                return false;
                            }
                        })
                    }else{
                        valid_zipcode = true;
                        
                        $("#generate_lead_form").submit();
                    }
                },
                error: function(e){}
            });

            event.preventDefault();    

        } else{
            
            $.ajax({
                url: '{{ url("company-profile-page/generate-lead") }}',
                type: 'POST',
                data: $("#generate_lead_form").serialize(),
                success: function (data) {
                    $(".generate_lead_btn").html('Submit Request');
                    $(".generate_lead_btn").attr('disabled', false);
                    
                    Swal.fire({
                        title: data.title,
                        type: data.type,
                        html: data.message,
                        confirmButtonText: "Ok",
                    }).then(function(t){
                        window.location.reload();
                    });
                }
            });
            return false;
        }
    });
    /* Submit lead end */
});



function get_main_categories (){
    var service_category_type_id = $("#service_category_type_id").val();
    var top_level_category_id = $("#top_level_category_id").val();

    if (top_level_category_id == '') {
        $('.msc_div, .sc_div').addClass('hide');
        $("#main_category_id, #service_category_id").val("");
        $("#main_category_id, #service_category_id").attr("disabled", true);
    } else {
        $('.msc_div').removeClass('hide');
        $("#main_category_id").attr("disabled", false);
    }

    if (typeof service_category_type_id !== 'undefined' && service_category_type_id != '' && typeof top_level_category_id !== 'undefined' && top_level_category_id != '') {
        $.ajax({
            url: '{{ url("company-profile-page/get-main-categories") }}',
            type: 'POST',
            data: {
                'service_category_type_id': service_category_type_id,
                'top_level_category_id': top_level_category_id,
                'company_id': '{{ $companyObj->id }}',
                '_token': '{{ csrf_token() }}'
            },
            success: function (data) {
                if (typeof data.success !== 'undefined' && data.success == 0) {
                    Swal.fire({
                        title: data.title,
                        type: data.type,
                        html: data.message
                    });
                } else {
                    $("#main_category_id").html(data);
                }
            }
        });
    }
}
</script>
@endpush