<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
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
                'required'
                => true,
                'data-parsley-type' => 'digits']) !!}
                <div class="input-group-append">
                    <span class="input-group-text">Days</span>
                </div>
            </div>
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
