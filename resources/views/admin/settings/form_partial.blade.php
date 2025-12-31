<div class="row">
    <div class="col-md-6">

        @if (isset($settings) && count($settings) > 0)

        @foreach ($settings as $key=>$item)

        @if ($item->name == 'hr')
        <hr />

        @elseif ($item->name == 'h4')
        <h4>{{ $item->title }}</h4>
        @elseif ($item->name == 'h3')
        <h3 style="font-size: 18px; color: #CA3C3C">{{ $item->title }}</h3>
        @elseif ($item->name == 'p')
        <p>{{ $item->title }}</p>

        @elseif ($item->name == 'br')
    </div>
    <div class="col-md-6">
        @elseif($item->field_type == 'text')
        <div class="form-group">
            <label>{{ $item->title }}</label>

            <?php
            $field_array = [ 'class' => 'form-control'];

            if ($item->required == '1') {
              $field_array['data-parsley-required'] = 'required';
            }
            if ($item->min_length > 0) {
              $field_array['data-parsley-minlength'] = $item->min_length;
            }
            if ($item->max_length > 0) {
              $field_array['data-parsley-maxlength'] = $item->max_length;
            }
            if ($item->min_value > 0) {
              $field_array['data-parsley-min'] = $item->min_value;
            }
            if ($item->max_value > 0) {
              $field_array['data-parsley-max'] = $item->max_value;
            }
            ?>

            {!! Form::text('settings[' . $item->id . ']', $item->value, $field_array) !!}
            @if ($item->help_text != '')
            <small id="emailHelp" class="form-text text-muted">{!! $item->help_text !!}</small>
            @endif
        </div>
        @elseif($item->field_type == 'image')
        <div class="form-group">
            <label>{{ $item->title }}</label>

            <?php
            $field_array = [ 'class' => 'filestyle'];

            if ($item->required == '1') {
              $field_array['data-parsley-required'] = 'required';
            }
            ?>

            {!! Form::file('file[' . $item->id . ']', $field_array) !!}
            @if ($item->help_text != '')
            <small id="emailHelp" class="form-text text-muted">{!! $item->help_text !!}</small>
            @endif
            @if ($item->value != '')
            <?php $img_arr = json_decode($item->value, true); ?>
            <div class="media_box">
                <a href="{{ asset('/') }}uploads/media/{{ $img_arr['filename'] }}" data-fancybox="gallery">
                    <img src="{{ asset('/') }}uploads/media/fit_thumbs/50x50/{{ $img_arr['filename'] }}"
                        class='img-thumbnail' />
                </a>
                <br />
                <a class="btn img-del-btn btn-danger btn-xs" data-setting_id="{{ $item->id }}"
                    data-id="{{ $img_arr['id'] }}"> Remove</a>
            </div>
            @endif
        </div>
        @elseif($item->field_type == 'textarea')
        <div class="form-group">
            <label>{{ $item->title }}</label>

            <?php
            //$field_array = [ 'class' => 'form-control summernote'];
            $field_array = [ 'class' => 'form-control ckeditor'];

            if ($item->required == '1') {
              $field_array['data-parsley-required'] = 'required';
            }
            if ($item->min_length > 0) {
              $field_array['data-parsley-minlength'] = $item->min_length;
            }
            if ($item->max_length > 0) {
              $field_array['data-parsley-maxlength'] = $item->max_length;
            }
            if ($item->min_value > 0) {
              $field_array['data-parsley-min'] = $item->min_value;
            }
            if ($item->max_value > 0) {
              $field_array['data-parsley-max'] = $item->max_value;
            }
            ?>

            {!! Form::textarea('settings[' . $item->id . ']', $item->value, $field_array) !!}
            @if ($item->help_text != '')
            <small id="emailHelp" class="form-text text-muted">{!! $item->help_text !!}</small>
            @endif
        </div>
        @elseif($item->field_type == 'radio')
        <div class="form-group">
            <label>{{ $item->title }}</label>

            @if ($item->field_options != '')
            <?php $options = explode(",", $item->field_options); ?>

            <?php foreach ($options as $optVal): ?>
            <?php $option = explode(":", $optVal); ?>
            <div class="form-check">
                <label class="form-check-label">
                    <input class="form-check-input" name="settings[{{ $item->id }}]" id="exampleRadios1"
                        value="{{ $option[0] }}" <?php echo $item->value == $option[0] ? 'checked' : '' ?> type="radio">
                    {{ $option[1] }}
                </label>
            </div>
            <?php endforeach; ?>
            @if ($item->help_text != '')
            <small id="emailHelp" class="form-text text-muted">{!! $item->help_text !!}</small>
            @endif

            @endif

        </div>
        @elseif($item->field_type == 'select')
        <div class="form-group">
            <label>{{ $item->title }}</label>

            <select name="settings[{{ $item->id }}]" class="form-control">
                @if ($item->field_options != '')
                <?php $options = explode(",", $item->field_options); ?>
                <?php foreach ($options as $optVal): ?>
                <?php $option = explode(":", $optVal); ?>
                <option value="{{ $option[0] }}" <?php echo $item->value == $option[0] ? 'selected' : '' ?>>
                    {{ $option[1] }}</option>
                <?php endforeach; ?>
            </select>
            @if ($item->help_text != '')
            <small id="emailHelp" class="form-text text-muted">{!! $item->help_text !!}</small>
            @endif

            @endif

        </div>
        @endif

        @endforeach

        @endif

    </div>

</div>
