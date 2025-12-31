<div class="clearfix"></div>
<div class="row">
    <div class="col-xs-12 col-sm-6">@include('admin.includes._pagination_info')</div>
    <div class="col-xs-12 col-sm-6 text-right">
        @if (isset($enable_import) && !empty($enable_import))
            <a href="javascript:;" class="btn btn-info btn-toggle-section" data-target="import-box">Import</a>
        @endif

        @if (!isset($disable_search))
            <a href="javascript:;" class="btn btn-info btn-toggle-section" data-target="search-box">Search</a>
        @endif

        @if ($module_urls['reorder'] && !isset($disable_reorder))
            <a href="{{ $module_urls['reorder'] }}" class="btn btn-orange">Reorder</a>
        @endif

        @if (isset($module_urls['add']) && $module_urls['add'] != '' && !isset($disable_add))
            @can($module_urls['url_key'] . '.' . 'create')
                <a href="{{ $module_urls['add'] }}" class="btn btn-dark add_button">Add New</a>
            @endcan
        @endif
    </div>
</div>
<div class="clearfix">&nbsp;</div>