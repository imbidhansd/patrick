@extends('admin.layout')
@section('title', $admin_page_title)


@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

<div class="card card-border card-info">
    <div class="card-header border-info bg-transparent">
        <h3 class="card-title text-info mb-0">{{ $module_plural_name }}</h3>
    </div>

    <div class="card-body">

        @if (isset($confirmation_email) && !is_null($confirmation_email))
        <div class="table-responsive">
            <table class="table table-colored-bordered table-bordered-primary">
                <thead>
                    <tr>
                        <th>Confirmation Email</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $confirmation_email->title }}</td>
                        <td class="text-right">
                            <div class="btn-group btn-group-solid">
                                <a title="Send Test Emails" href="javascript:;" class="btn btn-orange btn-xs send_test_email" data-type="non_member_emails" data-id="{{ $confirmation_email->id }}"><i class="fas fa-envelope"></i></a>
                                <a href="javascript:;" data-toggle="modal" data-target="#{{ $module_urls['url_key'] }}_{{ $confirmation_email->id }}" class="btn btn-info btn-xs"><i class="fas fa-list"></i></a>
                                <a title="Edit {{ $module_singular_name}}" href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $confirmation_email->id]) }}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="{{ $module_urls['url_key'] }}_{{ $confirmation_email->id }}" tabindex="-1"
                                 role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-white" id="exampleModalLabel">
                                                {{ $confirmation_email->title }}
                                            </h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body text-left">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td>
                                                            <b>Title: </b>
                                                            {{ $confirmation_email->title }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>From Email Address: </b>
                                                            {{ $confirmation_email->from_email_address }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Subject: </b>
                                                            {{ $confirmation_email->subject }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Email Preview: </b> <br />

                                                            {!! $confirmation_email->email_header !!}
                                                            {!! $confirmation_email->email_content !!}
                                                            {!! $confirmation_email->email_footer !!}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <b>Status: </b> {{ ucfirst($confirmation_email->status) }}
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
                </tbody>
            </table>
        </div>
        @endif


        @if (isset($followup_emails) && count($followup_emails) > 0)
        <div class="table-responsive">
            <table class="table table-colored-bordered table-bordered-teal">
                <thead>
                    <tr>
                        <th>Message Title</th>
                        <th>Send Time</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                @foreach ($followup_emails AS $follow_up_mail_item)
                <tr>
                    <td>{{ $follow_up_mail_item->title }}</td>
                    <td>{{ $follow_up_mail_item->send_time }}</td>
                    <td class="text-right">
                        <div class="btn-group btn-group-solid">
                            <a title="Send Test Emails" href="javascript:;" class="btn btn-orange btn-xs send_test_email" data-type="non_member_emails" data-id="{{ $follow_up_mail_item->id }}"><i class="fas fa-envelope"></i></a>
                            <a href="javascript:;" data-toggle="modal" data-target="#{{ $module_urls['url_key'] }}_{{ $follow_up_mail_item->id }}" class="btn btn-info btn-xs"><i class="fas fa-list"></i></a>
                            <a title="Edit {{ $module_singular_name}}" href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $follow_up_mail_item->id]) }}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                            <a title="Delete {{ $module_singular_name}}" href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $follow_up_mail_item->id])}}" class="btn btn-danger btn-xs delete_btn" data-id=""><i class="fas fa-trash-alt"></i></a>  
                        </div>


                        <!-- Modal -->
                        <div class="modal fade" id="{{ $module_urls['url_key'] }}_{{ $follow_up_mail_item->id }}" tabindex="-1"
                             role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-white" id="exampleModalLabel">
                                            {{ $follow_up_mail_item->title }}
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-left">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td>
                                                        <b>Title: </b>
                                                        {{ $follow_up_mail_item->title }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>From Email Address: </b>
                                                        {{ $follow_up_mail_item->from_email_address }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Subject: </b>
                                                        {{ $follow_up_mail_item->subject }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Email Preview: </b> <br />
                                                        {!! $follow_up_mail_item->email_header !!}
                                                        {!! $follow_up_mail_item->email_content !!}
                                                        {!! $follow_up_mail_item->email_footer !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <b>Status: </b> 
                                                        {{ ucfirst($follow_up_mail_item->status) }}
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
            </table>
        </div>
        @endif
    </div>
    <div class="card-footer text-right">
        <div class="btn-group btn-group-solid">
            <?php /* <a href="{{ url('admin/follow_up_emails/re-order/?follow_up_mail_category_id='.$follow_up_category_item->id) }}" class="btn btn-orange btn-sm">Reorder</a> */ ?>
            <a href="{{ $module_urls['add'] }}" class="btn btn-primary btn-sm">Add Followup Template</a>
        </div>
    </div>
</div>
@include('admin.includes._global_delete_form')

@stop
