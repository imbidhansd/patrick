{!! Form::open(['url' => route('post-background-check-step1'),'id' => 'step1_form', 'class' => 'module_form', 'files' => true]) !!}

<h4>Thank you for submitting your Background/Credit Check.</h4>

<p>All background checks are processed by our exclusive background check company Datasource Background Screening Services. For more information about Datasource Background Screening Services, please visit their website. <a href="http://www.datasourcecorp.com/" class="text-info" target="_blank">Datasource Background Screening Services</a> Your private information will remain private. TrustPatrick.com does not see nor do we store your social security number. Please review the following:</p>

<ul class="pl-15">
    <li>While we do check credit, it is considered a “soft pull” and will not effect your credit score</li>
    <li>If you have a freeze on your social security number with Equifax or TransUnion, please remove that freeze immediately upon submitting this application. Please unfreeze for at least 5 days.</li>
    <li>We do not base our decision on credit worthiness. Your personal credit is just one part of many factors that we use to base our decision as a whole.</li>
    <li> We do not make any profits from background checks. They are a pass through. As a matter of fact, we often lose a few dollars on them. 
        That being said, if you have moved residences frequently in the past 7 years we may ask that you pay additional fees for your background check. <br/>
        <a target="_blank" href="https://acrobat.adobe.com/link/track?uri=urn%3Aaaid%3Ascds%3AUS%3Ada7b74b6-aa99-3536-819c-e93d46b0240c"><span class="text-info">Please review these additional court access fees for your state.</span></a></li>
    </ul>

    <h6 class="text-danger">Please have a copy of your drivers license on your device ready to upload.</h6>


    <div class="alert alert-warning p-3">
        <div class="checkbox checkbox-primary" style="margin-bottom: -10px;">
            {!! Form::checkbox('step1_agree', 'yes', null, ['id' => 'step1_agree', 'required' => true]) !!}
            <label for="step1_agree">I understand and agree</label>
        </div>
    </div>



    <hr/>


    <button type="submit" class="btn btn-info float-md-right step1_submit last_input">Save & Next</button>
    {!! Form::close() !!}

    @push('page_scripts')
    <script type="text/javascript">
        $(function(){

        // Step 1 Form Submission Event
        $('#step1_form').submit(function(){


            $(".step1_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');


            var form = $('#step1_form')[0]; // You need to use standard javascript object here
            var formData = new FormData(form);

            $.ajax({
                url: $('#step1_form').attr('action'),
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
                    $(".step1_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e){
                    Swal.fire(
                        'Warning',
                        'Error while processing',
                        'error'
                        );
                    $(".step1_submit").removeAttr('disabled').html('Save & Next');
                },
            });
            // Ajax call of step 1 [End]
            return false;
            
        });

    });
</script>

@endpush
