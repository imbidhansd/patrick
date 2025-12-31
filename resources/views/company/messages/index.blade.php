<?php
$admin_page_title = $module_plural_name;
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-lg-9 col-md-8 col-sm-12">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h3 class="card-title text-white mb-0">Messages</h3>
            </div>
            <div class="card-body">
                @if(isset($company_messages_list) && count($company_messages_list) > 0)

                {!! Form::open(['url' => url($url_key.'/update-status'), 'class' => 'module_form list-form']) !!}
                <div class="table-responsive list-page">
                    <table class="table table-hover table-centered m-0">
                        <thead>
                            <tr>
                                <th>
                                    <div class="checkbox checkbox-primary">
                                        <input id="chk_all" ng-model="all" type="checkbox">
                                        <label for="chk_all">
                                            Title
                                        </label>
                                    </div>
                                </th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($company_messages_list AS $message_item)
                            <tr class="table-{{ $message_item->message_type}}">
                                <td>
                                    <div class="checkbox checkbox-primary">
                                        <input name="ids[]" class="ids" value="{{ $message_item->id}}" ng-checked="all" id="chk_{{ $message_item->id}}" type="checkbox">
                                        <label for="chk_{{ $message_item->id}}">

                                            <div id="message_tr_{{ $message_item->id}}" class="message_title" style="{{ (($message_item->checked == 'no') ? 'font-weight: bold;' : '')}}">
                                                <a href="javascript:;" class="text-primary expand_link" data-id="{{ $message_item->id}}">
                                                    {{ $message_item->title}}
                                                </a>
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $message_item->created_at->format(env('DATE_FORMAT'))}}</td>
                                <td>
                                    <div class="btn-group btn-group-solid">
                                        <a href="javascript:;" class="btn btn-info btn-xs expand_link" data-id="{{ $message_item->id}}"><i class="fa fa-list"></i></a>
                                        <a title="Delete Message" href="{{ route($module_urls['delete'], $message_item->id)}}" class="btn btn-danger delete_btn btn-xs" data-id="{{ $message_item->id}}"><i class="fa fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <tr id="detail_tr{{ $message_item->id}}" style="display: none;">
                                <td colspan="3">
                                    <table class="table table-striped">
                                        <tr class="no-top-border">
                                            <td><b>Date:</b> {{ $message_item->created_at->format(env('DB_DATE_FORMAT'))}}</td>
                                        </tr>
                                        <tr>
                                            <td><b>Title:</b> {{ $message_item->title}}</td>
                                        </tr>
                                        <tr>
                                            <td class="body_message"><b>Message:</b> {!! $message_item->content !!}</td>
                                        </tr>
                                        @if(!is_null($message_item->link))
                                        <tr>
                                            <td>
                                                <b>Link:</b>
                                                <a href="{{ $message_item->link}}" target="_blank">{{ $message_item->link}}</a>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3">No messages found.</td>
                            </tr>
                            @endforelse
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="3 pl-0">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="select">
                                                <select name="action" id="actionSel"
                                                        data-parsley-required-message="Select any option" required="required"
                                                        disabled="" class="form-control custom-select">
                                                    <option value="">Select Option</option>
                                                    <option value="checked">Checked</option>
                                                    <option value="not_checked">Not Checked</option>
                                                    <option value="delete">Delete</option>
                                                </select>
                                                <div class="select__arrow"></div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary index-form-btn"
                                                    disabled="">Submit</button>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {!! Form::close() !!}

                <div class="clearfix">&nbsp;</div>
                <div class="float-left">
                    {{ $company_messages_list->render()}}
                </div>
                @else
                <div class="clearfix">&nbsp;</div>
                <h4 class="text-danger text-center">
                    No new messages found!<br/>
                </h4>
                @endif
            </div>
        </div>
    </div>

    @include('company.profile._company_profile_sidebar')
</div>

@include('admin.includes._global_delete_form')
@endsection

@section ('page_js')
@include('company.profile._js')

<script type="text/javascript">
    $(function () {
        $('.expand_link').click(function () {
            var ids = $(this).data("id");
            open_tr(ids);
        });

        var modal_id = '{{ (Request::has("id") ? Request::get("id") : "") }}';
        if (typeof modal_id !== 'undefined' && modal_id != '') {
            open_tr(modal_id);
        }
    });


    function open_tr(ids) {
        $("#detail_tr" + ids).fadeToggle('slow');

        var data = {
            'ids[]': ids,
            'action': 'checked',
            'ajax_form': 'yes',
            '_token': '{{ csrf_token() }}'
        };

        $.ajax({
            context: this,
            url: '{{ url($url_key."/update-status") }}',
            type: 'POST',
            data: data,
            success: function (data) {
                $("#message_tr_" + ids).removeAttr('style');
            }
        });
    }
</script>

@endsection
