@extends('admin.layout')
@section('title', $admin_page_title)


@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')
<div id="import-box" class="box-toggle card-box import-box hide">
{!! Form::open(['method' => 'PUT','url'=>$module_urls['import'],'files' => true]) !!}
    <div class="row">      
        <div class="col-md-8">
            <div class="form-group">
            {!! Form::label('Upload Template') !!}<br />
            {!! Form::file('nonmemberfile', ['class' => 'filestyle']) !!}
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
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Company Name', $url_key. '.company_name',
                                $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                                $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>{!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Email', $url_key. '.email', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}</th>
                    <th>{!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Phone', $url_key. '.phone', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}</th>
                    <th>Top Level Categories</th>
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
                                {{ $row->company_name }} <br />
                                <small><i>{{ $row->first_name }} {{ $row->last_name }}</i></small>
                            </label>
                        </div>
                    </td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->phone }}</td>
                    <td>
                        @if (count($row->top_level_category_list) > 0)
                            @foreach ($row->top_level_category_list AS $top_level_category_item)
                            {{ $top_level_category_item->top_level_category->title }}<br />
                            @endforeach
                        @endif
                    </td>
                    <td>
                        @if ($row->status == 'active')
                        <span class="badge badge-info">{{ ucfirst($row->status)}}</span>
                        @else
                        <span class="badge badge-danger">{{ ucfirst($row->status)}}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            @can ($module_urls['url_key'] . '.' . 'view')
                            <a title="View {{ $module_singular_name }}" href="javascript:;" data-toggle="modal" data-target="#detailModal_{{ $row->id }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>
                            @endcan

                            @can ($module_urls['url_key'] . '.' . 'edit')
                            <a title="Edit {{ $module_singular_name}}" href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            @endcan

                            @can ($module_urls['url_key'] . '.' . 'delete')
                            <a title="Delete {{ $module_singular_name}}" href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i class="fas fa-trash-alt"></i></a>
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
                                        <div class="table-responsive111">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <td>
                                                        <b>Company Name: </b>
                                                        {{ $row->company_name}}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Full Name: </b>
                                                        {{ $row->first_name }} {{ $row->last_name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Email: </b>
                                                        {{ $row->email }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Phone: </b>
                                                        {{ $row->phone }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Address: </b>
                                                        {{ $row->address }},
                                                        {{ $row->city }},
                                                        {{ $row->state->name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Zipcode: </b> {{ $row->zipcode }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Zipcode Radius: </b>{{ $row->mile_range }} Mile
                                                    </td>
                                                </tr>
                                                @if (count($row->zipcode_list) > 0)
                                                <tr>
                                                    <td>
                                                        <b>Zipcode List: </b>

                                                        <div class="get_listed_service_area_list">
                                                            <div class="row">
                                                                @foreach ($row->zipcode_list AS $service_area_item)
                                                                <div class="col-md-4 col-sm-6">
                                                                    <div class="service_area_item">
                                                                    {{ $service_area_item->zipcode.', '.$service_area_item->city.', ('.$service_area_item->distance.' miles)' }}
                                                                    </div>
                                                                </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <?php /* <div class="table-responsive111">
@php
$zipcode_list = array_chunk($row->zipcode_list->toArray(), ceil(count($row->zipcode_list) / 3));
@endphp
<table class="table table-bordered table-hover">
@foreach ($zipcode_list AS $arr_item)
<tr>
@foreach ($arr_item AS $item)
<td>{{ $item['zipcode'].', '.$item['city'].', ('.$item['distance'].' miles)' }}</td>
@endforeach
</tr>
@endforeach
</table>
</div> */?>
                                                    </td>
                                                </tr>
                                                @endif
                                                <tr>
                                                    <td>
                                                        <b>Trade: </b>
                                                        {{ $row->trade->title }}
                                                    </td>
                                                </tr>
                                                @if ($row->trade_id == '1')
                                                <tr>
                                                    <td>
                                                        <b>Service Category Type: </b>
                                                        @if (!is_null($row->service_category_type_id))
                                                        {{ $row->service_category_type->title }}
                                                        @else
                                                        Both
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif

                                                @if (count($row->top_level_category_list) > 0)
                                                <tr>
                                                    <td>
                                                        <b>Top Level Category Listing: </b> <br />
                                                        @foreach ($row->top_level_category_list AS $top_level_category_item)
                                                        {{ $top_level_category_item->top_level_category->title }}<br />
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @endif

                                                <tr>
                                                    <td>
                                                        <b>How did you hear about us? </b>
                                                        {{ $row->how_did_you_hear_about_us }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Comments/Questions: </b> <br />
                                                        {!! $row->comments !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Status: </b>
                                                        @if ($row->status == 'active')
                                                        <span class="badge badge-info">{{ ucfirst($row->status)}}</span>
                                                        @else
                                                        <span class="badge badge-danger">{{ ucfirst($row->status)}}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="7">No Records Found.</td>
                </tr>
                @endif

            </tbody>

        </table>

    </div>

    <div class="row">
        <div class="col-sm-12 col-md-6">
            @include ('admin.includes._tfoot', ['options' => ['delete' => 'Delete']])
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
        $("#search_field").on("change", function (){
            if ($(this).val() == 'non_members.zipcode'){
                $("#mile_range_filter").show();
            } else{
                $("#mile_range_filter").hide();
            }
        });

        $("#trade_id").on("change", function (){
            var trade_id = $(this).val();

            if (typeof trade_id !== 'undefined' && trade_id != ''){
                $.ajax({
                    url: '{{ url("get-listed/get-top-level-categories") }}',
                    type: 'POST',
                    data: {'trade_id': trade_id, '_token': '{{ csrf_token() }}'},
                    success: function (data){
                        if (typeof data.success !== 'undefined'){
                            Swal.fire({
                                title: data.title,
                                type: data.type,
                                text: data.message
                            });
                        } else {
                            data = '<option value="">All</option>' + data;
                            $("#top_level_categories").html(data);
                        }
                    }
                });
            }
        });               
    });     
</script>
@endsection