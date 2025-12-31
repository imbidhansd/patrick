@extends('admin.layout')
@section('title', $admin_page_title)



@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button')

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
                    <th>Top Level Category</th>
                    <th>Main Categories</th>
                    <th>Type</th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Abbreviation', $url_key. '.abbr',
                        $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                        $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <?php /* <th class="col-md-1 col-lg-1 col-sm-1">Status</th>
                    <th class="col-2">Action</th> */ ?>
                    <th>Status</th>
                    <th>Action</th>
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
                                <span class="service_category_name">{{ $row->title }}</span> <br />
                                <span><b>SC ID:</b> <span class="service_category_id_text">{{ $row->sc_code }}</span></span> <br />
                                
                                @if (!is_null($row->networx_task))
                                <span><b>Networx Task ID: </b><span class="networx_task_id_text">{{ $row->networx_task_id }}</span></span> <br />
                                <span><b>Networx Task Name: </b><span class="networx_task_name_text">{{ $row->networx_task->task_name }}</span></span>
                                @endif
                            </label>
                        </div>
                    </td>
                    <td>{{ $row->top_level_category->title }}</td>
                    <td>{{ $row->main_category->title }}</td>
                    <td>{{ $row->service_category_type->title }}</td>
                    <td>{{ $row->abbr }}</td>
                    <td>
                        @if ($row->status == 'active')
                        <span class="label label-info">{{ ucfirst($row->status)}}</span>
                        @else
                        <span class="label label-danger">{{ ucfirst($row->status)}}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="Update {{ $module_singular_name }} Networx Task ID" href="javascript:;" data-toggle="modal" data-target="#networxModal" class="btn btn-orange btn-xs change_networx" data-id="{{ $row->id }}"><i class="fas fa-cogs"></i></a>
                            
                            <a title="Update {{ $module_singular_name }} ID" href="javascript:;" data-toggle="modal" data-target="#updateSCIDModal" class="btn btn-secondary btn-xs change_scid" data-id="{{ $row->id }}"><i class="fas fa-wrench"></i></a>
                            
                            <a title="Copy {{ $module_singular_name}}"
                                href="{{ route('copy-item', ['url_key' => $module_urls['url_key'] ,'id' => $row->id])}}"
                                class="btn btn-success copy_btn btn-xs"><i class="far fa-copy"></i></a>
                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
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
            @include ('admin.includes._tfoot', ['options' => ['active' => 'Active', 'inactive' => 'Inactive', 'delete'
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


<div class="modal fade" id="updateSCIDModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Update SC ID of <span id="service_category_name_text"></span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/service_categories/update-service-category-id'), 'id' => 'update_service_category_id']) !!}
            {!! Form::hidden('category_id', null, ['id' => 'category_id']) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('SC ID') !!}
                    {!! Form::text('service_category_id', null, ['class' => 'form-control', 'id' => 'service_category_id', 'placeholder' => 'Service Category ID', 'required' => true]) !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>


<div class="modal fade" id="networxModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update Networx Task ID</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/service_categories/update-networx-details'), 'id' => 'update_networx_details']) !!}
            {!! Form::hidden('category_id', null, ['id' => 'category_id']) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Networx task ID') !!}
                    {!! Form::text('networx_task_id', null, ['class' => 'form-control', 'id' => 'networx_task_id', 'placeholder' => 'Networx task ID', 'required' => true]) !!}
                </div>
                
                <div class="form-group">
                    {!! Form::label('Networx task Name') !!}
                    {!! Form::text('networx_task_name', null, ['class' => 'form-control', 'id' => 'networx_task_name', 'placeholder' => 'Networx task Name', 'readonly' => true]) !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect waves-light" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@stop

@section('page_js')
@include ('admin.service_categories._js')
@stop
