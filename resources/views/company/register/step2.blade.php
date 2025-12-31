{!! Form::open(['url' => 'register/step2','id' => 'step2_form', 'class' => 'module_form', 'files' => true]) !!}

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <label>Company Name <span class="required">*</span></label>
            {!! Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => '',
            'required'
            =>
            true, 'maxlength' => 255, 'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Company Website</label>
            {!! Form::text('company_website', null, ['class' => 'form-control', 'placeholder' => '',
            'required' =>
            false, 'maxlength' => 255]) !!}
            <span class="help-block text-info"><small>Leave Blank If No Website</small></i>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Main Company Telephone <span class="required">*</span></label>
            {!! Form::text('main_company_telephone', null, ['class' => 'form-control',
            'required' => true, 'maxlength' => 255, 'data-toggle' =>
            'input-mask',
            'data-mask-format' => '(000) 000-0000', 
            'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Secondary Telephone</label>
            {!! Form::text('secondary_telephone', null, ['class' => 'form-control',
            'required' => false, 'maxlength' => 255,
            'data-toggle' => 'input-mask',
            'data-mask-format' => '(000) 000-0000']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Company Mailling Address <span class="required">*</span></label>
            {!! Form::text('company_mailing_address', null, ['class' => 'form-control', 
                'required' => true, 
                'maxlength' => 255, 
                'id' => 'autocomplete-address', 
                'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Apt/Suite</label>
            {!! Form::text('suite', null, ['class' => 'form-control', 'required' => false, 
                'maxlength' => 255, 
                'id' => 'autocomplete-suite']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>City <span class="required">*</span></label>
            {!! Form::text('city', null, ['class' => 'form-control', 
                'required' => true, 
                'maxlength' => 255, 
                'id' => 'autocomplete-city',
                'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>State <span class="required">*</span></label>
            {!! Form::select('state_id', $states, null, ['class' => 'form-control custom-select',
            'required' => true, 'maxlength' => 255, 
            'id' => 'autocomplete-state', 
            'placeholder' => 'Select', 
            'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>   

    <div class="col-md-6">
        <div class="form-group">
            <label>Zipcode <span class="required">*</span></label>
            {!! Form::text('zipcode', null, ['class' => 'form-control last_input',
            'required' => true, 'maxlength' => 5,
            'data-toggle' => 'input-mask',
            'data-mask-format' => '00000', 
            'id' => 'autocomplete-zipcode', 
            'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

</div>

<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right last_input step2_submit btn-bg-default">Save & Next</button>

{!! Form::close() !!}



@push('page_scripts')

<script type="text/javascript">
    $(function(){

            // Step 1 Form Submission Event
            $('#step2_form').submit(function(){

                $(".step2_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

                $.ajax({
                    type: 'POST',
                    url: $('#step2_form').attr('action'),
                    data: $('#step2_form').serialize(),
                    success: function(data){

                        if (data.status == 0){
                            Swal.fire(
                                    'Warning',
                                    data.message,
                                    'warning'
                                );
                        }else{
                            slick_next();
                        }
                        $(".step2_submit").removeAttr('disabled').html('Save & Next');
                    },
                    error: function(e){
                        Swal.fire(
                            'Warning',
                            'Error while processing',
                            'warning'
                        );
                        $(".step2_submit").removeAttr('disabled').html('Save & Next');
                    },
                });

                return false;
            });
        });
        
        function initAutocomplete() {
            let address1Field = document.querySelector("#autocomplete-address");                       
            autocomplete = new google.maps.places.Autocomplete(address1Field, {
                componentRestrictions: { country: ["us"] },
                fields: ["address_components"],
                types: ["address"],
            });
            address1Field.focus();          
            autocomplete.addListener("place_changed", fillInAddress);
        }
        function fillInAddress() {
            const place = autocomplete.getPlace(); 

            for (const component of place.address_components) {
                const componentType = component.types[0];
                switch (componentType) {    
                    case "postal_code": {
                        document.querySelector("#autocomplete-zipcode").value = component.long_name;
                        break;
                    }                
                    case "locality": {
                        document.querySelector("#autocomplete-city").value = component.long_name;
                        break;
                    }                        
                    case "administrative_area_level_1": {
                        selectedState = component.long_name;
                        var autocompleteStateElement = document.getElementById('autocomplete-state');
                        var autocompleteStateOptions = autocompleteStateElement.options;

                        for (var i = 0; i < autocompleteStateOptions.length; i++) {
                            if (autocompleteStateOptions[i].text.toLowerCase() === selectedState.toLowerCase()) {
                                autocompleteStateOptions[i].selected = true;
                                break;
                            }
                        }
                        break;
                    }
                }
            }           
            
            document.querySelector("#autocomplete-suite").focus();
        }

            window.initAutocomplete = initAutocomplete;
</script>

@endpush
