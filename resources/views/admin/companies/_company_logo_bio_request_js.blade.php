<!-- Summernote css -->
<link href="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
<!-- Summernote js -->
<script src="{{ asset('/themes/admin/assets/libs/summernote/summernote-bs4.min.js') }}"></script>
<?php /* <!-- Summernote Cleaner js -->
<script src="{{ asset('/js/summernote-cleaner.js') }}"></script> */ ?>

<script type="text/javascript">
    $(function () {
        $(".summernote").summernote({
            height: 250,
            minHeight: null,
            maxHeight: null,
            focus: !1,
            toolbar: [
                //['cleaner',['cleaner']], // The Button
                //['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
                //['fontname', ['fontname']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ol', 'ul', 'paragraph', 'height']],
                ['table', ['table']],
                //['insert', ['link']],
                ['view', ['undo', 'redo', 'fullscreen', 'codeview', 'help']]
            ],
        });

        $('#udpateCompanyInfoModal, #udpateCompanyOwnerModal, #udpateCompanyBioModal').on('hidden.bs.modal', function () {
            $(this).find('form')[0].reset();
        });

        $(".company_logo .filestyle").on("change", function () {
            $(this).parents(".company_logo").submit();
        });


        /* Company Bio approval status start */
        @if (isset($admin_form) && $admin_form)
        $(document).on("submit", "#update_company_bio_form", function (){
            var sendData = $(this).serialize();
            $.ajax({
                url: '{{ url("admin/companies/update-company-bio") }}',
                type: 'POST',
                data: sendData,
                success: function (data){
                    $("#udpateCompanyBioModal").modal("hide");

                    $("#udpateCompanyBioModal .update_company_profile_btn").html('Save changes');
                    $("#udpateCompanyBioModal .update_company_profile_btn").attr('disabled', false);

                    if (typeof data.success !== 'undefined'){
                        Swal.fire({
                            title: data.title,
                            text: data.message,
                            type: data.type,
                        }).then(function (t) {
                            //window.location.reload();
                        });
                    } else {
                        $("#company_bio_update").html(data);

                        Swal.fire({
                            title: 'Success',
                            text: 'Company Bio Updated successfully.',
                            type: 'success',
                        });
                    }
                },
                error: function () {
                    type = "error";
                    html = "Error while processing, ";

                    Swal.fire({
                        type: type,
                        html: html
                    });
                }
            });
            return false;
        });
        @endif

        $(document).on("click", ".accept_company_bio", function (){
            swal_types('company_bio');
        });

        $(document).on("click", ".reject_company_bio", function (){
            $("#rejectCompanyInfoModal .modal-title").text("Reason for Reject Compaby Bio");
            $("#rejectCompanyInfoModal #approval_status_type").val("company_bio");
        });

        $(document).on("click", ".remove_company_bio", function (){
            swal_remove_types('company_bio');
        });

        $(document).on("submit", "#company_bio_reject_form", function (){
            var sendData = $(this).serialize();
            approvalStatusAjax (sendData);
            $("#company_bio_reject_form #submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#company_bio_reject_form #submit_btn").attr('disabled', true);
            return false;
        });
        /* Company Bio approval status end */


        /* Company Logo approval status start */
        $(document).on("click", ".accept_company_logo", function (){
            swal_types('company_logo');
        });

        $(document).on("click", ".reject_company_logo", function (){
            $("#rejectCompanyInfoModal .modal-title").text("Reason for Reject Company Logo");
            $("#rejectCompanyInfoModal #approval_status_type").val("company_logo");
        });

        $(document).on("click", ".remove_company_logo", function (){
            swal_remove_types('company_logo');
        });
        /* Company Bio approval status end */
    });


function swal_types (status_type){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#53479a",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, Accept it!"
    }).then(function (t) {
        if (typeof t.value !== 'undefined'){
            var sendData = {
                'company_id': '{{ $company_item->id }}',
                'approval_status_type': status_type,
                'approval_status': 'completed',
                '_token': '{{ csrf_token() }}'
            };


            if (status_type == 'company_logo'){
                $(".accept_company_logo").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".accept_company_logo").off('click');
            } else if (status_type == 'company_bio'){
                $(".accept_company_bio").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".accept_company_bio").off('click');
            }

            approvalStatusAjax(sendData);
        }
    });
}


function swal_remove_types (status_type){
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#ff0000",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Yes, Remove it!"
    }).then(function (t) {
        if (typeof t.value !== 'undefined'){
            var sendData = {
                'company_id': '{{ $company_item->id }}',
                'approval_status_type': status_type,
                'approval_status': 'remove',
                '_token': '{{ csrf_token() }}'
            };

            if (status_type == 'company_logo'){
                $(".remove_company_logo").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".remove_company_logo").off('click');
            } else if (status_type == 'company_bio'){
                $(".remove_company_bio").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $(".remove_company_bio").off('click');
            }

            approvalStatusAjax(sendData);
        }
    });
}

function approvalStatusAjax(sendData){
    $.ajax({
        url: '{{ url("admin/companies/change-company-approval-status") }}',
        type: 'POST',
        data: sendData,
        success: function (data){
            $("#rejectCompanyInfoModal").modal("hide");
            $("#company_bio_reject_form #submit_btn").html('Submit');
            $("#company_bio_reject_form #submit_btn").attr('disabled', false);
            $("#company_bio_reject_form .modal-body textarea").val('');

            if (typeof data.success !== 'undefined'){
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    type: data.type,
                }).then(function (t) {
                    //window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Success',
                    text: 'Company Profile updated successfully.',
                    type: 'success',
                });

                if (data.type == 'company_bio'){
                    $("#company_bio_update").html(data.data);
                } else if (data.type == 'company_logo'){
                    $("#company_logo_update").html(data.data);

                    $(".filestyle").each(function() {
                        var t = $(this),
                        i = {
                            input: "false" !== t.attr("data-input"),
                            htmlIcon: t.attr("data-icon"),
                            buttonBefore: "true" === t.attr("data-buttonBefore"),
                            disabled: "true" === t.attr("data-disabled"),
                            size: t.attr("data-size"),
                            text: t.attr("data-text"),
                            btnClass: t.attr("data-btnClass"),
                            badge: "true" === t.attr("data-badge"),
                            dragdrop: "false" !== t.attr("data-dragdrop"),
                            badgeName: t.attr("data-badgeName"),
                            placeholder: t.attr("data-placeholder")
                        };
                        t.filestyle(i)
                    });
                }
            }
        },
        error: function () {
            type = "error";
            html = "Error while processing, ";

            Swal.fire({
                type: type,
                html: html
            });
        }
    });
}
</script>
