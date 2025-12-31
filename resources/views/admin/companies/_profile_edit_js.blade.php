<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<!-- Summernote css -->
<link href="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
<!-- Summernote js -->
<script src="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.min.js') }}"></script>

<script type="text/javascript">
    $(function (){
        $(".summernote").summernote({
            height: 250,
            minHeight: null,
            maxHeight: null,
            focus: !1,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'insert', [ 'link'] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            ]
        });

        $('#udpateCompanyInfoModal, #udpateCompanyOwnerModal, #udpateCompanyBioModal').on('hidden.bs.modal', function(){
            $(this).find('form')[0].reset();
        });

        $(".company_logo .filestyle").on("change", function (){
            $(this).parents(".company_logo").submit();
        });
    });
</script>