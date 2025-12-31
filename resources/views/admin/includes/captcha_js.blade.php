<script src="https://www.google.com/recaptcha/api.js?render={{ env("RECAPTCHA_V3_SITE_KEY") }}"></script>
<script type="text/javascript">
    grecaptcha.ready(function () {
        grecaptcha.execute('{{ env("RECAPTCHA_V3_SITE_KEY") }}', {action: '<?php echo $action_field; ?>'}).then(function (token) {
            if (token) {
                document.getElementById('recaptcha').value = token;
            }
        })
    });
</script>
