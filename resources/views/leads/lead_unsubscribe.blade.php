<?php
$admin_page_title = 'Unsubscribe';
?>
@extends('leads.layout')

@section ('content')
<?php /* @include('admin.includes.breadcrumb') */ ?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('admin.includes.formErrors')
            @include('flash::message')

            <div id="unsubscribe_options">
                {!! Form::model($lead, ['url' => 'lead-unsubscribe-first-step', 'class' => 'module_form', 'id' => 'unsubscribe_lead_form_first']) !!}

                {!! Form::hidden('lead_id', $lead->id) !!}
            
                <h3 class="text-center text-theme_color">Don't miss out!</h3>
                <p class="text-center text-theme_color font-14">You are currently subscribed as {{ $lead->email }}</p>
                <div class="clearfix">&nbsp;</div>

                <div class="card card-primary">
                    <div class="card-body border_left">
                        <div class="row">
                            <div class="col-md-9">
                                <h5 class="text-theme_color mt-0">Regarding Your request</h5>
                                <p class="text-muted mb-0">
                                    Receive important emails regarding your request.
                                </p>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="radio radio-primary radio-circle mt5">
                                    {!! Form::radio('regarding_your_request', 'subscribe', null, ['id' => 'regarding_your_request_subscribe', 'data-parsley-errors-container' => '#regarding_your_request_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="regarding_your_request_subscribe">Subscribed</label>
                                </div>
                                <div class="radio radio-primary radio-circle">
                                    {!! Form::radio('regarding_your_request', 'unsubscribe', null, ['id' => 'regarding_your_request_unsubscribe', 'data-parsley-errors-container' => '#regarding_your_request_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="regarding_your_request_unsubscribe">Unsubscribe</label>
                                </div>

                                <div id="regarding_your_request_error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-body border_left">
                        <div class="row">
                            <div class="col-md-9">
                                <h5 class="text-theme_color mt-0">Special Promotions/Offers</h5>
                                <p class="text-muted mb-0">
                                    Receive emails regarding special promotions to make your life easier.
                                </p>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="radio radio-primary radio-circle mt5">
                                    {!! Form::radio('special_offers', 'subscribe', null, ['id' => 'special_offers_subscribe', 'data-parsley-errors-container' => '#special_offers_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="special_offers_subscribe">Subscribed</label>
                                </div>
                                <div class="radio radio-primary radio-circle">
                                    {!! Form::radio('special_offers', 'unsubscribe', null, ['id' => 'special_offers_unsubscribe', 'data-parsley-errors-container' => '#special_offers_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="special_offers_unsubscribe">Unsubscribe</label>
                                </div>

                                <div id="special_offers_error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-body border_left">
                        <div class="row">
                            <div class="col-md-9">
                                <h5 class="text-theme_color mt-0">Scams & Ripoffs Updates</h5>
                                <p class="text-muted mb-0">
                                    Receive important notifications regarding scams and ripoff trends to void.
                                </p>
                            </div>
                            <div class="col-md-3">
                                <div class="radio radio-primary radio-circle mt5">
                                    {!! Form::radio('scams_updates', 'subscribe', null, ['id' => 'scams_updates_subscribe', 'data-parsley-errors-container' => '#scams_updates_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="scams_updates_subscribe">Subscribed</label>
                                </div>
                                <div class="radio radio-primary radio-circle">
                                    {!! Form::radio('scams_updates', 'unsubscribe', null, ['id' => 'scams_updates_unsubscribe', 'data-parsley-errors-container' => '#scams_updates_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="scams_updates_unsubscribe">Unsubscribe</label>
                                </div>

                                <div id="scams_updates_error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary">
                    <div class="card-body border_left">
                        <div class="row">
                            <div class="col-md-9">
                                <h5 class="text-theme_color mt-0">General Updates</h5>
                                <p class="text-muted mb-0">
                                    Receive general updates including Terms of Use/Privacy Policy Updates.
                                </p>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="radio radio-primary radio-circle mt5">
                                    {!! Form::radio('general_updates', 'subscribe', null, ['id' => 'general_updates_subscribe', 'data-parsley-errors-container' => '#general_updates_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="general_updates_subscribe">Subscribed</label>
                                </div>
                                <div class="radio radio-primary radio-circle">
                                    {!! Form::radio('general_updates', 'unsubscribe', null, ['id' => 'general_updates_unsubscribe', 'data-parsley-errors-container' => '#general_updates_error', 'required' => true, 'class' => 'radio_btn']) !!}
                                    <label for="general_updates_unsubscribe">Unsubscribe</label>
                                </div>

                                <div id="general_updates_error"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-border card-primary">
                    <div class="card-body border_left">
                        <div class="row">
                            <div class="col-md-9">
                                <h5 id="unsubscribe_all" style="cursor: pointer;" class="text-theme_color m-0">Unsubscribe from all emails</h5>
                            </div>
                            <?php /*
                            <div class="col-md-3">
                                <div class="checkbox checkbox-primary checkbox-circle">
                                    {!! Form::checkbox('unsubscribe_all', 'unsubscribe_all', null, ['id' => 'unsubscribe_all']) !!}
                                    <label for="unsubscribe_all">Yes</label>
                                </div>
                            </div>
                            */ ?>
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <button type="submit" class="btn btn-theme_color hide update_subscription">Update Subscription</button>
                </div>
                <div class="clearfix">&nbsp;</div>
                
                {!! Form::close() !!}
            </div>


            <div id="unsubscribe_reason" style="display: none;">
                {!! Form::model($lead, ['url' => 'lead-unsubscribe-second-step', 'class' => 'module_form', 'id' => 'unsubscribe_lead_form_second']) !!}

                {!! Form::hidden('lead_id', $lead->id) !!}
                
                <h3 class="text-center">Unsubscribed Successfully</h3>

                <div id="selected_unsubscribed_options"></div>
                
                <a href="javascript:;" class="btn btn-theme_color" id="back_btn">Back</a>
                <div class="clearfix">&nbsp;</div>
                
                <p class="text-muted">We're sorry to see you go!</p>
                <p class="text-muted">
                    Would you please take just a moment and let us know why you unsubscribed?
                </p>
                
                <div class="form-group">
                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio('why_unsubscribe', 'no_longer_want_to_receive_emails', null, ['id' => 'no_longer_want_to_receive_emails', 'class' => 'why_unsubscribe', 'data-parsley-errors-container' => '#why_unsubscribe_error', 'required' => true]) !!}
                        <label for="no_longer_want_to_receive_emails">I no longer want to receive these emails</label>
                    </div>

                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio('why_unsubscribe', 'never_signup_for_mailing_list', null, ['id' => 'never_signup_for_mailing_list', 'class' => 'why_unsubscribe', 'data-parsley-errors-container' => '#why_unsubscribe_error', 'required' => true]) !!}
                        <label for="never_signup_for_mailing_list">I never signed up for this mailing list</label>
                    </div>

                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio('why_unsubscribe', 'emails_inappropriate', null, ['id' => 'emails_inappropriate', 'class' => 'why_unsubscribe', 'data-parsley-errors-container' => '#why_unsubscribe_error', 'required' => true]) !!}
                        <label for="emails_inappropriate">The emails are inappropriate</label>
                    </div>

                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio('why_unsubscribe', 'emails_spam_reported', null, ['id' => 'emails_spam_reported', 'class' => 'why_unsubscribe', 'data-parsley-errors-container' => '#why_unsubscribe_error', 'required' => true]) !!}
                        <label for="emails_spam_reported">The emails are spam and should be reported</label>
                    </div>

                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio('why_unsubscribe', 'other', null, ['id' => 'why_unsubscribe_other', 'class' => 'why_unsubscribe', 'data-parsley-errors-container' => '#why_unsubscribe_error', 'required' => true]) !!}
                        <label for="why_unsubscribe_other">Other (fill in reason below)</label>
                    </div>

                    <div id="why_unsubscribe_error"></div>
                </div>
                
                <?php /* 
                 * <div class="clearfix">&nbsp;</div>
                 * @php
                $style = 'display:none;';
                if ($lead->why_unsubscribe == 'other' && !is_null($lead->unsubscribe_reason)){
                $style = '';
                }
                @endphp {{ $style }}*/ ?>
                
                <div class="form-group" id="other_reason" style="display:none;">
                    {!! Form::textarea('unsubscribe_reason', null, ['class' => 'form-control max', 'maxlength' => 500]) !!}
                </div>
                
                <div class="text-left">
                    <button type="submit" class="btn btn-theme_color" id="unsubscribe_lead_btn">Submit</button>
                    
                    <br /><br />
                    <a href="https://opp.trustpatrick.com/" class="text-muted"><i class="fas fa-angle-double-left"></i> Return to our website</a>
                </div>
                
                {!! Form::close() !!}
            </div>            
        </div>
    </div>
</div>
@endsection

@section ('page_js')
<script type="text/javascript">
    $(function () {




        $("#unsubscribe_all").click(function () {
            $("#regarding_your_request_unsubscribe, #special_offers_unsubscribe, #scams_updates_unsubscribe, #general_updates_unsubscribe").attr("checked", true);
            $("#regarding_your_request_subscribe, #special_offers_subscribe, #scams_updates_subscribe, #general_updates_subscribe").attr("checked", false);
            $("#unsubscribe_lead_form_first").trigger('submit');
        });


        $('.radio_btn').click(function(){
            $('.update_subscription').removeClass('hide');
        });
        
        $("#unsubscribe_lead_form_first").on("submit", function (){
            $(".update_subscription").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".update_subscription").attr("disabled", true);
            
            $.ajax({
                url: '{{ url("lead-unsubscribe-first-step") }}',
                type: 'POST',
                data: $("#unsubscribe_lead_form_first").serialize(),
                success: function (data){
                    $(".update_subscription").html('Update Subscription');
                    $(".update_subscription").attr("disabled", false);
            
                    if (data.success == 1){
                        var regarding_request = $("#regarding_your_request_unsubscribe:checked").val();
                        var special_offers = $("#special_offers_unsubscribe:checked").val();
                        var scams_updates = $("#scams_updates_unsubscribe:checked").val();
                        var general_updates = $("#general_updates_unsubscribe:checked").val();

                        if (regarding_request == 'unsubscribe' && special_offers == 'unsubscribe' && scams_updates == 'unsubscribe' && general_updates == 'unsubscribe') {
                            var option_html = '<p class="mb-0">You have been unsubscribed from all emails.</p><p>You have unsubscribed from receiving any further communication from TrustPatrick.com</p>';
                        } else {
                            var option_html = '<h5>You have been unsubscribed from: </h5>';
                            option_html += '<ul>';
                            if (regarding_request == 'unsubscribe') {
                                option_html += '<li class="font-bold">Regarding Your request</li>';
                            }

                            if (special_offers == 'unsubscribe') {
                                option_html += '<li class="font-bold">Special Promotions/Offers</li>';
                            }

                            if (scams_updates == 'unsubscribe') {
                                option_html += '<li class="font-bold">Scams & Ripoffs Updates</li>';
                            }

                            if (general_updates == 'unsubscribe') {
                                option_html += '<li class="font-bold">General Updates</li>';
                            }

                            option_html += '</ul>';
                        }
                        $("#unsubscribe_reason #selected_unsubscribed_options").html(option_html);

                        $("#unsubscribe_reason").show();
                        $("#unsubscribe_options").hide();
                    } else {
                        Swal.fire({
                            title: data.title,
                            type: data.type,
                            html: data.message
                        });
                    }
                }
            });
            
            return false;
        });

        $("#next_btn").on("click", function () {
            
        });
        
        $("#back_btn").on("click", function () {
            $("#unsubscribe_reason").hide();
            $("#unsubscribe_options").show();
        });


        $(".why_unsubscribe").on("change", function () {
            if ($(".why_unsubscribe:checked").val() == 'other') {
                $("#other_reason").show();
                //$("#other_reason textarea").attr("required", true);
            } else {
                $("#other_reason").hide();
                //$("#other_reason textarea").attr("required", false);
            }
        });

        $("#unsubscribe_lead_btn").on("click", function () {
            var instance = $("#unsubscribe_lead_form").parsley();
            if (!instance.isValid()) {
                $("#unsubscribe_options").show();
            }
        });

        $("#unsubscribe_lead_form").on("submit", function () {
            var instance = $(this).parsley();
            if (instance.isValid()) {
                $("#unsubscribe_lead_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $("#unsubscribe_lead_btn").attr("disabled", true);
            } else {
                $("#unsubscribe_lead_btn").html('Submit');
                $("#unsubscribe_lead_btn").attr("disabled", false);
            }
        });
    });
</script>
@endsection
