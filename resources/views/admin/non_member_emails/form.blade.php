<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Title') !!}
            {!! Form::text('title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Title', 'required' =>
            true, 'maxlength' => 255]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('From Email Address') !!}
            {!! Form::email('from_email_address', (isset($formObj) ? $formObj->from_email_address : env('MAIL_FROM_ADDRESS')), ['class' => 'form-control', 'placeholder' => 'From Email Address', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Subject') !!}
            {!! Form::text('subject', null, ['class' => 'form-control max', 'placeholder' => 'Enter Subject', 'required'
            => true, 'maxlength' => 255]) !!}
        </div>
    </div>
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
            {!! Form::label('Email Content') !!}
            {!! Form::textarea('email_content', null, ['class' => 'form-control ckeditor']) !!}

            @php
            if (isset($formObj)){
            $mail_variables = config('new_email_keywords.non_members.'.$formObj->email_type);
            } else {
            $mail_variables = config('new_email_keywords.non_members.followup_email');
            }
            @endphp

            @if (isset($mail_variables) && count($mail_variables) > 0)
            <div class="clearfix">&nbsp;</div>
            <label>Mail Variables</label><br />
            @foreach ($mail_variables as $variable_item)
            <span data-toggle="tooltip" data-placement="top" data-clipboard-action="copy" id="var_{{ $loop->index }}" data-clipboard-target="#var_{{ $loop->index }}" class="badge badge-info badge-label variable">{{ $variable_item }}</span>
            @endforeach
            @endif
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
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Send Time') !!}
                    {!! Form::text('sendtime', null, ['class' => 'form-control', 'placeholder' => 'Enter Send Time', 'required' => true]) !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('Send Time Selection') !!}
                    {!! Form::select('sendtime_selection', ['Seconds' => 'Seconds', 'Minutes' => 'Minutes', 'Hours' => 'Hours', 'Days' => 'Days'], null, ['class' => 'form-control custom-select', 'required' => true]) !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => 'required']) !!}
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>


<div class="modal fade" id="emailHeaderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Header Content</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="header_email_content"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="emailFooterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white" id="exampleModalLabel">Footer Content</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="footer_email_content"></div>
            </div>
        </div>
    </div>
</div>


@push('formpage_js')
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>

<script type="text/javascript">
$('.variable').tooltip();

$('.variable').click(function () {
    $(this).tooltip('show');
});

var clipboard = new ClipboardJS('.variable');
clipboard.on('success', function (e) {
    $.toast({
        text: 'Copied to clipboard!',
        icon: 'info',
    })
    e.clearSelection();
});

CKEDITOR.replace('email_content', {
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


$(function () {
    /* Header Start */
    $(".view_header").on("click", function () {
        var email_header_id = $("#email_header_id").val();
        if (typeof email_header_id !== 'undefined' && email_header_id != ''){
            get_header_email_content(email_header_id, 'view');
        } else {
            Swal.fire({
                title: 'Error',
                type: 'error',
                text: 'Select header template first!'
            });
        }
    });

    $(".set_header").on("click", function () {
        var email_header_id = $("#email_header_id").val();
        if (typeof email_header_id !== 'undefined' && email_header_id != ''){
            var confirmation_msg = true;
            @if (isset($formObj))
            var old_id = '{{ $formObj->email_header_id }}';
            if (old_id != email_header_id){
                confirmation_msg = confirm('Are you sure you want to change Header content?');
            }
            @endif    

            if (confirmation_msg){
                get_header_email_content(email_header_id, 'set');
            }
        } else {
            Swal.fire({
                title: 'Error',
                type: 'error',
                text: 'Select header template first!'
            });
        }
    });
    /* Header End */


    /* Footer Start */
    $(".view_footer").on("click", function () {
        var email_footer_id = $("#email_footer_id").val();
        if (typeof email_footer_id !== 'undefined' && email_footer_id != ''){
            get_footer_email_content(email_footer_id, 'view');
        } else {
            Swal.fire({
                title: 'Error',
                type: 'error',
                text: 'Select footer template first!'
            });
        }
    });

    $(".set_footer").on("click", function () {
        var email_footer_id = $("#email_footer_id").val();
        if (typeof email_footer_id !== 'undefined' && email_footer_id != ''){
            var confirmation_msg = true;
            @if (isset($formObj))
            var old_id = '{{ $formObj->email_footer_id }}';
            if (old_id != email_footer_id){
                confirmation_msg = confirm('Are you sure you want to change Footer content?');
            }
            @endif    

            if (confirmation_msg){
                get_footer_email_content(email_footer_id, 'set');
            }
        } else {
            Swal.fire({
                title: 'Error',
                type: 'error',
                text: 'Select footer template first!'
            });
        }
    });
    /* Footer End */
});

function get_header_email_content(id, type) {
    $.ajax({
        url: '{{ url("admin/default_email_header_footers/get_header_template") }}',
        type: 'POST',
        data: {'id': id, '_token': '{{ csrf_token() }}'},
        success: function (data) {
            if (data.success == 1) {
                if (type == 'view') {
                    $("#emailHeaderModal #header_email_content").html(data.text);
                    $("#emailHeaderModal").modal('show');
                } else if (type == 'set') {
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

function get_footer_email_content(id, type) {
    $.ajax({
        url: '{{ url("admin/default_email_header_footers/get_footer_template") }}',
        type: 'POST',
        data: {'id': id, '_token': '{{ csrf_token() }}'},
        success: function (data) {
            if (data.success == 1) {
                if (type == 'view') {
                    $("#emailFooterModal #footer_email_content").html(data.text);
                    $("#emailFooterModal").modal('show');
                } else if (type == 'set') {
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