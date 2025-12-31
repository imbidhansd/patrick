@extends('admin.layout')
@section('title', $admin_page_title)



@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

<?php /* @include('admin.includes.searchForm') 
@include('admin.includes._add_button', ['disable_reorder' => true])*/ ?>

<div class="card-box">
    <div class="table-responsive list-page">

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th class="col-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>{{ $row->title}}</td>
                    <td>
                        @if ($row->status == 'active')
                        <span class="label label-info">{{ ucfirst($row->status)}}</span>
                        @else
                        <span class="label label-danger">{{ ucfirst($row->status)}}</span>
                        @endif
                    </td>
                    <td>
                        <!-- Modal -->
                        <div class="modal fade" id="{{ $module_urls['url_key'] }}_{{ $row->id }}" tabindex="-1"
                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
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
                                                <td>Header Content</td>
                                                <td>{!! $row->email_header !!}</td>
                                            </tr>
                                            <tr>
                                                <td>Footer Content</td>
                                                <td>{!! $row->email_footer !!}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{{ $row->status }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="btn-group btn-group-solid">
                            @can ($module_urls['url_key'] . '.' . 'view')
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal"
                                data-target="#{{ $module_urls['url_key'] }}_{{ $row->id }}"
                                class="btn btn-info btn-xs"><i class="fas fa-list"></i></a>
                            @endcan

                            @can ($module_urls['url_key'] . '.' . 'edit')
                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}"
                                class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
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
        <div class="col-sm-12 col-md-12">
            <div class="pagination-area text-center">
                {!! $rows->appends($list_params)->render() !!}
            </div>
        </div>
    </div>
</div>
@include('admin.includes._global_delete_form')

@stop
