@extends('admin.layout')
@section('title', $admin_page_title)


@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

<div class="card-box">
    @if (isset($trades) && count($trades) > 0)
    <ul class="nav nav-tabs nav-justified tabs-bordered" role="tablist">
        @foreach ($trades AS $key => $trade_item)
        <li class="nav-item">
            <a class="nav-link {{ (($loop->first) ? 'active': '') }}" id="{{ $trade_item->id }}-tab" data-toggle="tab" href="#mail_trades{{ $trade_item->id }}" role="tab" aria-controls="{{ $trade_item->id }}" aria-selected="false">
                <span class="d-none d-sm-block">{{ $trade_item->short_name }}</span>
            </a>
        </li>
        @endforeach
    </ul>


    <div class="tab-content pt-0">
        @foreach ($trades AS $trade_item)
        <div class="tab-pane {{ (($loop->first) ? 'show active': '') }}" id="mail_trades{{ $trade_item->id }}" role="tabpanel" aria-labelledby="{{ $trade_item->id }}-tab">
            <div class="card mb-0 p-3">
                <div class="card-body">
                    <div class="text-left">
                        @if (count($trade_item->follow_up_mail_category) > 0)
                        @foreach ($trade_item->follow_up_mail_category AS $follow_up_category_item)
                        <div class="card card-border card-info">
                            <div class="card-header border-info bg-transparent">
                                <h3 class="card-title text-info mb-0">{{ $follow_up_category_item->title }}</h3>
                            </div>

                            <div class="card-body">
                                @if (isset($follow_up_category_item->follow_up_confirmation_email))
                                @php
                                $confirmation_mail = $follow_up_category_item->follow_up_confirmation_email;
                                @endphp

                                <table class="table table-colored-bordered table-bordered-primary">
                                    <thead>
                                        <tr>
                                            <th>Confirmation Email</th>
                                            <th class="text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $confirmation_mail->title }}</td>
                                            <td class="text-right">
                                                <div class="btn-group btn-group-solid">
                                                    <a href="javascript:;" data-toggle="modal" data-target="#{{ $module_urls['url_key'] }}_{{ $confirmation_mail->id }}" class="btn btn-info btn-xs"><i class="fas fa-list"></i></a>

                                                    <a title="Edit {{ $module_singular_name}}" href="{{ route($module_urls['edit'], [$module_urls['url_key_singular'] => $confirmation_mail->id]) }}" class="btn btn-primary btn-xs"><i class="fas fa-edit"></i></a>
                                                </div>


                                                <!-- Modal -->
                                                <div class="modal fade" id="{{ $module_urls['url_key'] }}_{{ $confirmation_mail->id }}" tabindex="-1"
                                                     role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-white" id="exampleModalLabel">
                                                                    {{ $confirmation_mail->title }}
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
                                                                                <b>Email for: </b>
                                                                                {{ $confirmation_mail->email_for }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <b>Title: </b>
                                                                                {{ $confirmation_mail->title }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <b>Subject: </b>
                                                                                {{ $confirmation_mail->subject }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <b>Email Preview: </b> <br />

                                                                                {!! $confirmation_mail->email_header !!}
                                                                                {!! $confirmation_mail->email_content !!}
                                                                                {!! $confirmation_mail->email_footer !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <b>Status: </b> {{ ucfirst($confirmation_mail->status) }}
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
                                @endif

                                @if (count($follow_up_category_item->follow_up_emails) > 0)
                                <div class="table-responsive">
                                    <table class="table table-colored-bordered table-bordered-teal">
                                        <thead>
                                            <tr>
                                                <th>Message Title</th>
                                                <th>Send Time</th>
                                                <th class="text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        @foreach ($follow_up_category_item->follow_up_emails AS $follow_up_mail_item)
                                        <tr>
                                            <td>{{ $follow_up_mail_item->title }}</td>
                                            <td>{{ $follow_up_mail_item->send_time }}</td>
                                            <td class="text-right">
                                                <div class="btn-group btn-group-solid">
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
                                                                                <b>Email For: </b>
                                                                                {{ $follow_up_mail_item->email_for }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td>
                                                                                <b>Title: </b>
                                                                                {{ $follow_up_mail_item->title }}
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
                                <a href="{{ $module_urls['add'].'?follow_up_mail_category_id='.$follow_up_category_item->id }}" class="btn btn-primary btn-sm">Add Followup Template</a>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@include('admin.includes._global_delete_form')

@stop
