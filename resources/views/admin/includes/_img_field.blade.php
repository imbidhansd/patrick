<div class="form-group">
    {!! Form::label($label) !!} <br />
    
    @if (isset($multiple))
        {!! Form::file($ref_func, ['class' => 'filestyle', 'multiple' => true, 'accept' => (isset($accept) ? $accept : '')]) !!}
        <small id="emailHelp" class="form-text text-muted">You can upload multiple images</small>
    @else
        {!! Form::file($ref_func, ['class' => 'filestyle', 'accept' => (isset($accept) ? $accept : '')]) !!}
    @endif

    @if (isset($formObj) && !is_null($formObj->$ref_func))
    <div class="media_box">
        <a href="{{ asset('/') }}uploads/media/{{ $formObj->$ref_func->file_name }}" data-fancybox="gallery">
            @if ($formObj->$ref_func->file_type == 'application/pdf')
            <i class="far fa-file-pdf font-40"></i>
            @else
            <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $formObj->$ref_func->file_name }}" class='img-thumbnail' />
            @endif
        </a>
        <br />
        <a class="btn img-del-btn btn-danger btn-xs" data-id="{{ $formObj->$ref_func->id }}"> Remove</a>
    </div>
    @endif
</div>
