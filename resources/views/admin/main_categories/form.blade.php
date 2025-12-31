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
            {!! Form::text('abbr', null, ['class' => 'form-control max', 'placeholder' => 'Enter Abbreviation',
            'required'
            => true, 'maxlength' => 20]) !!}
        </div>
    </div> */ ?>
    
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Tags') !!}
            {!! Form::text('tags', null, ['class' => 'form-control', 'placeholder' => 'Enter Tags', 'data-role' => 'tagsinput', 'required' => false]) !!}
            <i>Note: You can Add multiple tags using "Tab" key</i>
        </div>
    </div>
    
    <div class="col-md-12">
        <div class="form-group">
            <label>Top Level Category:</label>
            {!! Form::select('top_level_categories[]', $top_level_categories,
            is_object($main_category_top_level_categories) ? $main_category_top_level_categories->toArray() : null,
            ['class' => 'select2 form-control', 'multiple' => 'multiple']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Image Link') !!}
            {!! Form::text('image_link', null, ['class' => 'form-control', 'placeholder' => 'Enter Image Link', 'required' => false]) !!}
        </div>
    </div>
    
    <?php /* <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Image', 'ref_func' => 'media','formObj' => isset($formObj) ? $formObj : null])
    </div> */ ?>
    
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
<div class="clearfix">&nbsp;</div>
<hr />
<h5>Pricing</h5>
<div class="clearfix">&nbsp;</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Annual Price') !!}
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('annual_price', ($new_form) ? env('DEFAULT_ANNUAL_PRICE'): null, ['class' => 'form-control text-right', 'required' => true,
                'data-parsley-type' => 'digits']) !!}
                <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Monthly Price') !!}
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('monthly_price', ($new_form) ? env('DEFAULT_MONTHLY_PRICE'): null, ['class' => 'form-control text-right', 'required' => true,
                'data-parsley-type' => 'digits']) !!}
                <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('PPL Price') !!}
            <div class="input-group mt-3">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                </div>
                {!! Form::text('ppl_price', ($new_form) ? env('DEFAULT_PPL_PRICE'): null, ['class' => 'form-control text-right', 'required' => true,
                'data-parsley-type' => 'digits']) !!}
                <div class="input-group-append">
                    <span class="input-group-text">.00</span>
                </div>
            </div>
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <h4>Top Search</h4>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Available for Top Search?') !!}
            {!! Form::select('top_search_status', ['yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control custom-select', 'required' => false]) !!}
        </div>
    </div>
    
    @if(isset($formObj))
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Sort Order') !!}
            {!! Form::text('top_search_sort_order', null, ['class' => 'form-control', 'placeholder' => 'Enter Order', 'required' => false]) !!}
        </div>
    </div>
    @endif
</div>
<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
