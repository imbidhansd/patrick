<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Trade Name') !!}
            {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'id' => 'trade_id',
            'placeholder' =>
            'All', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Service Category Type') !!}
            {!! Form::select('service_category_type_id', $service_category_type, null, ['class' => 'form-control
            custom-select',
            'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Top Level Category') !!}
            {!! Form::select('top_level_category_id', $top_level_category, null, ['class' => 'form-control
            custom-select', 'id' =>
            'top_level_category_id', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Main Category') !!}
            {!! Form::select('main_category_id', $main_category, null, ['class' => 'form-control custom-select', 'id' =>
            'main_category_id', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Company List') !!}
            {!! Form::select('company_id[]', $companies, ((isset($selected_companies) && count($selected_companies) > 0)
            ? $selected_companies : null), ['class' => 'form-control select2', 'multiple' => 'multiple',
            'required' =>
            true]) !!}
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
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Og Image', 'ref_func' => 'media', 'formObj' =>
        isset($formObj) ? $formObj : null])
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Video Embed Url') !!}
            {!! Form::text('embed_url', null, ['class' => 'form-control max', 'placeholder' => 'Enter Video Embed Url',
            'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Podcast Url') !!}
            {!! Form::text('podcast_url', null, ['class' => 'form-control max', 'placeholder' => 'Enter Podcast Url',
            'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Vimeo Url') !!}
            {!! Form::text('vimeo_url', null, ['class' => 'form-control max', 'placeholder' => 'Enter Vimeo Url',
            'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Description') !!}
            {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Internal Tags') !!}
            <div class="select">
                {!! Form::text('tags', null, ['class' => 'form-control', 'data-role' => 'tagsinput', 'placeholder' =>
                'Internal Tags', 'required' => true]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Zipcode') !!}
            <div class="select">
                {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Zipcode', 'data-toggle' =>
                'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Miles') !!}
            <div class="select">
                {!! Form::select('mile_range', config('config.mile_options'), null, ['class' => 'form-control custom-select', 'placeholder'
                => 'Select
                Mile', 'required' => true]) !!}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Index') !!}
            <div class="select">
                {!! Form::select('index', ['Index' => 'Index', 'No Index' => 'No Index'], null, ['class' =>
                'form-control custom-select', 'required' => true]) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Follow') !!}
            <div class="select">
                {!! Form::select('follow', ['Follow' => 'Follow', 'No Follow' => 'No Follow'], null, ['class' =>
                'form-control custom-select', 'required' => true]) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['Publish' => 'Publish', 'Not Publish' => 'Not Publish'], null, ['class' =>
                'form-control custom-select', 'required' => true]) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
