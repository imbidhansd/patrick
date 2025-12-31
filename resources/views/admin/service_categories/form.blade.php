<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>Service Category Type:</label>
            {!! Form::select('service_category_type_id', $service_category_types, null,
            ['class' => 'custom-select', 'placeholder' => 'Select Service Category Type']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Top Level Category:</label>
            {!! Form::select('top_level_category_id', $top_level_categories, null, ['class' => 'form-control
            custom-select',
            'placeholder' => 'Select Top Level Category', 'id' =>
            'top_level_category_id']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Main Category:</label>
            {!! Form::select('main_category_id', $main_categories, null, ['class' => 'form-control custom-select', 'id'
            =>
            'main_category_id']) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>
    <?php /* <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Abbreviation') !!}
            {!! Form::text('abbr', null, ['class' => 'form-control max', 'placeholder' => 'Enter Abbreviation', 'required' => false, 'maxlength' => 20]) !!}
        </div>
    </div> */ ?>
    
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Tags') !!}
            {!! Form::text('tags', null, ['class' => 'form-control', 'placeholder' => 'Enter Tags', 'data-role' => 'tagsinput', 'required' => false]) !!}
            <i>Note: You can Add multiple tags using "Tab" key</i>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('PPL Price') !!}
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('ppl_price', null, ['id' => 'ppl_price','class' => 'form-control text-right', 'required'
                => true,
                'data-parsley-type' => 'digits']) !!}
                <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                </div>
            </div>
        </div>
    </div>

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
