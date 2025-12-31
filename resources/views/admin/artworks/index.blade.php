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
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Title', $url_key. '.title', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <?php /* <th>Artwork Type</th> */ ?>
                    <th>Artwork For</th>
                    
                    @if (isset($list_params['artwork_type']) && $list_params['artwork_type'] == 'social_media')
                    <th>Social Type</th>
                    @endif
                    
                    @if (isset($list_params['artwork_type']) && $list_params['artwork_type'] == 'print_ready')
                    <th>Type</th>
                    @endif
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
                            <input name="ids[]" class="ids" value="{{ $row->id}}" ng-checked="all" id="chk_{{ $row->id}}" type="checkbox">
                            <label for="chk_{{ $row->id}}">
                                {{ $row->title }}
                            </label>
                        </div>
                    </td>
                    <?php /* <td>{{ ucwords(str_replace('_', ' ', $row->artwork_type)) }}</td> */ ?>
                    
                    <td>{{ $row->artwork_for }}</td>
                    @if (isset($list_params['artwork_type']) && $list_params['artwork_type'] == 'social_media')
                    <td>{{ $row->social_type }}</td>
                    @endif
                    
                    @if (isset($list_params['artwork_type']) && $list_params['artwork_type'] == 'print_ready')
                    <td>{{ ucwords($row->image_type) }}</td>
                    @endif
                    
                    <td>
                        @if ($row->status == 'active')
                        <span class="label label-info">{{ ucfirst($row->status) }}</span>
                        @else
                        <span class="label label-danger">{{ ucfirst($row->status) }}</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <?php /* <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal" data-target="#{{ $module_urls['url_key']}}_{{ $row->id}}" class="btn btn-info btn-xs"><i class="fas fa-list"></i></a> */ ?>
                            <a title="Edit {{ $module_singular_name}}"
                                href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            <a title="Delete {{ $module_singular_name}}" href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i class="fas fa-trash-alt"></i></a>
                        </div>
                        
                        
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
                                                <?php /* <tr>
                                                    <td>
                                                        <b>Artwork Type: </b> 
                                                        {{ ucwords(str_replace('_', ' ', $row->artwork_type)) }}
                                                    </td>
                                                </tr> */ ?>
                                                
                                                <tr>
                                                    <td>
                                                        <b>Artwork For: </b> 
                                                        {{ $row->artwork_for }}
                                                    </td>
                                                </tr>
                                                
                                                @if (isset($list_params['artwork_type']) && $list_params['artwork_type'] == 'social_media')
                                                <tr>
                                                    <td>
                                                        <b>Social Type: </b> 
                                                        {{ $row->social_type }}
                                                    </td>
                                                </tr>
                                                @endif
                                                
                                                @if (isset($list_params['artwork_type']) && $list_params['artwork_type'] == 'print_ready')
                                                <tr>
                                                    <td>
                                                        <b>Type: </b>
                                                        {{ ucwords($row->image_type) }}
                                                    </td>
                                                </tr>
                                                @endif
                                                <?php /* <tr>
                                                    <td>
                                                        <b>JPG URL: </b>
                                                        {{ $row->jpg_url }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>PNG URL: </b>
                                                        {{ $row->png_url }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>PDF URL: </b>
                                                        {{ $row->pdf_url }}
                                                    </td>
                                                </tr> */ ?>
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
            @include ('admin.includes._tfoot', ['options' => ['active' => 'Active', 'inactive' => 'Inactive', 'delete' => 'Delete']])
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
    $(function (){
        @if (isset($list_params['artwork_type']) && $list_params['artwork_type'] != '')
        var artwork_type = '{{ $list_params['artwork_type'] }}';
        var add_button_link = $(".add_button").attr("href");
        $(".add_button").attr("href", add_button_link+"?artwork_type="+artwork_type);

        var reset_link = $(".reset_button").attr("href");
        $(".reset_button").attr("href", reset_link+"?artwork_type="+artwork_type);
        @endif
    });
</script>
@stop
