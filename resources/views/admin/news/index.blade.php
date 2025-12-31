@extends('admin.layout')
@section('title', $admin_page_title)


@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_reorder' => false])

<div class="card-box">
    {!! Form::open(['route' => 'update-status', 'class' => 'module_form list-form']) !!}
    {!! Form::hidden ('cur_url', url()->full()) !!}
    {!! Form::hidden ('url_key', $url_key) !!}

    <div class="table-responsive list-page">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        <div class="checkbox checkbox-primary">
                            <input id="chk_all" ng-model="all" type="checkbox">
                            <label for="chk_all">
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Title', $url_key. '.title',
                                $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                                $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>{!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Date', $url_key. '.date', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}</th>
                    <th>Status</th>
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
                                {{ $row->title}}
                            </label>
                        </div>
                    </td>
                    <td>{{ $row->date }}</td>
                    <td>
                        @if ($row->status == 'active')
                        <span class="label label-info">{{ ucfirst($row->status)}}</span>
                        @else
                        <span class="label label-danger">{{ ucfirst($row->status)}}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            @can ($module_urls['url_key'] . '.' . 'create')
                            <a title="Copy {{ $module_singular_name}}"
                                href="{{ route('copy-item', ['url_key' => $module_urls['url_key'] ,'id' => $row->id])}}"
                                class="btn btn-success copy_btn btn-xs"><i class="far fa-copy"></i></a>
                            @endcan
                            @can ($module_urls['url_key'] . '.' . 'edit')
                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            @endcan
                            @can ($module_urls['url_key'] . '.' . 'delete')
                            <a title="Delete {{ $module_singular_name}}"
                                href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i
                                    class="fas fa-trash-alt"></i></a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="3">No Records Found.</td>
                </tr>
                @endif

            </tbody>

        </table>

    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @include ('admin.includes._tfoot', ['options' => ['active' => 'Active', 'inactive' => 'Inactive', 'delete' => 'Delete']])
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
