<?php
$admin_page_title = 'Photo Gallery';
?>
@extends('company.layout')

@section ('content')
@include('admin.includes.breadcrumb')

@include('admin.includes.formErrors')
@include('flash::message')

@if (isset($company_gallery_list) && count($company_gallery_list) == 10)
@include('company.includes._add_button', ['disable_add' => true, 'disable_search' => true])
@else
@include('company.includes._add_button', ['disable_search' => true])
@endif
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="card-box">
            <h4 class="header-title">{{ $admin_page_title }}</h4>
            <p class="font-13"><i>This photos will appear on your company page.</i></p>

            @if(isset($company_gallery_list) && count($company_gallery_list) > 0)
            <div class="table-responsive111">
                {!! Form::open(['url' => url($url_key.'/update-status'), 'class' => 'module_form list-form']) !!}
                <table class="table table-hover table-centered m-0">
                    <thead>
                        <tr>
                            <th class="col-md-8 col-lg-8 col-sm-8">
                                <div class="checkbox checkbox-primary">
                                    <input id="chk_all" ng-model="all" type="checkbox">
                                    <label for="chk_all">
                                        File
                                    </label>
                                </div>
                            </th>
                            <th>Status</th>
                            <th class="col-md-2 col-lg-2 col-sm-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($company_gallery_list AS $row)
                        @if(!is_null($row->media))
                        <tr>
                            <td>
                                <div class="checkbox checkbox-primary">
                                    <input name="ids[]" class="ids" value="{{ $row->id}}" ng-checked="all" id="chk_{{ $row->id}}" type="checkbox">
                                    <label for="chk_{{ $row->id}}">
                                        @if ($row->gallery_type == 'image')
                                            <img src="{{ asset('uploads/media/fit_thumbs/100x100/' . $row->media->file_name) }}" class="img-thumbnail" />
                                            <a data-toggle="tooltip" data-placement="bottom" title="View Photo" href="{{ asset('/uploads/media/'.$row->media->file_name) }}" data-fancybox="gallery" class="btn btn-info btn-xs gallery-photo-expand"><i class="fas fa-expand"></i></a>
                                        @else
                                            @if ($row->video_type == 'vimeo')
                                            @php $video_link = 'https://vimeo.com/'.$row->video_id; @endphp
                                            @elseif ($row->video_type == 'youtube')
                                            @php $video_link = 'https://www.youtube.com/watch?v='.$row->video_id; @endphp
                                            @endif

                                            <img src="{{ asset('uploads/media/fit_thumbs/100x100/' . $row->media->file_name) }}" class="img-thumbnail" />
                                            <a data-toggle="tooltip" data-placement="bottom" title="View Photo" href="{{ $video_link }}" data-fancybox="gallery" class="btn btn-info btn-xs gallery-photo-expand"><i class="fas fa-expand"></i></a>
                                        @endif
                                    </label>
                                </div>
                            </td>
                            <td>
                                @if ($row->status == 'pending')
                                <span class="badge badge-info">{{ ucfirst($row->status) }}</span>
                                @elseif ($row->status == 'approved')
                                <span class="badge badge-success">{{ ucfirst($row->status) }}</span>
                                @elseif ($row->status == 'rejected')
                                <span class="badge badge-danger">{{ ucfirst($row->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-solid">
                                    @if ($row->status == 'rejected')
                                    <a href="javascript:;" data-toggle="modal" data-target="#rejectModal_{{ $row->id }}" title="{{ $module_singular_name }} Reject Reason"  class="btn btn-info btn-xs"><i class="fas fa-list"></i></a>
                                    @endif
                                    
                                    <a title="Delete {{ $module_singular_name}}" href="{{ route($module_urls['delete'], [$module_urls['url_key_singular'] => $row->id])}}" class="btn btn-danger delete_btn btn-xs" data-id="{{ $row->id}}"><i class="fas fa-trash-alt"></i></a>
                                </div>
                                
                                <div class="modal fade" id="rejectModal_{{ $row->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content ">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Reject reason</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>

                                            <div class="modal-body">
                                                {!! $row->reject_note !!}
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @empty
                        <tr>
                            <td colspan="3">No gallery found.</td>
                        </tr>
                        @endforelse
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="select">
                                            <select name="action" id="actionSel" data-parsley-required-message="Select any option" required="required" disabled="" class="form-control custom-select">
                                                <option value="">Select Option</option>
                                                <option value="delete">Delete</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary index-form-btn" disabled="">Submit</button>
                                    </div>
                                </div>

                            </td>
                        </tr>
                    </tfoot>
                </table>
                {!! Form::close() !!}
            </div>
            @else
            <div class="clearfix">&nbsp;</div>
            <h4 class="text-danger text-center">No gallery found.</h4>
            @endif
        </div>
    </div>
</div>

@include('admin.includes._global_delete_form')
@endsection

@section ('page_js')
@endsection
