<?php
    $city_licensing = $country_licensing = $state_licensing = $no_licensing = false;

    if (!is_null($company_licensing) && $company_licensing->licensing_required != ''){
        $licensing_required_arr = json_decode($company_licensing->licensing_required);

        if (is_array($licensing_required_arr)){
            if (in_array('No licensing is required', $licensing_required_arr)){
                $no_licensing = true;
            }
            if (in_array('State licensing is required', $licensing_required_arr)){
                $state_licensing = true;
            }
            if (in_array('Country licensing is required', $licensing_required_arr)){
                $country_licensing = true;
            }
            if (in_array('City licensing is required', $licensing_required_arr)){
                $city_licensing = true;
            }
        }
    }

?>

<div class="card card-border card-primary">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Licensing Requirements</h3>
        <p class="text-muted">
            Is State, county or city licensing required to perform the services you provide? <span class="required">*</span>
            <br />
            Please choose all that apply
        </p>
    </div>
    <div class="card-body xs_max_width">
        <div class="checkbox">
            {!! Form::checkbox("licensing_required[]", 'No licensing is required', $no_licensing, ['id' =>
            'no_licensing', 'class' => 'licensing_requirements', 'required' => 'true', 'data-parsley-errors-container' => '#licensing_requirements_error']) !!}
            <label for="no_licensing">No licensing is required</label>
        </div>

        <div class="checkbox">
            {!! Form::checkbox("licensing_required[]", 'State licensing is required', $state_licensing, ['id' =>
            'state_licensing', 'class' => 'licensing_requirements',
            'required' => 'true', 'data-parsley-errors-container' => '#licensing_requirements_error']) !!}
            <label for="state_licensing">State licensing is required</label>
        </div>
        <div class="card-box state_licensing_container {{ $state_licensing ? : 'hide' }}">

            <div class="form-group mb-0">
                <label>Is your company state licensed? <span class="required">*</span></label>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("state_licensed", 'yes', null, ['id' =>
                    'state_licensing_yes', 'class' => 'rdo_state_licensing',
                    'data-parsley-errors-container' => '#rdo_state_licensing_error']) !!}
                    <label for="state_licensing_yes">Yes</label>
                </div>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("state_licensed", 'no', null, ['id' =>
                    'state_licensing_no', 'class' => 'rdo_state_licensing',
                    'data-parsley-errors-container' => '#rdo_state_licensing_error']) !!}
                    <label for="state_licensing_no">No</label>
                </div>
            </div>
            <div id="rdo_state_licensing_error"></div>

            <div
                class="form-group {{ !is_null($company_licensing) && $company_licensing->state_licensed == 'yes' ? : 'hide' }} state_licensing_yes_optoins">
                <label>Do you have a copy of your state license on your computer available to upload? <span class="required">*</span></label>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_state_licensed", 'yes', null, ['id' => 'state_licensing_file_yes', 'class' =>
                    'rdo_state_licensing_file', 'data-parsley-errors-container' => '#rdo_state_licensing_file_error'])
                    !!}
                    <label for="state_licensing_file_yes">Yes</label>
                </div>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_state_licensed", 'no', null, ['id' => 'state_licensing_file_no', 'class' =>
                    'rdo_state_licensing_file', 'data-parsley-errors-container' => '#rdo_state_licensing_file_error'])
                    !!}
                    <label for="state_licensing_file_no"> No - I will email/upload a copy to the approval
                        department
                        after submitting the application. </label>
                </div>
                <div id="rdo_state_licensing_file_error"></div>


                <div
                    class="form-group mb-0 {{ !is_null($company_licensing) && $company_licensing->copy_state_licensed == 'yes' ? : 'hide' }} div_state_licensing_file mt-3">
                    <label>Please upload a copy of your state license: <span class="required">*</span></label>
                    <input type="file" name="state_licensed_file_id" id="state_licensed_file_id" class="filestyle state_licensing_file"
                        data-input="false" data-preview="state_licensed_file_id_preview" data-parsley-errors-container="#state_licensing_file_error" accept="image/*,.doc,.docx,.pdf">

                    <div class="state_licensed_file_id_preview preview_file"></div>

                    @if (!is_null($company_licensing) && $company_licensing->state_licensed_file_id > 0)
                    <a data-fancybox="gallery"
                        href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->state_licensed_file->media->file_name]) }}">
                        <i class="mdi mdi-file-pdf font-50"></i>
                    </a>
                    @endif
                </div>
                <div id="state_licensing_file_error"></div>


            </div>

        </div>

        <div class="checkbox">
            {!! Form::checkbox("licensing_required[]", 'Country licensing is required', $country_licensing,
            ['id' => 'country_licensing', 'class' => 'licensing_requirements',
            'required' => 'true', 'data-parsley-errors-container' => '#licensing_requirements_error']) !!}
            <label for="country_licensing">County licensing is required</label>
        </div>
        <div class="card-box country_licensing_container {{ $country_licensing ? : 'hide' }}">

            <div class="form-group mb-0">
                <label>Is your company county licensed? <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("country_licensed", 'yes', null,
                    ['id' => 'country_licensing_yes', 'class' => 'rdo_country_licensing', 'data-parsley-errors-container' => '#rdo_country_licensing_error']) !!}
                    <label for="country_licensing_yes">Yes</label>
                </div>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("country_licensed", 'no', null,
                    ['id' => 'country_licensing_no', 'class' => 'rdo_country_licensing',
                    'data-parsley-errors-container' => '#rdo_country_licensing_error']) !!}
                    <label for="country_licensing_no">No</label>
                </div>
            </div>
            <div id="rdo_country_licensing_error"></div>


            <div class="form-group {{ !is_null($company_licensing) && $company_licensing->country_licensed == 'yes' ? '' : 'hide' }} country_licensing_yes_optoins">
                <label>Do you have a copy of your county license on your computer available to upload? <span
                        class="required">*</span></label>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_country_licensed", 'yes', null, ['id' => 'country_licensing_file_yes', 'class'
                    =>
                    'rdo_country_licensing_file', 'data-parsley-errors-container' => '#rdo_country_licensing_file_error']) !!}
                    <label for="country_licensing_file_yes">Yes</label>
                </div>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_country_licensed", 'no', null, ['id' => 'country_licensing_file_no', 'class'
                    =>
                    'rdo_country_licensing_file', 'data-parsley-errors-container' => '#rdo_country_licensing_file_error']) !!}
                    <label for="country_licensing_file_no"> No - I will email/upload a copy to the approval
                        department
                        after submitting the application. </label>
                </div>
                <div id="rdo_country_licensing_file_error"></div>

                <div class="form-group {{ !is_null($company_licensing) && $company_licensing->copy_country_licensed == 'yes' ? '' : 'hide' }} div_country_licensing_file mt-3">
                    <label>Please upload a copy of your county license: <span class="required">*</span></label>
                    <input type="file" name="country_licensed_file_id" id="country_licensed_file_id" class="filestyle country_licensing_file"
                        data-input="false" data-preview="country_licensed_file_id_preview" data-parsley-errors-container="#country_licensing_file_error" accept="image/*,.doc,.docx,.pdf">

                    <div class="country_licensed_file_id_preview preview_file"></div>

                    @if (!is_null($company_licensing) && $company_licensing->country_licensed_file_id > 0)
                    <a data-fancybox="gallery"
                        href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->country_licensed_file->media->file_name]) }}">
                        <i class="mdi mdi-file-pdf font-50"></i>
                    </a>
                    @endif
                </div>
                <div id="country_licensing_file_error"></div>

            </div>

        </div>



        <div class="checkbox">
            {!! Form::checkbox("licensing_required[]", 'City licensing is required', $city_licensing, ['id' =>
            'city_licensing', 'class' => 'licensing_requirements', 'required' => 'true', 'data-parsley-errors-container' => '#licensing_requirements_error']) !!}
            <label for="city_licensing">City licensing is required</label>
        </div>
        <div class="card-box city_licensing_container {{ $city_licensing ? '' : 'hide' }}">

            <div class="form-group mb-0">
                <label>Is your company city licensed? <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("city_licensed", 'yes', null, ['id' =>
                    'city_licensing_yes', 'data-parsley-errors-container' => '#rdo_city_licensing_error', 'class' => 'rdo_city_licensing']) !!}
                    <label for="city_licensing_yes">Yes</label>
                </div>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("city_licensed", 'no', null, ['id' =>
                    'city_licensing_no', 'data-parsley-errors-container' => '#rdo_city_licensing_error' , 'class' => 'rdo_city_licensing']) !!}
                    <label for="city_licensing_no">No</label>
                </div>
            </div>
            <div id="rdo_city_licensing_error"></div>



            <div
                class="form-group {{ !is_null($company_licensing) && $company_licensing->city_licensed == 'yes' ? '' : 'hide' }} city_licensing_yes_optoins">
                <label>Do you have a copy of your city license on your computer available to upload? <span
                        class="required">*</span></label>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_city_licensed", 'yes', null, ['id' => 'city_licensing_file_yes', 'class' =>
                    'rdo_city_licensing_file', 'data-parsley-errors-container' => '#rdo_city_licensing_file_error'])
                    !!}
                    <label for="city_licensing_file_yes">Yes</label>
                </div>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_city_licensed", 'no', null, ['id' => 'city_licensing_file_no', 'class' =>
                    'rdo_city_licensing_file', 'data-parsley-errors-container' => '#rdo_city_licensing_file_error']) !!}
                    <label for="city_licensing_file_no"> No - I will email/upload a copy to the approval
                        department
                        after submitting the application. </label>
                </div>
                <div id="rdo_city_licensing_file_error"></div>


                <div
                    class="form-group mb-0 {{ !is_null($company_licensing) && $company_licensing->copy_city_licensed == 'yes' ? '' : 'hide' }} div_city_licensing_file mt-3">
                    <label>Please upload a copy of your city license: <span class="required">*</span></label>
                    <input type="file" name="city_licensed_file_id" id="city_licensed_file_id" class="filestyle city_licensing_file"
                        data-input="false" data-preview="city_licensed_file_id_preview" data-parsley-errors-container="#city_licensing_file_error" accept="image/*,.doc,.docx,.pdf">

                    <div class="city_licensed_file_id_preview preview_file"></div>

                    @if (!is_null($company_licensing) && $company_licensing->city_licensed_file_id > 0)
                    <a data-fancybox="gallery"
                        href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->city_licensed_file->media->file_name]) }}">
                        <i class="mdi mdi-file-pdf font-50"></i>
                    </a>
                    @endif
                </div>
                <div id="city_licensing_file_error"></div>


            </div>

        </div>


        <div id="licensing_requirements_error"></div>

    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
    $(function(){


        $('.licensing_requirements').change(function(){

            if ($(this).val() == 'No licensing is required'){
                $('#country_licensing').prop('checked', false);
                $('#state_licensing').prop('checked', false);
                $('#city_licensing').prop('checked', false);

                $('.state_licensing_container').hide();
                $('.country_licensing_container').hide();
                $('.city_licensing_container').hide();



                // remove all required
                $('.rdo_state_licensing').removeAttr('required');
                $('.rdo_state_licensing_file').removeAttr('required');
                $('.state_licensing_file').removeAttr('required');

                $('.rdo_country_licensing').removeAttr('required');
                $('.rdo_country_licensing_file').removeAttr('required');
                $('.country_licensing_file').removeAttr('required');

                $('.rdo_city_licensing').removeAttr('required');
                $('.rdo_city_licensing_file').removeAttr('required');
                $('.city_licensing_file').removeAttr('required');

            }else{

                $('#no_licensing').prop('checked', false);
            }

            refresh_slick_content();;
        });


        // State


        $('#state_licensing').change(function(){
            if ($(this).is(':checked') == true){
                $('.state_licensing_container').show();
                $('.rdo_state_licensing').attr('required', true);
            }else{
                $('.state_licensing_container').hide();
                $('.rdo_state_licensing').removeAttr('required');
            }
            refresh_slick_content();;
        });

        $('.rdo_state_licensing').change(function(){
            switch($(this).val()){
                case 'yes':
                    $('.state_licensing_yes_optoins').show();
                    $('.rdo_state_licensing_file').attr('required', true);
                    break;
                case 'no':
                    $('.state_licensing_yes_optoins').hide();
                    $('.rdo_state_licensing_file').removeAttr('required');
                    break;
            }
            refresh_slick_content();;
        });


        $('.rdo_state_licensing_file').change(function(){
            switch($(this).val()){
                case 'yes':
                    $('.div_state_licensing_file').show();
                    $('.state_licensing_file').attr('required', true);
                    break;
                case 'no':
                    $('.div_state_licensing_file').hide();
                    $('.state_licensing_file').removeAttr('required');
                    break;
            }
            refresh_slick_content();;
        });


        // Country


        $('#country_licensing').change(function(){
            if ($(this).is(':checked') == true){
                $('.country_licensing_container').show();
                $('.rdo_country_licensing').attr('required', true);
            }else{
                $('.country_licensing_container').hide();
                $('.rdo_country_licensing').removeAttr('required');
            }
            refresh_slick_content();;
        });


        $('.rdo_country_licensing').change(function(){
            switch($(this).val()){
                case 'yes':
                    $('.country_licensing_yes_optoins').show();
                    $('.rdo_country_licensing_file').attr('required', true);
                    break;
                case 'no':
                    $('.country_licensing_yes_optoins').hide();
                    $('.rdo_country_licensing_file').removeAttr('required');
                    break;
            }
            refresh_slick_content();;
        });


        $('.rdo_country_licensing_file').change(function(){
            switch($(this).val()){
                case 'yes':
                    $('.div_country_licensing_file').show();
                    $('.country_licensing_file').attr('required', true);
                    break;
                case 'no':
                    $('.div_country_licensing_file').hide();
                    $('.country_licensing_file').removeAttr('required');
                    break;
            }
            refresh_slick_content();;
        });


        // City

        $('#city_licensing').change(function(){
            if ($(this).is(':checked') == true){
                $('.city_licensing_container').show();
                $('.rdo_city_licensing').attr('required', true);
            }else{
                $('.city_licensing_container').hide();
                $('.rdo_city_licensing').removeAttr('required');
            }
            refresh_slick_content();;
        });


        $('.rdo_city_licensing').change(function(){
            switch($(this).val()){
                case 'yes':
                    $('.city_licensing_yes_optoins').show();
                    $('.rdo_city_licensing_file').attr('required', true);
                    break;
                case 'no':
                    $('.city_licensing_yes_optoins').hide();
                    $('.rdo_city_licensing_file').removeAttr('required');
                    break;
            }
            refresh_slick_content();;
        });


        $('.rdo_city_licensing_file').change(function(){
            switch($(this).val()){
                case 'yes':
                    $('.div_city_licensing_file').show();
                    $('.city_licensing_file').attr('required', true);
                    break;
                case 'no':
                    $('.div_city_licensing_file').hide();
                    $('.city_licensing_file').removeAttr('required');
                    break;
            }
            refresh_slick_content();;
        });



    });

</script>
@endpush
