{!! Form::open(['url' => url('update-company-profile'), 'class' => 'module_form company_logo', 'files' => true]) !!}

{!! Form::hidden('update_type', 'company_logo') !!}
<div class="form-group">
    <input type="file" name="company_logo" class="filestyle" data-input="false" accept="image/x-png,image/gif,image/jpeg" />
</div>
{!! Form::close() !!}