<!-- Modal -->
<div class="modal fade" id="submitComplaintModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Write a complaint of {{ $companyObj->company_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => 'company-profile-page/submit-complaint', 'class' => 'module_form', 'id' => 'submit_complaint_form', 'files' => true]) !!}
            {!! Form::hidden('company_id', $companyObj->id) !!}
            <div class="modal-body">
                <h5>Something go wrong?</h5>
                <p class="font-bold">Feel you received sub-standard service?</p>
                <h5>Let your voice be heard and tell us about it!</h5>
                <p>If you feel you’ve been wronged by this business tell us your story. We’ll review your complaint with you and once we have all of the facts, we’ll contact this business, send them your complaint and give them a chance to respond.</p>
                <h5>Our experts are here to help.</h5>
                <p>We’ll investigate and try to help mediate a resolution. In the end, if the company is proven to have performed sub standard services and won’t step up to the plate and do the right thing, we’ll list your complaint right here, expose them and warn other consumers in your area about them!</p>
                <h5>File a formal complaint against this company below!</h5>


                <h6 class="text-theme_color">Your Information:</h6>
                <div class="form-group">
                    {!! Form::label('First and Last Name:') !!} <span class="required">*</span>
                    {!! Form::text('first_last_name', null, ['class' => 'form-control', 'placeholder' => 'Enter First and Last Name', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Display Name: (This is the name that will be displayed to the public)') !!} <span class="required">*</span>
                    {!! Form::text('customer_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Display Name', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Email:') !!} <span class="required">*</span>
                    {!! Form::email('customer_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number: <span class="required">*</span> (For use only in case there are questions about on your complaint)</label>
                    {!! Form::text('customer_phone', null, ['class' => 'form-control', 'id' => 'phone_number', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
                </div>
                
                <div class="form-group">
                    {!! Form::label('Zipcode') !!} <span class="required">*</span>
                    {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Enter Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
                </div>

                <hr />

                <div class="form-group">
                    {!! Form::label('Please enter a brief but detailed description of your complaint. What went wrong? Have you contacted them and explained the issues? What do you feel company should do?') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Enter your text here...']) !!}

                    <span class="help-block"><small>(We reserve the right to edit your complaint to protect the privacy of both you and <b>{{ $companyObj->company_name }}</b>. We also reserve the right to delete foul language).</small></span>
                </div>

                <hr />

                <div class="form-group">
                    {!! Form::label('Do you have photos of your problems?') !!} <span class="required">*</span>
                    
                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio("complaint_upload_images", 'yes', null, ['id' => 'complaint_upload_images_yes', 'class' => 'complaint_upload_images', 'data-parsley-errors-container' => '#complaint_upload_images_error', 'required' => true]) !!}
                        <label for="complaint_upload_images_yes">Yes - I can upload them now!</label>
                    </div>
                    
                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio("complaint_upload_images", 'no', null, ['id' => 'complaint_upload_images_no', 'class' => 'complaint_upload_images', 'data-parsley-errors-container' => '#complaint_upload_images_error', 'required' => true]) !!}
                        <label for="complaint_upload_images_no">No - I don't have any photos at this time</label>
                    </div>
                    
                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio("complaint_upload_images", 'later', null, ['id' => 'complaint_upload_images_later', 'class' => 'complaint_upload_images', 'data-parsley-errors-container' => '#complaint_upload_images_error', 'required' => true]) !!}
                        <label for="complaint_upload_images_later">No - I will upload a copy once once I've submitted my complaint</label>
                    </div>
                    
                    <div id="complaint_upload_images_error"></div>
                </div>
                
                <div class="form-group" id="complaint_image_upload" style="display: none;">
                    {!! Form::label('Images') !!}
                    {!! Form::file('media[]', ['class' => 'filestyle', 'accept' => 'application/pdf, image/*', 'multiple' => true]) !!}
                    <span class="help-block"><small>Ignore if you don't have photos of your problem. You can upload multiple files.</small></span>
                </div>
                
                <hr />

                <div class="form-group">
                    {!! Form::label('Do you have a copy of the contract, proposal or agreement describing the service to be completed available for upload?') !!}

                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio('have_contract_agreement', 'yes', null, ['id' => 'yes_have_contract_agreement', 'class' => 'have_contract_agreement', 'data-parsley-errors-container' => '#have_contract_agreement_error', 'required' => true]) !!}
                        <label for="yes_have_contract_agreement">Yes - I can upload it now!</label>
                    </div>

                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio('have_contract_agreement', 'no', null, ['id' => 'no_have_contract_agreement', 'class' => 'have_contract_agreement', 'data-parsley-errors-container' => '#have_contract_agreement_error', 'required' => true]) !!}
                        <label for="no_have_contract_agreement">No - I don't have a contract or agreement</label>
                    </div>
                    
                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio("have_contract_agreement", 'later', null, ['id' => 'later_have_contract_agreement', 'class' => 'have_contract_agreement', 'data-parsley-errors-container' => '#have_contract_agreement_error', 'required' => true]) !!}
                        <label for="later_have_contract_agreement">No - I will upload a copy once once I've submitted my complaint</label>
                    </div>
                    
                    <div id="have_contract_agreement_error"></div>
                </div>

                <div class="form-group" id="contract_agreement_file_div" style="display: none;">
                    {!! Form::label('Upload copy of the contract, proposal or agreement') !!}
                    <input type="file" name="contract_agreement_file" id="contract_agreement_file" class="filestyle" accept="application/pdf, image/*" data-input="false" data-preview="contract_agreement_file_preview" />
                    <div class="contract_agreement_file_preview preview_file"></div>
                </div>

                <hr />

                <div class="form-group">
                    <div class="checkbox checkbox-primary checkbox-circle">
                        {!! Form::checkbox("terms", 'yes', null, ['id' => 'terms', 'required' => true, 'data-parsley-errors-container' => '#terms_error', ]) !!}
                        <label for="terms">By submitting this complaint, I am representing that it is accurate and truthful regarding my experience with <b>{{ $companyObj->company_name }}</b>. I understand that once my complaint has been reviewed and all of the facts have been gathered this complaint and my contact information will be sent to <b>{{ $companyObj->company_name }}</b> as part of the complaint process. <b>{{ $companyObj->company_name }}</b> must respond in a timely manner. I also understand that once my review has been processed, it will be publicly posted on TrustPatrick.com and/or it's affiliated websites in an effort to educate and help other consumers.</label>
                    </div>
                    <div id="terms_error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light complaint_submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


@push('page_script')
<script type="text/javascript">
    $(function () {
        /* Submit Complaint start */
        $('.complaint_upload_images').change(function() {
            switch ($(this).val()) {
                case 'yes':
                    $("#complaint_image_upload").show();
                    break;
                case 'no':
                    Swal.fire({
                        title: '',
                        type: 'question',
                        text: 'Uploading images highly recommended, Images can help expedite the complaint process much more rapidly. In most service quality cases, we will require images in order to help mediate a resolution. If you wish, once you’ve submitted your complaint, you can upload images later.'
                    });
                    
                    $("#complaint_image_upload").hide();
                    break;
                case 'later':
                    $("#complaint_image_upload").hide();
                    break;
            }
        });
        
        $('#contract_agreement_file_div input[type="file"]').change(function (e) {
            var files = e.target.files[0]
            var fileExtension = files.type.split("/").pop();
            var fileName = files.name

            var validFileExtensions = ['png', 'jpg', 'jpeg', 'tif', 'bmp', 'pdf'];
            //alert (validFileExtensions.includes(fileExtension));

            if (validFileExtensions.includes(fileExtension)) {

                //alert ($(this).data('preview'));
                var preview_div = $('.' + $(this).data('preview'));

                //var html = '<a href="' + URL.createObjectURL(e.target.files[0]) + '" data-fancybox="gallery">';
                var html = '<div class="mt-2">' + files.name + '<br/>';
                if (fileExtension == 'pdf') {
                    html += '<i class="fas fa-file-pdf font-50 mt-1"></i>';
                } else {
                    html += '<i class="fas fa-file-image font-50 mt-1"></i>';
                }

                html += '<br/><a href="javascript:;" data-id="#' + $(this).attr('id') + '" class="btn btn-xs btn-danger mt-1 rem_file">Remove</a></div>';
                preview_div.html(html);
            } else {
                $(this).val('');
            }
        });

        $(document).on('click', '.rem_file', function () {
            var file_elem = $(this).data('id');
            $(file_elem).val('');
            $(this).closest('.preview_file').html('');
        });

        $(".have_contract_agreement").on("change", function () {
            if ($(this).val() == 'yes') {
                $("#contract_agreement_file_div").show();
                $("#contract_agreement_file_div input").attr("required", true);
            } else if ($(this).val() == 'no' || $(this).val() == 'later') {
                $("#contract_agreement_file_div").hide();
                $("#contract_agreement_file_div input").attr("required", false);
            }
        });

        $("#submit_complaint_form").on("submit", function () {
            var form_url = $(this).attr("action");
            var form = $('#submit_complaint_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".complaint_submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".complaint_submit_btn").attr('disabled', true);
            } else {
                $(".complaint_submit_btn").html('Submit');
                $(".complaint_submit_btn").attr('disabled', false);
            }
            
            $.ajax({
                url: form_url,
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function (data) {
                    $(".complaint_submit_btn").html('Submit');
                    $(".complaint_submit_btn").attr('disabled', false);
                    
                    Swal.fire({
                        title: data.title,
                        type: data.type,
                        html: data.message
                    }).then(function (t) {
                        window.location.reload();
                    });
                }
            });
            return false;
        });
        /* Submit Review end */
    });
</script>
@endpush