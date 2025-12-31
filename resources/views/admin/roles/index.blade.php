@extends('admin.layout')
@section('title', $admin_page_title)


@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')


@include('admin.includes._add_button', ['disable_reorder' => true, 'disable_add' => true, 'disable_search' => true])

<div class="card-box">
    {!! Form::open(['route' => 'update-status', 'class' => 'module_form list-form']) !!}
    {!! Form::hidden ('cur_url', url()->full()) !!}
    {!! Form::hidden ('url_key', $url_key) !!}

    <div class="table-responsive list-page">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Title', $url_key. '.title',
                        $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                        $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th class="col-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>

                    <td>{{ $row->name }}</td>
                    <td>
                        @if ($row->name != 'Super Admin')
                        <div class="btn-group btn-group-solid">
                            <a title="{{ $module_singular_name}} Role Permissions"
                                href="{{ route('role-permissions', [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-dark btn-xs"><i class="fas fa-lock"></i></a>

                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>

                            <?php /* <a title="Delete {{ $module_singular_name}}"
                                href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i class="fas fa-trash-alt"></i></a> */ ?>
                        </div>
                        @endif
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
        {!! Form::close() !!}
    </div>


    <div class="row">
        <div class="col-sm-12 col-md-6">
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
