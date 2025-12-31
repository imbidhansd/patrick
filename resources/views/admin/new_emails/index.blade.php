@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_add' => true, 'disable_reorder' => true])

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
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Subject', $url_key. '.subject', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Email For', $url_key.'.email_for', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Email Type', $url_key.'.email_type', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>Status</th>
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
                                {{ $row->subject}}
                            </label>
                        </div>
                    </td>
                    <td>{{ ucfirst($row->email_for)}}</td>
                    <td>{{ ucwords($row->email_type)}}</td>
                    <td>
                        @if ($row->status == 'active')
                        <span class="label label-info">{{ ucfirst($row->status)}}</span>
                        @else
                        <span class="label label-danger">{{ ucfirst($row->status)}}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="Send Test Emails" href="javascript:;" class="btn btn-orange btn-xs send_test_email" data-type="new_emails" data-id="{{ $row->id }}"><i class="fas fa-envelope"></i></a>
                            
                            @can ($module_urls['url_key'] . '.' . 'view')
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal" data-target="#{{ $module_urls['url_key']}}_{{ $row->id}}" class="btn btn-info btn-xs"><i class="fas fa-list"></i></a>
                            @endcan

                            <?php /* @can ($module_urls['url_key'] . '.' . 'create')
                              <a title="Copy {{ $module_singular_name}}"
                              href="{{ route('copy-item', ['url_key' => $module_urls['url_key'] ,'id' => $row->id])}}"
                              class="btn btn-success copy_btn btn-xs"><i class="far fa-copy"></i></a>
                              @endcan */ ?>

                            @can ($module_urls['url_key'] . '.' . 'edit')
                            <a title="Edit {{ $module_singular_name}}"
                               href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            @endcan

                            <?php /* @can ($module_urls['url_key'] . '.' . 'delete')
                              <a title="Delete {{ $module_singular_name}}"
                              href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}"
                              class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i
                              class="fas fa-trash-alt"></i></a>
                              @endcan */ ?>
                        </div>


                        <!-- Modal -->
                        <div class="modal fade" id="{{ $module_urls['url_key']}}_{{ $row->id}}" tabindex="-1"
                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">
                                            {{ $row->title}}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>
                                                        <b>Email For: </b> 
                                                        {{ ucfirst($row->email_for)}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Email Type: </b> 
                                                        {{ $row->email_type }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Title: </b>
                                                        {{ $row->title }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>From Email Address: </b>
                                                        {{ $row->from_email_address }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Subject: </b>
                                                        {{ $row->subject }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Email Preview: </b> <br />
                                                        {!! $row->email_header !!}
                                                        <table align="center" width="100%" cellpadding="0" cellspacing="0">
                                                            <tr>
                                                                <td valign="top" align="left" style="padding: 0px;">
                                                                    <table align="center" cellpadding="0" cellspacing="0" style="background: #fff; max-width: 600px; text-align: left;">
                                                                        <tr>
                                                                            <td style="font-family: 'Open Sans', sans-serif; font-weight: 400; letter-spacing: 0.02em; line-height: 25px;">{!! $row->content !!}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>&nbsp;</td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                        {!! $row->email_footer !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Status: </b>
                                                        @if ($row->status == 'active')
                                                        <span class="label label-info">{{ ucfirst($row->status) }}</span>
                                                        @else
                                                        <span class="label label-danger">{{ ucfirst($row->status) }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            @include ('admin.includes._tfoot', ['options' => ['active' => 'Active', 'inactive' => 'Inactive']])
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


@section('page_js')
<script type="text/javascript">
$(function () {
    @if (isset($list_params['email_for']) && $list_params['email_for'] != '')
    var email_for = '{{ $list_params['email_for'] }}';

    var reset_link = $(".reset_button").attr("href");
    $(".reset_button").attr("href", reset_link+"?email_for="+email_for);
    @endif
});
</script>
@endsection