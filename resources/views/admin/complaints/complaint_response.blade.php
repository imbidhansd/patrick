@extends('admin.layout')
@section('title', $admin_page_title)



@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

<div class="card-box">
    <div class="card">
        <div class="card-header bg-primary py-3 text-white">
            <div class="card-widgets">
                <a data-toggle="collapse" href="#complaint_detail" role="button" aria-expanded="false" aria-controls="complaint_detail"><i class="mdi mdi-minus"></i></a>
            </div>
            <h5 class="card-title mb-0">
                <a data-toggle="collapse" class="text-white" href="#complaint_detail" role="button" aria-expanded="false" aria-controls="complaint_detail"> Complaint Detail </a>
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
                                                    <a href="{{ asset('/') }}uploads/media/{{ $files->media->file_name }}" data-fancybox="gallery">
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
        @if (count($formObj->complaint_response) > 0)
            <div class="timeline timeline-left complaint_response_timeline">
                <article class="timeline-item timeline-item-left">
                    <div class="text-left">
                        <div class="time-show first">
                            <a href="#" class="btn btn-primary width-lg">Complaint Responses</a>
                        </div>
                    </div>
                </article>

                @foreach ($formObj->complaint_response AS $i => $complaint_response_item)
                    @php
                        $full_name = "";
                        $couser_type = $complaint_response_item->couser_type;
                        $text_cls = $bg_cls = "";
                        if ($couser_type == 'App/User'){
                            $user_name = \App\Models\User::find($complaint_response_item->couser_id);
                            $full_name = $user_name->first_name.' '.$user_name->last_name;
                            $text_cls = "text-success";
                            $bg_cls = "bg-success";
                        } else {
                            $user_name = \App\Models\Company::find($complaint_response_item->couser_id);
                            $full_name = $user_name->company_name;
                            $text_cls = "text-primary";
                            $bg_cls = "bg-primary";
                        }
                    @endphp

                    <article class="timeline-item">
                        <div class="timeline-desk">
                            <div class="panel">
                                <div class="timeline-box">
                                    <span class="arrow"></span>
                                    <span class="timeline-icon {{ $bg_cls }}"><i class="mdi mdi-checkbox-blank-circle-outline"></i></span>

                                    <h4 class="{{ $text_cls }}">{{ $full_name }}</h4>
                                    <p class="timeline-date text-muted"><small>{{ $complaint_response_item->created_at->format(env('DATE_FORMAT')) }}</small></p>
                                    <p class="mb-0">
                                        {!! $complaint_response_item->content !!}

                                        @if (!is_null($complaint_response_item->media))
                                        <div class="media_box">
                                            <h6><i class="fa fa-paperclip"></i> &nbsp; Attachment</h6>
                                            <a href="{{ asset('/') }}uploads/media/{{ $complaint_response_item->media->file_name }}" data-fancybox="gallery">
                                                @if ($complaint_response_item->media->file_type == 'application/pdf')
                                                <i class="fas fa-file-pdf"></i>
                                                @else
                                                <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $complaint_response_item->media->file_name }}"
                                                    class='img-thumbnail' />
                                                @endif
                                            </a>
                                        </div>
                                        @endif
                                    </p>

                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            @if ($formObj->complaint_status != 'Posted')
            <div class="text-center">
                <a href="javascript:;" data-toggle="modal" data-target="#complaintResponse" class="btn btn-primary btn-sm">Add Response</a>
            </div>
            @endif

            <div class="clearfix">&nbsp;</div>
        @endif
    </div>

</div>



<div class="modal fade" id="complaintResponse" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">Add Complaint Response</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => url('admin/complaints/add-complaint-response'), 'class' => 'module_form', 'files' => true]) !!}
            {!! Form::hidden('complaint_id', $formObj->id) !!}
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Response Message') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => 'Response Message', 'required' => true]) !!}
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
@stop


@section('page_js')
@stop
