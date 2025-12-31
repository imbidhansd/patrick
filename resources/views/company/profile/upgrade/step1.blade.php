{!! Form::open(['url' => 'account/upgrade/step1','id' => 'step1_form', 'class' => 'module_form', 'files' => true]) !!}
{!! Form::hidden('membership_id', null, ['id' => 'membership_id']) !!}

<div class="row justify-content-center">
    <div class="col-xl-10 center-page">
        @include('company.profile.upgrade._membership_plan_selection')
    </div>
    <!-- end col -->
</div>

{!! Form::close() !!}



@push('page_scripts')
<script type="text/javascript">
    $(function(){
        $('.membership_selection_btn').click(function(){
            $('#membership_id').val($(this).data('id'));
            $('#step1_form').trigger('submit');

        });


        $('#step1_form').submit(function(){

            $.ajax({
                type: 'POST',
                url: $('#step1_form').attr('action'),
                data: $('#step1_form').serialize(),
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

    });
</script>
@endpush
