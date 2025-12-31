<?php
$admin_page_title = 'Unsubscribe Emails';
?>
@extends('company.layout-without-sidebar')

@section ('content')
@include('admin.includes.breadcrumb')

<div class="card-box">
    @include('admin.includes.formErrors')
    @include('flash::message')

    {!! Form::open(['url' => 'unsubscribe-page/company/post-unsubscribe', 'class' => 'module_form', 'id' => 'unsubscribe_lead_form']) !!}

    {!! Form::hidden('company_slug', $companyObj->slug) !!}
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card card-border card-primary">
                <div class="card-header border-primary bg-transparent">
                    <h3 class="card-title text-primary mb-0">We're sorry to see you go!</h3>
                    <p class="text-muted mb-0">
                        Would you please take just a moment and let us know why you unsubscribed?
                    </p>
                </div>
                <div class="card-body">
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

                    <div class="clearfix">&nbsp;</div>

                    <div class="form-group" id="other_reason" style="display: none;">
                        {!! Form::textarea('unsubscribe_reason', null, ['class' => 'form-control max', 'maxlength' => 500]) !!}
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary" id="unsubscribe_lead_btn">Submit</button>
                
                <br /><br />
                <a href="{{ url('/') }}" class="text-muted"><i class="fas fa-angle-double-left"></i> Return to our website</a>
            </div>

        </div>
    </div>

    {!! Form::close() !!}
</div>
@endsection

@section ('page_js')
<script type="text/javascript">
    $(function () {
        $(".why_unsubscribe").on("change", function () {
            if ($(".why_unsubscribe:checked").val() == 'other') {
                $("#other_reason").show();
                $("#other_reason textarea").attr("required", true);
            } else {
                $("#other_reason").hide();
                $("#other_reason textarea").attr("required", false);
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
