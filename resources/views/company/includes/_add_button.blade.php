<div class="clearfix"></div>
<div class="row">
    <div class="col-xs-12 col-sm-6">@include('admin.includes._pagination_info')</div>
    <div class="col-xs-12 col-sm-6 text-right">

        @if (!isset($disable_search))
        <a href="javascript:;" class="btn btn-info " onclick="$('.search-box').slideToggle();">Search</a>
        @endif


        @if (!isset($disable_reorder))
        <a href="{{ url($url_key.'/re-order') }}" class="btn btn-orange">Reorder</a>
        @endif

        @if (isset($module_urls['add']) && $module_urls['add'] != '' && !isset($disable_add))
        <a href="{{ $module_urls['add']}}" class="btn btn-dark">Add New</a>
        @endif
    </div>
</div>
<div class="clearfix">&nbsp;</div>
