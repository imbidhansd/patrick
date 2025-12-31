<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>

<!-- Init js-->
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>

<script type="text/javascript">
$(function () {
    $(".payment_option").on("change", function () {
        var p_option = $(this).attr("id");

        if (p_option == 'credit_card') {
            $("#credit_card_payment_detail").show();
            $("#credit_card_payment_detail input, #credit_card_payment_detail select").attr("required", true);

            $("#check_payment_detail").hide();
        } else {
            $("#credit_card_payment_detail").hide();
            $("#credit_card_payment_detail input, #credit_card_payment_detail select").attr("required", false);

            $("#check_payment_detail").show();
        }
    });

    $("#same_as").on("change", function () {
        if ($(this).is(":checked")) {
            $("#ship_company_name").val($("#bill_company_name").val());
            $("#ship_first_name").val($("#bill_first_name").val());
            $("#ship_last_name").val($("#bill_last_name").val());
            $("#ship_mailing_address").val($("#bill_mailing_address").val());
            $("#ship_suite").val($("#bill_suite").val());
            $("#ship_city").val($("#bill_city").val());
            $("#ship_state_id").val($("#bill_state_id").val());
            $("#ship_zipcode").val($("#bill_zipcode").val());
            $("#ship_phone").val($("#bill_phone").val());
        } else {
            $("#ship_company_name, #ship_first_name, #ship_last_name, #ship_mailing_address, #ship_suite, #ship_city, #ship_state_id, #ship_zipcode, #ship_phone").val('');
        }
        
        $("#shipping_address").toggle("slide");
    });
    
    
    $(".checkout_btn").on("click", function (){
        var instance = $("#checkout_form").parsley();
        if (!instance.isValid()){
            $("#same_as").prop("checked", false);
            $("#shipping_address").slideDown();
        }
    });

    $("#checkout_form").on("submit", function (){
        var instance = $(this).parsley();
        if (instance.isValid()){
            $(".checkout_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
            $(".checkout_btn").attr("disabled", true);
        } else {
            $(".checkout_btn").html('<i class="fas fa-credit-card"></i>&nbsp; Pay Now');
            $(".checkout_btn").attr("disabled", false);
        }
    });
});
</script>