<div class="row">
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('First Name') !!}
            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First Name', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('Last Name') !!}
            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last Name', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('Email') !!}
            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('Company Name') !!}
            {!! Form::text('company_name', null, ['class' => 'form-control', 'placeholder' => 'Company Name', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('Phone') !!}
            {!! Form::text('phone', null, ['class' => 'form-control', 'placeholder' => 'Phone', 'data-toggle' => 'input-mask', 'data-mask-format' => '(000) 000-0000', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('Address') !!}
            {!! Form::text('address', null, ['class' => 'form-control', 'placeholder' => 'Address', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('City') !!}
            {!! Form::text('city', null, ['class' => 'form-control', 'placeholder' => 'City', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('State') !!}
            {!! Form::select('state_id', $states, null, ['class' => 'form-control custom-select', 'placeholder' => 'State', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('zipcode') !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-xl-6">
        <div class="form-group">
            {!! Form::label('Mile Range') !!}
            {!! Form::select ('mile_range', config('config.mile_options'), null, ['id' => 'mile_range' ,'class' => 'form-control custom-select', 'placeholder' => 'Select Mile Range', 'required' => true]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="form-group">
            {!! Form::label('Type of service provider') !!}
            {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'Select Trade', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-xl-12" id="top_level_category_selection">
        <div class="form-group">
            {!! Form::label('Service Offered (Please select all that apply)') !!}
            {!! Form::select('top_level_categories[]', [], null, ['class' => 'form-control custom-select select2', 'id' => 'top_level_categories', 'multiple' => true, 'required' => true]) !!}
        </div>
    </div>
    
    @if (isset($service_category_types) && count($service_category_types) > 0)
    <div class="col-xl-12" id="service_category_type">
        <div class="form-group">
            {!! Form::label('Service Type') !!}
            <select name="service_category_type_id" id="service_category_type_id" class="form-control custom-select">
                <option value="">Select Service Type</option>
                @foreach ($service_category_types AS $key => $value)
                <option value="{{ $key }}" @if (isset($formObj) && $formObj->service_category_type_id == $key) selected @endif>{{ $value }}</option>
                @endforeach
                <option value="both" @if (isset($formObj) && is_null($formObj->service_category_type_id) && $formObj->trade_id == '1') selected @endif>Both</option>
            </select>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="form-group">
            {!! Form::label('How did you hear about us?') !!}
            {!! Form::select('how_did_you_hear_about_us', $how_did_you_hear_about_us, null, ['class' => 'form-control custom-select', 'placeholder' => 'How did you hear about us?', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-xl-12">
        <div class="form-group">
            {!! Form::label('Comments/Questions') !!}
            {!! Form::textarea('comments', null, ['class' => 'form-control', 'placeholder' => 'Comments/Questions', 'required' => false]) !!}
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>


@push ('non_member_js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script type="text/javascript">
    $(function () {
        $('.select2').select2();
        
        $("#trade_id").on("change", function (){
            var trade_id = $(this).val();
            
            if (typeof trade_id !== 'undefined' && trade_id != ''){
                if (trade_id == 1){
                    $("#service_category_type").show();
                    $("#service_category_type #service_category_type_id").attr("required", true);
                } else {
                    $("#service_category_type").hide();
                    $("#service_category_type #service_category_type_id").attr("required", false);
                }
                
                var edit_form = 'no';
                var non_member_id = '';
                @if (isset($formObj))
                edit_form = 'yes';
                non_member_id = '{{ $formObj->id }}';
                @endif
                
                $.ajax({
                    url: '{{ url("get-listed/get-top-level-categories") }}',
                    type: 'POST',
                    data: {'trade_id': trade_id, 'edit_form': edit_form, 'non_member_id': non_member_id, '_token': '{{ csrf_token() }}'},
                    success: function (data){
                        if (typeof data.success !== 'undefined'){
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message
                            });
                        } else {
                            $("#top_level_categories").html(data);
                        }
                    }
                });
            }
        });
        
        
        @if (isset($formObj))
        $("#trade_id").trigger("change");
        @endif
    });
</script>
@endpush