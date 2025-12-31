{!! Form::open(['url' => 'account/upgrade/step2','id' => 'step2_form', 'class' => 'module_form', 'files' => true]) !!}
{!! Form::hidden('ownership_type', null, ['id' => 'ownership_type']) !!}
{!! Form::hidden('number_of_owners', null, ['id' => 'number_of_owners']) !!}

<div class="row justify-content-center">

    <div class="col-lg-10">

        <div class="text-center">
            <h3 class="mb-4 mt-3">Company Ownership</h3>
            <p>Is your company publicly traded, owned by an investment firm or privately owned and operated?*</p>
        </div>

        <div class="row">
            <div class="col-lg-5 offset-lg-1">
                <div class="about-features-box text-center mt-4">
                    <div class="feature-icon bg-primary avatar-lg mb-4 rounded-circle mx-auto">
                        <i class="fas fa-users avatar-title h2 m-0"></i>
                    </div>
                    <h5 class="mb-3">Yes - Publicly Traded/Investment Firm</h5>
                    <a href="javascript:;" data-owners="1" data-type="public" class="btn btn-primary submit-step-2">Choose</a>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="about-features-box text-center mt-4">
                    <div class="feature-icon bg-info avatar-lg mb-4 rounded-circle mx-auto">
                        <i class="fas fa-user-shield avatar-title h2 m-0"></i>
                    </div>
                    <h5 class="mb-3">No-Privately Owned</h5>
                    <a href="javascript:;" class="btn btn-info show_owner_options">Choose</a>
                </div>
            </div>
        </div>


        <div id="owner_options" class="text-center">
            @include('company.profile.upgrade._owner_selection')
        </div>
    </div>
</div>
@if ($company_item->membership_level_id != '3')
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<button type="button" class="btn btn-dark float-md-left back_btn">Back</button>
@endif


{!! Form::close() !!}





@push('page_scripts')

<script type="text/javascript">
    $('.show_owner_options').click(function(){
        choose_owners_event = true;
        $('#owner_options').toggle();
        
        $("html, body").animate({
            scrollTop: $(document).height() 
        }, 1000);
        refresh_slick_content();
    });

    $('.submit-step-2').click(function(){
        $('#ownership_type').val($(this).data('type'));
        $('#number_of_owners').val($(this).data('owners'));
         $('#owner_options').hide();
        $('#step2_form').trigger('submit');

    });


    $('#step2_form').submit(function(){

        $.ajax({
            type: 'POST',
            url: $('#step2_form').attr('action'),
            data: $('#step2_form').serialize(),
            success: function(data){

                if (data.status == 0){
                    Swal.fire(
                        'Warning',
                        data.message,
                        'warning'
                        );
                }else{
                    slick_next();
                }
            },
            error: function(e){
                Swal.fire(
                    'Warning',
                    'Error while processing',
                    'warning'
                    );
            },
        });

        return false;

    });

</script>


@endpush
