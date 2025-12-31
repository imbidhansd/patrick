{!! Form::open(['url' => route('post-background-check-step4'),'id' => 'step4_form', 'class' => 'module_form', 'files' => true]) !!}


<h4 class="text-center">ELECTRONIC SIGNATURE CONSENT</h4>
<p>As part of the selection process at Trust Patrick Referral Network, the "Company," you will need to consent to a background check electronically. By
    typing your name, you are consenting to receive any communications (legally required or otherwise) and all changes to such communications electronically. In order to use
    the website, you must provide at your own expense an Internet connected device that is compatible with the minimum requirements outlined below. You also confirm that
    your device will meet these specifications and requirements and will permit you to access and retain the communications electronically each time you access and use the
website.</p>

<div class="clearfix">&nbsp;</div>

<h5 class="text-center">System Requirements to Access Information</h5>
<p>To receive and view an electronic copy of the Communications you must have the following equipment and software:</p>
<ul>
    <li>A personal computer or other device which is capable of accessing the Internet. Your access to this page verifies that your system/device meets these requirements.</li>
    <li>An Internet web browser which is capable of supporting 128-bit SSL encrypted communications, JavaScript, and cookies. Your system or device must have 128-bit SSL encryption software. Your access to this page verifies that your browser and encryption software/device meet these requirements.</li>
</ul>

<div class="clearfix">&nbsp;</div>


<h5 class="text-center">System Requirements to Retain Information</h5>
<p>To retain a copy, you must either have a printer connected to your personal computer or other device or, alternatively, the ability to save a copy through use of printing
service or software such as Adobe Acrobat®. If you would like to proceed using paper forms, please contact Datasource Background Screening Services.</p>

<div class="clearfix">&nbsp;</div>

<h5 class="text-center">Withdrawal of Electronic Acceptance of Disclosures and Notices</h5>
<p>You can also contact us to withdraw your consent to receive any future communications electronically, including if the system requirements described above change and
    you no longer possess the required system. If you withdraw your consent, we will terminate your use of the Datasource Background Screening Services website and the services
provided through Datasource Background Screening Services website.</p>
<p>To ensure that a signature is unique and to safeguard you against unauthorized use of your name, your IP address (103.78.207.194) has been recorded and will be stored
    along with your electronic signature. Please note that if you wish to submit your Disclosure and Authorization Forms electronically, Datasource Background Screening Services
requires that you include your social security number or user identification. All of your information will be encrypted and transmitted via our secure website.</p>
<p>I understand that Datasource Background Screening Services uses computer technology to ensure that my signed documents are not altered after submission. I agree to allow Datasource Background Screening Services to validate my signed documents in this way.</p>


<div class="alert alert-warning p-3">
    <div class="checkbox checkbox-primary" style="margin-bottom: -10px;">
        {!! Form::checkbox('step4_signature', 'yes', null, ['id' => 'step4_signature', 'required' => true]) !!}
        <label for="step4_signature">I consent to transacting electronically, including receiving legally required notices electronically</label>
    </div>
</div>

<hr/>

<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
<button type="submit" class="btn btn-info float-md-right step4_submit last_input">Save & Next</button>
{!! Form::close() !!}

@push('page_scripts')
<script type="text/javascript">
    $(function(){

        // Step 1 Form Submission Event
        $('#step4_form').submit(function(){

            $(".step4_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                type: 'POST',
                url: $('#step4_form').attr('action'),
                data: $('#step4_form').serialize(),
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
                    $(".step4_submit").removeAttr('disabled').html('Save & Next');
                },
                error: function(e){
                    Swal.fire(
                        'Warning',
                        'Error while processing',
                        'error'
                        );
                    $(".step4_submit").removeAttr('disabled').html('Save & Next');
                },
            });

            return false;
        });

    });
</script>

@endpush
