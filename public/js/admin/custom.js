


$(function () {

    $("input.max").maxlength({
        alwaysShow: !0,
        warningClass: "badge badge-success",
        limitReachedClass: "badge badge-danger"
    });

    //$('.select_field').select2();

    if ($('.module_form').length > 0) {
        $('.module_form').parsley();
    }

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
            format: 'mm/dd/yyyy'
        });
    }

    $('.pagination').find('span, a').addClass('page-link');
    $('.pagination').find('li').addClass('page-item');
    $('.delete_btn').click(function () {

        $url = $(this).attr('href');
        $('#global_delete_form').attr('action', $url);
        $('#global_delete_form #row_id').val($(this).data('id'));

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#ff0000",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!"
        }).then(function (t) {
            if (typeof t.value != 'undefined') {
                $('#global_delete_form').submit();
            }
        })
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

    // Parsley Click Event
    $(document).on('click', '.parsley-errors-list', function () {
        $(this).hide();
    })
});


$(function () {
    //has uppercase
    window.Parsley.addValidator('uppercase', {
        requirementType: 'number',
        validateString: function (value, requirement) {
            var uppercases = value.match(/[A-Z]/g) || [];
            return uppercases.length >= requirement;
        },
        messages: {
            en: 'Your password must contain at least (%s) uppercase letter.'
        }
    });

    //has lowercase
    window.Parsley.addValidator('lowercase', {
        requirementType: 'number',
        validateString: function (value, requirement) {
            var lowecases = value.match(/[a-z]/g) || [];
            return lowecases.length >= requirement;
        },
        messages: {
            en: 'Your password must contain at least (%s) lowercase letter.'
        }
    });

    //has number
    window.Parsley.addValidator('number', {
        requirementType: 'number',
        validateString: function (value, requirement) {
            var numbers = value.match(/[0-9]/g) || [];
            return numbers.length >= requirement;
        },
        messages: {
            en: 'Your password must contain at least (%s) number.'
        }
    });

    //has special char
    window.Parsley.addValidator('special', {
        requirementType: 'number',
        validateString: function (value, requirement) {
            var specials = value.match(/[^a-zA-Z0-9]/g) || [];
            return specials.length >= requirement;
        },
        messages: {
            en: 'Your password must contain at least (%s) special characters.'
        }
    });

});
