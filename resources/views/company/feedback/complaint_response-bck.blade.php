<?php
    $admin_page_title = 'Complaint';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

<div class="row">
    <div class="col-sm-9">
        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#complaint_detail" role="button" aria-expanded="false"
                        aria-controls="complaint_detail"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-white" href="#complaint_detail" role="button"
                        aria-expanded="false" aria-controls="complaint_detail"> Complaint Detail </a>
                </h5>
            </div>
            <div id="complaint_detail" class="collapse">
                <div class="card-body">
                    <div class="table-responsive111">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>
                                    <b>Company Name: </b> {{ $formObj->company->company_name }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Complaint Status: </b> {{ $formObj->complaint_status }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Customer Name: </b> {{ $formObj->customer_name }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Customer Email: </b> {{ $formObj->customer_email }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Customer Phone: </b> {{ $formObj->customer_phone }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Complaint: </b> <br />
                                    {!! $formObj->content !!}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Image</b> <br />
                                    @if (isset($formObj->complaint_files) && count($formObj->complaint_files) > 0)
                                    <div class="form-group">
                                        <div class="row">
                                            @foreach($formObj->complaint_files AS $files)
                                            @if(!is_null($files->media))
                                            <div class="col-md-2">
                                                <div class="media_box">
                                                    <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}"
                                                        data-fancybox="gallery">
                                                        <img src="{{ asset('/') }}uploads/media/fit_thumbs/100x100/{{ $files->media->file_name }}"
                                                            class='img-thumbnail' />
                                                    </a>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-primary py-3 text-white">
                <div class="card-widgets">
                    <a data-toggle="collapse" href="#complaint_responses" role="button" aria-expanded="false"
                        aria-controls="complaint_responses"><i class="mdi mdi-minus"></i></a>
                </div>
                <h5 class="card-title mb-0">
                    <a data-toggle="collapse" class="text-white" href="#complaint_responses" role="button"
                        aria-expanded="false" aria-controls="complaint_responses"> Complaint Responses </a>
                </h5>
            </div>

            <div id="complaint_responses" class="collapse show">
                <div class="card-body">
                    @if (count($formObj->complaint_response) > 0)
                        @foreach ($formObj->complaint_response AS $complaint_response_item)
                        <div class="card card-border card-primary">
                            @php
                                $full_name = "";
                                $couser_type = $complaint_response_item->couser_type;
                                if ($couser_type == 'App/User'){
                                    $user_name = \App\Models\User::find($complaint_response_item->couser_id);
                                    $full_name = $user_name->first_name.' '.$user_name->last_name;
                                } else {
                                    $user_name = \App\Models\Company::find($complaint_response_item->couser_id);
                                    $full_name = $user_name->company_name;
                                }
                            @endphp

                            <div
                                class="card-header border-{{ (($couser_type == 'App/User') ? 'primary text-right' : 'purple') }} bg-transparent py-3">
                                <h5 class="card-title mb-0">
                                    {!! $full_name.' - '.$complaint_response_item->created_at->format(env('DATE_FORMAT'))
                                    !!}
                                </h5>
                            </div>

                            <div id="complaint_responses" class="collapse show">
                                <div class="card-body {{ (($couser_type == 'App/User') ? 'text-right' : '') }}">
                                    {!! $complaint_response_item->content !!}

                                    @if (!is_null($complaint_response_item->media))
                                    <div class="media_box">
                                        <h3><i class=""></i> Attachment</h3>
                                        <a href="{{ asset('/') }}uploads/media/{{ $complaint_response_item->media->file_name }}"
                                            data-fancybox="gallery">
                                            @if ($complaint_response_item->media->file_type == 'application/pdf')
                                            <i class="fas fa-file-alt"></i>
                                            @else
                                            <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $complaint_response_item->media->file_name }}"
                                                class='img-thumbnail' />
                                            @endif
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @if ($formObj->complaint_status != 'Posted')
                        <div class="text-right">
                            <a href="javascript:;" data-toggle="modal" data-target="#complaintResponse"
                                class="btn btn-primary btn-sm">Add Response</a>
                        </div>
                        @endif
                    @elseif ($formObj->complaint_status != 'Posted')
                    <div class="text-right">
                        <a href="javascript:;" data-toggle="modal" data-target="#complaintResponse"
                            class="btn btn-primary btn-sm">Add Response</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>


    </div>

    @include('company.profile._company_profile_sidebar')
</div>

<div class="modal fade" id="complaintResponse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Add Complaint Response</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('feedback/complaints/add-complaint-response'), 'class' => 'module_form', 'files' => true]) !!}
            {!! Form::hidden('complaint_id', $formObj->id) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Response Message') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Response Message',
                    'required' => true]) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('File') !!}
                    {!! Form::file('media', ['class' => 'filestyle', 'accept' => 'application/pdf,image/*']) !!}
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light">Save changes</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection

@section ('page_js')
@include('company.profile._js')
@endsection
