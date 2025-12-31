@extends('admin.layout')
@section('title', $admin_page_title)



@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
<?php /* @include('admin.includes._add_button', ['disable_reorder' => true]) */ ?>

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
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Company Name', $url_key.
                                '.company_name',
                                $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                                $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>Company Telephone</th>
                    <th>Email</th>
                    <th>Internal Contact Name</th>
                    <th>Internal Contact Phone</th>
                    <th>Internal contact Email</th>
                    <th>Category Listings</th>
                    <th>Level</th>
                    <th>Member Status</th>
                    <th>Zipcodes</th>
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
                                {{ $row->company_name }}
                            </label>
                        </div>
                    </td>
                    <td>{{ $row->company_telephone }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->internal_contact_name }}</td>
                    <td>{{ $row->internal_contact_phone }}</td>
                    <td>{{ $row->internal_contact_email }}</td>
                    <td>
                        @php $main_category_id = ""; @endphp
                        @forelse($row->service_category AS $service_category_item)
                        <div>
                            @if ($main_category_id != $service_category_item->main_category_id)
                            @php $main_category_id = $service_category_item->main_category_id; @endphp
                            <p><b>{{ $service_category_item->main_category->title }}</b></p>
                            @endif
                        </div>
                        @empty
                        @endforelse
                    </td>
                    <td>Company Level</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $row->main_zipcode }}</td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            @can ($module_urls['url_key'] . '.' . 'view')
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal"
                                data-target="#{{ $module_urls['url_key'] }}_{{ $row->id }}"
                                class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
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
                    <td colspan="11">No Records Found.</td>
                </tr>
                @endif

            </tbody>

        </table>

    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @isset($action_arr)
            @include ('admin.includes._tfoot', ['options' => $action_arr])
            @endisset
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
