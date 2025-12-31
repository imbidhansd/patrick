@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

@include('admin.includes.searchForm')
@include('admin.includes._add_button', ['disable_reorder' => true])

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
                                {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Review Number', $url_key.
                                '.feedback_id', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                            </label>
                        </div>
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Customer Name', $url_key.
                                '.customer_name', $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Customer Email', $url_key.
                        '.customer_email', $list_params['sort_by'], $list_params['sort_order'],
                        $list_params['search_field'], $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
                    <th>
                        {!! \App\Models\Custom::getSortingLink($module_urls['list'], 'Company', $url_key. '.company_id',
                        $list_params['sort_by'], $list_params['sort_order'], $list_params['search_field'],
                        $list_params['search_text'], http_build_query($list_params)) !!}
                    </th>
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
                            <input name="ids[]" class="ids" value="{{ $row->id}}" ng-checked="all"
                                id="chk_{{ $row->id}}" type="checkbox">
                            <label for="chk_{{ $row->id}}">
                                {{ $row->feedback_id }}
                            </label>
                        </div>
                    </td>
                    <td>{{ $row->customer_name }}</td>
                    <td>{{ $row->customer_email }}</td>
                    <td>{{ $row->company->company_name }}</td>
                    <td>
                        <span class="badge badge-{{ \App\Models\Custom::feedback_status_color($row->feedback_status) }} feedback_status">
                            {{ $row->feedback_status }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group btn-group-solid">
                            <a title="View {{ $module_singular_name}}" href="javascript:;" data-toggle="modal" data-target="#detailModal_{{ $row->id }}" class="btn btn-info btn-xs"><i class="fas fa-eye"></i></a>

                            <a title="Update {{ $module_singular_name }} Status" href="javascript:;" data-toggle="modal" data-target="#statusChangeModal" class="btn btn-secondary btn-xs change_status" data-id="{{ $row->id}}"><i class="fas fa-wrench"></i></a>

                            <a title="Edit {{ $module_singular_name}}" href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>

                            <a title="Delete {{ $module_singular_name}}"
                                href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i class="fas fa-trash-alt"></i></a>
                        </div>

                        <div class="modal fade" id="detailModal_{{ $row->id }}" tabindex="-1" role="dialog"
                            aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content ">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title w-100 font-weight-bold text-left">Feedback Details - {{ $row->feedback_id }}</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <div class="table-responsive111">
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <td>
                                                        <b>Company Name: </b> {{ $row->company->company_name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Feedback Status: </b> {{ $row->feedback_status }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Customer Name: </b> {{ $row->customer_name }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Customer Email: </b> {{ $row->customer_email }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Customer Phone: </b> {{ $row->customer_phone }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Ratings: </b> {{ $row->ratings }} &nbsp;
                                                        <div id="starHalf"></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Review: </b> <br />
                                                        {!! $row->content !!}
                                                    </td>
                                                </tr>
                                                @if (count($row->feedback_files) > 0)
                                                <tr>
                                                    <td>
                                                        <b>File(s): </b> <br />
                                                        <div class="form-group">
                                                            <div class="row">
                                                                @foreach($row->feedback_files AS $files)
                                                                @if(!is_null($files->media))
                                                                <div class="col-md-1">
                                                                    <div class="media_box">
                                                                        <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}"
                                                                            data-fancybox="gallery">

                                                                            @if ($files->media->file_type == 'application/pdf')
                                                                            <i class="far fa-file-pdf font-40"></i>
                                                                            @else
                                                                            <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $files->media->file_name }}"
                                                                                class='img-thumbnail' />
                                                                            @endif
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                @endforeach
                                                            </div>
                                                        </div>
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
                @endforeach
                @else
                <tr>
                    <td colspan="6">No Records Found.</td>
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


<div class="modal fade" id="statusChangeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Update {{ $module_singular_name }} Status</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/feedback/change_status') ]) !!}
            {!! Form::hidden('feedback_id', null, ['id' => 'feedback_id']) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Status') !!}
                    <div class="select">
                        {!! Form::select('feedback_status', $feedback_statuses, null, ['class' => 'form-control
                        custom-select', 'id' => 'feedback_status', 'required' => true]) !!}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-info waves-effect waves-light">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect waves-light"
                    data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@stop


@section('page_js')
<!-- rating js -->
<script src="{{ asset('/themes/admin/assets/libs/ratings/jquery.raty-fa.js') }}"></script>

<script type="text/javascript">
    $(function (){
        @if(isset($rows) && count($rows) > 0)
            @foreach($rows as $row)
            var row_id = '{{ $row->id }}';
            $("#detailModal_"+row_id+" #starHalf").raty({
                readOnly: !0,
                half: !0,
                starHalf: "fas fa-star-half text-success",
                starOff: "far fa-star text-muted",
                starOn: "fas fa-star text-success",
                score: "{{ $row->ratings }}",
            });
            @endforeach
        @endif


        $(".change_status").on("click", function (){
            var feedback_id = $(this).data("id");
            var feedback_status = $(this).parents("tr").find(".feedback_status").text();

            $("#statusChangeModal #feedback_id").val(feedback_id);
            $("#statusChangeModal #feedback_status").val($.trim(feedback_status));
        });
    });
</script>
@stop
