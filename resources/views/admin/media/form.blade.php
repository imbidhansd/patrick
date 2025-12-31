<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('File') !!}
            {!! Form::file('file[]', ['class' => 'filestyle', 'multiple' => 'true', 'required' => 'required']) !!}
            <br />
            <i>Note: You can upload multiple images</i>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
