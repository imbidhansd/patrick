<?php
    $admin_page_title = 'Find A Pro';
?>
@extends('company.layout-without-sidebar')

@section ('content')
@include('admin.includes.breadcrumb')

<div class="card-box">
    @include('admin.includes.formErrors')
    @include('flash::message')

    {!! Form::open(['url' => url('generate-lead'), 'class' => 'module_form']) !!}
    <h3>What are you looking for?</h3>
    <div class="clearfix">&nbsp;</div>

    @if (isset($trades) && count($trades) > 0)
    <div class="form-group">
        @foreach ($trades AS $key => $trade_item)
        <div class="radio radio-primary">
            <input name="trade_id" value="{{ $key }}" id="trade_{{ $key }}" type="radio" class="trade_id" />
            <label for="trade_{{ $key }}">
                {{ $trade_item }}
            </label>
        </div>
        @endforeach
    </div>
    @endif

    <div class="clearfix">&nbsp;</div>

    <div class="text-left" id="service_category_types">
    </div>

    <div class="text-left" id="top_level_categories">
    </div>

    <div class="text-left" id="category_selection_form">

    </div>
    {!! Form::close() !!}
</div>
@endsection

@section ('page_js')
<!-- Plugins js -->
<script src="{{ asset('/themes/admin/assets/libs/jquery-mask-plugin/jquery.mask.min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/libs/autonumeric/autoNumeric-min.js') }}"></script>
<script src="{{ asset('/themes/admin/assets/js/pages/form-masks.init.js') }}"></script>
<script type="text/javascript">
$(function (){
    $(".trade_id").on("change", function (){
        var trade_id = $(this).val();

        if (trade_id !== 'undefined' && trade_id == '1'){
            $.ajax({
                url: '{{ url("get-service-category-type") }}',
                type: 'POST',
                data: {'_token': '{{ csrf_token() }}'},
                success: function (data){
                        $("#service_category_types").html(data);
                }
            });
            $("#top_level_categories, #category_selection_form").html('');
        } else if (trade_id !== 'undefined' && trade_id == '2'){
            get_top_level_categories(trade_id);
            $("#service_category_types, #category_selection_form").html('');
        }
    });

    $(document).on("change", ".service_category_type_id", function (){
            var trade_id = $(".trade_id:checked").val();
            get_top_level_categories(trade_id);
    });

    $(document).on("change", ".top_level_category_id", function (){
        var trade_id = $(".trade_id:checked").val();
        var top_level_category_id = $(this).val();

        if (trade_id !== 'undefined' && trade_id != '' && top_level_category_id !== 'undefined' && top_level_category_id != ''){
            $.ajax({
                url: '{{ url("get-category-selection") }}',
                type: 'POST',
                data: {
                        'trade_id': trade_id,
                        'top_level_category_id': top_level_category_id,
                        '_token': '{{ csrf_token() }}'
                },
                success: function (data){
                        $("#category_selection_form").html(data);

                        $("#category_selection_form #zipcode").mask('00000');
                        $("#category_selection_form #phone").mask('(000) 000-0000');
                }
            });
        }
    });

    $(document).on("change", "#main_category_id", function (){
        var top_level_category_id = $(".top_level_category_id:checked").val();
        var service_category_type_id = $(".service_category_type_id:checked").val();
        var main_category_id = $(this).val();

        if (top_level_category_id !== 'undefined' && top_level_category_id != '' && service_category_type_id !== 'undefined' && service_category_type_id != '' && main_category_id !== 'undefined' && main_category_id != ''){
            $.ajax({
                url: '{{ url("get-service-categories") }}',
                type: 'POST',
                data: {
                        'top_level_category_id': top_level_category_id,
                        'service_category_type_id': service_category_type_id,
                        'main_category_id': main_category_id,
                        '_token': '{{ csrf_token() }}'
                },
                success: function (data){
                        $("#service_category_id").html(data);
                }
            });
        }
    });
});


function get_top_level_categories (trade_id){
    $.ajax({
        url: '{{ url("get-top-level-categories") }}',
        type: 'POST',
        data: {'trade_id': trade_id, '_token': '{{ csrf_token() }}'},
        success: function (data){
                $("#top_level_categories").html(data);
        }
    });
}
</script>
@endsection
