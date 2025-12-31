<div class="clearfix"></div>
<hr />
<h5>Meta Information</h5>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Meta Title') !!}
            {!! Form::text('meta_title', null, ['class' => 'form-control max', 'maxlength' => 1000]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Meta Keywords') !!}
            {!! Form::text('meta_keywords', null, ['class' => 'form-control max', 'maxlength' => 1000]) !!}
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Meta Description') !!}
            {!! Form::textarea('meta_description', null, ['class' => 'form-control max', 'maxlength' => 1000]) !!}
        </div>
    </div>
</div>
<div class="clearfix"></div>
<hr />
