{!! Form::hidden('draft_message', 'no', ['id' => 'draft_message']) !!}
<div class="row">
    @if(isset($email_for))
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Email For') !!}
            {!! Form::select('email_for', $email_for, null, ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>
    @endif
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Trade') !!}
            {!! Form::select('trade_id', $trades, null, ['class' => 'form-control custom-select', 'id' => 'trade_id', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Top Level Category') !!}
            {!! Form::select('top_level_category_id', [], null, ['class' => 'form-control custom-select', 'id' => 'top_level_category_id', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Main Category') !!}
            {!! Form::select('main_category_id', [], null, ['class' => 'form-control custom-select', 'id' => 'main_category_id', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Service Category') !!}
            {!! Form::select('service_category_id', [], null, ['class' => 'form-control custom-select', 'id' => 'service_category_id', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Zipcode') !!}
            {!! Form::text('zipcode', null, ['class' => 'form-control', 'placeholder' => 'Zipcode', 'data-toggle' => 'input-mask', 'data-mask-format' => '00000', 'required' => false]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Mile Range') !!}
            {!! Form::select('mile_range', config('config.mile_options'), null, ['class' => 'form-control custom-select', 'placeholder' => 'Mile Range', 'required' => false]) !!}
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('From Email Address') !!}
            {!! Form::email('from_email_address', (isset($formObj) ? $formObj->from_email_address : env('MAIL_FROM_ADDRESS')), ['class' => 'form-control', 'placeholder' => 'From Email Address', 'required' => true]) !!}
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Subject') !!}
            {!! Form::text('subject', null, ['class' => 'form-control', 'placeholder' => 'Subject', 'required' => true]) !!}
        </div>
    </div>
    
    @if(is_null($email_type))
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Subscription type') !!}
            {!! Form::select('subscription_type', $subscription_type, null, ['class' => 'form-control custom-select', 'placeholder' => 'Select Subscription Type', 'required' => true]) !!}
        </div>
    </div>
    @endif
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Header Email Selection') !!}
            {!! Form::select('email_header_id', $header_emails, null, ['class' => 'form-control custom-select', 'id' => 'email_header_id', 'placeholder' => 'Select Header Email']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <label for="">&nbsp;</label>
        <div class="clearfix"></div>
        <div class="btn-group btn-group-solid">
            <a href="javascript:;" class="btn btn-primary btn-sm view_header">View</a>
            <a href="javascript:;" class="btn btn-orange btn-sm set_header">Set Header</a>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Header Content') !!}
            {!! Form::textarea('email_header', null, ['class' => 'form-control ckeditor', 'id' => 'email_header']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor', 'required' => true]) !!}
            
            <div id="mail_variables">
                @php
                    $mail_variables = config('broadcast_email_keywords');
                @endphp
                @if (isset($mail_variables) && count($mail_variables) > 0)
                <div class="clearfix">&nbsp;</div>
                <label>Mail Content Variables</label><br />
                @foreach ($mail_variables as $variable_item)
                <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $loop->index }}" data-clipboard-target="#var_{{ $loop->index }}" class="badge badge-info badge-label variable">
                    {{ $variable_item }}
                </span>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Footer Email Selection') !!}
            {!! Form::select('email_footer_id', $footer_emails, null, ['class' => 'form-control custom-select', 'id' => 'email_footer_id', 'placeholder' => 'Select Footer Email']) !!}
        </div>
    </div>
    <div class="col-md-6">
        <label for="">&nbsp;</label>
        <div class="clearfix"></div>
        <div class="btn-group btn-group-solid">
            <a href="javascript:;" class="btn btn-primary btn-sm view_footer">View</a>
            <a href="javascript:;" class="btn btn-orange btn-sm set_footer">Set Footer</a>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Footer Content') !!}
            {!! Form::textarea('email_footer', null, ['class' => 'form-control ckeditor', 'id' => 'email_footer']) !!}
        </div>
    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('When you want to send?') !!}
            <div class="radio radio-primary radio-circle">
                <input type="radio" name="when_send" class="when_send" value="now" id="send_now" data-parsley-errors-container="#when_send_error_container" required {{ ((isset($formObj) && is_null($formObj->send_datetime)) ? 'checked' : '') }} />
                <label for="send_now">Now</label>
            </div>

            <div class="radio radio-primary radio-circle">
                <input type="radio" name="when_send" class="when_send" value="later" id="send_later" data-parsley-errors-container="#when_send_error_container" required {{ ((isset($formObj) && !is_null($formObj->send_datetime)) ? 'checked' : '') }} />
                <label for="send_later">Later</label>
            </div>
            <div id="when_send_error_container"></div>
        </div>
    </div>
    <div class="col-md-6" id="send_later_datetime" style="display: none;">
        <div class="form-group">
            {!! Form::label('Send DateTime') !!}
            {!! Form::text('send_datetime', null, ['class' => 'form-control', 'id' => 'send_datetime', 'placeholder' => 'MM/DD/YYYY HH:II', 'data-toggle' => 'input-mask', 'data-mask-format' => '00/00/0000 00:00', 'data-parsley-pattern' =>'\d{2}/\d{2}/\d{4} \d{2}:\d{2}']) !!}
            <i>Note: Enter Send DateTime format same as: 'MM/DD/YYYY HH::II' <br />(HH must be added in 24 hour format)</i>
        </div>
    </div>
</div>

<hr />
<button type="button" class="btn btn-warning float-right waves-effect waves-light save_as_draft">Save As Draft</button>
<button type="submit" class="btn btn-info float-right waves-effect waves-light submit_broadcast_mail">Submit</button>


@push('page_scripts')
@include('admin.broadcast_emails._js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>

<script type="text/javascript">
    CKEDITOR.replace('content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });

    CKEDITOR.replace('email_header', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });

    CKEDITOR.replace('email_footer', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });

    $(function (){
        /* When Mail send process Start */
        $(".when_send").on("change", function () {
            var send_value = $(this).val();
            if (send_value == 'now') {
                $("#send_later_datetime").hide();
                $("#send_later_datetime #send_datetime").attr('required', false);
                $("#send_later_datetime #send_datetime").val('');
            } else if (send_value == 'later') {
                $("#send_later_datetime").show();
                $("#send_later_datetime #send_datetime").attr('required', true);
            }
        });
        
        @if (isset($formObj) && !is_null($formObj->send_datetime))
            $(".when_send").trigger("change");
            $("#send_later_datetime #send_datetime").val('{{ $formObj->send_datetime }}');
        @endif
        /* When Mail send process End */
        
        /* Header Start */
        $(".view_header").on("click", function (){
            var email_header_id = $("#email_header_id").val();
            get_header_email_content(email_header_id, 'view');
        });
        
        $(".set_header").on("click", function (){
            var email_header_id = $("#email_header_id").val();
            get_header_email_content(email_header_id, 'set');
        });
        /* Header End */
        
        
        /* Footer Start */
        $(".view_footer").on("click", function (){
            var email_footer_id = $("#email_footer_id").val();
            get_footer_email_content(email_footer_id, 'view');
        });
        
        $(".set_footer").on("click", function (){
            var email_footer_id = $("#email_footer_id").val();
            get_footer_email_content(email_footer_id, 'set');
        });
        /* Footer End */
        
        
        var clipboard = new ClipboardJS('.variable');
        clipboard.on('success', function (e) {
            $.toast({
                text: 'Copied to clipboard!',
                icon: 'info',
            })
            e.clearSelection();
        });
        
        
        $(".submit_broadcast_mail").on("click", function (){
            $("#draft_message").val('no');
            $(".module_form").submit();
        });
        
        $(".save_as_draft").on("click", function (){
            $("#draft_message").val('yes');
            $(".module_form").submit();
        });
    });
    
    function get_header_email_content(id, type){
        $.ajax({
            url: '{{ url("admin/default_email_header_footers/get_header_template") }}',
            type: 'POST',
            data: {'id': id, '_token': '{{ csrf_token() }}'},
            success: function (data){
                if (data.success == 1){
                    if (type == 'view'){
                        $("#emailHeaderModal #header_email_content").html(data.text);
                        $("#emailHeaderModal").modal('show');
                    } else if (type == 'set'){
                        CKEDITOR.instances['email_header'].setData(data.text);
                        $("#email_header").html(data.text);
                    }
                } else {
                    Swal.fire({
                        title: data.title,
                        type: data.type,
                        text: data.text
                    });
                }
            }
        });
    }
    
    function get_footer_email_content(id, type){
        $.ajax({
            url: '{{ url("admin/default_email_header_footers/get_footer_template") }}',
            type: 'POST',
            data: {'id': id, '_token': '{{ csrf_token() }}'},
            success: function (data){
                if (data.success == 1){
                    if (type == 'view'){
                        $("#emailFooterModal #footer_email_content").html(data.text);
                        $("#emailFooterModal").modal('show');
                    } else if (type == 'set'){
                        CKEDITOR.instances['email_footer'].setData(data.text);
                        $("#email_footer").html(data.text);
                    }
                } else {
                    Swal.fire({
                        title: data.title,
                        type: data.type,
                        text: data.text
                    });
                }
            }
        });
    }
</script>
@endpush