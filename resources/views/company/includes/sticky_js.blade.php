<script src="{{ asset('/') }}thirdparty/sticky-header/jquery.sticky.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("header").sticky({topSpacing: -5});

        $('.nav-item.dropdown').hover(function () {
            $('.nav-item.dropdown').removeClass('show');
            $('.nav-item.dropdown .dropdown-menu').removeClass('show');

            $(this).addClass('show');
            $(this).find('.dropdown-menu').addClass('show');
        }, function () {
        });


        $('.nav_sec').hover(function () {

        }, function () {
            $('.nav-item.dropdown').removeClass('show');
            $('.dropdown-menu').removeClass('show');
        });
    });
</script>