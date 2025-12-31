


$(function () {

    //$('.select_field').select2();

    if ($('.module_form').length > 0) {
        $('.module_form').parsley();
    }

    $('input.max').maxlength({
        alwaysShow: true
    });

    $('.navbar-toggle').click(function () {
        $(this).toggleClass('open');
        $('.navbar-custom').find('#navigation').toggle();
    });

    $('.has-submenu').click(function () {
        $(this).toggleClass('open');
        $(this).find('.submenu').toggleClass('open');
    });


    //$('.colorbox').colorbox();

    if ($('#start_date').length != 'undefined' && $.fn.datepicker) {
        $('#start_date, #end_date').datepicker({
            autoclose: true,
            todayHighlight: true
        });
    }

    if ($('.date_field').length != 'undefined' && $.fn.datepicker) {
        $('.date_field').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy'
        });
    }


    $('.pagination').find('span, a').addClass('page-link');
    $('.pagination').find('li').addClass('page-item');
    $('.delete_btn').click(function () {

        $url = $(this).attr('href');
        $('#global_delete_form').attr('action', $url);
        $('#global_delete_form #row_id').val($(this).data('id'));

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4fa7f3',
            cancelButtonColor: '#d57171',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $('#global_delete_form').submit();
            }
        });
        return false;
    });

    $('[data-toggle="tooltip"]').tooltip();

    // Index list page, Check All checkbox change even
    $('#chk_all').change(function () {
        $('.ids').prop('checked', $(this).is(':checked'));
        $('#actionSel').prop('disabled', !$('.ids').is(':checked'));
        $('.index-form-btn').prop('disabled', !$('.ids').is(':checked'));
    });

    // Index list page, enable action select box depand on selected ids
    $('#actionSel').prop('disabled', !$('.ids').is(':checked'));
    $('.ids').change(function () {
        $('#actionSel').prop('disabled', !$('.ids').is(':checked'));
        $('.index-form-btn').prop('disabled', !$('.ids').is(':checked'));
    });

});
