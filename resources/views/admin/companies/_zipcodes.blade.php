@php
$form_url = "update-company-zipcode-list";
$show_zip = isset($admin_form) && $admin_form;

if (isset($admin_form) && $admin_form){
    $form_url = "admin/companies/update-company-zipcode-list";
}
@endphp

<div class="text-left">
{!! Form::open(['url' => url($form_url), 'class' => 'module_form', 'id' => 'zipcode_form']) !!}

    {!! Form::hidden('company_id', $company_item->id) !!}
    <p class="text-muted font-13">
        <strong>Please enter the main zip code of your working territory. *</strong>
        <br />
        <span>A Region is defined by a <span class="mile_range_change_txt">{{ $company_item->mile_range }}</span> Mile
            radius of your center Zip Code. To purchase
            additional regions, please contact us at 720-445-4400.</span>
    </p>

    <div class="row">
        <div class="col-sm-4">
            <div class="form-group">
                <label>Main zip code</label>
                {!! Form::text('main_zipcode', $company_item->main_zipcode, ['id' => 'zipcode',
                'class' => 'form-control', 'data-toggle' => 'input-mask', 'data-mask-format' =>
                '00000', 'readonly' => false]) !!}
            </div>
        </div>

        @if($show_zip)        
        <div class="col-sm-4">
            <div class="form-group">
                <label>Allow 100 Miles?</label>
                {!! Form::select('allow_100_miles', ['no' => 'No', 'yes' => 'Yes'], $company_item->allow_100_miles, ['class' => 'form-control custom-select', 'id' => 'allow_100_miles', 'required' => false]) !!}
            </div>
        </div>
        @endif
        
        <div class="col-sm-4">
            <div class="form-group">
                <label>Mail Range</label>
                @php
                    $miles_arr = config('config.mile_options');
                    
                    if ($company_item->allow_100_miles == 'yes'){
                        $miles_arr['100'] = '100 Miles';
                    }
                @endphp
                
                {!! Form::select ('mile_range', $miles_arr, isset($company_item->mile_range) ? $company_item->mile_range : null, ['id' => 'mile_range' ,'class' => 'form-control custom-select', 'placeholder' => 'Select Zip radius', 'required' => false]) !!}
            </div>
        </div>
    </div>

    <div class="googlemapborder">
        <div id="map-canvas" style="height:300px;"></div>
    </div>

    <div class="clearfix">&nbsp;</div>
    <div class="alert alert-info">All zip codes below fall within the ZIP code radius for the main zip code you
        have chosen. Please deselect zip codes you do not service or edit your zip code and
        increase/decrease your zip code radius. If you would like to unsubscribe, you can do
        so by clicking on the Unsubscribe button in the upper-right hand corner of this
        page.</div>

    <div class="clearfix">&nbsp;</div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            <a href="javascript:;" class="btn btn-sm btn-success chk-all-zipcodes">Check All</a>&nbsp;
            <a href="javascript:;" class="btn btn-sm btn-danger unchk-all-zipcodes">Uncheck All</a>
        </div>

        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="text-right">
                <button type="submit" class="btn btn-sm btn-primary btn-rounded width-sm waves-effect waves-light">Update</button>
            </div>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>
    <div id="zip_code_list">
        @if (isset($company_zip_codes) && count($company_zip_codes) > 0)
        <div class="row">
            @foreach ($company_zip_codes AS $zip_code_item)
            <div class="col-sm-4">
                <ul class="pl20">
                    <li>
                        <div class="checkbox checkbox-primary">
                            <input name="zipcode_item[]" class="chk-option" value="{{ $zip_code_item->zip_code }}" id="miles_{{ $zip_code_item->zip_code }}" type="checkbox" {{ (($zip_code_item->status == 'active') ? 'checked' : '') }} />
                            <label for="miles_{{ $zip_code_item->zip_code }}">
                                {{ $zip_code_item->state.', '.$zip_code_item->zip_code.', '.$zip_code_item->city.', ('.$zip_code_item->distance.' miles)' }}
                            </label>
                        </div>
                    </li>
                </ul>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="text-right">
        <button type="submit" class="btn btn-sm btn-primary btn-rounded width-sm waves-effect waves-light">Update</button>
    </div>

    {!! Form::close() !!}
</div>


@push('_edit_company_profile_js')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&key={{ env('GOOGLE_MAP_API_KEY') }}"></script>
<script src="{{ asset('js/zipcode-radius.js') }}"></script>
<script type="text/javascript">   
    function showSuccessMessage(message) {
        // Create a div for the success message
        var successDiv = $('<div>', {
            id: 'successMessage',
            text: message,
            css: {
                position: 'fixed',
                bottom: '10px',
                left: '50%',
                transform: 'translateX(-50%)',
                padding: '5px 10px', // Reduced padding
                backgroundColor: '#4CAF50',
                color: 'white',
                display: 'none',
                zIndex: 1000,
                borderRadius: '5px', // Rounded corners
                fontSize: '14px' // Reduced font size
            }
        });

        // Append the div to the body
        $('body').append(successDiv);

        // Show the success message div
        successDiv.fadeIn().delay(3000).fadeOut(function() {
            // Remove the div after fading out
            $(this).remove();
        });
    }
    
    $(function () {
        $("#zipcode").on("blur", function () {
            getGoogleMaps($("#mile_range").val());
        });

        $('.chk-all-zipcodes').click(function () {
            $('.chk-option').attr('checked', true);
        });

        $('.unchk-all-zipcodes').click(function () {
            $('.chk-option').removeAttr('checked');
        });
        
        
        $("#allow_100_miles").on("change", function (){
            if ($(this).val() == 'yes'){
                $("#mile_range").append('<option value="100">100 Miles</option>');
            } else if ($(this).val() == 'no'){
                $("#mile_range option:last").remove();
            }
        });
        
        $('#mile_range').change(function () {
            if ($(this).val() > 0) {
                getGoogleMaps($(this).val());
                $(".mile_range_change_txt").text($(this).val());
            } else {
                getGoogleMaps(1);
            }

            /*var zipcode = '{{ $company_item->main_zipcode }}';*/
            var zipcode = $("#zipcode").val();
            var mile_range = $(this).val();

            @if (isset($admin_form) && $admin_form)
            var ajax_link = "{{ url('admin/companies/zipcode-list-display') }}";
            @else
            var ajax_link = "{{ url('zipcode-list-display') }}";
            @endif


            $.ajax({
                url: ajax_link,
                type: 'POST',
                data: {'zipcode': zipcode, 'mile_range': mile_range, '_token': '{{ csrf_token() }}'},
                success: function (data) {                    
                    if (typeof data.status !== 'undefined') {
                        alert(data.message);
                    } else {
                        $("#zip_code_list").html(data);
                    }
                }
            });
        });
        
    });
</script>
@endpush
