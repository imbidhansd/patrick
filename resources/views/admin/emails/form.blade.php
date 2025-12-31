<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'id' => 'email_title', 'placeholder' => 'Enter Title', 'required' => true, 'readonly' => true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Subject') !!}
            {!! Form::text('subject', null, ['class' => 'form-control max', 'placeholder' => 'Enter Subject', 'required'
            => true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email Type') !!}
            {!! Form::select('email_type', ['custom_email' => 'Custom Email', 'ern_email' => 'ERN Email', 'user_email' => 'User Email'], null, ['class' => 'form-control custom-select', 'id' => 'email_type', 'required' => 'required']) !!}
        </div>
    </div>
</div>

<hr />

<?php /* <div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Header Content') !!}
            {!! Form::textarea('email_header', ((isset($default_email)) ? $default_email->email_header : null), ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div> */?>

{!! Form::hidden('email_header', ((isset($default_email)) ? $default_email->email_header : null)) !!}

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Email Content') !!}
            {!! Form::textarea('email_content', null, ['class' => 'form-control ckeditor']) !!}

            <div id="mail_variables">
                @php
                    $mail_variables = config('email_keywords.common');
                @endphp

                @if (isset($mail_variables) && count($mail_variables) > 0)
                <div class="clearfix">&nbsp;</div>
                <label>Mail Content Variables</label><br />
                @foreach ($mail_variables as $variable_item)
                <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $loop->index }}"
                    data-clipboard-target="#var_{{ $loop->index }}"
                    class="badge badge-info badge-label variable">{{ $variable_item }}</span>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<?php /* <div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Footer Content') !!}
            {!! Form::textarea('email_footer', ((isset($default_email)) ? $default_email->email_footer : null), ['class' => 'form-control ckeditor']) !!}

            @php
                $mail_variables = config('email_keywords.footer');
            @endphp

            @if (isset($mail_variables) && count($mail_variables) > 0)
            <div class="clearfix">&nbsp;</div>
            <label>Footer Content Variables</label><br />
            @foreach ($mail_variables as $variable_item)
            <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $loop->index }}"
                data-clipboard-target="#var_{{ $loop->index }}"
                class="badge badge-info badge-label variable">{{ $variable_item }}</span>
            @endforeach
            @endif
        </div>
    </div>
</div> */ ?>

{!! Form::hidden('email_footer', ((isset($default_email)) ? $default_email->email_footer : null)) !!}

<hr />

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Send Time') !!}
            {!! Form::text('sendtime', null, ['class' => 'form-control', 'placeholder' => 'Enter Send Time', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Send Time Selection') !!}
            {!! Form::select('sendtime_selection', ['Seconds' => 'Seconds', 'Minutes' => 'Minutes', 'Hours' => 'Hours', 'Days' => 'Days'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email For') !!}
            {!! Form::select('email_for', $email_for, null, ['class' => 'form-control custom-select', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => 'required']) !!}
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
