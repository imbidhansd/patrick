{!! Form::open(['url' => url('update-profile'), 'class' => 'module_form', 'id' => 'company_user_logo_form', 'files' => true]) !!}
{!! Form::hidden('update_type', 'company_user_logo') !!}
<div class="form-group">
    <input type="file" name="media" id="company_user_logo" class="filestyle" data-input="false" accept="image/*" />
</div>
{!! Form::close() !!}
