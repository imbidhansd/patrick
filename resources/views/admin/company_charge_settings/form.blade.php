<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'required'
            => 'true']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Slug') !!}
            {!! Form::text('slug', null, ['class' => 'form-control', 'placeholder' => '', 'required' => true]) !!}
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Amount') !!}
            {!! Form::text('amount', null, ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
