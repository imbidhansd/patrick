@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="card-box">
    <div class="text-center">
        <h2>{{ $admin_page_title.' '.$company_item->company_name }}</h2>

        <div class="clearfix">&nbsp;</div>
        <p>Upon completion of the pre screen questions you will be supplied with an expiring URL to submit your background/credit check. Please submit your background check immediately upon completing the following pre screen questions:</p>

        <div class="clearfix">&nbsp;</div>
        <a href="javascript:;" id="get_started_btn" class="btn btn-primary btn-md">Get Started</a>
    </div>
</div>

<div class="card-box" id="pre_screen_quesion_form" style="display: none;">
    {!! Form::open(['class' => 'module_form']) !!}

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Last Name') !!} <span class="required">*</span>
                        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => true]) !!}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('Last Four of SS#') !!} <span class="required">*</span>
                        {!! Form::text('last_four_of_ss', null, ['class' => 'form-control', 'placeholder' => 'Last Four of SS#', 'maxlength' => 4, 'data-parsley-type' => 'integer', 'data-toggle' => 'input-mask', 'data-mask-format' => '0000', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="clearfix">&nbsp;</div>

            <div class="form-group">
                <label>Have you ever been convicted of fraud? <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('convicted_in_fraud', 'Yes', null, ['id' => 'yes_convicted_in_fraud', 'required' => true]) !!}
                    <label for="yes_convicted_in_fraud">Yes</label>
                </div>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('convicted_in_fraud', 'No', null, ['id' => 'no_convicted_in_fraud', 'required' => true]) !!}
                    <label for="no_convicted_in_fraud">No</label>
                </div>
            </div>

            <div class="form-group">
                <label>Have you ever been convicted of a felony? <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('convicted_in_felony', 'Yes', null, ['id' => 'yes_convicted_in_felony', 'required' => true]) !!}
                    <label for="yes_convicted_in_felony">Yes</label>
                </div>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('convicted_in_felony', 'No', null, ['id' => 'no_convicted_in_felony', 'required' => true]) !!}
                    <label for="no_convicted_in_felony">No</label>
                </div>
            </div>

            <div class="form-group">
                <label>Have you filed for bankruptcy in the last 7 years? <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('bankruptcy', 'Yes', null, ['id' => 'yes_bankruptcy', 'required' => true]) !!}
                    <label for="yes_bankruptcy">Yes</label>
                </div>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('bankruptcy', 'No', null, ['id' => 'no_bankruptcy', 'required' => true]) !!}
                    <label for="no_bankruptcy">No</label>
                </div>
            </div>

            <div class="form-group">
                <label>Have you operated this business or a similar business under any other business name?  <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('other_business_name', 'Yes', null, ['class' => 'other_business_name', 'id' => 'yes_other_business_name', 'required' => true]) !!}
                    <label for="yes_other_business_name">Yes</label>
                </div>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('other_business_name', 'No', null, ['class' => 'other_business_name', 'id' => 'no_other_business_name', 'required' => true]) !!}
                    <label for="no_other_business_name">No</label>
                </div>
            </div>

            <div id="other_business_name_list" style="display: none;">
                <div class="form-group">
                    {!! Form::label('Please list all business names:') !!} <span class="required">*</span>
                    {!! Form::textarea('business_name_list', null, ['class' => 'form-control', 'rows' => '4', 'id' => 'business_name_list']) !!}
                </div>
            </div>

            <div class="form-group">
                <label>Have you changed your name in the last 7 years? <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('changed_name', 'Yes', null, ['class' => 'changed_name', 'id' => 'yes_changed_name', 'required' => true]) !!}
                    <label for="yes_changed_name">Yes</label>
                </div>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('changed_name', 'No', null, ['class' => 'changed_name', 'id' => 'no_changed_name', 'required' => true]) !!}
                    <label for="no_changed_name">No</label>
                </div>
            </div>

            <div id="other_name_list" style="display: none;">
                <div class="form-group">
                    {!! Form::label('Please list all name changes:') !!} <span class="required">*</span>
                    {!! Form::textarea('changed_name_list', null, ['class' => 'form-control', 'rows' => '4',  'id' => 'changed_name_list']) !!}
                </div>
            </div>

            <div class="form-group">
                <label>Have you changed your home address in the last 7 years? <span class="required">*</span></label>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('changed_home_address', 'Yes', null, ['class' => 'changed_home_address', 'id' => 'yes_changed_home_address', 'required' => true]) !!}
                    <label for="yes_changed_home_address">Yes</label>
                </div>
                <div class="radio radio-primary radio-circle">
                    {!! Form::radio('changed_home_address', 'No', null, ['class' => 'changed_home_address', 'id' => 'no_changed_home_address', 'required' => true]) !!}
                    <label for="no_changed_home_address">No</label>
                </div>
            </div>

            <div id="other_home_address_list" >
                <label>Please list all addresses you've lived for the past 7 years:</label>

                <div class="address_item">
                    <h4>Address <span class="address_count" style="display: none;">1</span></h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::text('address_line_1[]', null, ['class' => 'form-control', 'placeholder' => 'Address Line 1', 'required' => true]) !!}    
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::text('address_line_2[]', null, ['class' => 'form-control', 'placeholder' => 'Address Line 2', 'required' => true]) !!}    
                            </div>
                        </div>
                        

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::text('city[]', null, ['class' => 'form-control', 'placeholder' => 'City', 'required' => true]) !!}    
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::select('state[]', $states, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select State', 'required' => true]) !!}    
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::text('zipcode[]', null, ['class' => 'form-control', 'placeholder' => 'Zipcode', 'maxlength' => 5, 'data-parsley-type' => 'integer', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}    
                            </div>
                        </div>

                        <div class="col-md-6" id="other_home_address_stays" style="display: none;">
                            <div class="form-group">
                                {!! Form::select('stay_years[]', $stay_years, null, ['class' => 'form-control custom-select stay_years', 'placeholder' => 'Select How Many year you stay their?']) !!}    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center">
        <div class="clearfix">&nbsp;</div>
        <button type="submit" class="btn btn-primary btn-md">Submit</button>
    </div>

    {!! Form::close() !!}
</div>


<div class="copy_address_item" style="display: none;">
    <h4>Address <span class="address_count"></span></h4>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {!! Form::text('address_line_1[]', null, ['class' => 'form-control', 'placeholder' => 'Address Line 1']) !!}    
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::text('address_line_2[]', null, ['class' => 'form-control', 'placeholder' => 'Address Line 2']) !!}    
            </div>
        </div>
        

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::text('city[]', null, ['class' => 'form-control', 'placeholder' => 'City']) !!}    
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::select('state[]', $states, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select State']) !!}    
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::text('zipcode[]', null, ['class' => 'form-control', 'placeholder' => 'Zipcode', 'maxlength' => 5, 'data-parsley-type' => 'integer', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000']) !!}    
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {!! Form::select('stay_years[]', $stay_years, null, ['class' => 'form-control custom-select stay_years', 'placeholder' => 'Select How Many year you stay their?']) !!}    
            </div>
        </div>
    </div>
</div>
@endsection

@section ('page_js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script type="text/javascript">
    $(function (){
        $("#get_started_btn").on("click", function (){
            $("#pre_screen_quesion_form").show();

            $('html, body').animate({
                scrollTop: $("#pre_screen_quesion_form").offset().top - 130
            }, 1000);
        });
        //$("#get_started_btn").trigger("click");
        

        $(".other_business_name").on("change", function (){
            var radio_value = $(this).val();

            if (radio_value == 'Yes'){
                $("#other_business_name_list").show();
                $("#other_business_name_list #business_name_list").attr('required', true);
            } else {
                $("#other_business_name_list").hide();
                $("#other_business_name_list #business_name_list").attr('required', false);
                $("#other_business_name_list #business_name_list").val('');
            }
        });

        $(".changed_name").on("change", function (){
            var radio_value = $(this).val();

            if (radio_value == 'Yes'){
                $("#other_name_list").show();
                $("#other_name_list #changed_name_list").attr('required', true);
            } else {
                $("#other_name_list").hide();
                $("#other_name_list #changed_name_list").attr('required', false);
                $("#other_name_list #changed_name_list").val('');
            }
        });

        $(".changed_home_address").on("change", function (){
            var radio_value = $(this).val();

            if (radio_value == 'Yes'){
                $("#other_home_address_list #other_home_address_stays, #other_home_address_list .address_item .address_count").show();
                $("#other_home_address_list input, #other_home_address_list select").attr('required', true);
            } else {
                $("#other_home_address_list #other_home_address_stays, #other_home_address_list .address_item .address_count").hide();
                $("#other_home_address_list #other_home_address_stays input").attr('required', false);
                $("#other_home_address_list input, #other_home_address_list select").val('');
                $("#other_home_address_list .address_item").not(":first").remove();
                
                //$("#other_home_address_list input, #other_home_address_list select").attr('required', false);
                //$("#other_home_address_list input, #other_home_address_list select").val('');
            }
        });

        $(document).on("change", "#other_home_address_list .address_item .stay_years", function (){
            var total_years = 0;

            $("#other_home_address_list .address_item .stay_years").each(function (){
                if ($(this).val() != ''){
                    total_years += parseInt($(this).val());
                }
            });

            if (total_years < 7){
                $("#other_home_address_list .address_item:last").after('<div class="address_item">'+$(".copy_address_item").html()+'</div>');
                $("#other_home_address_list input, #other_home_address_list select").attr('required', true);
                var counter = $("#other_home_address_list .address_item").length;

                $("#other_home_address_list .address_item:last .address_count").text(counter);
            } else {
                var selected_years = 0;
                var flag = false;
                
                $("#other_home_address_list .address_item .stay_years").each(function (){
                    if ($(this).val() != ''){
                        selected_years += parseInt($(this).val());
                    }

                    if (selected_years > 7 && flag == true){
                        $(this).parents(".address_item").remove();
                    }
                    
                    if (selected_years >= 7) {
                        flag = true; 
                    }
                });
            }
        });
    });
</script>
@endsection
