<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Membership Level') !!}
            {!! Form::select('membership_level_id', $membership_levels, null, ['class' => 'form-control custom-select', 'id' => 'membership_level_id', 'placeholder' => 'Select Membership Level', 'required' => true]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Membership Status') !!}
            {!! Form::select('membership_status_id', [], null, ['class' => 'form-control custom-select', 'id' => 'membership_status_id', 'placeholder' => 'Select Membership Status', 'required' => true]) !!}
        </div>
    </div>
</div>

<?php /* <table class="table table-bordered">
    <tr>
        <th>Membership Level</th>
        <td>{{ $formObj->membership_level->title }}</td>
    </tr>
    <tr>
        <th>Status</th>
        <td>{{ $formObj->membership_status->title }}</td>
    </tr>
</table> */ ?>


<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Video ID') !!}
            {!! Form::text('video_id', null, ['class' => 'form-control max', 'placeholder' => 'Enter Vimeo Video ID', 'required' => false, 'maxlength' => 255]) !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('Video Title') !!}
            {!! Form::text('video_title', null, ['class' => 'form-control max', 'placeholder' => 'Enter Video Title', 'maxlength' => 255]) !!}
        </div>
    </div>
</div>

<hr />
<button type="submit" class="btn btn-info float-right waves-effect waves-light">Submit</button>
