<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Sub Title') !!}
            {!! Form::text('sub_title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Sub Title', 'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Charges Upon Approval Text') !!}
            {!! Form::text('charges_on_approval', null, ['class' => 'form-control max', 'placeholder' => 'Enter Charges Upon Approval Text', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
    
    <?php /* <div class="col-md-6">
        <div class="form-group">
            {!! Form::label("What's included") !!}
            {!! Form::text('whats_included', null, ['class' => 'form-control max', 'placeholder' => "Enter What's included", 'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div> */ ?>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Vimeo Video ID') !!}
            {!! Form::text('video_id', null, ['class' => 'form-control max', 'placeholder' => 'Enter Vimeo Video ID', 'maxlength' => 255]) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label>Membership Level Status:</label>
            {!! Form::select('membership_status_id[]', $membership_statuses, is_object($membership_level_statuses) ? $membership_level_statuses->toArray() : null, ['class' => 'form-control select2', 'multiple' => 'multiple']) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Day Limit?') !!}
            {!! Form::select('day_limit', ['yes' => 'Yes', 'no' => 'No'], null, ['id' => 'day_limit','class' =>
            'form-control custom-select', 'placeholder' =>
            'Select',
            'required' =>
            true]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Number Of Days') !!}
            <div class="input-group">
                {!! Form::text('number_of_days', null, ['id' => 'number_of_days','class' => 'form-control text-right',
                'data-parsley-type' => 'digits']) !!}
                <div class="input-group-append">
                    <span class="input-group-text">Days</span>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Is Paid Member?') !!}
            {!! Form::select('paid_members', ['yes' => 'Yes', 'no' => 'No'], null, ['id' => 'paid_members','class' =>
            'form-control custom-select',
            'required' =>
            true]) !!}
        </div>
    </div>



    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Membership Fees') !!}
            <div class="input-group">
                <div class="input-group-append">
                    <span class="input-group-text">$</span>
                </div>
                {!! Form::text('membership_fee', null, ['id' => 'membership_fee','class' => 'form-control text-right', 'required' => false, 'data-parsley-type' => 'digits']) !!}
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Can Access Leads?') !!}
            {!! Form::select('lead_access', ['yes' => 'Yes', 'no' => 'No'], null, ['id' => 'paid_member','class' =>
            'form-control custom-select',
            'required' =>
            true]) !!}
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Hide Leads?') !!}
            {!! Form::select('hide_leads', ['yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Charge Type') !!}
            {!! Form::select('charge_type', ['annual_price' => 'Annual', 'monthly_price' => 'Monthly', 'ppl_price' => 'Per Lead'], null, ['id' => 'charge_type', 'class' => 'custom-select custom- select', 'placeholder' => 'Select']) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Color') !!}
            <?php
                $color_arr = [
                    'secondary' => 'Secondary',
                    'primary' => 'Primary',
                    'success' => 'Success',
                    'info' => 'Info',
                    'warning' => 'Warning',
                    'danger' => 'Danger',
                    'dark' => 'Dark',
                    'purple' => 'Purple',
                    'pink' => 'Pink',
                    'orange' => 'Orange',
                    'brown' => 'Brown',
                    'teal' => 'Teal',
                    'theme_color' => 'Theme color'
                ];
            ?>
            {!! Form::select('color', $color_arr, null, ['id' => 'color','class' =>
            'form-control custom-select',
            'required' =>
            true]) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Is Popular?') !!}
            {!! Form::select('is_popular', ['yes' => 'Yes', 'no' => 'No'], null, ['id' => 'is_popular','class' =>
            'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Pay by Check available?') !!}
            {!! Form::select('pay_by_check', ['yes' => 'Yes', 'no' => 'No'], null, ['id' => 'is_popular','class' => 'form-control custom-select', 'required' => true]) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Lead Pause Message') !!}
            {!! Form::textarea('pause_lead_message', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Short Content') !!}
            {!! Form::textarea('short_content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Renew Content') !!}
            {!! Form::textarea('renew_content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Terms & Conditions') !!}
            {!! Form::textarea('terms_content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' =>
                'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
