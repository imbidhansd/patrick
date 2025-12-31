<?php
$admin_page_title = 'Unsubscribe Emails';
?>
@extends('company.non_members.layout')

@section ('content')
<?php /* @include('admin.includes.breadcrumb') */ ?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card-box">
            @include('admin.includes.formErrors')
            @include('flash::message')

            {!! Form::open(['url' => 'get-listed/unsubscribe-page/post-unsubscribe', 'class' => 'module_form', 'id' => 'unsubscribe_lead_form']) !!}

            {!! Form::hidden('company_id', $companyObj->id) !!}
            
            <h3 class="text-center text-theme_color">Unsubscribe Successful</h3>
            <p class="text-center text-theme_color font-14">You will no longer receive emails from {{ env('APP_NAME') }}</p>
            <p class="text-muted">If you have a moment, please let us know why you unsubscribed:</p>

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

            <div class="form-group" id="other_reason" style="display: none;">
                {!! Form::textarea('unsubscribe_reason', null, ['class' => 'form-control max', 'maxlength' => 500]) !!}
            </div>

            <div class="clearfix">&nbsp;</div>
            <div class="text-left">
                <button type="submit" class="btn btn-theme_color" id="unsubscribe_lead_btn">Submit</button>

                <br /><br />
                <a href="https://opp.trustpatrick.com/" class="text-muted"><i class="fas fa-angle-double-left"></i> Return to our website</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
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
