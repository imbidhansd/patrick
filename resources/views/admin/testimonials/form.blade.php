<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Company Name') !!}
            {!! Form::text('company_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter Company Name',
            'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Owner Name') !!}
            {!! Form::text('owner_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter Company Name',
            'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Image', 'ref_func' => 'media','formObj' =>
        isset($formObj) ? $formObj : null])
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Website') !!}
            {!! Form::text('website', null, ['class' => 'form-control max', 'placeholder' => 'Enter Website', 'required'
            =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Telephone') !!}
            {!! Form::text('telephone', null, ['class' => 'form-control', 'placeholder' => 'Enter Telephone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('City') !!}
            {!! Form::text('city', null, ['class' => 'form-control max', 'placeholder' => 'Enter Telephone', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('State') !!}
            {!! Form::select('state_id', $states, null, ['class' => 'form-control custom-select max', 'placeholder' => 'Select', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Zipcode') !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control max', 'placeholder' => 'Enter Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => false, 'maxlength' => 10]) !!}
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
