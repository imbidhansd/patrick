@if(isset($rows) && count($rows) > 0)
<span class="pull-left pagination_info">Showing <b class="text-info">{{ $rows->firstItem() }} to
        {{ $rows->lastItem() }}</b> From <b class="text-danger">{{ $rows->total() }}</b> {{ $module_plural_name }}
</span>
@endif
