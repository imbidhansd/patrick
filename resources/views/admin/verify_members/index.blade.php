@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_reorder' => true, 'disable_add' => true])
<div class="card-box">
    <div class="table-responsive list-page">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>{!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Phone Number', $url_key. '.phone_number', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}</th>
                    <th>Search Date</th>
                    <?php /* <th>Search Count</th> */ ?>
                    <th>Status</th>
                </tr>
            </thead>
            
            <tbody>
                @if(isset($rows) && count($rows) > 0)
                @foreach($rows as $row)
                <tr>
                    <td>{{ $row->phone_number }}</td>
                    <td>{{ $row->created_at->format(env('DATE_FORMAT')) }}</td>
                    <?php /* <td>{{ $row->search_count }}</td> */ ?>
                    <td>
                        @if(!is_null($row->company_id))
                        <label class="badge badge-success">{{ $row->company->company_name }}</label>
                        @else
                        <label class="badge badge-danger">Nothing Found</label>
                        @endif
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
            <div class="pagination-area text-center">
                {!! $rows->appends($list_params)->render() !!}
            </div>
        </div>
    </div>
</div>
@stop
