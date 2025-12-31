{!! Form::open(['url' => route('post-background-check-step2'),'id' => 'step2_form', 'class' => 'module_form', 'files' => true]) !!}

<h4>Personal Information</h4>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('First Name') !!} <span class="required">*</span>
            {!! Form::text('first_name', $company_user->first_name, ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Middle Name') !!} <span class="required">*</span>
            {!! Form::text('middle_name', null, ['class' => 'form-control', 'placeholder' => 'Middle Name', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Last Name') !!} <span class="required">*</span>
            {!! Form::text('last_name', $company_user->last_name, ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email') !!} <span class="required">*</span>
            {!! Form::email('email', $company_user->email, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Telephone') !!} <span class="required">*</span>
            {!! Form::text('telephone', null, ['class' => 'form-control', 'placeholder' => 'Telephone', 'maxlength' => 12, 'data-toggle' => 'input-mask', 'data-mask-format' => '(000)-000-0000', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Gender') !!} <span class="required">*</span>
            {!! Form::select('gender', ['M' => 'Male', 'F' => 'Female'] , null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Gender', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Date Of Birth') !!} <span class="required">*</span>

            <div class="input-group">
                {!! Form::text('birth_date', null, ['class' => 'form-control bdt_date_field', 'autocomplete' => 'off', 'placeholder' => 'MM/DD/YYYY', 'data-toggle' => 'input-mask', 'data-mask-format' => '00/00/0000', 'required' => true]) !!}
                <div class="input-group-append">
                    <span class="input-group-text bg-primary text-white b-0"><i class="mdi mdi-calendar"></i></span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('SSN') !!} <span class="required">*</span>
            {!! Form::text('ssn', null, ['class' => 'form-control', 'placeholder' => 'Enter SSN', 'maxlength' => 11, 'data-toggle' => 'input-mask', 'data-mask-format' => '000-00-0000', 'required' => true]) !!}
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label>Please upload a copy of driver's license: <span
                class="required">*</span></label>
                <input type="file" name="driver_license" id="driver_license" required="true" 
                class="filestyle driver_license_file" data-preview="driver_license_preview" data-input="false" data-parsley-errors-container="#driver_license_error" accept="image/*,.doc,.docx,.pdf">
                <div id="driver_license_error"></div>
                <div class="driver_license_preview preview_file"></div>
            </div>
            
        </div>


    </div>
    <hr/>




    <h4>Address Information</h4>

    <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('address_line_1', null, ['class' => 'form-control', 'placeholder' => 'Address Line 1', 'required' => true, 'id'=> 'autocomplete_address']) !!}    
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('address_line_2', null, ['class' => 'form-control', 'placeholder' => 'Address Line 2', 'required' => false, 'id' => 'autocomplete-suite']) !!}    
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City', 'required' => true, 'id'=> 'autocomplete_city']) !!}    
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::select('state', $states, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select State', 'required' => true, 'id'=> 'autocomplete_state']) !!}    
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Zipcode', 'maxlength' => 5, 'data-parsley-type' => 'integer', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true, 'id'=> 'autocomplete_zipcode']) !!}    
                </div>
            </div>
        </div>

    <button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
    <button type="submit" class="btn btn-info float-md-right step2_submit last_input">Save & Next</button>
    {!! Form::close() !!}

    @push('page_scripts')
    <script type="text/javascript">
        $(function(){

        // Step 1 Form Submission Event
        $('#step2_form').submit(function(){
            

            $(".step2_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            var form = $('#step2_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            $.ajax({
                url: $('#step2_form').attr('action'),
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function(data){
                    if (data.status == 0){
                        Swal.fire(
                            'Warning',
                            data.message,
                            'error'
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
                        'error'
                        );
                    $(".step2_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            // Ajax call of step 1 [End]
            return false;
            
        });

    });

    function initAutocomplete() {
        let address1Field = document.querySelector("#autocomplete_address");                       
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
                    document.querySelector("#autocomplete_zipcode").value = component.long_name;
                    break;
                }                
                case "locality": {
                    document.querySelector("#autocomplete_city").value = component.long_name;
                    break;
                }                        
                case "administrative_area_level_1": {
                    selectedState = component.long_name;
                    var autocompleteStateElement = document.getElementById('autocomplete_state');
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