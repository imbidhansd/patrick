<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Link') !!}
            {!! Form::text('link', null, ['class' => 'form-control', 'placeholder' => 'Enter Link', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Image', 'ref_func' => 'media', 'formObj' => isset($formObj) ? $formObj : null])
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
            
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
