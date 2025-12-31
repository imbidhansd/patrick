<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Content Type') !!}
            {!! Form::select('content_type', ['header' => 'Header', 'footer' => 'Footer'], null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Content Type', 'required' => true]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
            
            
            @php
            $mail_variables = config('common_email_keywords');
            @endphp

            @if (isset($mail_variables) && count($mail_variables) > 0)
            <div class="clearfix">&nbsp;</div>
            <label>Header/Footer Content Variables</label><br />
            @foreach ($mail_variables as $variable_item)
            <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $loop->index }}" data-clipboard-target="#var_{{ $loop->index }}" class="badge badge-info badge-label variable">{{ $variable_item }}</span>
            @endforeach
            @endif
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>