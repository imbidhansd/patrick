<script type="text/javascript">
    var price_display = "yes";
    var package_membership_type = '{{ $package->membership_level_id }}';
    var package_id = '{{ $package->id }}';

    $(function (){
        /* Get Top Level Categories */
        $('#trade_id').change(function() {
            $('.top_level_categories_container, .main_service_category_container, .secondary_service_category_container, .rest_service_category_container').html('');

            $("#main_category_id, #secondary_main_category_id").val('');

            if ($('#trade_id').val() > 0) {
                $.ajax({
                    type: 'post',
                    url: '{{ url("admin/packages/get-top-level-category-list") }}',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'trade_id': $('#trade_id').val()
                    },
                    success: function(data) {
                        $('.top_level_categories_container').html(data);

                        @if (!is_null($package->top_level_categories))
                            @php $top_level_categories = json_decode($package->top_level_categories); @endphp
                            @foreach ($top_level_categories AS $top_level_category_item)
                                var top_level_category_id = '{{ $top_level_category_item }}';

                                $(".top_level_categories_container #top_level_category_"+top_level_category_id).attr("checked", true);

                                $(".top_level_categories_container #top_level_category_"+top_level_category_id).trigger("change");
                            @endforeach
                        @endif
                    },
                    error: function(data) {
                        alert('error');
                    },
                });
            } else {

            }
        });

        @if (!is_null($package->trade_id))
        $("#trade_id").trigger("change");
        @endif

        /* Get Main categories */
        $(document).on("change", ".chk_top_level_category_id", function (){
        	var data = {
                '_token': '{{ csrf_token() }}',
                'top_level_category_ids[]': []
            };

        	$(".chk_top_level_category_id").each (function (){
        		if ($(this).is(":checked")){
        			data['top_level_category_ids[]'].push($(this).val());
        		}
        	});

        	$('.main_service_category_container, .secondary_service_category_container, .rest_service_category_container').html('');

            $("#secondary_main_category_id").val('');
    		// Ajax for getting Main Categories for Step 2
            $.ajax({
                type: 'post',
                url: '{{ url("admin/packages/get-main-category-list") }}',
                data: data,
                success: function(data) {
                    $('#main_category_id').html(data);

                    @if (!is_null($package->main_category_id))
                        $('#main_category_id').val('{{ $package->main_category_id}}');
                        $('#main_category_id').trigger("change");
                    @endif
                },
                error: function(e) {
                    alert('error');
                },
            });

        });

        /* Get service categorues */
        $(document).on('change', '#main_category_id', function() {
            $(".main_service_category_container").html('');

            $(".secondary_service_category_container").html('');
            $('#secondary_main_category_id').html('');

            $.ajax({
                type: 'post',
                url: '{{ url("admin/packages/get-service-category-list") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#main_category_id').val(),
                    'price_display': price_display,
                    'package_membership_type': package_membership_type,
                    'package_id': package_id,
                },
                success: function(data) {
                    $(".main_service_category_container").html(data);

                    @if (!is_null($package->service_categories))
                        @php $service_categories = json_decode($package->service_categories); @endphp

                        $(".main_service_category_container .chk_service_category_id_step_2").attr("checked", false);
                        @foreach ($service_categories AS $service_category_item)
                            var service_category_id = '{{ $service_category_item }}';

                            $(".main_service_category_container #step_2_service_category_"+service_category_id).attr("checked", true);
                        @endforeach


                        @if(isset($package_service_category_list) && count($package_service_category_list) > 0)
                            @foreach ($package_service_category_list AS $package_service_category_item)
                                var service_category_type_id = '{{ $package_service_category_item['service_category_type_id'] }}';

                                var main_category_id = '{{ $package_service_category_item['main_category_id'] }}';

                                $('.main_category_fee[name="main_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                $('.main_category_fee[name="main_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').attr("disabled", false);
                                
                                
                                
                                $('.ppl_category_fee[name="ppl_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                $('.ppl_category_fee[name="ppl_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').attr("disabled", false);


                                var service_category_id = '{{ $package_service_category_item['service_category_id'] }}';

                                $('.service_category_fee[name="service_category_fee['+service_category_type_id+']['+service_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                $('.service_category_fee[name="service_category_fee['+service_category_type_id+']['+service_category_id+']"]').attr("disabled", false);
                            @endforeach
                        @endif
                    @endif


                    uncheckMainCategory();
                },
                error: function(data) {
                    alert('error');
                },
            });


            // check for checkbox selection
            // Ajax for getting Main Categories for Step 3
            var data = {
                '_token': '{{ csrf_token() }}',
                'main_category_id': $('#main_category_id').val(),
                'top_level_category_ids[]': []
            };
            $(".chk_top_level_category_id:checked").each(function() {
                data['top_level_category_ids[]'].push($(this).val());
            });

            $.ajax({
                type: 'post',
                url: '{{ url("admin/packages/get-main-category-list") }}',
                data: data,
                success: function(data) {
                    if (data == 'false') {
                    } else {
                        $('#secondary_main_category_id').html(data);
                        $('.secondary_service_category_container').html('');

                        @if (!is_null($package->secondary_main_category_id))
                            $('#secondary_main_category_id').val('{{ $package->secondary_main_category_id}}');
                            $('#secondary_main_category_id').trigger("change");
                        @endif
                    }
                },
                error: function(e) {
                    alert('error');
                },
            });
        });


        /* Get secondary service categorues */
        $(document).on('change', '#secondary_main_category_id', function() {
            $(".secondary_service_category_container").html('');
            $.ajax({
                type: 'post',
                url: '{{ url("admin/packages/get-service-category-list") }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#secondary_main_category_id').val(),
                    'price_display': price_display,
                    'package_membership_type': package_membership_type,
                    'package_id': package_id,
                },
                success: function(data) {
                    $(".secondary_service_category_container").html(data);

                    @if (!is_null($package->service_categories))
                        @php $service_categories = json_decode($package->service_categories); @endphp

                        $(".secondary_service_category_container .chk_service_category_id_step_2").attr("checked", false);
                        @foreach ($service_categories AS $service_category_item)
                            var service_category_id = '{{ $service_category_item }}';
                            $(".secondary_service_category_container #step_2_service_category_"+service_category_id).attr("checked", true);
                        @endforeach


                        @if(isset($package_service_category_list) && count($package_service_category_list) > 0)
                            @foreach ($package_service_category_list AS $package_service_category_item)
                                var service_category_type_id = '{{ $package_service_category_item['service_category_type_id'] }}';

                                var main_category_id = '{{ $package_service_category_item['main_category_id'] }}';
                                $('.main_category_fee[name="main_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                $('.main_category_fee[name="main_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').attr("disabled", false);
                                
                                
                                
                                $('.ppl_category_fee[name="ppl_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                $('.ppl_category_fee[name="ppl_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').attr("disabled", false);

                                var service_category_id = '{{ $package_service_category_item['service_category_id'] }}';
                                $('.service_category_fee[name="service_category_fee['+service_category_type_id+']['+service_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                $('.service_category_fee[name="service_category_fee['+service_category_type_id+']['+service_category_id+']"]').attr('disabled', false);
                            @endforeach
                        @endif
                    @endif

                    uncheckMainCategory();
                },
                error: function(data) {
                    alert('error');
                },
            });

            if ($('#secondary_main_category_id option').length >= 3) {
                // Call ajax for Step 3.4
                // Ajax for getting Main Categories for Step 3
                var data = {
                    '_token': '{{ csrf_token() }}',
                    'main_category_id': $('#main_category_id').val(),
                    'secondary_main_category_id': $('#secondary_main_category_id').val(),
                    'top_level_category_ids[]': [],
                    'package_membership_type': package_membership_type,
                    'package_id': package_id,
                };

                $(".chk_top_level_category_id:checked").each(function() {
                    data['top_level_category_ids[]'].push($(this).val());
                });

                $.ajax({
                    type: 'post',
                    url: '{{ url("admin/packages/get-rest-category-list") }}',
                    data: data,
                    success: function(data) {
                        $('.rest_service_category_container').html(data);

                        @if (!is_null($package->service_categories))
                            @php $service_categories = json_decode($package->service_categories); @endphp

                            $(".rest_service_category_container .chk_service_category_id_step_4").attr("checked", false);
                            @foreach ($service_categories AS $service_category_item)
                                var service_category_id = '{{ $service_category_item }}';
                                $(".rest_service_category_container #step_4_service_category_"+service_category_id).attr("checked", true);
                            @endforeach

                            @if(isset($package_service_category_list) && count($package_service_category_list) > 0)
                                @foreach ($package_service_category_list AS $package_service_category_item)
                                    var service_category_type_id = '{{ $package_service_category_item['service_category_type_id'] }}';

                                    var main_category_id = '{{ $package_service_category_item['main_category_id'] }}';
                                    $('.main_category_fee[name="main_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                    $('.main_category_fee[name="main_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').attr("disabled", false);



                                    $('.ppl_category_fee[name="ppl_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                    $('.ppl_category_fee[name="ppl_service_category_fee['+service_category_type_id+']['+main_category_id+']"]').attr("disabled", false);
                                
                                
                                    var service_category_id = '{{ $package_service_category_item['service_category_id'] }}';
                                    $('.service_category_fee[name="service_category_fee['+service_category_type_id+']['+service_category_id+']"]').val('{{ number_format($package_service_category_item["fee"], 2, ".", "") }}');
                                    $('.service_category_fee[name="service_category_fee['+service_category_type_id+']['+service_category_id+']"]').attr('disabled', false);
                                @endforeach
                            @endif
                        
                            $('.include_rest_categories').trigger("change");
                        @endif

                        uncheckRestMainCategory();
                    },
                    error: function(e) {
                        alert('error');
                    },
                });
            }
        });

        /* rest category hide/display selection */
        $('.include_rest_categories').change(function() {
            if ($(this).is(':checked')) {
                $('.rest_service_category_container').slideUp();

                $('.rest_service_category_container .checkbox input').removeAttr("checked");
                uncheckRestMainCategory();
            } else {
                $('.rest_service_category_container').slideDown();
            }
        });



        /* check all service categories */
        $(document).on("change", ".main_category_item", function (){
            if ($(this).is(":checked")){
                $(this).parents(".table").find(".service_category_item_list .last_input").prop("checked", true).prop("required", true);
                
                var main_category_fee = $(this).parents(".table").find(".main_category_fee").data("val");
                $(this).parents(".table").find(".main_category_fee").attr("disabled", false);
                $(this).parents(".table").find(".main_category_fee").val(main_category_fee);
                
                $(this).parents(".table").find(".ppl_category_fee").attr("disabled", false);
                $(this).parents(".table").find(".ppl_category_fee").val(main_category_fee);
                $(this).parents(".table").find(".service_category_item_list .service_category_fee").each(function (){
                    var service_category_fee = $(this).data("val");
                    $(this).val(service_category_fee);
                    $(this).attr("disabled", false);
                });
                
            } else {
                $(this).parents(".table").find(".service_category_item_list .last_input").prop("checked", false).prop("required", false);
                
                $(this).parents(".table").find(".main_category_fee").attr("disabled", true);
                $(this).parents(".table").find(".main_category_fee").val('');
                
                $(this).parents(".table").find(".ppl_category_fee").attr("disabled", true);
                $(this).parents(".table").find(".ppl_category_fee").val('');
                $(this).parents(".table").find(".service_category_item_list .service_category_fee").each(function (){
                    $(this).val('');
                    $(this).attr("disabled", true);
                });
            }
        });
        
        
        $(document).on("change", ".service_category_item_list .last_input", function (){
            if ($(this).is(":checked")){
                $(this).parents(".table").find(".main_category_item").prop("checked", true);
                $(this).parents(".table").find(".chk_all").prop("checked", true);
                
                var service_category_fee = $(this).parents(".service_category_item_list").find(".service_category_fee").data("val");
                $(this).parents(".service_category_item_list").find(".service_category_fee").attr("disabled", false);
                $(this).parents(".service_category_item_list").find(".service_category_fee").val(service_category_fee);
            } else {
                $(this).parents(".service_category_item_list").find(".service_category_fee").attr("disabled", true);
                $(this).parents(".service_category_item_list").find(".service_category_fee").val('');
                
                var counter = 0;
                $(this).parents(".table").find(".service_category_item_list .last_input").each(function (){
                    if ($(this).is(":checked")){
                        counter++;
                    }
                });
                
                if (counter == 0){
                    $(this).parents(".table").find(".main_category_item").prop("checked", false);
                    $(this).parents(".table").find(".chk_all").prop("checked", false);
                }
            }
        });
        

        $(document).on("change", ".chk_all", function (){
            if ($(this).is(":checked")){
                $(this).parents(".table").find(".service_category_item_list .last_input").prop("checked", true).prop("required", true);
                
                var main_category_fee = $(this).parents(".table").find(".main_category_fee").data("val");
                $(this).parents(".table").find(".main_category_fee").attr("disabled", false);
                $(this).parents(".table").find(".main_category_fee").val(main_category_fee);
                
                
                $(this).parents(".table").find(".ppl_category_fee").attr("disabled", false);
                $(this).parents(".table").find(".ppl_category_fee").val(main_category_fee);
                $(this).parents(".table").find(".service_category_item_list .service_category_fee").each(function (){
                    var service_category_fee = $(this).data("val");
                    $(this).val(service_category_fee);
                    $(this).attr("disabled", false);
                });
                
            } else {
                $(this).parents(".table").find(".service_category_item_list .last_input").prop("checked", false).prop("required", false);
                
                $(this).parents(".table").find(".main_category_fee").attr("disabled", true);
                $(this).parents(".table").find(".main_category_fee").val('');
                
                $(this).parents(".table").find(".ppl_category_fee").attr("disabled", true);
                $(this).parents(".table").find(".ppl_category_fee").val('');
                $(this).parents(".table").find(".service_category_item_list .service_category_fee").each(function (){
                    $(this).val('');
                    $(this).attr("disabled", true);
                });
            }
        });


        $(document).on("blur", ".ppl_category_fee", function (){
            var fee = parseFloat($(this).val());
            if (fee > 0){
                fee = fee.toFixed(2);
                $(this).val(fee);
                $(this).parents(".table").find(".service_category_fee").val(fee);
            }
        });


        $(".submit_package_categories").on("click", function (){
            if ($(".include_rest_categories").is(":checked")){
                $(".rest_service_category_container").html('');
            }
            
            
            var counter = 0;
            $(".service_category_item_list .last_input").each(function (){
                if ($(this).is(":checked")){
                    counter++;
                }
            });
            
            if (counter == 0){
                Swal.fire({
                    title: 'Error',
                    type: "error",
                    text: "Select at least 1 service category."
                });
                
                return false;
            }
        });
    });



    function uncheckMainCategory(){
        $('.service_category_item_list').each(function(){
            var checked = false;

            $(this).find('.chk_service_category_id_step_2').each(function(){
                if ($(this).is(':checked')){
                    checked = true;
                }
            });

            $(this).closest('.checkbox').find('.main_category_item').prop('checked', checked);
        });
    }

    function uncheckRestMainCategory(){
        $('.service_category_item_list').each(function(){
            var checked = false;

            $(this).find('.chk_service_category_id_step_4').each(function(){
                if ($(this).is(':checked')){
                    checked = true;
                }
            });

            $(this).closest('.checkbox').find('.chk_all').prop('checked', checked);
        });
    }
</script>