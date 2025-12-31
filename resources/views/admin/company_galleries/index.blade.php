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
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Level', 'companies.membership_level_id', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th class="col-md-2">Action</th>
                </tr>
            </thead>

            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>
                        <a href="javascript:;" class="expand_link" data-id="#detail_tr_{{ $row->id }}">
                            {{ $row->company_name }}
                        </a><br />
                        @if($row->company_telephone != '')
                        <i class="fas fas fa-mobile-alt"></i> {{ $row->company_telephone }}<br />
                        @endif
                        <i class="fas fa-at"></i> {{ $row->email }}    
                    </td>
                    <td>{{ $row->membership_level->title }}</td>
                    <td class="col-md-2">
                        <div class="btn-group btn-group-solid">
                            <a href="{{ url('admin/companies/manage-gallery-requests', ['company_id' => $row->id]) }}" title="{{ $module_singular_name }}" class="btn btn-orange btn-xs"><i class="fas fa-exclamation-circle"></i></a>
                        </div>
                    </td>
                </tr>

                <tr id="detail_tr_{{ $row->id }}" style="display: none">
                    <td colspan="4" class="company_detail">
                        <table class="table table-striped">
                            <tr class="no-top-border">
                                <td class="col-3">Company Name</td>
                                <td><strong>{{ $row->company_name }}</strong></td>
                            </tr>

                            <tr>
                                <td class="col-3">Company Telephone</th>
                                <td>{{ $row->company_telephone }}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Email</th>
                                <td>{{ $row->email }}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Internal Contact Name</th>
                                <td>{{ $row->internal_contact_name }}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Internal Contact Phone</th>
                                <td>{{ $row->internal_contact_phone }}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Internal contact Email</th>
                                <td>{{ $row->internal_contact_email }}</td>
                            </tr>
                        </table>
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
@stop


@section ('page_js')
<script type="text/javascript">
    $(function(){
        $('.expand_link').click(function(){
            $($(this).data('id')).fadeToggle('slow');
        });
    });
</script>
@endsection
