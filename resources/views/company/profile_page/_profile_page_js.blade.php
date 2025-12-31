<script src="{{ asset('/themes/admin/assets/libs/ratings/jquery.raty-fa.js') }}"></script>

<!-- Plugins js-->
<link href="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.css') }}" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{{ asset('/themes/admin/assets/libs/nestable2/jquery.nestable.min.js') }}"></script>

<link href="{{ asset('/css/admin/company-profile-page.css') }}" rel="stylesheet" type="text/css" />

<script type="text/javascript">
$(function () {

    $(".starHalf").raty({
        readOnly: !0,
        half: !0,
        starHalf: "fas fa-star-half-alt yellow-star",
        starOff: "far fa-star text-muted",
        starOn: "fas fa-star yellow-star",
        score: "{{ ((!is_null($average_ratings)) ? $average_ratings->average_ratings : 0) }}",
        //score: "4.5",
    });

    @if (isset($latest_reviews) && count($latest_reviews) > 0)
    @foreach($latest_reviews AS $review_item)
    $(".stars{{ $review_item->feedback_id }}").raty({
        readOnly: !0,
        half: !0,
        starHalf: "fas fa-star-half-alt yellow-star",
        starOff: "far fa-star text-muted",
        starOn: "fas fa-star yellow-star",
        score: "{{ $review_item->ratings }}",
    });
    @endforeach
    @endif

    $("#search_zipcode").on("keyup", function (){
        var value = $(this).val().toLowerCase();
        $(".zipcode_item").filter(function () {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    
    $(".filestyle").on("change", function (){
        var files = $(this)[0].files;
        $(this).parents(".form-group").find(".form-control").val(files.length+' files selected');
    });
});
</script>