<!-- Modal -->
<div class="modal fade" id="submitReviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Write a review of {{ $companyObj->company_name }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {!! Form::open(['url' => 'company-profile-page/submit-review', 'class' => 'module_form', 'id' => 'submit_review_form', 'files' => true]) !!}
            {!! Form::hidden('company_id', $companyObj->id) !!}
            <div class="modal-body">
                <div class="form-group">
                    <p>Your first and last name will only be used to verify that you are a customer of company name and will not be displayed to the public. We do not accept anonymous reviews.</p>
                    {!! Form::label('First and Last Name') !!}
                    {!! Form::text('first_last_name', null, ['class' => 'form-control', 'placeholder' => 'Enter First and Last Name', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Display Name: (This is the name that will be publicly displayed)') !!}
                    {!! Form::text('customer_name', null, ['class' => 'form-control', 'placeholder' => 'Enter Full Name', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Customer Email') !!}
                    {!! Form::email('customer_email', null, ['class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Phone Number: (We require your phone number only in case we have questions on your review. Your private information is never and will never be publicly displayed or shared)') !!}
                    {!! Form::text('customer_phone', null, ['class' => 'form-control', 'placeholder' => 'Enter Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
                </div>

                <hr />
                <div class="form-group">
                    {!! Form::label('Images') !!}
                    {!! Form::file('media[]', ['class' => 'filestyle', 'accept' => 'application/pdf, image/*', 'multiple' => true]) !!}
                    <span class="help-block"><small>Ignore if you don't have photos of your problem. You can upload multiple files.</small></span>
                </div>

                <hr />

                <div class="form-group">
                    {!! Form::label('How would you rate your experience with '.$companyObj->company_name.'?') !!}
                    <div id="starHalf"></div>

                    {!! Form::hidden('ratings', null, ['id' => 'ratings_h']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('I want to share my experience with '.$companyObj->company_name) !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
                    <span><small>(Please do not include any personal information in describing your review including address, phone numbers, etc. We reserve the right to edit your review to protect the privacy of both you and <b>{{ $companyObj->company_name }}</b>. We also reserve the right to delete foul language).</small></span>
                </div>

                <hr />

                <div class="form-group">
                    <div class="checkbox checkbox-primary checkbox-circle">
                        {!! Form::checkbox("review_terms", 'yes', null, ['id' => 'review_terms', 'required' => true, 'data-parsley-errors-container' => '#review_terms_error', ]) !!}
                        <label for="review_terms">I certify that my review is my genuine opinion of <b>{{ $companyObj->company_name }}</b> and that I have no personal or business affiliation with them, it's owners or representatives, and have not been offered any incentive or payment to write this review. By submitting this review, I am representing that it is accurate and truthful regarding my experience with <b>{{ $companyObj->company_name }}</b>. I understand that this review and my contact information will be sent to <b>{{ $companyObj->company_name }}</b> as part of the authentic review verification process. I understand that once my review has been deemed authentic, it will be publicly posted on trustpatrick.com and/or it's affiliated websites in an effort to educate and help other property owners.</label>
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
            starHalf: "fas fa-star-half text-success",
            starOff: "far fa-star text-muted",
            starOn: "fas fa-star text-success",
            score: "",
            click: function (a, t) {
                $("#ratings_h").val(a);
                //alert("ID: "+$(this).attr("id")+"\nscore: "+a+"\nevent: "+t.type);
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