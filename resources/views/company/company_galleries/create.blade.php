@extends('company.layout')
@section('title', $admin_page_title)

@section ('content')

@include('admin.includes.breadcrumb', ['breadCrumbArray' => ['Photo Gallery' => $module_urls['list'],
$admin_page_title => 'Upload Photo']])
@include('flash::message')

<div class="card-box">
    @include('admin.includes.formErrors')

    {!! Form::open(['url' => $module_urls['store'], 'class' => 'module_form', 'files' => true, ]) !!}
    @include('company.company_galleries.form', ['new_form' => true])
    {!! Form::close() !!}
</div>
@stop

@section('page_js')
<script type="text/javascript">
    $(function (){
        $(".filestyle").on("change", function (){
            var files = $(this)[0].files;
            $(this).parents(".form-group").find(".form-control").val(files.length+' files selected');
        });
        
        
        $(".gallery_type").on("change", function (){
            var gallery_type = $(this).val();
            
            if (gallery_type == 'image'){
                $("#image").show();
                $("#image .filestyle").attr('required', true);
                
                $("#video").hide();
                $("#video .video_type").attr('required', false);
            } else if (gallery_type == 'video'){
                $("#video").show();
                $("#video .video_type").attr('required', true);
                
                $("#image").hide();
                $("#image .filestyle").attr('required', false);
            }
        });
        
        $(".video_type").on("change", function (){
            var video_type = $(this).val();
            
            if (video_type == 'vimeo'){
                $("#vimeo_link_field").show();
                $("#vimeo_link_field input").attr('required', true);
                
                $("#youtube_link_field").hide();
                $("#youtube_link_field input").attr('required', false);
            } else if (video_type == 'youtube'){
                $("#youtube_link_field").show();
                $("#youtube_link_field input").attr('required', true);
                
                $("#vimeo_link_field").hide();
                $("#vimeo_link_field input").attr('required', false);
            }
        });
    });
</script>
@stop
