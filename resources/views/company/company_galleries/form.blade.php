<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Gallery Type') !!}
            <div class="radio radio-primary radio-circle">
                <input type="radio" name="gallery_type" class="gallery_type" value="image" id="gallery_image" data-parsley-errors-container="#gallery_type_error_container" required />
                <label for="gallery_image">Image</label>
            </div>

            <div class="radio radio-primary radio-circle">
                <input type="radio" name="gallery_type" class="gallery_type" value="video" id="gallery_video" data-parsley-errors-container="#gallery_type_error_container" required />
                <label for="gallery_video">Video</label>
            </div>
            <div id="gallery_type_error_container"></div>
        </div>
    </div>
</div>

<div class="row" id="image" style="display:none">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('File') !!}
            {!! Form::file('file[]', ['class' => 'filestyle', 'multiple' => 'true', 'accept' => 'image/*', 'data-parsley-errors-container' => '#file_error_container', 'required' => false]) !!}
            <br />
            <i>Note: You can upload multiple photos</i>
            
            <div id="file_error_container"></div>
        </div>
    </div>
</div>
<div class="row" id="video" style="display:none;">
    <div class="col-md-4">
        <div class="form-group">
            {!! Form::label('Video Type') !!}
            <div class="radio radio-primary radio-circle">
                <input type="radio" name="video_type" class="video_type" value="vimeo" id="vimeo" data-parsley-errors-container="#video_type_error_container" />
                <label for="vimeo">Vimeo Video</label>
            </div>

            <div class="radio radio-primary radio-circle">
                <input type="radio" name="video_type" class="video_type" value="youtube" id="youtube" data-parsley-errors-container="#video_type_error_container" />
                <label for="youtube">Youtube Video</label>
            </div>
            <div id="video_type_error_container"></div>
        </div>
    </div>

    <div class="col-md-8" id="youtube_link_field" style="display: none;">
        <div class="form-group">
            {!! Form::label('Youtube Video ID') !!}
            {!! Form::text('youtube_video_id', null, ['class' => 'form-control', 'placeholder' => 'Youtube Video ID']) !!}
            <i>Note: Insert Youtube video id only. For Example: 8XbTb9yt0Ls</i>
        </div>
    </div>

    <div class="col-md-8" id="vimeo_link_field" style="display: none;">
        <div class="form-group">
            {!! Form::label('Vimeo Video ID') !!}
            {!! Form::text('vimeo_video_id', null, ['class' => 'form-control', 'placeholder' => 'Vimeo Video ID']) !!}
        </div>
    </div>
</div>


<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
