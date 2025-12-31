<link href="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('themes/admin/assets/libs/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
$(function () {
    $('.current_field').datepicker({
        autoclose: true,
        format: 'mm/dd/yyyy',
        startDate: '+1d',
        endDate: '+1M'
    });

    
    $('#lead_pause_date').datepicker({  
        autoclose: true,
        format: 'mm/dd/yyyy',
        startDate: '+1d',
        endDate: '+1M'
    }).on('changeDate', function (selected) {
        var minDate = new Date(selected.date.valueOf());
        minDate.setDate(minDate.getDate() + 1);
        $('#lead_resume_date').datepicker('setStartDate', minDate);
    });

    $('#lead_resume_date').datepicker({
        autoclose: true,
        format: 'mm/dd/yyyy',
        startDate: '+1d',
        endDate: '+1M'
    }).on('changeDate', function (selected) {
        if (selected.date.valueOf() != ''){
            var maxDate = new Date(selected.date.valueOf());
            maxDate.setDate(maxDate.getDate() - 1);
            $('#lead_pause_date').datepicker('setEndDate', maxDate);    
        }else{
            $('#lead_pause_date').datepicker('setEndDate', '+1M');    
        }
    });
    
    $("#lead_status_update_form").on("submit", function (){
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(".lead_status_update_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".lead_status_update_btn").attr('disabled', true);
        } else {
            $(".lead_status_update_btn").html('Submit');
            $(".lead_status_update_btn").attr('disabled', false);
        }
    });
   

    /* Change company subscription start */
    $(".change_subscription").on("click", function () {
        var sub_type = $(this).data("type");
        $("#udpate_company_subscription_form #sub_type").val(sub_type);
        
        if (sub_type == 'unsubscribe'){
            var title = "Are you sure you'd like to unsubscribe from the {{ $company_item->membership_level->title }}?";
            var html = "You can resubscribe at any time.";
        } else if (sub_type == 'subscribe'){
            var title = "Resubscribe {{ $company_item->membership_level->title }}?";
            var html = "This will take effect immediately.<br/> You can unsubscribe again at any time.";
        }

        Swal.fire({
            title: title,
            html: html,
            type: "question",
            showCancelButton: !0,
            confirmButtonColor: "#188ae2",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes"
        }).then(function (t) {
            if (typeof t.value != 'undefined') {
                $('#udpate_company_subscription_form').submit();
            }
        });
    });
    
    $("#unsubscribe_all").on("change", function () {
        if ($(this).is(":checked")) {
            $("#regarding_your_request_unsubscribe, #special_offers_unsubscribe, #scams_updates_unsubscribe, #general_updates_unsubscribe").attr("checked", true);
        } else {
            $("#regarding_your_request_subscribe, #special_offers_subscribe, #scams_updates_subscribe, #general_updates_subscribe").attr("checked", true);
        }
    });

    $("#next_btn").on("click", function (){
        $("#unsubscribe_reason").show();
        $("#unsubscribe_options").hide();
    });


    $(".why_unsubscribe").on("change", function () {
        if ($(".why_unsubscribe:checked").val() == 'other') {
            $("#other_reason").show();
            $("#other_reason textarea").attr("required", true);
        } else {
            $("#other_reason").hide();
            $("#other_reason textarea").attr("required", false);
        }
    });

    $("#unsubscribe_company_btn").on("click", function (){
        var instance = $("#unsubscribe_lead_form").parsley();
        if (!instance.isValid()){
            $("#unsubscribe_options").show();
        }
    });

    $("#unsubscribe_company_form").on("submit", function (){
        var instance = $(this).parsley();
        if (instance.isValid()){
            $("#unsubscribe_company_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $("#unsubscribe_company_btn").attr("disabled", true);
        } else {
            $("#unsubscribe_company_btn").html('Submit');
            $("#unsubscribe_company_btn").attr("disabled", false);
        }
    });
    /* Change company subscription end */
        

    $(".change_lead_status").on("click", function () {
        var lead_status = $(this).data("type");
        $("#udpate_company_lead_status_form #lead_status").val(lead_status);
        
        var title = "<strong>You'd like to Unpause Leads?</strong><br/>This will take effect immediately!";
        @if ($company_item->leads_status == 'inactive' && !is_null($company_item->lead_resume_date))
            title = "<strong>Your leads scheduled to resume on {{ \App\Models\Custom::date_formats($company_item->lead_resume_date, env('DB_DATE_FORMAT'), env('DATE_FORMAT')) }}</strong> <br /> Would you like to resume immediately.";
        @endif

        Swal.fire({
            title: "Are you sure?",
            html: title,
            type: "question",
            showCancelButton: !0,
            confirmButtonColor: "#188ae2",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes"
        }).then(function (t) {
            if (typeof t.value != 'undefined') {
                $('#udpate_company_lead_status_form').submit();
            }
        });
    });


    $(".lead_status_effect").on("change", function () {
        if ($(this).val() == 'Yes' || $(this).val() == 'No') {
            $("#lead_status_date").hide();
            $("#lead_status_date input").attr("required", false);
        } else if ($(this).val() == 'Custom') {
            $("#lead_status_date").show();
            $("#lead_status_date input").attr("required", true);
        }
    });


    $('.lead_pause_option').on('change', function(){
        if ($('.lead_pause_option:checked').val() == 'custom'){
            $('#lead_pause_date_div').show();
            $('#lead_pause_date').attr('required', true);
        }else{
            $('#lead_pause_date_div').hide();
            $('#lead_pause_date').removeAttr('required');
        }
    });

    $('.lead_resume_option').on('change', function(){
        if ($('.lead_resume_option:checked').val() == 'custom'){
            $('#lead_resume_date_div').show();
            $('#lead_resume_date').attr('required', true);
        }else{
            $('#lead_resume_date_div').hide();
            $('#lead_resume_date').removeAttr('required');
        }
    });

    $(".upload_document_form").on("submit", function (){
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(this).find(".upload_document_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(this).find(".upload_document_btn").attr('disabled', true);
        } else {
            $(this).find(".upload_document_btn").html('Upload File');
            $(this).find(".upload_document_btn").attr('disabled', false);
        }
    });
});
</script>
@stack('company_document_approval_status_js')