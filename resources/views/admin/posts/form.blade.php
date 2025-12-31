<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Category') !!}
            {!! Form::select('post_category_id', $post_categories, null, ['class' => 'form-control', 'placeholder' =>
            'Select Category', 'required' =>
            true]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Post Image', 'ref_func' => 'media','formObj' =>
        isset($formObj) ? $formObj : null])
    </div>
</div>

@include ('admin.includes._meta_fields')

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
                'form-control', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
