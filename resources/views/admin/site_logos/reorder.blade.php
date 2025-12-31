@extends('admin.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

@if (isset($founding_item_list) && count($founding_item_list) > 0)
<h4>Founding Member</h4>
<div class="card-box p-0">
    <ul class="list-group list-group-sortable">
        @foreach($founding_item_list as $row)
        <li id='{{ $row->id }}' class="list-group-item"><i class="fa fa-bars reorder-icon"></i>
            {{ $row->title }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (isset($official_item_list) && count($official_item_list) > 0)
<h4>Official Member</h4>
<div class="card-box p-0">

    <ul class="list-group list-group-sortable">
        @foreach($official_item_list as $row)
        <li id='{{ $row->id }}' class="list-group-item"><i class="fa fa-bars reorder-icon"></i>
            {{ $row->title }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (isset($recommended_item_list) && count($recommended_item_list) > 0)
<h4>Recommended Company</h4>
<div class="card-box p-0">

    <ul class="list-group list-group-sortable">
        @foreach($recommended_item_list as $row)
        <li id='{{ $row->id }}' class="list-group-item"><i class="fa fa-bars reorder-icon"></i>
            {{ $row->title }}</li>
        @endforeach
    </ul>
</div>
@endif

@if (isset($certifiedpro_item_list) && count($certifiedpro_item_list) > 0)
<h4>Certified Pro</h4>
<div class="card-box p-0">

    <ul class="list-group list-group-sortable">
        @foreach($certifiedpro_item_list as $row)
        <li id='{{ $row->id }}' class="list-group-item"><i class="fa fa-bars reorder-icon"></i>
            {{ $row->title }}</li>
        @endforeach
    </ul>
</div>
@endif


@stop


@section('page_js')

<script type="text/javascript" src="{{ asset('thirdparty/sortable/jquery.sortable.min.js') }}"></script>

<script type="text/javascript">
    $(function () {
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
                });
            },
        });
    });
});
</script>


@stop
