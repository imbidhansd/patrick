@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button')

<div class="card m-t-15">
    <div class="card-body">

        <div class="table-responsive list-page">
            {!! Form::open(['route' => $module_urls['update_status'], 'class' => 'module_form list-form']) !!}
            {!! Form::hidden ('cur_url', url()->full()) !!}
            {!! Form::hidden ('url_key', $url_key) !!}
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
                        <th>Date</th>
                        <th class="col-md-1 col-lg-1 col-sm-1">Status</th>
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
                        <td>
                            {{ $row->date}}
                        </td>
                        <td>
                            @if ($row->status == 'active')
                            <span class="label label-info">{{ ucfirst($row->status)}}</span>
                            @else
                            <span class="label label-danger">{{ ucfirst($row->status)}}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-solid">
                                <a title="Copy {{ $module_singular_name}}"
                                    href="{{ route('copy-item', ['url_key' => $module_urls['url_key'] ,'id' => $row->id])}}"
                                    class="btn btn-success copy_btn btn-xs"><i class="far fa-copy"></i></a>
                                <a title="{{ $module_singular_name}} Images"
                                    href="{{ route('post-files', ['table' => $url_key, 'id' => $row->id])}}"
                                    class="btn btn-success btn-xs"><i class="fa fa-image"></i></a>
                                <a title="Edit {{ $module_singular_name}}"
                                    href="{{ route($module_urls['edit'], ['id' => $row->id])}}"
                                    class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a>
                                <a title="Delete {{ $module_singular_name}}"
                                    href="{{ route($module_urls['delete'], array('id' => $row->id))}}"
                                    class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i
                                        class="fa fa-trash"></i></a>
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

                <tfoot>
                    <tr>
                        <td colspan="4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="select">
                                        <select name="action" id="actionSel"
                                            data-parsley-required-message="Select any option" required="required"
                                            disabled="" class="form-control select2">
                                            <option value="">Select Option</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="delete">Delete</option>
                                        </select>
                                        <div class="select__arrow"></div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary index-form-btn"
                                        disabled="">Submit</button>
                                </div>
                            </div>

                        </td>
                    </tr>
                </tfoot>

            </table>
            {!! Form::close() !!}

            <div class="pagination-area text-center">
                {!! $rows->setPath(http_build_query($list_params))->appends($list_params)->render() !!}
            </div>
        </div>

    </div>
</div>

@include('admin.includes._global_delete_form')

@stop
