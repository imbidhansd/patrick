{!! Form::hidden('proof_of_ownership', null, ['id' => 'hid_proof_of_ownership']) !!}
<div class="card card-border card-primary">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Business Entity Information</h3>
    </div>
    <div class="card-body xs_max_width">

        <div class="form-group mb-0">
            <label>Is your company legally registered within the state you operate? <span
                    class="required">*</span></label>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("legally_registered_within_state",
                'yes', null, ['id' => 'company_legally_registered_within_state_you_oprated_yes', 'class' =>
                'company_legally_registered_within_state_you_oprated',
                'required' => true,
                'data-parsley-errors-container' => '#company_legally_registered_within_state_you_oprated_error',
                ]) !!}
                <label for="company_legally_registered_within_state_you_oprated_yes">Yes</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("legally_registered_within_state", 'no',
                null, ['id' => 'company_legally_registered_within_state_you_oprated_no',
                'class' => 'company_legally_registered_within_state_you_oprated', 'required' => true,
                'data-parsley-errors-container' => '#company_legally_registered_within_state_you_oprated_error'
                ]) !!}
                <label for="company_legally_registered_within_state_you_oprated_no">No</label>
            </div>
        </div>
        <div id="company_legally_registered_within_state_you_oprated_error"></div>


        <div class="form-group {{ !is_null($company_licensing) && $company_licensing->legally_registered_within_state == 'yes' ? '' : 'hide' }}"
            id="copy_of_state_business_registration">
            <label>Do you have a copy of your state business registration on your computer available to upload?: <span class="required">*</span>
                <br />
                
                <small><i>This could be a certificate of good standing from your secretary of state or formation papers for your LLC or corporation.</i></small>
                
                <?php /* This could be the formation papers for your LLC, Corporation, Partnership or Sole Proprietorship or a bank statement. */ ?>
            </label>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("state_business_registeration", 'yes', null, ['id' =>
                'copy_of_your_state_business_registration_yes', 'class' =>
                'copy_of_your_state_business_registration',
                'data-parsley-errors-container' => '#copy_of_your_state_business_registration_error'
                ]) !!}
                <label for="copy_of_your_state_business_registration_yes">Yes</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("state_business_registeration", 'no', null, ['id' =>
                'copy_of_your_state_business_registration_no', 'class' =>
                'copy_of_your_state_business_registration',
                'data-parsley-errors-container' => '#copy_of_your_state_business_registration_error'
                ]) !!}
                <label for="copy_of_your_state_business_registration_no">No - I will email/upload a copy to the approval department after submitting the application.</label>
            </div>
            <div id="copy_of_your_state_business_registration_error"></div>

            <?php
                $div_state_business_registration_file_class = 'hide';
                if (isset($company_licensing) && $company_licensing->state_business_registeration == 'yes'){
                    $div_state_business_registration_file_class = '';
                }
            ?>

            <div
                class="form-group mb-0 {{ $div_state_business_registration_file_class }} div_state_business_registration_file mt-3">
                <label>Please upload a copy of proof of your state business registration: <span
                        class="required">*</span></label>

                <input type="file" name="state_business_registeration_file_id" id="state_business_registeration_file_id" 
                    class="filestyle state_business_registration_file" data-preview="state_business_registration_file_preview" data-input="false" data-parsley-errors-container="#state_business_registration_file_error" accept="image/*,.doc,.docx,.pdf">

                <div class="state_business_registration_file_preview preview_file"></div>

                @if (!is_null($company_licensing) && $company_licensing->state_business_registeration_file_id > 0)
                <a data-fancybox="gallery"
                    href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->state_business_registeration_file->media->file_name]) }}">
                    <i class="mdi mdi-file-pdf font-50"></i>
                </a>
                @endif

            </div>
            <div id="state_business_registration_file_error"></div>
        </div>

        <div class="form-group {{ !is_null($company_licensing) && $company_licensing->legally_registered_within_state == 'no' ? '' : 'hide' }}"
            id="proof_of_ownership">
            <label>Do you have a copy of proof of ownership on your computer available to upload? <span
                    class="required">*</span>
            </label>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("copy_proof_of_ownership", 'yes', null, ['id' => 'copy_of_proof_of_ownership_yes', 'class' =>
                'copy_of_proof_of_ownership', 'data-parsley-errors-container' => '#copy_of_proof_of_ownership_error']) !!}

                <label for="copy_of_proof_of_ownership_yes">Yes</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("copy_proof_of_ownership", 'no', null, ['id' => 'copy_of_proof_of_ownership_no', 'class' =>
                'copy_of_proof_of_ownership', 'data-parsley-errors-container' => '#copy_of_proof_of_ownership_error']) !!}
                <label for="copy_of_proof_of_ownership_no">No - I will email/upload a copy to the approval
                    department after submitting the application.</label>
            </div>
            <div id="copy_of_proof_of_ownership_error"></div>

            <div
                class="form-group mb-0 div_proof_of_ownership_file {{ !is_null($company_licensing) && $company_licensing->copy_proof_of_ownership == 'yes' ? '' : 'hide' }} mt-3">
                <label>Please upload a copy of proof of ownership: <span class="required">*</span></label>
                <input type="file" name="proof_of_ownership_file_id" class="filestyle file_proof_of_ownership" data-preview="file_proof_of_ownership_preview"
                    data-input="false" data-parsley-errors-container="#file_proof_of_ownership_error" accept="image/*,.doc,.docx,.pdf" />
                
                <div class="file_proof_of_ownership_preview preview_file"></div>

                @if (!is_null($company_licensing) && $company_licensing->proof_of_ownership_file_id > 0)
                <a data-fancybox="gallery"
                    href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->proof_of_ownership_file->file_name]) }}">
                    <i class="mdi mdi-file-pdf font-50"></i>
                </a>
                @endif

            </div>
            <div id="file_proof_of_ownership_error"></div>
        </div>

    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
    $('.company_legally_registered_within_state_you_oprated').change(function() {
        switch ($(this).val()) {
            case 'yes':
                $('#hid_proof_of_ownership').val('no');
                $('#copy_of_state_business_registration').show();
                $('.copy_of_your_state_business_registration').attr('required', true);

                $('.copy_of_proof_of_ownership').removeAttr('required');

                $('#proof_of_ownership').hide();
                $('.file_proof_of_ownership').removeAttr('required');

                // Income Tax
                $('.income_tax_card').hide();
                $('.have_you_file_your_business_income_taxes').attr('required', false);
                break;
            case 'no':
                $('#hid_proof_of_ownership').val('yes');
                $('#copy_of_state_business_registration').hide();
                $('.copy_of_your_state_business_registration').removeAttr('required');

                $('.copy_of_proof_of_ownership').attr('required', true);

                $('#proof_of_ownership').show();
                $('.state_business_registration_file').removeAttr('required');

                // Income Tax
                $('.income_tax_card').show();
                $('.have_you_file_your_business_income_taxes').attr('required', true);
                break;
        }
        refresh_slick_content();

    });

    $('.copy_of_your_state_business_registration').change(function() {
        switch ($(this).val()) {
            case 'yes':
                $('.div_state_business_registration_file').show();
                $('.state_business_registration_file').attr('required', true);
                break;
            case 'no':
                $('.div_state_business_registration_file').hide();
                $('.state_business_registration_file').removeAttr('required');
                break;
        }
        refresh_slick_content();
    });

    $('.copy_of_proof_of_ownership').change(function() {
        switch ($(this).val()) {
            case 'yes':
                $('.div_proof_of_ownership_file').show();
                $('.file_proof_of_ownership').attr('required', true);
                break;
            case 'no':
                $('.div_proof_of_ownership_file').hide();
                $('.file_proof_of_ownership').removeAttr('required');
                break;
        }
        refresh_slick_content();
    });
</script>
@endpush
