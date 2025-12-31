@extends('company.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => ['Photo Gallery' => $module_urls['list'],
$admin_page_title => '']])
@include('flash::message')

<div class="card-box">
    <div class="banners">
        <div class="table-responsive">
            <div id="sortable">
                @forelse ($galleries as $item)
                <div class="col-md-2 float-left" id="image_item_{{ $item->id }}" data-id="{{ $item->id }}">
                    <div class="item">
                        @if (!is_null($item->media))
                        <img src="{{ asset('/') }}/uploads/media/fit_thumbs/100x100/{{ $item->media->file_name }}" class="img-responsive" />
                        <a data-toggle="tooltip" data-placement="bottom" title="View Photo" href="{{ asset('/') }}/uploads/media/{{ $item->media->file_name }}" data-fancybox="gallery" class="btn btn-info btn-xs gallery-photo-expand"><i class="fas fa-expand"></i></a>
                        @endif
                    </div>
                </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
@stop


@section('page_js')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">
$(function () {
    $("#sortable").sortable({
        update: function (event, ui) {
            var listValues = [];
            $("#sortable").children('.col-md-2').each(function (index, k) {
                listValues.push($(this).data('id'));
            });

            $.ajax({
                type: 'post',
                url: '{{ url($url_key."/re-order") }}',
                data: {"_token": "{{ csrf_token() }}", image_order: listValues},
                success: function (data) {
                    $.toast({
                        text: 'Reorder completed successfully!',
                        icon: 'info',
                    });
                },
                error: function (data) {
                    alert(data);
                },
            });
        }
    });
});
</script>
@stop
