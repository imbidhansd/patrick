<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script>
$(function () {
    $('.select2').select2();

    $('#day_limit').change(function () {
        if ($(this).val() == 'yes') {
            $('#number_of_days').removeAttr('disabled');
        } else if ($(this).val() == 'no') {
            $('#number_of_days').attr('disabled', true).val('0');
        }
    });

    $('#paid_members').change(function () {
        if ($(this).val() == 'yes') {
            $('#membership_fee').prop('disabled', false);
            $('#charge_type').prop('disabled', false);
        } else {
            $('#membership_fee').prop('disabled', true);
            $('#membership_fee').val(0);
            $('#charge_type').prop('disabled', true);
        }
    });
    $('#paid_members').trigger('change');
});

CKEDITOR.replace('short_content', {
    filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
    filebrowserUploadMethod: 'form'
}).config.allowedContent = true;

CKEDITOR.replace('content', {
    filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
    filebrowserUploadMethod: 'form'
}).config.allowedContent = true;
CKEDITOR.replace('renew_content', {
    filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
    filebrowserUploadMethod: 'form'
});
CKEDITOR.replace('terms_content', {
    filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
    filebrowserUploadMethod: 'form'
});
</script>
