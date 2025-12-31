<?php
    $module_urls['add'] = null;
?>
@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

<div class="card-box">
    <div class="table-responsive list-page">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Company Name', 'companies.company_name', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>Category Listings</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th>Zipcode</th>
                    <th>Sales Representative</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>
                        {{ $row->company_name }}
                    </td>
                    <td>
                        @php $main_category_id = ""; @endphp
                        @forelse($row->service_category AS $service_category_item)
                        @if ($main_category_id != $service_category_item->main_category_id)
                        @php $main_category_id = $service_category_item->main_category_id; @endphp
                        {{ $service_category_item->main_category->title }}<br />
                        @endif
                        @empty
                        @endforelse
                    </td>
                    <td>{{ $row->membership_level->title }}</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $row->main_zipcode }}</td>
                    <td>{{ $row->sales_representative->first_name.' '.$row->sales_representative->last_name }}</td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a href="{{ route('sign-in-company', ['company' => $row->id]) }}" title="Masquerade Mode" class="btn btn-warning btn-xs" target="_blank"><i class="fas fa-mask"></i></a>
                            
                            <a href="{{ url('/', ['company_slug' => $row->slug]) }}" title="View {{ $module_singular_name }} Profile Page" class="btn btn-teal btn-xs" target="_blank"><i class="far fa-address-card"></i></a>
                            
                            <a href="javascript:;" title="{{ $module_singular_name }} Sales Representative" data-toggle="modal" data-target="#salesRepresentativeModel" data-company_id="{{ $row->id }}" class="btn btn-dark btn-xs assign_sales_representative"><i class="fas fa-user"></i></a>
                            
                            @can ($module_urls['url_key'] . '.' . 'view')
                            <a title="View {{ $module_singular_name }}" href="javascript:;" data-toggle="modal" data-target="#detailModal_{{ $row->id }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                            @endcan

                            @can ($module_urls['url_key'] . '.' . 'edit')
                            <a title="Edit {{ $module_singular_name}}" href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            @endcan
                        </div>

                        <div class="modal fade" id="detailModal_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">Company Details</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <th>Company Name</th>
                                                    <td>{{ $row->company_name}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Company Telephone</th>
                                                    <td>{{ $row->main_company_telephone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Internal Contact Name</th>
                                                    <td>{{ $row->internal_contact_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Internal Contact Phone</th>
                                                    <td>{{ $row->internal_contact_phone }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Category Listing</th>
                                                    <td>
                                                        @php $main_category_id = ""; @endphp

                                                        @forelse($row->service_category AS $service_category_item)
                                                        @if ($main_category_id !=
                                                        $service_category_item->main_category_id)
                                                        @php $main_category_id =
                                                        $service_category_item->main_category_id; @endphp
                                                        {{ $service_category_item->main_category->title }}<br />
                                                        @endif
                                                        @empty
                                                        @endforelse
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Level</th>
                                                    <td>Company Level</td>
                                                </tr>
                                                <tr>
                                                    <th>Company Status</th>
                                                    <td>{{ $row->status }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect"
                                            data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                @endforeach
                @else
                <tr>
                    <td colspan="4">No Records Found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="pagination-area text-center">
                {!! $rows->appends($list_params)->render() !!}
            </div>
        </div>
    </div>
</div>

@include('admin.companies.company_list_common')
@stop


@section ('page_js')
@stack('company_list_common_js')
@endsection