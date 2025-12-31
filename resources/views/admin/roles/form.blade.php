<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Role Name') !!}
            {!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' =>
            true]) !!}
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
