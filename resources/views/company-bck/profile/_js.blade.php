<script type="text/javascript">
    $(function (){
        $(".change_subscription").on("click", function (){
        	var sub_type = $(this).data("type");

        	$("#udpate_company_subscription_form #sub_type").val(sub_type);

        	Swal.fire({
                title: "Are you sure?",
                html: "<strong>You'd like to " + sub_type + " your free preview trial?</strong><br/>You can subscribe/unsubscribe again at any time!",
                type: "warning",
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
	});
</script>
