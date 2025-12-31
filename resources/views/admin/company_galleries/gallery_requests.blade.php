<?php
$module_urls['add'] = null;
?>
@extends('admin.layout')
@section('title', $admin_page_title)

@section('content')
@include('admin.includes.breadcrumb', ['breadCrumbArray' => [$module_plural_name => '']])
@include('flash::message')

<div class="card-box">
    <div class="banners">
        <div class="row">
            @foreach ($company_galleries AS $gallery_item)
            
            @if($gallery_item->media)
            <div class="col-md-2 text-center">
                <div class="item active">
                    @if ($gallery_item->gallery_type == 'image')
                        <a href="{{ asset('/uploads/media/'.$gallery_item->media->file_name) }}" data-fancybox="company_gallery">
                            <img src="{{ asset('/uploads/media/fit_thumbs/100x100/'.$gallery_item->media->file_name) }}" class="img-thumbnail" />
                        </a>
                    @else
                        @if ($gallery_item->video_type == 'vimeo')
                        @php $video_link = 'https://vimeo.com/'.$gallery_item->video_id; @endphp
                        @elseif ($gallery_item->video_type == 'youtube')
                        @php $video_link = 'https://www.youtube.com/watch?v='.$gallery_item->video_id; @endphp
                        @endif
                        <a href="{{ $video_link }}" data-fancybox="company_gallery">
                            <img src="{{ $gallery_item->image_link }}" class="img-thumbnail" />
                        </a>
                    @endif

                    <div class="btn-group text-center">
                        <a href="javascript:;" class="btn btn-success btn-xs change_status" data-type="approved" data-id="{{ $gallery_item->id }}" data-toggle="tooltip" data-placement="left" title="Approve"><i class="far fa-thumbs-up"></i></a>

                        <a href="javascript:;" class="btn btn-warning btn-xs reject_gallery_photo" data-id="{{ $gallery_item->id }}" data-toggle="tooltip" data-placement="bottom" title="Reject"><i class="far fa-thumbs-down"></i></a>

                        <a href="javascript:;" class="btn btn-danger btn-xs change_status" data-type="delete" data-id="{{ $gallery_item->id }}" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fas fa-trash-alt"></i></a>
                    </div>
                </div>
            </div>
            @endif
            
            @if ($loop->iteration % 6 == 0)
        </div>
        <hr />
        <div class="row">
            @endif
            @endforeach
        </div>
    </div>
</div>

<div class="modal fade" id="rejectGalleryPhotoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content ">
            <div class="modal-header text-center">
                <h4 class="modal-title w-100 font-weight-bold text-left">
                    Reject reason
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::open(['url' => 'admin/companies/change-company-gallery-status', 'class' => 'module_form', 'id' => 'photo_gallery_reject_form']) !!}
            {!! Form::hidden('gallery_status', 'rejected', ['required' => true]) !!}
            {!! Form::hidden('gallery_id', null, ['id' => 'gallery_id', 'required' => true]) !!}

            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('Note') !!}
                    {!! Form::textarea('reject_note', null, ['class' => 'form-control', 'required' => true]) !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-light" id="submit_btn">Submit</button>
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

{!! Form::open(['url' => 'admin/companies/change-company-gallery-status', 'class' => 'module_form', 'id' => 'gallery_status_change_form']) !!}
{!! Form::hidden('gallery_id', null, ['id' => 'gallery_id']) !!}
{!! Form::hidden('gallery_status', null, ['id' => 'gallery_status']) !!}
{!! Form::close() !!}
@stop


@section ('page_js')
<script type="text/javascript">
    $(function () {
        $(".change_status").on("click", function () {
            var type = $(this).data("type");
            var gallery_id = $(this).data("id");

            $("#gallery_status_change_form #gallery_id").val(gallery_id);
            $("#gallery_status_change_form #gallery_status").val(type);


            var btn_color = "#6c757d";
            var btn_title = "Yes, Accept it!";

            if (type == 'approved') {
                btn_color = '#4bd396';
                btn_title = "Yes, Approve it!";
            } else if (type == 'rejected') {
                btn_color = '#f7b820';
                btn_title = "Yes, Reject it!";
            } else if (type == 'delete') {
                btn_color = '#f34c59';
                btn_title = "Yes, Delete it!";
            }

            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: !0,
                confirmButtonColor: btn_color,
                cancelButtonColor: "#6c757d",
                confirmButtonText: btn_title
            }).then(function (t) {
                if (typeof t.value !== 'undefined') {
                    $("#gallery_status_change_form").submit();
                }
            });
        });

        $(".reject_gallery_photo").on("click", function () {
            var gallery_photo_id = $(this).data("id");

            $("#rejectGalleryPhotoModal #photo_gallery_reject_form #gallery_id").val(gallery_photo_id);
            $("#rejectGalleryPhotoModal").modal("show");
        });

        $("#photo_gallery_reject_form").on("submit", function () {
            var instance = $(this).parsley();
            if (instance.isValid()) {
                $("#photo_gallery_reject_form #submit_btn").html('Processing... <i class="fas fa-spinner fa-spin"></i>');
                $("#photo_gallery_reject_form #submit_btn").attr('disabled', true);
            } else {
                $("#photo_gallery_reject_form #submit_btn").html('Submit');
                $("#photo_gallery_reject_form #submit_btn").attr('disabled', false);
            }
        });
    });
</script>
@endsection
