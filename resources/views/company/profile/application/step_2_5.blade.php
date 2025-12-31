<div class="card card-border card-primary">
    <div class="card-header border-primary bg-transparent">
        <h3 class="card-title text-primary mb-0">Subcontractor Agreement</h3>
    </div>
    <div class="card-body xs_max_width">
        <div class="form-group mb-0">
            <label>Do you subcontract entire jobs to other companies? <span class="required">*</span></label>
            <div class="radio radio-primary radio-circle">
                {!! Form::radio("subcontract_with_other_companies", 'yes', null, ['id' =>
                'subcontract_with_other_companies_yes', 'class' =>
                'subcontractor_with_other_companies', 'required' => true, 'data-parsley-errors-container' => '#subcontractor_with_other_companies_error']) !!}
                <label for="subcontract_with_other_companies_yes">Yes. We subcontract all of our work</label>
            </div>

            <div class="radio radio-primary radio-circle">
                {!! Form::radio("subcontract_with_other_companies", 'no', null, ['id' =>
                'subcontract_with_other_companies_no',
                'class' => 'subcontractor_with_other_companies', 'required' => true, 'data-parsley-errors-container' => '#subcontractor_with_other_companies_error']) !!}
                <label for="subcontract_with_other_companies_no">No. We do not subcontract any part of our jobs</label>
            </div>
            <div id="subcontractor_with_other_companies_error"></div>
        </div>


        <div class="{{ !is_null($company_licensing) && $company_licensing->subcontract_with_other_companies == 'yes' ? '' : 'hide' }}" id="subcontract_with_other_companies_selection">
            <div class="form-group mb-0">
                <label>Do you have a subcontractor agreement that you require from your subcontractors? <span class="required">*</span></label>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("subcontractor_to_work_with_other_companies", 'yes', null, ['id' =>
                    'subcontractor_to_work_with_other_companies_yes', 'class' =>
                    'subcontractor_to_work_with_other_companies', 'required' => !is_null($company_licensing) && $company_licensing->subcontract_with_other_companies == 'yes' ? true : false, 'data-parsley-errors-container' => '#subcontractor_to_work_with_other_companies_error']) !!}
                    <label for="subcontractor_to_work_with_other_companies_yes">Yes</label>
                </div>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("subcontractor_to_work_with_other_companies", 'no', null, ['id' =>
                    'subcontractor_to_work_with_other_companies_no',
                    'class' => 'subcontractor_to_work_with_other_companies', 'required' => !is_null($company_licensing) && $company_licensing->subcontract_with_other_companies == 'yes' ? true : false, 'data-parsley-errors-container' => '#subcontractor_to_work_with_other_companies_error']) !!}
                    <label for="subcontractor_to_work_with_other_companies_no">No</label>
                </div>
            </div>
            <div id="subcontractor_to_work_with_other_companies_error"></div>


            <div class="form-group mb-0 {{ !is_null($company_licensing) && $company_licensing->subcontractor_to_work_with_other_companies == 'yes' ? '' : 'hide' }}" id="copy_of_subcontractor_to_work_with_other_companies">
                <label>Do you have a copy of your subcontractor agreement available to upload now? <span class="required">*</span></label>


                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_of_subcontractor_agreement", 'yes', null, ['id' =>
                    'copy_of_subcontractor_agreement_yes',
                    'class' => 'copy_of_subcontractor_agreement',
                    'data-parsley-errors-container' => '#copy_of_subcontractor_agreement_error'
                    ]) !!}
                    <label for="copy_of_subcontractor_agreement_yes">Yes</label>
                </div>

                <div class="radio radio-primary radio-circle">
                    {!! Form::radio("copy_of_subcontractor_agreement", 'no', null, ['id' =>
                    'copy_of_subcontractor_agreement_no',
                    'class' => 'copy_of_subcontractor_agreement',
                    'data-parsley-errors-container' => '#copy_of_subcontractor_agreement_error'
                    ]) !!}
                    <label for="copy_of_subcontractor_agreement_no">No - I will email/upload a copy to the approval department after submitting the application.</label>
                </div>
                <div id="copy_of_subcontractor_agreement_error"></div>


                <div
                    class="form-group mb-0 {{ !is_null($company_licensing) && $company_licensing->copy_of_subcontractor_agreement == 'yes' ? '' : 'hide' }} div_copy_of_subcontractor_agreement mt-3">
                    <label>Please upload a copy of your subcontractor agreement file: <span  class="required">*</span></label>

                    <input type="file" name="subcontractor_agreement_file_id" id="subcontractor_agreement_file_id" 
                           class="filestyle copy_of_subcontractor_agreement_file" data-preview="subcontractor_agreement_file_id_preview" data-input="false" data-parsley-errors-container="#copy_of_subcontractor_agreement_file_error" accept="image/*,.doc,.docx,.pdf">

                    <div class="subcontractor_agreement_file_id_preview preview_file"></div>

                    @if (!is_null($company_licensing) && $company_licensing->subcontractor_agreement_file_id > 0)
                    <a data-fancybox="gallery"
                       href="{{ route('secure.file.company', ['path' => 'media/'.$company_licensing->subcontractor_agreement_file->media->file_name]) }}">
                        <i class="mdi mdi-file-pdf font-50"></i>
                    </a>
                    @endif
                </div>
                <div id="copy_of_subcontractor_agreement_file_error"></div>
            </div>
        </div>
    </div>
</div>



@push('page_scripts')
<script type="text/javascript">
    $(".subcontractor_with_other_companies").on("change", function (){
        switch ($(this).val()) {
            case 'yes':
                $('#subcontract_with_other_companies_selection').show();
                $('.subcontractor_to_work_with_other_companies').attr('required', true);

                break;
            case 'no':
                $('#subcontract_with_other_companies_selection').hide();
                $('.subcontractor_to_work_with_other_companies, .copy_of_subcontractor_agreement, .copy_of_subcontractor_agreement_file').removeAttr('required');
                break;
        }
        refresh_slick_content();
    });
    
    $('.subcontractor_to_work_with_other_companies').change(function () {
        switch ($(this).val()) {
            case 'yes':
                $('#copy_of_subcontractor_to_work_with_other_companies').show();
                $('.copy_of_subcontractor_agreement').attr('required', true);

                break;
            case 'no':
                $('#copy_of_subcontractor_to_work_with_other_companies').hide();
                $('.copy_of_subcontractor_agreement').removeAttr('required');

                $('.copy_of_subcontractor_agreement_file').removeAttr('required');
                break;
        }
        refresh_slick_content();

    });


    $('.copy_of_subcontractor_agreement').change(function () {
        switch ($(this).val()) {
            case 'yes':
                $('.div_copy_of_subcontractor_agreement').show();
                $('.copy_of_subcontractor_agreement_file').attr('required', true);
                break;
            case 'no':
                $('.div_copy_of_subcontractor_agreement').hide();
                $('.copy_of_subcontractor_agreement_file').removeAttr('required');
                break;
        }
        refresh_slick_content();
    });
</script>
@endpush
