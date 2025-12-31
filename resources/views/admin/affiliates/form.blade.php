@php
$aweberEnabledSwitchClass = '';
$display_aweber_configuration = 'display: none;';
$ariaPressed = 'false';
$show_service_type = true;
$show_main_category = true;
$aweber_enabled = 0;
if (isset($formObj) && $formObj->aweber_enabled) {
    $aweberEnabledSwitchClass = ' active';
    $display_aweber_configuration = '';
    $ariaPressed = 'true';
    $aweber_enabled = 1;
}

/*if (isset($formObj) && isset($formObj->trade_id)) {
    $show_service_type = true;
    $show_main_category = true;
}*/
@endphp
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Name') !!}
            {!! Form::text('affiliate_name', null, ['class' => 'form-control', 'id' => 'affiliate_name', 'placeholder' => 'Affiliate Name', 'readonly' => false, 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Domain') !!}
            {!! Form::text('domain', null, ['class' => 'form-control', 'id' => 'domain', 'placeholder' => 'Domain', 'readonly' => false, 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Abbreviation') !!}
            {!! Form::text('domain_abbr', null, ['class' => 'form-control', 'id' => 'domain_abbr', 'placeholder' => 'Abbreviation', 'readonly' => false, 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Member Base url') !!}
            {!! Form::text('member_base_url', null, ['class' => 'form-control', 'id' => 'member_base_url', 'placeholder' => 'Member Base Url', 'readonly' => false, 'required' => true]) !!}
        </div>
    </div>
</div>
<div class="row">
    <!-- <div class="col-xl-12">
        <div class="form-group">
            {!! Form::label('Type of service provider') !!}
            {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'Select Trade']) !!}
        </div>
    </div> -->
    @if (isset($service_category_types) && count($service_category_types) > 0 && $show_service_type)
        <div class="col-xl-12" id="service_category_type">
            <div class="form-group">
                {!! Form::label('Service Type') !!}
                <select name="service_category_type_id" id="service_category_type_id"
                    class="form-control custom-select" required>
                    <option value="">Select</option>
                    @foreach ($service_category_types as $key => $value)
                        <option value="{{ $key }}" @if (isset($formObj) && $formObj->service_category_type_id == $key) selected @endif>{{ $value }}</option>
                    @endforeach
                    <option value="both" @if (isset($formObj) && is_null($formObj->service_category_type_id)) selected @endif>Both</option>
                </select>
            </div>
        </div>
    @endif
    @if ($show_service_type)
    <div class="col-xl-12" id="main_category_selection">
        <div class="form-group">
            {!! Form::label('Service Offered (Please select all that apply)') !!}
            {!! Form::select('main_categories[]', [], null, ['class' => 'form-control custom-select select2 w-100', 'id' => 'main_categories', 'multiple' => true]) !!}
        </div>
    </div>
    @endif
</div>
<hr />
<div class="toggle-switch">
    <div class="row">
        <div class="col-sm-5">            
            {!! Form::hidden('aweber_enabled', $aweber_enabled, ['id' => 'aweber_enabled']) !!}
            <label for="btnEnableAweber">Enable Aweber</label>
            <button id="btnEnableAweber" type="button"
                class="btn btn-sm btn-secondary btn-toggle {{ $aweberEnabledSwitchClass }}" data-toggle="button"
                aria-pressed="{{ $ariaPressed }}" autocomplete="off">
                <div class="handle"></div>
            </button>
        </div>
        <div class="col-md-12" id="section_aweber_configuration" style="{{ $display_aweber_configuration }}">
            <div class="card w-100">
                <div class="card-body">
                    <h5 class="card-title">Aweber Configuration</h5>
                    <p class="card-text">Please use below link to configure
                        <strong>{{ env('APP_NAME') }}</strong>
                        to use Aweber for,
                    </p>
                    Access your accounts and their integrations </br>
                    Create, edit, and delete custom fields </br>
                    Create, edit, delete, and move your subscribers </br>
                    See your lists, custom fields, tags, and sign up forms </br>
                    See your subscribers and their activity</br>
                    <p>
                    </p>
                    <p class="card-text">Refer the link for <a
                            href="https://datatracker.ietf.org/doc/html/rfc6749">more information<a> on how the
                                authorization works.</p>
                    <a href="javascript:window.open('https://auth.aweber.com/oauth2/authorize?response_type=code&client_id={{ env('AWEBER_CLIENT_KEY') }}&redirect_uri={{ env('AWEBER_REDIRECT_URL') }}&scope=account.read list.read list.write subscriber.read subscriber.write&state=23sdfsdf234rwe', 'Aweber authorize', 'width=780,height=410,toolbar=0,scrollbars=0,status=0,resizable=0,location=0,menuBar=0,left=' + 500 + ',top=' + 200);"
                        class="btn btn-primary mb-3">
                        Authorize
                    </a>
                    <div class="row" id="section_aweber_tokens"
                        style="{{ $display_aweber_configuration }}">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Access Token') !!}
                                {!! Form::text('aweber_access_token', null, ['class' => 'form-control', 'id' => 'aweber_access_token', 'placeholder' => 'Access Token', 'readonly' => true, 'required' => false]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Refresh Token') !!}
                                {!! Form::text('aweber_refresh_token', null, ['class' => 'form-control', 'id' => 'aweber_refresh_token', 'placeholder' => 'Refresh Token', 'readonly' => true, 'required' => false]) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::label('Account Id') !!}
                                {!! Form::text('aweber_account_id', null, ['class' => 'form-control', 'id' => 'aweber_account_id', 'placeholder' => 'Account Id', 'readonly' => true, 'required' => false]) !!}
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
<hr/>
<div class="row">
    <div class="col-md-5">
        <div class="form-group">
            {!! Form::label('API Key') !!}
            {!! Form::text('api_key', null, ['class' => 'form-control', 'id' => 'api_key', 'placeholder' => 'API Key', 'readonly' => true, 'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-7">
        <div class="form-group">
            {!! Form::label('API Secret') !!}
            <div class="input-group">
                {!! Form::text('api_secret', null, ['class' => 'form-control', 'id' => 'api_secret', 'placeholder' => 'API Secret', 'readonly' => true, 'required' => true]) !!}
                <span class="input-group-append">
                    <a href="javascript:;" id="generate_key" class="btn btn-primary">Generate Key</a>
                </span>
            </div>
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Note') !!}
            {!! Form::text('note', null, ['class' => 'form-control', 'placeholder' => 'Note', 'required' => false]) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>

<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
@push('page_scripts')
    <style>
        .form-group .select2-container {
            width: 100% !important;
        }

    </style>
    <!-- Plugins js -->
    <script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
    <script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

    <script type="text/javascript">
        var configure_form = '';
        var affiliate_id = '';
        $(function() {            
            $("#generate_key").on("click", function() {
                var api_key = '{{ \App\Models\Custom::getRandomString(32) }}';
                var api_secret = '{{ \App\Models\Custom::getRandomString(50) }}';

                $("#api_key").val(api_key);
                $("#api_secret").val(api_secret);
            });
        });
        $(document).ready(function() {
            $('.select2').select2();
            $('#btnEnableAweber').on('click', function(e) {                
                let enabled = !($(e.currentTarget).attr("aria-pressed") === 'true');
                if (enabled) {
                    $('#section_aweber_configuration').show(500);
                    $('#aweber_enabled').val(1);
                } else {
                    $('#section_aweber_configuration').hide(500);
                    $('#aweber_enabled').val(0);
                }
            });
            
            @if (isset($formObj))
                configure_form = 'yes';
                affiliate_id = '{{ $formObj->id }}';
            @endif

            $("#service_category_type_id").on("change", function() {
                // var trade_id = $(this).val();
                // if (typeof trade_id !== 'undefined' && trade_id != '') {
                //     // if (trade_id == 1) {
                //     //     $("#service_category_type").show();
                //     //     $("#service_category_type #service_category_type_id").attr("required", true);
                //     // } else {
                //     //     $("#service_category_type").hide();
                //     //     $("#service_category_type #service_category_type_id").attr("required", false);
                //     // }

                    
                // } else {
                //     $("#service_category_type").hide();
                //     $("#top_level_category_selection").hide();
                // }
                var service_category_type_id = $(this).val() == "both" ? [1,2] : [$(this).val()];
                $.ajax({
                        url: '{{ url('get-listed/get-main-categories') }}',
                        type: 'POST',
                        data: {
                            'service_category_type_id': service_category_type_id,
                            'configure_form': configure_form, 
                            'affiliate_id': affiliate_id,
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(data) {
                            if (typeof data.success !== 'undefined') {
                                Swal.fire({
                                    title: data.title,
                                    type: data.type,
                                    text: data.message
                                });
                            } else {
                                $("#main_category_selection").show();
                                $("#main_categories").html(data);
                            }
                        }
                    });
            });

            @if (isset($formObj))
                $("#service_category_type_id").trigger("change");
            @endif
        });

        function setTokens(message) {
            $('#section_aweber_tokens').show(500);
            $('#section_aweber_tokens').find("#aweber_access_token").val(message.accessToken);
            $('#section_aweber_tokens').find("#aweber_refresh_token").val(message.refreshToken);
            $('#section_aweber_tokens').find("#aweber_account_id").val(message.account_id);
            $( "#submitConfiguration").trigger("click");
        }
    </script>
@endpush
