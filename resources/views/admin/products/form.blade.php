<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Product Name') !!}
            {!! Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Product Name', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Service Category Type') !!}
            {!! Form::select('service_category_type_id', $service_category_type, null, ['class' => 'form-control custom-select', 'id' => 'service_category_type', 'placeholder' => 'All', 'required' => false]) !!}
        </div>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        @include ('admin.includes._img_field', ['label' => 'Product Image', 'ref_func' => 'media','formObj' => isset($formObj) ? $formObj : null])
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Video URL') !!}
            {!! Form::text('video_url', null, ['class' => 'form-control', 'placeholder' => 'Enter Video URL', 'required' => false]) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Price') !!}
            {!! Form::text('price', null, ['class' => 'form-control', 'placeholder' => 'Enter Price', 'required' => true]) !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Maximum Quantity') !!}
            {!! Form::text('max_quantity', null, ['class' => 'form-control', 'placeholder' => 'Enter Maximum Quantity', 'required' => true]) !!}
        </div>
    </div>
</div>

@if (isset($top_level_categories) && count($top_level_categories) > 0)
<hr />
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Top Level Categories') !!}
            
            @php
                $top_level_category_arr = array_chunk($top_level_categories->toArray(), ceil(count($top_level_categories) / 3));
            @endphp
            <div id="all_top_level_categories">
                <div class="row">
                    @foreach ($top_level_category_arr as $arr_item)
                    <div class="col-md-4">
                        @foreach ($arr_item as $item)
                        <div class="checkbox checkbox-primary">
                            @php
                                $checked = "";
                            @endphp

                            
                            @if (isset($top_level_category_ids) && count($top_level_category_ids) > 0 && in_array($item['id'], $top_level_category_ids))
                                @php
                                    $checked = "checked";
                                @endphp
                            @endif

                            <input name="top_level_category_ids[]" class="chk_top_level_category_id"
                                data-text="{{ $item['title'] }}" id="top_level_category_{{ $item['id'] }}" value="{{ $item['id'] }}" type="checkbox" {{ $checked }} />
                            <label for="top_level_category_{{ $item['id'] }}">
                                {{ $item['title'] }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix">&nbsp;</div>
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Main Category') !!}

            <div id="main_category_selection"></div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">

            <div class="checkbox checkbox-primary">
                <input type="checkbox" name="chk_all_service_categories" id="chk_all_service_categories" value="chk_all_service_categories" />
                <label for="chk_all_service_categories">
                    Service Category
                </label>
            </div>

            <div id="service_category_selection"></div>
        </div>
    </div>
</div>
<hr />
@endif

<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Email Receipt') !!}
            {!! Form::textarea('email_receipt', null, ['class' => 'form-control']) !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Content') !!}
            {!! Form::textarea('content', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            {!! Form::label('Payment Terms') !!}
            {!! Form::textarea('payment_terms', null, ['class' => 'form-control ckeditor']) !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Status') !!}
            <div class="select">
                {!! Form::select('status', ['active' => 'Active', 'inactive' => 'Inactive'], null, ['class' => 'form-control custom-select', 'required' => 'required']) !!}
                <div class="select__arrow"></div>
            </div>
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>

@push('page_scripts')
<script src="{{ asset('/') }}thirdparty/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });

    CKEDITOR.replace('payment_terms', {
        filebrowserImageUploadUrl: '{{ url("admin/media/editorupload") }}',
        filebrowserUploadMethod: 'form'
    });

    $(function (){
        /*$("#chk_all_top_level_categories").on("change", function (){
            if ($(this).is(":checked")){
                $("#all_top_level_categories .chk_top_level_category_id").attr("checked", true);
            } else {
                $("#all_top_level_categories .chk_top_level_category_id").attr("checked", false);
                $('#main_category_selection, #service_category_selection').html('');
            }
            $("#all_top_level_categories .chk_top_level_category_id:checked").trigger("change");
        });

        $("#chk_all_main_categories").on("change", function (){
            if ($(this).is(":checked")){
                $("#main_category_selection .chk_main_category_id").attr("checked", true);
            } else {
                $("#main_category_selection .chk_main_category_id").attr("checked", false);
                $('#service_category_selection').html('');
            }
            $("#main_category_selection .chk_main_category_id:checked").trigger("change");
        });*/

        $("#chk_all_service_categories").on("change", function (){
            if ($(this).is(":checked")){
                $("#service_category_selection .chk_service_category_id").attr("checked", true);
            } else {
                $("#service_category_selection .chk_service_category_id").attr("checked", false);
            }
        });



        $(".chk_top_level_category_id").on("change", function (){
            var data = {
                '_token': '{{ csrf_token() }}',
                'type': 'main_category',
                'top_level_category_ids[]': []
            };

            $(".chk_top_level_category_id:checked").each(function() {
                data['top_level_category_ids[]'].push($(this).val());
            });
            
            $.ajax({
                type: 'post',
                url: '{{ url("admin/products/get-category-list") }}',
                data: data,
                success: function(data) {
                    $('#main_category_selection').html(data);

                    @if (isset($main_category_ids) && count($main_category_ids) > 0)
                    var main_category_ids = @php echo json_encode($main_category_ids); @endphp;
                    for (var i = 0; i < main_category_ids.length; i++){
                        $("#main_category_selection #main_category_"+main_category_ids[i]).attr("checked", true);
                    }

                    $(".chk_main_category_id:checked").trigger("change");
                    @endif
                },
                error: function(e) {
                    alert('error');
                },
            });
        });

        $(document).on("change", ".chk_main_category_id", function (){
            var data = {
                '_token': '{{ csrf_token() }}',
                'type': 'service_category',
                'top_level_category_ids[]': [],
                'main_category_ids[]': [],
                'service_category_type': ''
            };

            $(".chk_top_level_category_id:checked").each(function() {
                data['top_level_category_ids[]'].push($(this).val());
            });

            $(".chk_main_category_id:checked").each(function() {
                data['main_category_ids[]'].push($(this).val());
            });

            var service_category_type = $("#service_category_type").val();
            if (typeof service_category_type !== 'undefined'){
                data['service_category_type'] = service_category_type;
            }

            
            $.ajax({
                type: 'post',
                url: '{{ url("admin/products/get-category-list") }}',
                data: data,
                success: function(data) {
                    $('#service_category_selection').html(data);

                    @if (isset($service_category_ids) && count($service_category_ids) > 0)
                    var service_category_ids = @php echo json_encode($service_category_ids); @endphp;
                    for (var i = 0; i < service_category_ids.length; i++){
                        $("#service_category_selection #service_category_"+service_category_ids[i]).attr("checked", true);
                    }

                    var total_service_categories = $("#service_category_selection .chk_service_category_id").length;
                    var counter = 0;
                    $("#service_category_selection .chk_service_category_id").each(function (){
                        if ($(this).is(":checked")){
                            counter++;
                        }
                    });


                    if (total_service_categories == counter){
                        $("#chk_all_service_categories").attr("checked", true);
                    }
                    @endif
                },
                error: function(e) {
                    alert('error');
                },
            });
        });


        $("#service_category_type").on("change", function (){
            if($('#service_category_selection').is(":empty") == ''){
                $(".chk_main_category_id:checked").trigger("change");
            }
        });

        $(".chk_top_level_category_id:checked").trigger("change");
    });
</script>
@endpush