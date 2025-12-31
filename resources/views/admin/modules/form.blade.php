<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Module Category') !!}
            {!! Form::select('module_category_id', $module_categories, null, ['class' => 'form-control custom-select',
            'placeholder'
            =>
            'Select Category', 'required' =>
            true]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Permissions') !!}
            {!! Form::text('permissions', isset($formObj) ? $formObj->permissions : 'view, create, edit, delete',
            ['class' => 'form-control', 'data-role' => 'tagsinput', 'placeholder' => 'Enter Permissions', 'required' =>
            true]) !!}
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
