<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Networx') !!}
            {!! Form::text('networx_id', null, ['class' => 'form-control max', 'placeholder' => 'Enter Networx', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Task Name') !!}
            {!! Form::text('task_name', null, ['class' => 'form-control max', 'placeholder' => 'Enter Task Name', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Task ID') !!}
            {!! Form::text('task_id', null, ['class' => 'form-control max', 'placeholder' => 'Enter Task ID', 'required' => true, 'maxlength' => 255]) !!}
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>