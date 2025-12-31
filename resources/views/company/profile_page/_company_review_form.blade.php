<!-- Modal -->
<div class="modal fade" id="submitReviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Write a review of {{ $companyObj->company_name }} and share your opinions with others</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => 'company-profile-page/submit-review', 'class' => 'module_form', 'id' => 'submit_review_form', 'files' => true]) !!}
            {!! Form::hidden('company_id', $companyObj->id) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('I want to share my experience with '.$companyObj->company_name) !!} <span class="required">*</span>
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Enter your review here...']) !!}
                    <span><small>(Please do not include any personal information in describing your review including address, phone numbers, etc. We reserve the right to edit your review to protect the privacy of both you and <b>{{ $companyObj->company_name }}</b>. We also reserve the right to delete foul language).</small></span>
                </div>
                
                <div class="form-group">
                    {!! Form::label('How would you rate your experience with '.$companyObj->company_name.'?') !!} <span class="required">*</span>
                    <div id="starHalf" class="font-30"></div>
                    {!! Form::text('ratings', null, ['id' => 'ratings_h', 'class' => 'hide', 'required' => true, 'data-parsley-errors-container' => '#star_rating_error', 'data-parsley-error-message' => 'Please rate your experience']) !!}
                    
                </div>
                <div id="star_rating_error"></div>
                
                <div class="clearfix">&nbsp;</div>
                
                <div class="form-group">
                    <p>Your first and last name will only be used to verify that you are a customer of company name and will not be displayed to the public. We do not accept anonymous reviews.</p>
                    {!! Form::label('First and Last Name') !!} <span class="required">*</span>
                    {!! Form::text('first_last_name', null, ['class' => 'form-control', 'placeholder' => 'Enter First and Last Name', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Display Name: (This is the name that will be publicly displayed)') !!} <span class="required">*</span>
                    {!! Form::text('customer_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Display Name', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Customer Email') !!} <span class="required">*</span>
                    {!! Form::email('customer_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number <span class="required">*</span> (We require your phone number only in case we have questions on your review. Your private information is never and will never be publicly displayed or shared)</label>
                    {!! Form::text('customer_phone', null, ['class' => 'form-control', 'id' => 'phone_number', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
                </div>
                
                <div class="form-group">
                    {!! Form::label('Zipcode') !!} <span class="required">*</span>
                    {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Enter Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
                </div>

                <hr />
                
                <div class="form-group">
                    {!! Form::label('Would you like to upload photos with your review?') !!} <span class="required">*</span>
                    
                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio("upload_images", 'yes', null, ['id' => 'upload_images_yes', 'class' => 'upload_images', 'data-parsley-errors-container' => '#upload_images_error', 'required' => true]) !!}
                        <label for="upload_images_yes">Yes - I can upload them now!</label>
                    </div>
                    
                    <div class="radio radio-primary radio-circle">
                        {!! Form::radio("upload_images", 'no', null, ['id' => 'upload_images_no', 'class' => 'upload_images', 'data-parsley-errors-container' => '#upload_images_error', 'required' => true]) !!}
                        <label for="upload_images_no">No - I don't have any photos at this time</label>
                    </div>
                    
                    <div id="upload_images_error"></div>
                </div>
                
                <div class="form-group" id="review_image_upload" style="display: none;">
                    {!! Form::label('Images') !!}
                    {!! Form::file('media[]', ['class' => 'filestyle', 'accept' => 'application/pdf, image/*', 'multiple' => true]) !!}
                    <span class="help-block"><small>Ignore if you don't have photos of your problem. You can upload multiple files.</small></span>
                </div>

                <hr />

                <div class="form-group">
                    <div class="checkbox checkbox-primary checkbox-circle">
                        {!! Form::checkbox("review_terms", 'yes', null, ['id' => 'review_terms', 'required' => true, 'data-parsley-errors-container' => '#review_terms_error', ]) !!}
                        <?php /* <label for="review_terms">I certify that my review is my genuine opinion of <b>{{ $companyObj->company_name }}</b> and that I have no personal or business affiliation with them, it's owners or representatives, and have not been offered any incentive or payment to write this review. By submitting this review, I am representing that it is accurate and truthful regarding my experience with <b>{{ $companyObj->company_name }}</b>. I understand that this review and my contact information will be sent to <b>{{ $companyObj->company_name }}</b> as part of the authentic review verification process. I understand that once my review has been deemed authentic, it will be publicly posted on trustpatrick.com and/or it's affiliated websites in an effort to educate and help other property owners.</label> */ ?>
                        <label for="review_terms">I certify that my review is my genuine opinion of <b>{{ $companyObj->company_name }}</b> and that I have no personal or business affiliation with them, it's owners or representatives, and have not been offered any incentive or payment to write this review. By submitting this review, I am representing that it is accurate and truthful regarding my experience with <b>{{ $companyObj->company_name }}</b>. I understand that this review and my contact information will be sent to <b>{{ $companyObj->company_name }}</b> as part of the authentic review verification process. I understand that once my review has been deemed authentic, it will be publicly posted on TrustPatrick.com and/or it's affiliated websites in an effort to educate and help other consumers.</label>
                    </div>
                    <div id="review_terms_error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light review_submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@push('page_script')
<script type="text/javascript">
    $(function () {
        /* Submit Review start */
        $("#starHalf").raty({
            half: !0,
            starHalf: "fas fa-star-half-alt yellow-star",
            starOff: "far fa-star text-muted",
            starOn: "fas fa-star yellow-star",
            score: "",
            click: function (a, t) {
                $("#ratings_h").val(a);
                //alert("ID: "+$(this).attr("id")+"\nscore: "+a+"\nevent: "+t.type);
            }
        });
        
        
        $('.upload_images').change(function() {
            switch ($(this).val()) {
                case 'yes':
                    $("#review_image_upload").show();
                    break;
                case 'no':
                    $("#review_image_upload").hide();
                    break;
            }
        });

        $("#submit_review_form").on("submit", function () {
            var form_url = $(this).attr("action");
            var form = $('#submit_review_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            var instance = $(this).parsley();
            if (instance.isValid()){
                $(".review_submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".review_submit_btn").attr('disabled', true);
            } else {
                $(".review_submit_btn").html('Submit');
                $(".review_submit_btn").attr('disabled', false);
            }

            $.ajax({
                url: form_url,
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function (data) {
                    $(".review_submit_btn").html('Submit');
                    $(".review_submit_btn").attr('disabled', false);
                    
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