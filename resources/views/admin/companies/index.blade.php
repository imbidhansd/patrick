<?php
$module_urls['add'] = null;
if (!is_null($membership_level_obj)) {
    $module_urls['list'] .= '/' . $membership_level_obj->slug;
}
?>
@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')


@if (isset($top_menu_membership_levels) && count($top_menu_membership_levels) > 0 && ($list_params['membership_level_id'] == 'paid_members' || $list_params['membership_level_id'] == 'unpaid_members'))
<div class="row text-center">
    @foreach ($top_menu_membership_levels as $membership_level_item)
    @if ($list_params['membership_level_id'] == 'paid_members' && $membership_level_item->paid_members == 'yes')
    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
        <a href="{{ route('company_by_membership_level', ['membership_level' => $membership_level_item->slug])}}">
            <div class="card-box widget-box-one">
                <div class="wigdet-one-content">
                    <p class="m-0 text-uppercase text-overflow text-{{ $membership_level_item->color}}">{{ $membership_level_item->title}}</p>
                    <h2 class="text-{{ $membership_level_item->color}}"><span data-plugin="counterup">{{  $membership_level_item->companies_count}}</span></h2>
                </div>
            </div>
        </a>
    </div>
    @elseif ($list_params['membership_level_id'] == 'unpaid_members' && $membership_level_item->paid_members == 'no')
    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
        <a href="{{ route('company_by_membership_level', ['membership_level' => $membership_level_item->slug])}}">
            <div class="card-box widget-box-one">
                <div class="wigdet-one-content">
                    <p class="m-0 text-uppercase text-overflow text-{{ $membership_level_item->color}}">{{ $membership_level_item->title}}</p>
                    <h2 class="text-{{ $membership_level_item->color}}"><span data-plugin="counterup">{{  $membership_level_item->companies_count}}</span></h2>
                </div>
            </div>
        </a>
    </div>
    @endif    
    @endforeach
</div>
@endif
<div id="import-box" class="box-toggle card-box import-box hide">
{!! Form::open(['method' => 'PUT','url'=>$import_url,'files' => true]) !!}
    <div class="row">        
        <div class="col-md-8">
            <div class="form-group">
            {!! Form::label('Select the file') !!}<br />
            {!! Form::file('memberfile', ['class' => 'filestyle']) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="">&nbsp;</label>
                <div class="clearfix"></div>
                <button type="submit" class="btn btn-primary">Process File</button>
                <a href="{{ $module_urls['list'] }}" class="btn btn-dark reset_button">Reset</a>
            </div>
        </div>       
    </div>
{!! Form::close() !!}
</div>
@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_reorder' => true, 'enable_import' =>  true])


<div class="card-box">
    {!! Form::open(['route' => 'update-status', 'class' => 'module_form list-form']) !!}
    {!! Form::hidden ('cur_url', url()->full()) !!}
    {!! Form::hidden ('url_key', $url_key) !!}

    <div class="table-responsive list-page">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Company Name', $url_key.'.company_name', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>Category Listings</th>
                    <th>Level</th>
                    <th>Status</th>
                    @if ($list_params['membership_level_id'] == 'paid_members')
                    <th>Leads Status</th>
                    @endif
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
                        <a href="javascript:;" class="expand_link" data-id="#detail_tr_{{ $row->id}}">
                            {{ $row->company_name}}
                        </a><br />
                        @if($row->company_telephone != '')
                        <i class="fas fas fa-mobile-alt"></i> {{ $row->company_telephone}}<br />
                        @endif
                        @if ($row->email != '')
                        <i class="fas fa-at"></i> {{ $row->email}}
                        @endif
                    </td>
                    <td>
                        @php $main_category_id = ""; @endphp
                        @forelse($row->service_category AS $service_category_item)
                        @if ($main_category_id != $service_category_item->main_category_id)
                        @php $main_category_id = $service_category_item->main_category_id; @endphp
                        {{ $service_category_item->main_category->title}}<br />
                        @endif
                        @empty
                        @endforelse
                    </td>
                    <td>{{ $row->membership_level->title}}</td>
                    <td>{{ $row->status}}</td>
                    @if ($list_params['membership_level_id'] == 'paid_members')
                    <td>
                        @if ($row->leads_status == 'active')
                        <label class="badge badge-success">{{ ucfirst($row->leads_status) }}</label>
                        @else
                        <label class="badge badge-danger">Paused</label>
                        @endif
                    </td>
                    @endif
                    <td>{{ $row->main_zipcode}}</td>
                    <td>{{ $row->sales_representative->first_name.' '.$row->sales_representative->last_name}}</td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a href="{{ route('sign-in-company', ['company' => $row->id])}}" title="Masquerade Mode" class="btn btn-warning btn-xs" target="_blank"><i class="fas fa-mask"></i></a>

                            <a href="{{ url('/', ['company_slug' => $row->slug])}}" title="View {{ $module_singular_name}} Profile Page" class="btn btn-teal btn-xs" target="_blank"><i class="far fa-address-card"></i></a>

                            <a href="javascript:;" title="{{ $module_singular_name}} Sales Representative" data-toggle="modal" data-target="#salesRepresentativeModel" data-company_id="{{ $row->id}}" class="btn btn-dark btn-xs assign_sales_representative"><i class="fas fa-user"></i></a>
                            
                            @can ($module_urls['url_key'] . '.' . 'view')
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal"
                               data-target="#detailModal_{{ $row->id}}" class="btn btn-info btn-xs"><i
                                    class="fas fa-eye"></i></a>
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

                        <div class="modal fade" id="detailModal_{{ $row->id}}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                    <td>{{ $row->main_company_telephone}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Internal Contact Name</th>
                                                    <td>{{ $row->internal_contact_name}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Internal Contact Phone</th>
                                                    <td>{{ $row->internal_contact_phone}}</td>
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
                                                        {{ $service_category_item->main_category->title}}<br />
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
                                                    <td>{{ $row->status}}</td>
                                                </tr>
                                                @if (!is_null($row->company_users))
                                                <tr>
                                                    <th>Company Owners</th>
                                                    <td>
                                                        <ul class="pl-0">
                                                            @foreach ($row->company_users as $company_owner_item)
                                                            <li>{{ $company_owner_item->first_name}} {{ $company_owner_item->last_name}} - {{ $company_owner_item->email}}</li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                </tr>
                                                @endif
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

                <tr id="detail_tr_{{ $row->id}}" style="display: none">
                    <td colspan="8" class="company_detail">
                        <table class="table table-striped">
                            <tr class="no-top-border">
                                <td class="col-3">Company Name</td>
                                <td><strong>{{ $row->company_name}}</strong></td>
                            </tr>

                            <tr>
                                <td class="col-3">Company Telephone</th>
                                <td>{{ $row->company_telephone}}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Email</th>
                                <td>{{ $row->email}}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Internal Contact Name</th>
                                <td>{{ $row->internal_contact_name}}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Internal Contact Phone</th>
                                <td>{{ $row->internal_contact_phone}}</td>
                            </tr>

                            <tr>
                                <td class="col-3">Internal contact Email</th>
                                <td>{{ $row->internal_contact_email}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @endforeach
                @else
                <tr>
                    <td colspan="8">No Records Found.</td>
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
@include('admin.companies.company_list_common')

@stop


@section ('page_js')
@stack('company_list_common_js')
<script type="text/javascript">
    $(function(){
        $("#search_field").on("change", function (){
            if ($(this).val() == 'companies.main_zipcode'){
                $("#mile_range_filter").show();
            } else{
                $("#mile_range_filter").hide();
            }
        });
    });
</script>
@endsection
