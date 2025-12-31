<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script>
    $(function(){
        $('#day_limit').change(function(){
            if ($(this).val() == 'yes'){
                $('#number_of_days').removeAttr('disabled');
            }else if ($(this).val() == 'no'){
                $('#number_of_days').attr('disabled', true).val('0');
            }
        });
    });


    CKEDITOR.replace('content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('renew_content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('terms_content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });
</script>
