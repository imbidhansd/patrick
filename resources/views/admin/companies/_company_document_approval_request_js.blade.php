
<link href="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet"
    type="text/css" />
<script src="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
    $(function (){
        $(document).on("click", ".uploadFile", function (){
            var modal_title = $(this).parents(".col-md-4").find(".card-title").text();
            var document_type = $(this).data("document_type");
            var field_name = $(this).data("field_name");

            $("#CompanyFilesModal .modal-title .modal-title-text").text(modal_title);
            $("#CompanyFilesModal #document_type").val(document_type);
            $("#CompanyFilesModal #field_name").val(field_name);
            

            if ($(this).data("expiry") == 'yes'){
                $("#CompanyFilesModal #expiry_type").val('yes');
                $("#CompanyFilesModal #expiry_date_field").show();
                $("#CompanyFilesModal #expiry_date_field .date_field").attr("required", true);
            } else {
                $("#CompanyFilesModal #expiry_type").val('no');
                $("#CompanyFilesModal #expiry_date_field").hide();
                $("#CompanyFilesModal #expiry_date_field .date_field").attr("required", false);
            }
        });


        $(document).on("click", ".uploadSingleInsuranceFile", function (){
            var modal_title = $(this).parents(".col-md-4").find(".card-title").text();
            var document_type = $(this).data("document_type");
            var field_name = $(this).data("field_name");

            $("#CompanyInsuranceSingleFileModal .modal-title .modal-title-text").text(modal_title);
            $("#CompanyInsuranceSingleFileModal #document_type").val(document_type);
            $("#CompanyInsuranceSingleFileModal #field_name").val(field_name);
        });


        $(document).on("click", ".uploadInsuranceFile", function (){
            var modal_title = $(this).parents(".col-md-4").find(".card-title").text();
            var document_type = $(this).data("document_type");
            var field_name = $(this).data("field_name");

            $("#CompanyInsuranceFileModal .modal-title .modal-title-text").text(modal_title);
            $("#CompanyInsuranceFileModal #document_type").val(document_type);
            $("#CompanyInsuranceFileModal #field_name").val(field_name);

            if (field_name == "gen_lia_ins_file_id"){
                $("#CompanyInsuranceFileModal #liability_insuranve_document").show();
                $("#CompanyInsuranceFileModal #liability_insuranve_document input").attr("required", true);

                $("#CompanyInsuranceFileModal #compensation_insurance_document").hide();
                $("#CompanyInsuranceFileModal #compensation_insurance_document input").attr("required", false);
                $("#CompanyInsuranceFileModal #compensation_insurance_document input").attr("disabled", true);
            } else if (field_name == "work_com_ins_file_id"){
                $("#CompanyInsuranceFileModal #compensation_insurance_document").show();
                $("#CompanyInsuranceFileModal #compensation_insurance_document input").attr("required", true);

                $("#CompanyInsuranceFileModal #liability_insuranve_document").hide();
                $("#CompanyInsuranceFileModal #liability_insuranve_document input").attr("required", false);
                $("#CompanyInsuranceFileModal #liability_insuranve_document input").attr("disabled", true);
            }
        });
        
        $(document).on("submit", "#company_document_upload_form", function (){
            var form = $(this)[0]; // You need to use standard javascript object here
            var formData = new FormData(form);
            
            $("#company_document_upload_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#company_document_upload_btn").attr('disabled', true);
            
            $.ajax({
                url: '{{ url("admin/companies/upload-company-documents") }}',
                type: 'POST',
                data: formData,
                contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                processData: false, // NEEDED, DON'T OMIT THIS
                success: function (data){
                    $("#CompanyFilesModal .modal-body input").val('');
                    $("#CompanyFilesModal").modal("hide");
                    $("#company_document_upload_btn").html('Submit');
                    $("#company_document_upload_btn").attr('disabled', false);
                    
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
                            text: 'Company Document uploaded successfully.',
                            type: 'success',
                        });

                        $("#company_document_update").html(data);
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
        

        $(document).on("click", ".accept_file", function (){
            var document_type = $(this).data("document_type");
            var document_id = $(this).data("document_id");

            $("#acceptCompanyDocumentModal #document_type_hh").val(document_type);
            $("#acceptCompanyDocumentModal #document_id_hh").val(document_id);

            var modal_title = $(this).parents(".col-md-4").find(".card-title").text();
            $("#acceptCompanyDocumentModal .modal-title .modal-title-text").text(modal_title);

            if ($(this).data("expiry") == 'yes'){
                $("#acceptCompanyDocumentModal #expiry_date_field").show();
                $("#acceptCompanyDocumentModal #expiry_date_field .date_field").attr("required", true);
            } else {
                $("#acceptCompanyDocumentModal #expiry_date_field").hide();
                $("#acceptCompanyDocumentModal #expiry_date_field .date_field").attr("required", false);
            }
        });

        $(document).on("submit", "#company_document_accept_form", function (){
            var sendData = $(this).serialize();
            documentApprovalStatusAjax (sendData);
            
            $("#company_document_accept_form #submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#company_document_accept_form #submit_btn").attr('disabled', true);
            
            return false;
        });
        
        $(document).on("click", ".reject_file", function (){
            var document_type = $(this).data("document_type");
            var document_id = $(this).data("document_id");

            $("#rejectCompanyDocumentModal #document_type_hh").val(document_type);
            $("#rejectCompanyDocumentModal #document_id_hh").val(document_id);

            var modal_title = $(this).parents(".col-md-4").find(".card-title").text();
            $("#rejectCompanyDocumentModal .modal-title .modal-title-text").text(modal_title);
        });

        $(document).on("submit", "#company_document_reject_form", function (){
            var sendData = $(this).serialize();
            documentApprovalStatusAjax (sendData);
            
            $("#company_document_reject_form #submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#company_document_reject_form #submit_btn").attr('disabled', true);
            return false;
        });

        $(document).on("click", ".remove_file", function (){
            var remove_files = $(this);
            var document_type = remove_files.data("document_type");
            var field_name = remove_files.data("field_name");
            var document_id = remove_files.data("document_id");

            $("#remove_company_document_form #document_id_h").val(document_id);
            $("#remove_company_document_form #field_name_h").val(field_name);
            $("#remove_company_document_form #document_type_h").val(document_type);

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
                    remove_files.html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                    remove_files.off('click');
                    
                    $.ajax({
                        url: '{{ url("admin/companies/remove-company-documents") }}',
                        type: 'POST',
                        data: $("#remove_company_document_form").serialize(),
                        success: function (data){
                            $("#remove_company_document_form #document_id_h, #remove_company_document_form #field_name_h, #remove_company_document_form #document_type_h").val('');
            
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
                                    text: 'Company Document Removed successfully.',
                                    type: 'success',
                                });

                                $("#company_document_update").html(data);
                            }
                
                            //window.location.reload();
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
                    
                    //$("#remove_company_document_form").submit();
                }
            });
        });
    });


    function documentApprovalStatusAjax(sendData){
        $.ajax({
            url: '{{ url("admin/companies/change-company-document-status") }}',
            type: 'POST',
            data: sendData,
            success: function (data){
                $("#rejectCompanyDocumentModal, #acceptCompanyDocumentModal").modal("hide");
                $("#company_document_reject_form #submit_btn").html('Submit');
                $("#company_document_reject_form #submit_btn").attr('disabled', false);
                
                $("#company_document_accept_form #submit_btn").html('Submit');
                $("#company_document_accept_form #submit_btn").attr('disabled', false);
                
                $("#company_document_reject_form textarea").val('');
                $("#company_document_accept_form .modal-body input").val('');
            
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
                        text: 'Company Document Approval Status Updated successfully.',
                        type: 'success',
                    });
                    
                    $("#company_document_update").html(data);
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