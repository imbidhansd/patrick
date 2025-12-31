@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_reorder' => true])

<div class="card-box">
    {!! Form::open(['route' => 'update-status', 'class' => 'module_form list-form']) !!}
    {!! Form::hidden ('cur_url', url()->full()) !!}
    {!! Form::hidden ('url_key', $url_key) !!}

    <div class="table-responsive list-page">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="col-md-2 col-lg-2 col-sm-2">
                        <div class="checkbox checkbox-primary">
                            <input id="chk_all" ng-model="all" type="checkbox">
                            <label for="chk_all">File</label>
                        </div>
                    </th>
                    <th>URL</th>
                    <th class="col-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>

                    <td>
                        <div class="checkbox checkbox-primary">
                            <input name="ids[]" class="ids" value="{{ $row->id}}" ng-checked="all"
                                id="chk_{{ $row->id}}" type="checkbox">
                            <label for="chk_{{ $row->id}}">
                                <img src="{{ asset('uploads/media/fit_thumbs/50x50/' . $row->file_name) }}"
                                    class="img-thumbnail" />
                            </label>
                        </div>

                    </td>
                    <td><a data-toggle="tooltip" data-placement="top" data-original-title="Click to Copy"
                            data-clipboard-action="copy" data-clipboard-target="#link_{{ $row->id }}"
                            id="link_{{ $row->id }}" href="javascript:;"
                            class="copy-link">{{ asset('uploads/media/fit_thumbs/50x50/' . $row->file_name) }}</a></td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="Delete {{ $module_singular_name}}"
                                href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i
                                    class="fas fa-trash-alt"></i></a>
                        </div>
                    </td>

                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="5">No Records Found.</td>
                </tr>
                @endif

            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @include ('admin.includes._tfoot', ['options' => ['active' => 'Active', 'inactive' => 'Inactive',
            'delete'
            =>
            'Delete']])
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="pagination-area text-center">
                {!! $rows->appends($list_params)->render() !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@include('admin.includes._global_delete_form')
@stop

@section ('page_js')
<script src="{{ asset('thirdparty/clipboard/clipboard.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    new ClipboardJS('.copy-link');
</script>
@endsection
