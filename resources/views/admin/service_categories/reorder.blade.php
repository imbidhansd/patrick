@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Top Level Category') !!}
                {!! Form::select('top_level_category_id', $top_level_categories , $top_level_category_id, ['class' => 'form-control custom-select', 'placeholder' => 'Select Top Level Category', 'id' => 'category_id']) !!}
            </div>
        </div>

        @if (isset($main_categories) && count($main_categories))
        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Main Category') !!}
                {!! Form::select('main_category_id', $main_categories , $main_category_id, ['class' => 'form-control custom-select', 'placeholder' => 'Select Main Category', 'id' => 'main_category_id']) !!}
            </div>
        </div>
        @endif


        <div class="col-md-4">
            <div class="form-group">
                {!! Form::label('Service Category Type') !!}
                {!! Form::select('service_category_type_id', $service_category_types , $service_category_type_id, ['class' => 'form-control custom-select', 'placeholder' => 'Select Service Category Type', 'id' => 'service_category_type_id']) !!}
            </div>
        </div>


        @if (isset($item_list) && count($item_list) > 0)
        <div class="col-sm-12">
            <section>
                <br />
                <ul class="list-group list-group-sortable">
                    @foreach($item_list as $row)
                    <li id='{{ $row->id }}' class="list-group-item"><i class="fa fa-bars reorder-icon"></i>
                        {{ $row->title }}</li>
                    @endforeach
                </ul>
            </section>


        </div>
        @endif
    </div>
</div>


@stop


@section('page_js')

<script type="text/javascript" src="{{ asset('thirdparty/sortable/jquery.sortable.min.js') }}"></script>

<script type="text/javascript">
$(function () {

    $('#category_id').change(function () {
        if ($(this).val() != '') {
            window.location.href = '{{ url("admin/" . $url_key . "/re-order") }}/' + $(this).val();
        }
    });

    $('#main_category_id').change(function () {
        if ($(this).val() != '' && typeof $('#category_id').val() !== 'undefined' && $('#category_id').val() !== '' && typeof $('#service_category_type_id').val() !== 'undefined' && $('#service_category_type_id').val() !== ''){
            $("#service_category_type_id").trigger("change");
        }
        /*if ($(this).val() != '') {
            window.location.href = '{{ url("admin/" . $url_key . "/re-order") }}/' + $('#category_id').val() + '/' + $(this).val();
        }*/
    });

    $("#service_category_type_id").change(function () {
        if ($(this).val() != '' && typeof $('#category_id').val() !== 'undefined' && $('#category_id').val() !== '' && typeof $('#main_category_id').val() !== 'undefined' && $('#main_category_id').val() !== '') {
            window.location.href = '{{ url("admin/" . $url_key . "/re-order") }}/' + $('#category_id').val() + '/' + $('#main_category_id').val() + '/' + $(this).val();
        }
    });

    $('.list-group-sortable').sortable({
        placeholderClass: 'list-group-item'
    }).bind('sortupdate', function (e, ui) {
        //ui.item contains the current dragged element.
        //Triggered when the user stopped sorting and the DOM position has changed.
        //console.log(e)

        $items = [];
        $.each(e.target.children, function (index, val) {
            $items.push(val.id);
        })

        $.ajax({
            type: 'POST',
            url: '{{ url("admin/". $url_key ."/re-order" ) }}',
            data: {'items': $items, '_token': '{{ csrf_token() }}', },
            success: function (e) {

                $.toast({
                    heading: 'Success',
                    text: '{{ $module_plural_name }} are sorted successfully!',
                    icon: 'info',
                    loader: true, // Change it to false to disable loader
                    showHideTransition: 'slide',
                    position: 'bottom-right',
                    loaderBg: '#9EC600'  // To change the background
                })

            },
        });

    });



});
</script>

@stop
