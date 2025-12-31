{!! Form::label($label_name) !!}<br />
{!! Form::file($field_name, null, ['class' => 'form-control']) !!}
@if (!is_null($formObj) && call_user_func( array( $formObj, 'media' ) ))
@php $media_obj = call_user_func( array( $formObj, 'media' )); @endphp
<div class="media_box">
    <a href="{{ asset('/') }}uploads/media/{{ $media_obj->file_name }}" data-fancybox="gallery">
        <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $media_obj->file_name }}"
            class='img-thumbnail' />
    </a>
    <br />
    <a class="btn img-del-btn btn-danger btn-xs" data-id="{{ $media_obj->id }}"> Remove</a>
</div>
@endif
