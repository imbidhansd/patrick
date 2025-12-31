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
                    <th>
                        <div class="checkbox checkbox-primary">
                            <input id="chk_all" ng-model="all" type="checkbox">
                            <label for="chk_all">
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Title', $url_key.
                                '.title', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Content Type', $url_key.
                                '.content_type', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
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
                                {{ $row->title }}
                            </label>
                        </div>
                    </td>
                    <td>{{ ucfirst($row->content_type) }}</td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal" data-target="#{{ $module_urls['url_key'] }}_{{ $row->id }}" class="btn btn-info btn-xs"><i class="fas fa-list"></i></a>
                            <a title="Edit {{ $module_singular_name}}" href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            <a title="Delete {{ $module_singular_name}}" href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i class="fas fa-trash-alt"></i></a>
                        </div>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="{{ $module_urls['url_key'] }}_{{ $row->id }}" tabindex="-1"
                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-xl" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">View {{ $module_singular_name }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>Title</td>
                                                <td>{{ $row->title }}</td>
                                            </tr>
                                            <tr>
                                                <td>Content</td>
                                                <td>{!! $row->content !!}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
            @include ('admin.includes._tfoot', ['options' => ['delete' => 'Delete']])
        </div>
        <div class="col-sm-12 col-md-12">
            <div class="pagination-area text-center">
                {!! $rows->appends($list_params)->render() !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@include('admin.includes._global_delete_form')

@stop
