{!! Form::open(['url' => 'register/step1','id' => 'step1_form', 'class' => 'module_form', 'files' => true]) !!}


<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label>First Name <span class="required">*</span></label>
            {!! Form::text('first_name', null, ['id' => 'first_name',  'class' => 'form-control', 'placeholder' => '', 'required' => true, 'maxlength' => 255, 'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label>Last Name <span class="required">*</span></label>
            {!! Form::text('last_name', null, ['id' => 'last_name', 'class' => 'form-control', 'placeholder' => '', 'required' => true, 'maxlength' => 255, 'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Email Address <span class="required">*</span></label>
            {!! Form::email('email', null, ['id' => 'email', 'class' => 'form-control', 'placeholder' =>
            '', 'required' => true, 'maxlength' => 255, 'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label>Confirm Email Address <span class="required">*</span></label>
            {!! Form::email('con_email', null, ['id' => 'con_email', 'class' => 'form-control', 'placeholder' => '', 'required' => true, 'maxlength' => 255, 'data-parsley-equalto' => '#email', 'data-parsley-trigger' => 'blur', 'data-parsley-equalto-message' => 'Confirm Email should be same as Email']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Username <span class="required">*</span></label>
            {!! Form::text('username', null, ['id' => 'username','class' => 'form-control', 'placeholder' => '', 'required' => true, 'maxlength' => 255, 'data-parsley-trigger' => 'blur']) !!}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Password <span class="required">*</span></label>
            <div class="input-group">
                {!! Form::password('password', ['id' => 'password','class' => 'form-control', 'placeholder'
                =>
                '',
                'required' =>
                true, 'maxlength' => 255,
                //'data-parsley-uppercase' => 1,
                //'data-parsley-lowercase' => 1,
                //'data-parsley-number' => 1,
                //'data-parsley-special' => 1,
                'data-parsley-minlength' => 6, 'data-parsley-maxlength' => 50, 'data-parsley-trigger' => 'blur']) !!}

                <span class="input-group-append view-password">
                    <button type="button" class="btn btn-info"><i class="fas fa-eye"></i>
                    </button>
                </span>
            </div>

        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>Confirm Password <span class="required">*</span></label>
            <div class="input-group">
                {!! Form::password('confirm_password', ['id'=>'confirm_password', 'class' => 'form-control last_input', 'placeholder' => '',
                'required' =>
                true, 'maxlength' => 255, 'data-parsley-equalto' =>
                '#password', 'data-parsley-trigger' => 'blur', 'data-parsley-equalto-message' => 'Confirm Password should be same as Password']) !!}

                <span class="input-group-append view-password">
                    <button type="button" class="btn btn-info"><i class="fas fa-eye"></i>
                    </button>
                </span>
            </div>

        </div>
    </div>
</div>

<button type="submit" class="btn btn-info float-md-right last_input step1_submit btn-bg-default">Save & Next</button>
{!! Form::close() !!}

@push('page_scripts')
<script type="text/javascript">
    $(function(){       
        /*$('#con_email').bind("cut copy paste",function(e) {
            $.toast({
                //heading: 'Info',
                text: 'Action not allowed',
                icon: 'error',
                loader: true, // Change it to false to disable loader
                showHideTransition: 'slide',
                position: 'bottom-right',
                //loaderBg: '#9EC600'  // To change the background
            })
            e.preventDefault();
        });*/

        // Step 1 Form Submission Event
        $('#step1_form').submit(function(){
            $(".step1_submit").attr('disabled', true).html('Processing... <i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                type: 'POST',
                url: $('#step1_form').attr('action'),
                data: $('#step1_form').serialize(),
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

            return false;
        });


        $('.view-password').mousedown(function(){
            $(this).closest('.input-group').find('input').attr('type','text');
        });
        $('.view-password').mouseup(function(){
            $(this).closest('.input-group').find('input').attr('type','password');
        });
        
        if(navigator.userAgent.match(/Android|BlackBerry|iPhone|iPad|iPod|Opera Mini|IEMobile/i)){
            $('.view-password').on("touchstart", function (){
                $(this).closest('.input-group').find('input').attr('type','text');
            });
            $('.view-password').on("touchend", function (){
                $(this).closest('.input-group').find('input').attr('type','password');
            });
        }
    });
</script>
@endpush
