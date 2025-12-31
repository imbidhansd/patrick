<?php
    $card_style = 'display:none';
    $tax_field_required = false;
    if (isset($company_licensing) && $company_licensing->legally_registered_within_state == 'no'){
        $card_style = 'display:block';
        $tax_field_required = true;
    }
?>


<div class="card card-border card-primary income_tax_card" style="{{ $card_style }}">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Income Tax Filling</h3>
    </div>
    <div class="card-body xs_max_width">

        <div class="form-group mb-0">
            <label>Have you file your business income taxes? <span class="required">*</span></label>
            <div class="radio radio-primary radio-circle">

                {!! Form::radio("income_tax_filling", 'Sole Proprietor', null, ['id' =>
                'have_you_file_your_business_income_taxes_sole', 'class' => 'have_you_file_your_business_income_taxes',
                'required' => $tax_field_required, 'data-parsley-errors-container' => '#have_you_file_your_business_income_taxes_error']) !!}
                <label for="have_you_file_your_business_income_taxes_sole">Sole Proprietor</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("income_tax_filling", 'LLC', null, ['id' =>
                'have_you_file_your_business_income_taxes_llc', 'class' => 'have_you_file_your_business_income_taxes',
                'required' => $tax_field_required, 'data-parsley-errors-container' => '#have_you_file_your_business_income_taxes_error']) !!}
                <label for="have_you_file_your_business_income_taxes_llc">LLC</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("income_tax_filling", 'Corporation', null, ['id' =>
                'have_you_file_your_business_income_taxes_corporation', 'class' =>
                'have_you_file_your_business_income_taxes', 'required' => $tax_field_required,
                'data-parsley-errors-container' => '#have_you_file_your_business_income_taxes_error']) !!}
                <label for="have_you_file_your_business_income_taxes_corporation">Corporation</label>
            </div>
        </div>
        <div id="have_you_file_your_business_income_taxes_error"></div>

        <div
            class="alert alert-info {{ !is_null($company_licensing) && $company_licensing->income_tax_filling == 'Sole Proprietor' ? '' : 'hide' }} solo_income_tax_alert">
            In order to verify ownership of the company
            creating
            this
            profile, we will need
            documentation proving ownership and length of time in business. Examples: A bank statement, a letter
            from
            your bank on bank letterhead, a copy of a tax return from year started, or a letter from your
            accountant.
        </div>

        <div
            class="form-group {{ !is_null($company_licensing) && ($company_licensing->income_tax_filling != 'Sole Proprietor' && $company_licensing->income_tax_filling != '') ? '' : 'hide' }} income_tax_option_div">
            <label>Do you have a copy of your business articles of incorporation on your computer available to upload? (LLC or Corporation): <span class="required">*</span></label>

            <div class="radio radio-primary radio-circle">

                {!! Form::radio("articles_of_incorporation", 'yes', null, ['id' =>
                'copy_of_your_business_articles_of_incorporation_yes', 'class' =>
                'copy_of_your_business_articles_of_incorporation',
                'data-parsley-errors-container' => '#copy_of_your_business_articles_of_incorporation_error'
                ]) !!}
                <label for="copy_of_your_business_articles_of_incorporation_yes">Yes</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("articles_of_incorporation", 'no', null, ['id' =>
                'copy_of_your_business_articles_of_incorporation_no', 'class' =>
                'copy_of_your_business_articles_of_incorporation',
                'data-parsley-errors-container' => '#copy_of_your_business_articles_of_incorporation_error']) !!}

                <label for="copy_of_your_business_articles_of_incorporation_no">No - I will email/upload a copy to the
                    approval
                    department after submitting the application.</label>
            </div>
            <div id="copy_of_your_business_articles_of_incorporation_error"></div>

            <div
                class="form-group mb-0 div_income_tax_file {{ !is_null($company_licensing) && $company_licensing->articles_of_incorporation == 'yes' ? '' : 'hide' }} mt-3">
                <label>Please upload a copy of your business articles of incorporation: <span
                        class="required">*</span></label>
                <input type="file" name="articles_of_incorporation_file_id" id="articles_of_incorporation_file_id" class="filestyle file_income_tax" data-parsley-errors-container="#file_income_tax_error"
                    data-input="false" data-preview="articles_of_incorporation_file_id_preview" accept="image/*,.doc,.docx,.pdf">


                <div class="articles_of_incorporation_file_id_preview preview_file"></div>    

                @if (!is_null($company_licensing) && $company_licensing->articles_of_incorporation_file_id > 0)
                <a data-fancybox="gallery"
                    href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->articles_of_incorporation_file->media->file_name]) }}">
                    <i class="mdi mdi-file-pdf font-50"></i>
                </a>
                @endif
            </div>
            <div id="file_income_tax_error"></div>


        </div>


    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
    $(function(){


        $('.have_you_file_your_business_income_taxes').change(function(){
            switch($(this).val()){
                case 'Sole Proprietor':
                    $('.solo_income_tax_alert').show();
                    $('.income_tax_option_div').hide();

                    $('.copy_of_your_business_articles_of_incorporation').removeAttr('required');
                    break;
                case 'LLC':
                    $('.solo_income_tax_alert').hide();
                    $('.income_tax_option_div').show();

                    $('.copy_of_your_business_articles_of_incorporation').attr('required', true);

                    break;
                case 'Corporation':
                    $('.solo_income_tax_alert').hide();
                    $('.income_tax_option_div').show();

                    $('.copy_of_your_business_articles_of_incorporation').attr('required', true);
                    break;
            }
            refresh_slick_content();
        });


        $('.copy_of_your_business_articles_of_incorporation').change(function(){
            //
            switch($(this).val()){
                case 'yes':
                    $('.div_income_tax_file').show();
                    $('.file_income_tax').attr('required', true);
                    break;
                case 'no':
                    $('.div_income_tax_file').hide();
                    $('.file_income_tax').attr('required', false);
                    break;
            }

            refresh_slick_content();
        });



    });
</script>
@endpush
