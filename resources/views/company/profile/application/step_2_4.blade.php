<div class="card card-border card-primary">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Company Contracts / Work Agreements / Warranty</h3>
    </div>
    <div class="card-body xs_max_width">
        <div class="form-group mb-0">
            <label>Do you provide your customers with a written warranty/guaranty for the services you
                provide? <span class="required">*</span></label>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("provide_written_warrenty", 'yes', null, ['id' =>
                'do_you_provide_your_customers_with_a_written_warranty_yes', 'class' =>
                'do_you_provide_your_customers_with_a_written_warranty', 'required' => true, 'data-parsley-errors-container' => '#do_you_provide_your_customers_with_a_written_warranty_error']) !!}
                <label for="do_you_provide_your_customers_with_a_written_warranty_yes">Yes</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("provide_written_warrenty", 'no', null, ['id' =>
                'do_you_provide_your_customers_with_a_written_warranty_no',
                'class' => 'do_you_provide_your_customers_with_a_written_warranty', 'required' => true, 'data-parsley-errors-container' => '#do_you_provide_your_customers_with_a_written_warranty_error']) !!}
                <label for="do_you_provide_your_customers_with_a_written_warranty_no">No</label>
            </div>
        </div>
        <div id="do_you_provide_your_customers_with_a_written_warranty_error"></div>


        <div class="form-group mb-0 {{ !is_null($company_licensing) && $company_licensing->provide_written_warrenty == 'yes' ? '' : 'hide' }}"
            id="copy_of_your_contracts_with_warranty_information">
            <label>Do you have a copy of your contracts with warranty information on your computer available for
                upload ? <span class="required">*</span></label>


            <div class="radio radio-primary radio-circle">
                {!! Form::radio("written_warrenty", 'yes', null, ['id' =>
                'copy_of_your_contracts_with_warranty_information_yes',
                'class' => 'copy_of_your_contracts_with_warranty_information',
                'data-parsley-errors-container' => '#copy_of_your_contracts_with_warranty_information_error'
                ]) !!}
                <label for="copy_of_your_contracts_with_warranty_information_yes">Yes</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("written_warrenty", 'no', null, ['id' =>
                'copy_of_your_contracts_with_warranty_information_no',
                'class' => 'copy_of_your_contracts_with_warranty_information',
                'data-parsley-errors-container' => '#copy_of_your_contracts_with_warranty_information_error'
                ]) !!}
                <label for="copy_of_your_contracts_with_warranty_information_no">No - I will email/upload a copy to the
                    approval
                    department after submitting the application.</label>
            </div>
            <div id="copy_of_your_contracts_with_warranty_information_error"></div>


            <div
                class="form-group mb-0 {{ !is_null($company_licensing) && $company_licensing->written_warrenty == 'yes' ? '' : 'hide' }} div_copy_of_your_contracts_with_warranty_information_file mt-3">
                <label>Please upload a copy of your company agreement/contract with warranty information: <span
                        class="required">*</span></label>

                <input type="file" name="written_warrenty_file_id" id="written_warrenty_file_id" 
                    class="filestyle copy_of_your_contracts_with_warranty_information_file" data-preview="written_warrenty_file_id_preview" data-input="false" data-parsley-errors-container="#copy_of_your_contracts_with_warranty_information_file_error" accept="image/*,.doc,.docx,.pdf">

                <div class="written_warrenty_file_id_preview preview_file"></div>

                @if (!is_null($company_licensing) && $company_licensing->written_warrenty_file_id > 0)
                <a data-fancybox="gallery"
                    href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->written_warrenty_file->media->file_name]) }}">
                    <i class="mdi mdi-file-pdf font-50"></i>
                </a>
                @endif
            </div>
            <div id="copy_of_your_contracts_with_warranty_information_file_error"></div>

        </div>


    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
    $('.do_you_provide_your_customers_with_a_written_warranty').change(function() {
        switch ($(this).val()) {
            case 'yes':
                $('#copy_of_your_contracts_with_warranty_information').show();
                $('.copy_of_your_contracts_with_warranty_information').attr('required', true);

                break;
            case 'no':
                $('#copy_of_your_contracts_with_warranty_information').hide();
                $('.copy_of_your_contracts_with_warranty_information').removeAttr('required');

                $('.copy_of_your_contracts_with_warranty_information_file').removeAttr('required');
                break;
        }
        refresh_slick_content();

    });


    $('.copy_of_your_contracts_with_warranty_information').change(function() {
        switch ($(this).val()) {
            case 'yes':
                $('.div_copy_of_your_contracts_with_warranty_information_file').show();
                $('.copy_of_your_contracts_with_warranty_information_file').attr('required', true);
                break;
            case 'no':
                $('.div_copy_of_your_contracts_with_warranty_information_file').hide();
                $('.copy_of_your_contracts_with_warranty_information_file').removeAttr('required');
                break;
        }
        refresh_slick_content();
    });


</script>
@endpush
