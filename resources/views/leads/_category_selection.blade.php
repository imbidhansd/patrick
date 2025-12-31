<h3>Service Selection</h3>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Service Needed') !!}
            {!! Form::select('main_category_id', $main_categories, null, ['class' => 'form-control custom-select', 'id' => 'main_category_id', 'placeholder' => 'Select Service', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Sub Service Needed') !!}
            {!! Form::select('service_category_id', [], null, ['class' => 'form-control custom-select', 'id' => 'service_category_id', 'placeholder' => 'Select Sub Service', 'required' => true]) !!}
        </div>		
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('What is your timeframe for the work to be completed') !!}
            {!! Form::select('timeframe', $timeframe, null, ['class' => 'form-control custom-select', 'id' => 'service_category_id', 'placeholder' => 'Select Timeframe', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-9">
        <div class="form-group">
            {!! Form::label('Project Address') !!}
            {!! Form::text('project_address', null, ['class' => 'form-control', 'placeholder' => 'Enter Project Address', 'required' => true]) !!}
        </div>		
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Zipcode') !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control', 'id' => 'zipcode', 'placeholder' => 'Enter Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
        </div>		
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Project Info') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Enter Project Info', 'required' => false]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Full Name') !!}
            {!! Form::text('full_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Full Name', 'required' => true]) !!}
        </div>	
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Email') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Phone') !!}
            {!! Form::text('phone', null, ['class' => 'form-control', 'id' => 'phone', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="text-center">
    <div class="clearfix">&nbsp;</div>
    <button type="submit" class="btn btn-primary">Submit Request</button>
    <div class="clearfix">&nbsp;</div>
    <p>
        By clicking 'Submit Request' You agree to our Terms of Use and our TCPA Policy. <br />
        We respect your email privacy.
    </p>
</div>