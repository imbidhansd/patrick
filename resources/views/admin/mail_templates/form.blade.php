<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Subject') !!}
            {!! Form::text('subject', null, ['class' => 'form-control max', 'placeholder' => 'Enter Subject', 'required'
            => true, 'maxlength' => 255]) !!}
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Email Template') !!}
            {!! Form::textarea('html_template', null, ['class' => 'form-control ckeditor']) !!}


            @if (isset($mail_variables) && count($mail_variables) > 0)
            <div class="clearfix">&nbsp;</div>
            <label>Mail Variables</label><br />
            @foreach ($mail_variables as $variable_item)
            <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $loop->index }}"
                data-clipboard-target="#var_{{ $loop->index }}"
                class="badge badge-info badge-label variable">{{ '{'.'{ '  .$variable_item.  ' }'.'}' }}</span>
            @endforeach
            @endif

        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email For') !!}
            <div class="select">
                {!! Form::select('email_for', ['Site User' => 'Site User', 'Company' => 'Company', 'Admin' => 'Admin'],
                null, ['class' => 'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
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
