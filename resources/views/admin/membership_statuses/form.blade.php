<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Title', 'required' => true, 'maxlength' => 255, 'readonly' => true]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Receive Leads') !!}
            <div class="select">
                {!! Form::select('receive_leads', ['yes' => 'Yes', 'no' => 'No'], null, ['class' =>
                'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Color') !!}
            <?php
                $color_arr = [
                    'secondary' => 'Secondary',
                    'primary' => 'Primary',
                    'success' => 'Success',
                    'info' => 'Info',
                    'warning' => 'Warning',
                    'danger' => 'Danger',
                    'dark' => 'Dark',
                    'purple' => 'Purple',
                    'pink' => 'Pink',
                    'orange' => 'Orange',
                    'brown' => 'Brown',
                    'teal' => 'Teal',
                    'theme_color' => 'Theme color'
                ];
            ?>
            {!! Form::select('color', $color_arr, null, ['id' => 'color','class' => 'form-control custom-select', 'required' => true]) !!}
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
