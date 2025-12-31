@if (isset($options) && count($options))

<div class="list_actions">
    <div class="row">
        <div class="col-md-8 col-sm-8">
            <div class="select">
                {!! Form::select('action', $options, null, ['data-parsley-required-message' => 'Select any option',
                'required' => true, 'id' => 'actionSel', 'disabled' => true, 'class' => 'form-control custom-select',
                'placeholder' =>
                'Select Option']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
        <div class="col-md-4 col-sm-4">
            <button type="submit" class="btn btn-primary index-form-btn" disabled="">Submit</button>
        </div>
    </div>
</div>

@endif
